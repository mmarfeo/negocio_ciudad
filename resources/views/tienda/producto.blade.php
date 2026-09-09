<!DOCTYPE html>
<html lang="es">

<head>
    @include('tienda.partials.head', ['negocio' => $negocio, 'propiedades' => $propiedades, 'titulo' => $producto->nombre])
</head>

<body id="plantilla_tienda">

    @include('tienda.partials.nav', ['negocio' => $negocio])

    @php
        $imagenUrl = \App\Models\ProductoCatalogo::resolverImagenPivot($producto->pivot->imagen);
        $sinStock = $producto->pivot->stock !== null && $producto->pivot->stock <= 0;
        $descripcion = $producto->pivot->descripcion ?: $producto->descripcion;
    @endphp

    <section class="td-section">
        <div class="td-wrap">
            @if (session('status'))
                <div class="td-alert td-alert--success">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="td-alert td-alert--error">{{ $errors->first() }}</div>
            @endif

            <div class="td-detalle">
                <div class="td-detalle__img">
                    @if ($imagenUrl)
                        <img src="{{ $imagenUrl }}" alt="{{ $producto->nombre }}" onerror="this.onerror=null;this.src='{{ asset('img/placeholder.svg') }}'">
                    @endif
                </div>
                <div>
                    <h1>{{ $producto->nombre }}</h1>
                    @if ($producto->pivot->precio)
                        <div class="td-detalle__precio">$ {{ number_format($producto->pivot->precio, 0, ',', '.') }}</div>
                    @endif
                    @if ($descripcion)
                        <p class="td-detalle__desc">{{ $descripcion }}</p>
                    @endif

                    @if ($sinStock)
                        <p class="td-card__stock--agotado" style="margin-top:1.25rem;">Sin stock por ahora.</p>
                    @else
                        <form method="POST" action="{{ route('tienda.agregar', [$negocio->ciudad_slug, $negocio->slug]) }}">
                            @csrf
                            <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                            <div class="td-cantidad">
                                <label for="cantidad" class="mb-0">Cantidad</label>
                                <input type="number" id="cantidad" name="cantidad" min="1" value="1" @if($producto->pivot->stock !== null) max="{{ $producto->pivot->stock }}" @endif>
                            </div>
                            <button type="submit" class="td-btn">Agregar al carrito</button>
                        </form>
                        @if ($cantidadEnCarrito > 0)
                            <p class="td-card__stock" style="margin-top:.75rem;">Ya tenés {{ $cantidadEnCarrito }} en el carrito.</p>
                        @endif
                    @endif

                    <p style="margin-top:1.5rem;"><a href="{{ $negocio->urlPublica() }}" class="td-btn td-btn--ghost">← Volver al catálogo</a></p>
                </div>
            </div>
        </div>
    </section>

    @include('tienda.partials.footer', ['negocio' => $negocio])

</body>

</html>
