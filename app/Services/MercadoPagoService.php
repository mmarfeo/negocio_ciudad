<?php

namespace App\Services;

use App\Models\NegocioMercadopago;
use App\Models\Pedido;
use App\Models\Product;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Marketplace con Mercado Pago Connect (OAuth): cada negocio conecta su
 * propia cuenta acá y el cobro de sus ventas le llega directo a él, no a
 * una cuenta central de la plataforma. `client_id`/`client_secret` son de
 * la Aplicación de LA PLATAFORMA (developers.mercadopago.com) -- son los
 * que arman la pantalla de autorización, el access_token que queda
 * guardado por negocio es del negocio, no de la plataforma.
 *
 * Mismo molde que GeminiClient: credenciales desde config/services.php,
 * disponible() como chequeo de fallback, nunca lanza excepción hacia el
 * caller (try/catch + Log::warning), nunca loguea el token completo.
 *
 * Etapa 4 del plan: `crearPreferencia()`, `verificarFirmaWebhook()` y
 * `obtenerPago()`. Los nombres exactos de algunos parámetros (ej.
 * `marketplace_fee`, el formato de `x-signature`) están armados según la
 * documentación de Mercado Pago al momento de escribir esto -- antes de
 * usarlo con plata real, confirmar que no cambiaron contra la
 * documentación vigente en developers.mercadopago.com.
 */
class MercadoPagoService
{
    private const URL_AUTORIZACION = 'https://auth.mercadopago.com/authorization';
    private const URL_TOKEN = 'https://api.mercadopago.com/oauth/token';
    private const URL_PREFERENCIAS = 'https://api.mercadopago.com/checkout/preferences';
    private const URL_PAGO = 'https://api.mercadopago.com/v1/payments/%s';

    private $http;
    private $clientId;
    private $clientSecret;

    public function __construct()
    {
        $this->http = new Client(['timeout' => 15]);
        $this->clientId = config('services.mercadopago.client_id') ?: null;
        $this->clientSecret = config('services.mercadopago.client_secret') ?: null;
    }

    public function disponible(): bool
    {
        return ! empty($this->clientId) && ! empty($this->clientSecret);
    }

    public function negocioConectado(Product $negocio): bool
    {
        $credenciales = $this->credencialesDe($negocio);

        return $credenciales !== null && ! empty($credenciales->access_token);
    }

    public function credencialesDe(Product $negocio): ?NegocioMercadopago
    {
        return NegocioMercadopago::where('negocio_id', $negocio->id)->first();
    }

    /**
     * URL de autorización de Mercado Pago -- `state` lleva el id del
     * negocio para poder identificarlo cuando MP redirige de vuelta,
     * aunque acá igual llega por el {negocio} de la propia ruta.
     */
    public function urlConexion(Product $negocio, string $redirectUri): ?string
    {
        if (! $this->disponible()) {
            return null;
        }

        return self::URL_AUTORIZACION.'?'.http_build_query([
            'client_id' => $this->clientId,
            'response_type' => 'code',
            'platform_id' => 'mp',
            'redirect_uri' => $redirectUri,
            'state' => (string) $negocio->id,
        ]);
    }

    /**
     * Callback de OAuth: intercambia el `code` que manda Mercado Pago por
     * el access_token/refresh_token del negocio y los guarda cifrados.
     */
    public function intercambiarCodigoPorToken(Product $negocio, string $code, string $redirectUri): bool
    {
        if (! $this->disponible()) {
            return false;
        }

        try {
            $response = $this->http->post(self::URL_TOKEN, [
                'json' => [
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'redirect_uri' => $redirectUri,
                ],
            ]);

            $datos = json_decode((string) $response->getBody(), true);

            if (empty($datos['access_token'])) {
                return false;
            }

            NegocioMercadopago::updateOrCreate(
                ['negocio_id' => $negocio->id],
                [
                    'mp_user_id' => $datos['user_id'] ?? null,
                    'public_key' => $datos['public_key'] ?? null,
                    'access_token' => $datos['access_token'],
                    'refresh_token' => $datos['refresh_token'] ?? null,
                    'token_expires_at' => isset($datos['expires_in'])
                        ? now()->addSeconds((int) $datos['expires_in'])
                        : null,
                    'conectado_en' => now(),
                ]
            );

            return true;
        } catch (\Throwable $e) {
            Log::warning('Mercado Pago: no se pudo conectar la cuenta del negocio '.$negocio->id.': '.$e->getMessage());

            return false;
        }
    }

    /**
     * Refresca el access_token si venció, usando el refresh_token guardado.
     * Se llama antes de cualquier operación que necesite el token vigente
     * (crear preferencia, consultar un pago) -- no hace falta un cron.
     */
    public function refrescarTokenSiHaceFalta(NegocioMercadopago $credenciales): bool
    {
        if (! $credenciales->tokenVencido()) {
            return true;
        }

        if (empty($credenciales->refresh_token)) {
            return false;
        }

        try {
            $response = $this->http->post(self::URL_TOKEN, [
                'json' => [
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $credenciales->refresh_token,
                ],
            ]);

            $datos = json_decode((string) $response->getBody(), true);

            if (empty($datos['access_token'])) {
                return false;
            }

            $credenciales->access_token = $datos['access_token'];
            $credenciales->refresh_token = $datos['refresh_token'] ?? $credenciales->refresh_token;
            $credenciales->token_expires_at = isset($datos['expires_in'])
                ? now()->addSeconds((int) $datos['expires_in'])
                : null;
            $credenciales->save();

            return true;
        } catch (\Throwable $e) {
            Log::warning('Mercado Pago: no se pudo refrescar el token del negocio '.$credenciales->negocio_id.': '.$e->getMessage());

            return false;
        }
    }

