<!DOCTYPE html>
<html lang="es">

<head>
    @include('tienda.partials.head', ['negocio' => $negocio, 'propiedades' => $propiedades, 'titulo' => 'Pedido #'.$pedido->id])
</head>

<body id="plantilla_tienda">

    @include('tienda.partials.nav', ['negocio' => $negocio])

    @php
        $mensajes = [
            'pagado' => ['✅', '¡Pago confirmado!', 'Gracias por tu compra. Le avisamos a '.$negocio->nombre.' — te van a contactar al '.$pedido->telefono_cliente.' para coordinar '.($pedido->tipo_entrega === 'envio' ? 'el envío.' : 'el retiro.')],
            'cancelado' => ['❌', 'El pago no se pudo procesar', 'Podés volver a intentarlo desde el carrito, o coordinar el pago directo con '.$negocio->nombre.' al pedido #'.$pedido->id.'.'],
            'pendiente' => ['🕒', '¡Pedido registrado!', 'Le avisamos a '.$negocio->nombre.' de tu pedido. Te van a contactar al '.$pedido->telefono_cliente.' para coordinar '.($pedido->tipo_entrega === 'envio' ? 'el envío' : 'el retiro').' y el pago.'],
        ];
        [$icono, $titulo, $mensaje] = $mensajes[$pedido->estado] ?? $mensajes['pendiente'];
    @endphp

    <section class="td-section">
        <div class="td-wrap td-resultado">
            <div class="td-resultado__icono">{{ $icono }}</div>
            <h1>{{ $titulo }}</h1>
            <p>{{ $mensaje }}</p>

            <div class="td-resultado__pedido">
                <p style="margin:0 0 .75rem; font-weight:700;">Pedido #{{ $pedido->id }}</p>
                @foreach ($pedido->items as $item)
                    <div class="td-cart-summary__row">
                        <span>{{ $item->cantidad }}× {{ $item->nombre_producto }}</span>
                        <span>$ {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach
                <div class="td-cart-summary__row td-cart-summary__row--total">
                    <span>Total</span>
                    <span>$ {{ number_format($pedido->total, 0, ',', '.') }}</span>
                </div>
                @if ($pedido->tipo_entrega === 'envio' && $pedido->direccion_entrega)
                    <p style="margin-top:1rem;"><strong>Dirección:</strong> {{ $pedido->direccion_entrega }}</p>
                @endif
                @if ($pedido->notas)
                    <p style="margin-top:.5rem;"><strong>Notas:</strong> {{ $pedido->notas }}</p>
                @endif
            </div>

            <p style="margin-top:2rem;"><a href="{{ $negocio->urlPublica() }}" class="td-btn td-btn--ghost">Volver al catálogo</a></p>
        </div>
    </section>

    @include('tienda.partials.footer', ['negocio' => $negocio])

</body>

</html>
