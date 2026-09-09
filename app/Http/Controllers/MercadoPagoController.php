<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;

/**
 * Conexión OAuth de la cuenta de Mercado Pago de un negocio (marketplace,
 * ver App\Services\MercadoPagoService) -- rutas detrás de 'auth', solo el
 * dueño del negocio puede conectar/desconectar la suya. Nada de esto crea
 * ni cobra pagos todavía, eso es la plantilla "Tienda" (etapa 3 del plan).
 */
class MercadoPagoController extends Controller
{
    private $mercadoPago;

    public function __construct(MercadoPagoService $mercadoPago)
    {
        $this->mercadoPago = $mercadoPago;
    }

    public function conectar(Product $negocio)
    {
        $this->verificarPropietario($negocio);

        if (! $this->mercadoPago->disponible()) {
            return back()->withErrors(['mercadopago' => 'La conexión con Mercado Pago no está disponible ahora mismo.']);
        }

        $url = $this->mercadoPago->urlConexion($negocio, route('mp.callback', $negocio));

        return redirect()->away($url);
    }

    public function callback(Request $request, Product $negocio)
    {
        $this->verificarPropietario($negocio);

        $code = $request->query('code');

        if (! $code) {
            return redirect()->route('negocios.edit', $negocio)
                ->withErrors(['mercadopago' => 'No se pudo completar la conexión con Mercado Pago.']);
        }

        $ok = $this->mercadoPago->intercambiarCodigoPorToken($negocio, $code, route('mp.callback', $negocio));

        return redirect()->route('negocios.edit', $negocio)
            ->with($ok ? 'status' : 'error', $ok
                ? 'Cuenta de Mercado Pago conectada correctamente.'
                : 'No se pudo conectar la cuenta de Mercado Pago, probá de nuevo.');
    }

    public function desconectar(Product $negocio)
    {
        $this->verificarPropietario($negocio);

        $this->mercadoPago->desconectar($negocio);

        return redirect()->route('negocios.edit', $negocio)
            ->with('status', 'Se desconectó la cuenta de Mercado Pago de este negocio.');
    }

    /**
     * Mismo criterio que NegocioAdminController::verificarPropietario() --
     * no se comparte como trait a propósito, mismo estilo del resto del
     * proyecto (helpers chicos y locales antes que abstracciones nuevas).
     */
    private function verificarPropietario(Product $negocio): void
    {
        if ($negocio->user_id && $negocio->user_id !== auth()->id()) {
            abort(403, 'Ese negocio ya tiene otro dueño registrado.');
        }
    }
}