    public function desconectar(Product $negocio): void
    {
        NegocioMercadopago::where('negocio_id', $negocio->id)->delete();
    }

    /**
     * Crea la preferencia de pago EN LA CUENTA DEL NEGOCIO (Authorization:
     * Bearer con su access_token, no el de la plataforma) -- así el cobro
     * le llega directo a él. La comisión de la plataforma (`pedido->comision`,
     * ya calculada en TiendaController al armar el pedido) se descuenta de
     * ese pago, no se le suma al comprador.
     *
     * @return string|null la URL (`init_point`) a la que redirigir al
     *                      comprador para pagar, o null si no se pudo crear
     *                      (sin conexión, token vencido sin refresh, error
     *                      de red) -- el llamador debe hacer fallback en ese
     *                      caso, nunca bloquear el pedido ya registrado.
     */
    public function crearPreferencia(Pedido $pedido): ?string
    {
        $negocio = $pedido->negocio;
        $credenciales = $this->credencialesDe($negocio);

        if (! $credenciales || empty($credenciales->access_token)) {
            return null;
        }

        if (! $this->refrescarTokenSiHaceFalta($credenciales)) {
            return null;
        }

        $urlResultado = route('tienda.resultado', [$negocio->ciudad_slug, $negocio->slug, $pedido->id]);

        $body = [
            'items' => $pedido->items->map(fn ($item) => [
                'title' => $item->nombre_producto,
                'quantity' => $item->cantidad,
                'unit_price' => (float) $item->precio_unitario,
                'currency_id' => 'ARS',
            ])->values()->all(),
            'external_reference' => (string) $pedido->id,
            'notification_url' => route('mp.webhook', $pedido),
            'back_urls' => [
                'success' => $urlResultado,
                'pending' => $urlResultado,
                'failure' => $urlResultado,
            ],
            'auto_return' => 'approved',
        ];

        if ($pedido->comision > 0) {
            $body['marketplace_fee'] = (float) $pedido->comision;
        }

        try {
            $response = $this->http->post(self::URL_PREFERENCIAS, [
                'headers' => ['Authorization' => 'Bearer '.$credenciales->access_token],
                'json' => $body,
            ]);

            $datos = json_decode((string) $response->getBody(), true);

            if (empty($datos['init_point'])) {
                return null;
            }

            $pedido->mp_preference_id = $datos['id'] ?? null;
            $pedido->save();

            return $datos['init_point'];
        } catch (\Throwable $e) {
            Log::warning('Mercado Pago: no se pudo crear la preferencia del pedido '.$pedido->id.': '.$e->getMessage());

            return null;
        }
    }

    /**
     * Verifica la firma del webhook (header `x-signature`, formato
     * "ts=...,v1=...") antes de confiar en cualquier notificación entrante
     * -- sin esto, cualquiera podría pegarle a la URL del webhook y marcar
     * pedidos como pagados sin haber pagado nada.
     */
    public function verificarFirmaWebhook(Request $request): bool
    {
        $secreto = config('services.mercadopago.webhook_secret');

        if (empty($secreto)) {
            // Sin secreto configurado no hay forma de verificar -- se deja
            // pasar (falla segura hacia "seguir funcionando" en vez de
            // romper el checkout), pero bien visible en los logs.
            Log::warning('Mercado Pago: webhook recibido sin MERCADOPAGO_WEBHOOK_SECRET configurado, no se verificó la firma.');

            return true;
        }

        $encabezado = $request->header('x-signature');
        $dataId = $request->query('data_id') ?? $request->input('data.id');

        if (! $encabezado || ! $dataId) {
            return false;
        }

        $partes = [];
        foreach (explode(',', $encabezado) as $parte) {
            [$clave, $valor] = array_pad(explode('=', trim($parte), 2), 2, null);
            if ($clave) {
                $partes[$clave] = $valor;
            }
        }

        if (empty($partes['ts']) || empty($partes['v1'])) {
            return false;
        }

        $plantilla = "id:{$dataId};request-id:{$request->header('x-request-id')};ts:{$partes['ts']};";
        $firmaCalculada = hash_hmac('sha256', $plantilla, $secreto);

        return hash_equals($firmaCalculada, $partes['v1']);
    }

    /** Consulta el estado real de un pago, con el access_token DEL NEGOCIO. */
    public function obtenerPago(Product $negocio, string $paymentId): ?array
    {
        $credenciales = $this->credencialesDe($negocio);

        if (! $credenciales || empty($credenciales->access_token)) {
            return null;
        }

        if (! $this->refrescarTokenSiHaceFalta($credenciales)) {
            return null;
        }

        try {
            $response = $this->http->get(sprintf(self::URL_PAGO, $paymentId), [
                'headers' => ['Authorization' => 'Bearer '.$credenciales->access_token],
            ]);

            return json_decode((string) $response->getBody(), true);
        } catch (\Throwable $e) {
            Log::warning('Mercado Pago: no se pudo consultar el pago '.$paymentId.' del negocio '.$negocio->id.': '.$e->getMessage());

            return null;
        }
    }
}
