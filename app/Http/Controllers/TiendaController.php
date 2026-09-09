<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Product;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Catálogo público + carrito + checkout de la plantilla "Tienda" (id 11).
 * Rutas públicas, sin 'auth' -- el comprador nunca necesita cuenta (guest
 * checkout, ver plan del marketplace).
 *
 * Si el negocio tiene Mercado Pago conectado, `procesarCheckout()` crea la
 * preferencia y redirige a pagar; si no, o si falla la creación, el pedido
 * queda igual registrado como 'pendiente' y el negocio coordina el pago
 * aparte -- nunca se bloquea la venta por un problema con Mercado Pago.
 */
class TiendaController extends Controller
{
    private $mercadoPago;

    public function __construct(MercadoPagoService $mercadoPago)
    {
        $this->mercadoPago = $mercadoPago;
    }

    public function producto(string $ciudad, string $slug, string $productoSlug)
    {
        $negocio = $this->resolverNegocioTienda($ciudad, $slug);

        $producto = $negocio->productos()
            ->where('productos_catalogo.slug', $productoSlug)
            ->wherePivot('disponible', true)
            ->firstOrFail();

        return view('tienda.producto', [
            'negocio' => $negocio,
            'propiedades' => $negocio->propiedades,
            'producto' => $producto,
            'cantidadEnCarrito' => $this->carrito($negocio->id)[$producto->id] ?? 0,
        ]);
    }

    public function carritoVer(string $ciudad, string $slug)
    {
        $negocio = $this->resolverNegocioTienda($ciudad, $slug);
        $items = $this->itemsDelCarritoConDatos($negocio);

        return view('tienda.carrito', [
            'negocio' => $negocio,
            'propiedades' => $negocio->propiedades,
            'items' => $items,
            'subtotal' => $items->sum(fn ($item) => $item['subtotal']),
        ]);
    }

    public function agregar(Request $request, string $ciudad, string $slug)
    {
        $negocio = $this->resolverNegocioTienda($ciudad, $slug);

        $datos = $request->validate([
            'producto_id' => 'required|integer',
            'cantidad' => 'nullable|integer|min:1',
        ]);

        $producto = $negocio->productos()
            ->where('productos_catalogo.id', $datos['producto_id'])
            ->wherePivot('disponible', true)
            ->first();

        if (! $producto) {
            return back()->withErrors(['carrito' => 'Ese producto ya no está disponible.']);
        }

        $cantidadPedida = $datos['cantidad'] ?? 1;
        $carrito = $this->carrito($negocio->id);
        $cantidadActual = $carrito[$producto->id] ?? 0;
        $cantidadNueva = $cantidadActual + $cantidadPedida;

        if ($producto->pivot->stock !== null && $cantidadNueva > $producto->pivot->stock) {
            return back()->withErrors(['carrito' => 'No queda stock suficiente de "'.$producto->nombre.'".']);
        }

        $carrito[$producto->id] = $cantidadNueva;
        $this->guardarCarrito($negocio->id, $carrito);

        return back()->with('status', 'Se agregó "'.$producto->nombre.'" al carrito.');
    }

    public function actualizar(Request $request, string $ciudad, string $slug)
    {
        $negocio = $this->resolverNegocioTienda($ciudad, $slug);

        $datos = $request->validate([
            'producto_id' => 'required|integer',
            'cantidad' => 'required|integer|min:0',
        ]);

        $carrito = $this->carrito($negocio->id);

        if ($datos['cantidad'] === 0) {
            unset($carrito[$datos['producto_id']]);
        } else {
            $carrito[$datos['producto_id']] = $datos['cantidad'];
        }

        $this->guardarCarrito($negocio->id, $carrito);

        return redirect()->route('tienda.carrito', [$ciudad, $slug]);
    }

    public function quitar(Request $request, string $ciudad, string $slug)
    {
        $negocio = $this->resolverNegocioTienda($ciudad, $slug);

        $productoId = (int) $request->input('producto_id');
        $carrito = $this->carrito($negocio->id);
        unset($carrito[$productoId]);
        $this->guardarCarrito($negocio->id, $carrito);

        return redirect()->route('tienda.carrito', [$ciudad, $slug]);
    }

    public function checkout(string $ciudad, string $slug)
    {
        $negocio = $this->resolverNegocioTienda($ciudad, $slug);
        $items = $this->itemsDelCarritoConDatos($negocio);

        if ($items->isEmpty()) {
            return redirect()->route('tienda.carrito', [$ciudad, $slug])
                ->withErrors(['carrito' => 'Tu carrito está vacío.']);
        }

        return view('tienda.checkout', [
            'negocio' => $negocio,
            'propiedades' => $negocio->propiedades,
            'items' => $items,
            'mercadoPagoDisponible' => $this->mercadoPago->negocioConectado($negocio),
            'subtotal' => $items->sum(fn ($item) => $item['subtotal']),
        ]);
    }

