<!DOCTYPE html>
<html lang="es">

<head>
    @include('tienda.partials.head', ['negocio' => $negocio, 'propiedades' => $propiedades, 'titulo' => 'Carrito'])
</head>

<body id="plantilla_tienda">

    @include('tienda.partials.nav', ['negocio' => $negocio])

    <section class="td-section">
        <div class="td-wrap">
            <h1 style="margin-bottom:1.5rem;">Tu carrito</h1>

            @if ($errors->any())
                <div class="td-alert td-alert--error">{{ $errors->first() }}</div>
            @endif

            @if ($items->isEmpty())
                <p class="td-empty">Todavía no agregaste nada al carrito.</p>
                <a href="{{ $negocio->urlPublica() }}" class="td-btn">Ver el catálogo</a>
            @else
                <div class="td-cart-layout">
                    <div>
                        @foreach ($items as $item)
                            <div class="td-cart-item">
                                <div class="td-cart-item__img">
                                    @if ($item['imagen_url'])
                                        <img src="{{ $item['imagen_url'] }}" alt="{{ $item['nombre'] }}">
                                    @endif
                                </div>
                                <div class="td-cart-item__info">
                                    <strong>{{ $item['nombre'] }}</strong>
                                    <div class="td-cart-item__precio">$ {{ number_format($item['precio'], 0, ',', '.') }} c/u</div>
                                </div>
                                <form method="POST" action="{{ route('tienda.actualizar', [$negocio->ciudad_slug, $negocio->slug]) }}" style="display:flex; align-items:center; gap:.5rem;">
                                    @csrf
                                    <input type="hidden" name="producto_id" value="{{ $item['producto_id'] }}">
                                    <input type="number" name="cantidad" min="0" @if($item['stock'] !== null) max="{{ $item['stock'] }}" @endif value="{{ $item['cantidad'] }}" class="td-cart-item__cantidad">
                                    <button type="submit" class="td-btn td-btn--ghost" style="padding:.4rem .8rem;">Actualizar</button>
                                </form>
                                <div style="font-weight:700; min-width:5rem; text-align:right;">$ {{ number_format($item['subtotal'], 0, ',', '.') }}</div>
                                <form method="POST" action="{{ route('tienda.quitar', [$negocio->ciudad_slug, $negocio->slug]) }}">
                                    @csrf
                                    <input type="hidden" name="producto_id" value="{{ $item['producto_id'] }}">
                                    <button type="submit" class="td-cart-item__quitar">Quitar</button>
                                </form>
                            </div>
                        @endforeach
                        <p style="margin-top:1.25rem;"><a href="{{ $negocio->urlPublica() }}" class="td-btn td-btn--ghost">← Seguir comprando</a></p>
                    </div>

                    <div class="td-cart-summary">
                        <div class="td-cart-summary__row td-cart-summary__row--total">
                            <span>Total</span>
                            <span>$ {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('tienda.checkout', [$negocio->ciudad_slug, $negocio->slug]) }}" class="td-btn td-btn--block" style="margin-top:1.25rem;">Continuar la compra</a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    @include('tienda.partials.footer', ['negocio' => $negocio])

</body>

</html>
