<?php $negocio = $products; ?>
<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="{{ $propiedades->body_subtitulo ?? $propiedades->header_subtitulo_1 ?? $negocio->nombre }}">

  <title>{{ $negocio->nombre }}</title>
  <link rel="icon" type="image/x-icon" href="{{ $propiedades->imagenUrl('favicon_logo') ?? asset('img/negocio.ico') }}"/>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
  <link href="{{ asset('css/11-tienda/estilos.css') }}" rel="stylesheet">

  @if ($propiedades->estiloPersonalizado())
    <style>{!! $propiedades->estiloPersonalizado() !!}</style>
  @endif

</head>

<body id="plantilla_tienda">

  @php
    if ($negocio->exists) {
      // ProductController::InfoPlantilla es un switch genérico para todas
      // las plantillas (solo manda $products/$propiedades) -- igual que
      // Vidriera arma sus propios datos locales (fotos del header, ticker),
      // acá se resuelve el catálogo real de este negocio en la vista misma.
      $productosTienda = $negocio->productos()->wherePivot('disponible', true)->get();
    } else {
      // Vista previa en vivo del chat (Fase 4): el negocio no existe en la
      // base todavía, así que $negocio->productos() siempre daría vacío --
      // se arma una versión liviana con lo que ya está en la sesión
      // ($productosSesion, ver ChatController::preview()), con la misma
      // forma que $producto->pivot->{precio,imagen,descripcion,stock} para
      // no bifurcar el resto de esta vista.
      $productosTienda = collect($productosSesion ?? [])->map(function ($item, $indice) {
        return (object) [
          'id' => $indice,
          'nombre' => $item['nombre'] ?? '',
          'slug' => \Illuminate\Support\Str::slug($item['nombre'] ?? ''),
          'pivot' => (object) [
            'precio' => $item['precio'] ?? null,
            'imagen' => $item['imagen'] ?? null,
            'descripcion' => $item['descripcion'] ?? null,
            'stock' => null,
          ],
        ];
      });
    }
  @endphp

  @include('tienda.partials.nav', ['negocio' => $negocio])

  <header class="td-hero">
    <div class="td-wrap">
      @if ($negocio->rubro)
        <div class="td-hero__eyebrow">{{ $negocio->rubro }}</div>
      @endif
      <h1>{{ $propiedades->header_titulo_1 ?: $negocio->nombre }}</h1>
      @if ($propiedades->header_subtitulo_1 ?? $propiedades->body_subtitulo)
        <p>{{ $propiedades->header_subtitulo_1 ?: $propiedades->body_subtitulo }}</p>
      @endif
    </div>
  </header>

  <section class="td-section">
    <div class="td-wrap">
      @if ($productosTienda->isEmpty())
        <p class="td-empty">Todavía no hay productos cargados.</p>
      @else
        <div class="td-grid">
          @foreach ($productosTienda as $producto)
            @php
              $imagenUrl = \App\Models\ProductoCatalogo::resolverImagenPivot($producto->pivot->imagen);
              $sinStock = $producto->pivot->stock !== null && $producto->pivot->stock <= 0;
            @endphp
            <div class="td-card">
              @if ($negocio->exists)
                <a href="{{ route('tienda.producto', [$negocio->ciudad_slug, $negocio->slug, $producto->slug]) }}" class="td-card__img @if (! $imagenUrl) td-card__img--placeholder @endif">
                  @if ($imagenUrl)
                    <img src="{{ $imagenUrl }}" alt="{{ $producto->nombre }}" loading="lazy">
                  @else
                    <span>{{ $producto->nombre }}</span>
                  @endif
                </a>
              @else
                {{-- Vista previa: todavía no hay una página real a la que
                     linkear (sin slug/ciudad_slug), se muestra sin link. --}}
                <div class="td-card__img @if (! $imagenUrl) td-card__img--placeholder @endif">
                  @if ($imagenUrl)
                    <img src="{{ $imagenUrl }}" alt="{{ $producto->nombre }}" loading="lazy">
                  @else
                    <span>{{ $producto->nombre }}</span>
                  @endif
                </div>
              @endif
              <div class="td-card__body">
                @if ($negocio->exists)
                  <h3><a href="{{ route('tienda.producto', [$negocio->ciudad_slug, $negocio->slug, $producto->slug]) }}">{{ $producto->nombre }}</a></h3>
                @else
                  <h3>{{ $producto->nombre }}</h3>
                @endif
                @if ($producto->pivot->precio)
                  <div class="td-card__precio">$ {{ number_format($producto->pivot->precio, 0, ',', '.') }}</div>
                @endif
                @if ($sinStock)
                  <div class="td-card__stock td-card__stock--agotado">Sin stock</div>
                @elseif ($producto->pivot->stock !== null)
                  <div class="td-card__stock">Quedan {{ $producto->pivot->stock }}</div>
                @endif
                <div class="td-card__cta">
                  @if (! $negocio->exists)
                    <button class="td-btn td-btn--block td-btn--ghost" disabled>Vista previa</button>
                  @elseif ($sinStock)
                    <button class="td-btn td-btn--block" disabled>Sin stock</button>
                  @else
                    <form method="POST" action="{{ route('tienda.agregar', [$negocio->ciudad_slug, $negocio->slug]) }}">
                      @csrf
                      <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                      <button type="submit" class="td-btn td-btn--block">Agregar al carrito</button>
                    </form>
                  @endif
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </section>

  @include('tienda.partials.footer', ['negocio' => $negocio])

</body>

</html>