    public function procesarCheckout(Request $request, string $ciudad, string $slug)
    {
        $negocio = $this->resolverNegocioTienda($ciudad, $slug);

        $datos = $request->validate([
            'nombre_cliente' => 'required|string|max:255',
            'telefono_cliente' => 'required|string|max:50',
            'email_cliente' => 'nullable|email|max:255',
            'tipo_entrega' => 'required|in:retiro,envio',
            'direccion_entrega' => 'required_if:tipo_entrega,envio|nullable|string|max:255',
            'notas' => 'nullable|string|max:1000',
        ]);

        $items = $this->itemsDelCarritoConDatos($negocio);

        if ($items->isEmpty()) {
            return redirect()->route('tienda.carrito', [$ciudad, $slug])
                ->withErrors(['carrito' => 'Tu carrito está vacío.']);
        }

        // Revalidar stock justo antes de confirmar -- pudo cambiar desde que
        // se armó el carrito. Sin locking (ver plan): ventana chica de
        // carrera si dos compradores compiten por la última unidad a la vez,
        // aceptable al volumen actual.
        foreach ($items as $item) {
            if ($item['stock'] !== null && $item['cantidad'] > $item['stock']) {
                return redirect()->route('tienda.carrito', [$ciudad, $slug])
                    ->withErrors(['carrito' => 'Ya no queda stock suficiente de "'.$item['nombre'].'", ajustá la cantidad.']);
            }
        }

        $subtotal = $items->sum(fn ($item) => $item['subtotal']);
        $comisionPorcentaje = (float) config('services.mercadopago.comision_porcentaje', 0.5);
        // Informativo por ahora (sin cobro online todavía): representa lo
        // que se descontaría del pago del negocio cuando se conecte
        // Mercado Pago, no se le suma al total que ve el comprador.
        $comision = round($subtotal * $comisionPorcentaje / 100, 2);

        $pedido = DB::transaction(function () use ($negocio, $datos, $items, $subtotal, $comision) {
            $pedido = Pedido::create([
                'negocio_id' => $negocio->id,
                'nombre_cliente' => $datos['nombre_cliente'],
                'telefono_cliente' => $datos['telefono_cliente'],
                'email_cliente' => $datos['email_cliente'] ?? null,
                'tipo_entrega' => $datos['tipo_entrega'],
                'direccion_entrega' => $datos['tipo_entrega'] === 'envio' ? $datos['direccion_entrega'] : null,
                'notas' => $datos['notas'] ?? null,
                'subtotal' => $subtotal,
                'comision' => $comision,
                'total' => $subtotal,
                // Sin Mercado Pago conectado todavía: el pedido queda
                // 'pendiente' y el negocio coordina el pago aparte.
                'estado' => 'pendiente',
            ]);

            foreach ($items as $item) {
                PedidoItem::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $item['producto_id'],
                    'nombre_producto' => $item['nombre'],
                    'precio_unitario' => $item['precio'],
                    'cantidad' => $item['cantidad'],
                    'subtotal' => $item['subtotal'],
                ]);

                if ($item['stock'] !== null) {
                    $negocio->productos()->updateExistingPivot($item['producto_id'], [
                        'stock' => max(0, $item['stock'] - $item['cantidad']),
                    ]);
                }
            }

            return $pedido;
        });

        $this->guardarCarrito($negocio->id, []);

        if ($this->mercadoPago->negocioConectado($negocio)) {
            $urlPago = $this->mercadoPago->crearPreferencia($pedido);
            if ($urlPago) {
                return redirect()->away($urlPago);
            }
            // No se pudo crear la preferencia (token vencido sin refresh
            // válido, error de red, etc.) -- el pedido ya quedó registrado,
            // se sigue igual que un negocio sin Mercado Pago conectado en
            // vez de bloquear la venta.
        }

        return redirect()->route('tienda.resultado', [$ciudad, $slug, $pedido->id]);
    }

    public function resultado(string $ciudad, string $slug, Pedido $pedido)
    {
        $negocio = $this->resolverNegocioTienda($ciudad, $slug);

        if ($pedido->negocio_id !== $negocio->id) {
            abort(404);
        }

        return view('tienda.resultado', [
            'negocio' => $negocio,
            'propiedades' => $negocio->propiedades,
            'pedido' => $pedido->load('items'),
        ]);
    }

    private function resolverNegocioTienda(string $ciudad, string $slug): Product
    {
        $negocio = Product::where('ciudad_slug', $ciudad)->where('slug', $slug)->first();

        if (! $negocio || (int) $negocio->plantilla_id !== 11) {
            abort(404);
        }

        return $negocio;
    }

    /** @return array<int,int> producto_id => cantidad */
    private function carrito(int $negocioId): array
    {
        return session()->get("carrito.{$negocioId}", []);
    }

    private function guardarCarrito(int $negocioId, array $carrito): void
    {
        session()->put("carrito.{$negocioId}", $carrito);
    }

    /**
     * Carrito con datos frescos del catálogo (nombre/precio/stock/imagen
     * actuales, no lo que había cuando se agregó) -- si un producto dejó
     * de estar disponible o se borró, se saca solo del carrito acá.
     */
    private function itemsDelCarritoConDatos(Product $negocio)
    {
        $carrito = $this->carrito($negocio->id);

        if (empty($carrito)) {
            return collect();
        }

        $productos = $negocio->productos()
            ->whereIn('productos_catalogo.id', array_keys($carrito))
            ->wherePivot('disponible', true)
            ->get()
            ->keyBy('id');

        $items = collect();
        $carritoLimpio = [];

        foreach ($carrito as $productoId => $cantidad) {
            $producto = $productos->get($productoId);
            if (! $producto) {
                continue; // ya no existe o dejó de estar disponible
            }

            $precio = (float) ($producto->pivot->precio ?? 0);
            $items->push([
                'producto_id' => $producto->id,
                'nombre' => $producto->nombre,
                'slug' => $producto->slug,
                'precio' => $precio,
                'cantidad' => $cantidad,
                'subtotal' => $precio * $cantidad,
                'stock' => $producto->pivot->stock,
                'imagen_url' => \App\Models\ProductoCatalogo::resolverImagenPivot($producto->pivot->imagen),
            ]);
            $carritoLimpio[$productoId] = $cantidad;
        }

        if ($carritoLimpio !== $carrito) {
            $this->guardarCarrito($negocio->id, $carritoLimpio);
        }

        return $items;
    }
}
