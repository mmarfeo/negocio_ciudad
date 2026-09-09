<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Notificaciones de Mercado Pago cuando cambia el estado de un pago.
 * Público, sin 'auth' (lo llama Mercado Pago, no un usuario logueado) y
 * exento de CSRF (ver App\Http\Middleware\VerifyCsrfToken::$except) --
 * la seguridad acá la da `verificarFirmaWebhook()`, no el token de Laravel.
 *
 * El pedido viaja en la URL misma (`/webhooks/mercadopago/{pedido}`, ver
 * `notification_url` en MercadoPagoService::crearPreferencia()) en vez de
 * depender de `external_reference` del payload: así no hace falta un
 * access_token para saber DE QUÉ NEGOCIO es el pago antes de poder
 * consultarlo (cada negocio tiene el suyo propio, ver el resto del
 * marketplace).
 */
class MercadoPagoWebhookController extends Controller
{
    private $mercadoPago;

    public function __construct(MercadoPagoService $mercadoPago)
    {
        $this->mercadoPago = $mercadoPago;
    }

    public function handle(Request $request, Pedido $pedido)
    {
        if (! $this->mercadoPago->verificarFirmaWebhook($request)) {
            Log::warning('Mercado Pago: webhook con firma inválida para el pedido '.$pedido->id);

            return response()->json(['ok' => false], 401);
        }

        $paymentId = $request->query('data_id') ?? $request->input('data.id');

        if (! $paymentId) {
            // Mercado Pago manda notificaciones de varios tipos (topic
            // merchant_order, etc.), no solo pagos -- las que no traen un
            // id de pago no son relevantes acá, se responden 200 igual
            // para que MP no las siga reintentando.
            return response()->json(['ok' => true]);
        }

        $pago = $this->mercadoPago->obtenerPago($pedido->negocio, $paymentId);

        if (! $pago) {
            // Error transitorio ya logueado en el service -- 500 para que
            // Mercado Pago reintente más tarde, no se descarta la venta.
            return response()->json(['ok' => false], 500);
        }

        $pedido->mp_payment_id = $paymentId;
        $pedido->estado = $this->mapearEstado($pago['status'] ?? null);
        $pedido->save();

        return response()->json(['ok' => true]);
    }

    private function mapearEstado(?string $estadoMp): string
    {
        return match ($estadoMp) {
            'approved' => 'pagado',
            'rejected', 'cancelled' => 'cancelado',
            default => 'pendiente',
        };
    }
}
