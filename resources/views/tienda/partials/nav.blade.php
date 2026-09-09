{{--
    Nav compartida por todas las páginas de la plantilla "Tienda". Requiere
    $negocio. $negocio->exists distingue un negocio real y publicado (tiene
    slug/ciudad_slug) de la vista previa en vivo del chat (Fase 4): ahí
    $negocio es un modelo armado en memoria, nunca guardado, sin slug
    todavía -- route('tienda.carrito', ...) explota con 500 si se le pasa
    un slug null, por eso el link al carrito solo se arma con datos reales.
--}}
<nav class="td-nav">
    <div class="td-wrap td-nav__row">
        @if ($negocio->exists)
            <a href="{{ $negocio->urlPublica() }}" class="td-nav__brand">
                @if ($negocio->propiedades && $negocio->propiedades->imagenUrl('nav_logo'))
                    <img src="{{ $negocio->propiedades->imagenUrl('nav_logo') }}" alt="{{ $negocio->nombre }}" onerror="this.onerror=null;this.src='{{ asset('img/placeholder.svg') }}'">
                @endif
                <span>{{ $negocio->nombre }}</span>
            </a>
            <a href="{{ route('tienda.carrito', [$negocio->ciudad_slug, $negocio->slug]) }}" class="td-cart-link">
                🛒 Carrito
                @php $cantidadCarrito = array_sum(session()->get('carrito.'.$negocio->id, [])); @endphp
                @if ($cantidadCarrito > 0)
                    <span class="td-cart-count">{{ $cantidadCarrito }}</span>
                @endif
            </a>
        @else
            <span class="td-nav__brand">
                @if ($negocio->propiedades && $negocio->propiedades->imagenUrl('nav_logo'))
                    <img src="{{ $negocio->propiedades->imagenUrl('nav_logo') }}" alt="{{ $negocio->nombre }}" onerror="this.onerror=null;this.src='{{ asset('img/placeholder.svg') }}'">
                @endif
                <span>{{ $negocio->nombre }}</span>
            </span>
            <span class="td-cart-link" style="opacity:.5; cursor:default;">🛒 Carrito</span>
        @endif
    </div>
</nav>
