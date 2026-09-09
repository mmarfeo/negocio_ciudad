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
  <link href="{{ asset('css/10-vidriera/estilos.css') }}" rel="stylesheet">

  @if ($propiedades->estiloPersonalizado())
    <style>{!! $propiedades->estiloPersonalizado() !!}</style>
  @endif

</head>

<body id="plantilla_vidriera">

  @php
    // Igual criterio que 03-productos: solo se arman slides para las fotos
    // que realmente existen, nunca <img src=""> ni fondos vacíos.
    $fotosHero = [];
    for ($n = 1; $n <= 3; $n++) {
        $img = $propiedades->imagenUrl("header_img_{$n}");
        if ($img) {
            $fotosHero[] = $img;
        }
    }

    $telefonoContacto = $negocio->telefono ?: $negocio->celular;

    $itemsMenu = [];
    for ($n = 1; $n <= 8; $n++) {
        $titulo = $propiedades->{"body_tarjeta_titulo_{$n}"};
        if ($titulo) {
            $itemsMenu[] = [
                'titulo' => $titulo,
                'parrafo' => $propiedades->{"body_tarjeta_parrafo_{$n}"},
                'precio' => $propiedades->{"body_tarjeta_precio_{$n}"},
                'img' => $propiedades->imagenUrl("body_tarjeta_img_{$n}"),
            ];
        }
    }

    $redes = [
        'instagram' => $propiedades->footer_redes_instagram,
        'facebook' => $propiedades->footer_redes_facebook,
        'youtube' => $propiedades->footer_redes_youtube,
        'twitter' => $propiedades->footer_redes_twitter,
        'linkedin' => $propiedades->footer_redes_linkedin,
    ];
  @endphp

  <nav class="vd-nav">
    <div class="vd-wrap vd-nav__row">
      <a href="#" class="vd-nav__brand">
        @if ($propiedades->imagenUrl('nav_logo'))
          <img src="{{ $propiedades->imagenUrl('nav_logo') }}" alt="{{ $negocio->nombre }}">
        @endif
        <span>{{ $negocio->nombre }}</span>
      </a>
      <div class="vd-nav__links">
        <a href="#menu">Menú</a>
        <a href="#nosotros">Nosotros</a>
        <a href="#ubicacion">Ubicación</a>
      </div>
      @if ($telefonoContacto)
        <a href="tel:{{ $telefonoContacto }}" class="vd-btn">Pedir ahora</a>
      @endif
    </div>
  </nav>

  @php
    $tickerTexto = collect([$negocio->rubro, $negocio->horario, $negocio->ciudad])->filter()->implode(' · ');
  @endphp
  @if ($tickerTexto)
    <div class="vd-ticker" aria-hidden="true">
      <div class="vd-ticker__track">
        @for ($i = 0; $i < 6; $i++)
          <span class="vd-ticker__item"><span class="vd-ticker__dot"></span>{{ $tickerTexto }}</span>
        @endfor
      </div>
    </div>
  @endif

  <header class="vd-hero @if (empty($fotosHero)) vd-hero--empty @endif">
    @if (! empty($fotosHero))
      <div class="vd-hero__bg">
        @foreach ($fotosHero as $foto)
          <span style="background-image: url('{{ $foto }}')"></span>
        @endforeach
      </div>
    @endif
    <div class="vd-wrap vd-hero__content">
      @if ($negocio->rubro)
        <div class="vd-hero__eyebrow">{{ $negocio->rubro }}</div>
      @endif
      <h1>{{ $propiedades->header_titulo_1 ?: $negocio->nombre }}</h1>
      @if ($propiedades->header_subtitulo_1)
        <p>{{ $propiedades->header_subtitulo_1 }}</p>
      @endif
      <div class="vd-hero__ctas">
        @if (! empty($itemsMenu))
          <a href="#menu" class="vd-btn">Ver el menú</a>
        @endif
        @if ($telefonoContacto)
          <a href="tel:{{ $telefonoContacto }}" class="vd-btn vd-btn--ghost" style="color:#fdf8ee !important; border-color: rgba(253,248,238,.45);">Llamar / pedir</a>
        @endif
      </div>
    </div>
  </header>

  @if ($propiedades->body_titulo || $propiedades->body_parrafo_1)
    <section class="vd-section" id="nosotros">
      <div class="vd-wrap">
        <div class="vd-section__head vd-reveal">
          <span class="vd-eyebrow">Nosotros</span>
          @if ($propiedades->body_titulo)
            <h2>{{ $propiedades->body_titulo }}</h2>
          @endif
          @if ($propiedades->body_subtitulo)
            <p>{{ $propiedades->body_subtitulo }}</p>
          @endif
        </div>

        @php
          $parrafos = collect([
              $propiedades->body_parrafo_1,
              $propiedades->body_parrafo_2,
              $propiedades->body_parrafo_3,
              $propiedades->body_parrafo_4,
          ])->filter()->values();
        @endphp

        @if ($parrafos->isNotEmpty())
          <div class="vd-proceso">
            @foreach ($parrafos as $i => $parrafo)
              <div class="vd-proceso__card vd-reveal">
                <div class="vd-proceso__num">{{ sprintf('%02d', $i + 1) }}</div>
                <p>{{ $parrafo }}</p>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </section>
  @endif

  <section class="vd-section" id="menu" style="padding-top: 0;">
    <div class="vd-wrap">
      <div class="vd-section__head vd-reveal">
        <span class="vd-eyebrow">Carta</span>
        <h2>{{ $propiedades->body_titulo_2 ?: 'Lo que hacemos' }}</h2>
      </div>

      @if (! empty($itemsMenu))
        <div class="vd-menu">
          @foreach ($itemsMenu as $item)
            <div class="vd-card vd-reveal">
              <div class="vd-card__img @if (! $item['img']) vd-card__img--placeholder @endif">
                @if ($item['img'])
                  <img src="{{ $item['img'] }}" alt="{{ $item['titulo'] }}" loading="lazy">
                @else
                  <span style="font-family:'Poppins',sans-serif; font-weight:700;">{{ $negocio->nombre }}</span>
                @endif
              </div>
              <div class="vd-card__body">
                <div class="vd-card__top">
                  <h3>{{ $item['titulo'] }}</h3>
                  @if ($item['precio'])
                    <span class="vd-card__precio">$ {{ $item['precio'] }}</span>
                  @endif
                </div>
                @if ($item['parrafo'])
                  <p>{{ $item['parrafo'] }}</p>
                @endif
                @if ($telefonoContacto)
                  <a href="tel:{{ $telefonoContacto }}" class="vd-card__cta">Pedir este →</a>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      @else
        <p class="vd-menu-empty">Todavía no hay productos cargados.</p>
      @endif

      @if ($propiedades->body_tipos_medios_pago)
        <p class="vd-medios-pago"><strong>Medios de pago:</strong> {{ $propiedades->body_tipos_medios_pago }}</p>
      @endif
    </div>
  </section>

  @if ($negocio->direccion || $negocio->horario || $propiedades->body_maps)
    <section class="vd-info" id="ubicacion">
      <div class="vd-wrap vd-info__grid">
        @if ($negocio->direccion)
          <div class="vd-info__item">
            <h4>Dónde estamos</h4>
            <p>{{ $negocio->direccion }}@if($negocio->ciudad), {{ $negocio->ciudad }}@endif</p>
          </div>
        @endif
        @if ($negocio->horario)
          <div class="vd-info__item">
            <h4>Horario</h4>
            <p>{{ $negocio->horario }}</p>
          </div>
        @endif
        @if ($telefonoContacto)
          <div class="vd-info__item">
            <h4>Contacto</h4>
            <p><a href="tel:{{ $telefonoContacto }}">{{ $telefonoContacto }}</a></p>
          </div>
        @endif
      </div>
      @if ($propiedades->body_maps)
        <iframe class="vd-mapa" src="{{ $propiedades->body_maps }}" loading="lazy" allowfullscreen></iframe>
      @endif
    </section>
  @endif

  <footer class="vd-footer">
    <div class="vd-wrap vd-footer__row">
      <div class="vd-footer__brand">{{ $negocio->nombre }}</div>
      @if (collect($redes)->filter()->isNotEmpty())
        <div class="vd-social">
          @if ($redes['instagram'])
            <a href="{{ $redes['instagram'] }}" target="_blank" rel="noopener" aria-label="Instagram">
              <svg viewBox="0 0 24 24"><path d="M12 2.2c3.2 0 3.6 0 4.9.07 1.2.06 2.1.26 2.6.5.7.3 1.2.6 1.7 1.1.5.5.8 1 1.1 1.7.2.5.4 1.4.5 2.6.06 1.3.07 1.7.07 4.9s0 3.6-.07 4.9c-.06 1.2-.26 2.1-.5 2.6-.3.7-.6 1.2-1.1 1.7-.5.5-1 .8-1.7 1.1-.5.2-1.4.4-2.6.5-1.3.06-1.7.07-4.9.07s-3.6 0-4.9-.07c-1.2-.06-2.1-.26-2.6-.5-.7-.3-1.2-.6-1.7-1.1-.5-.5-.8-1-1.1-1.7-.2-.5-.4-1.4-.5-2.6C2.2 15.6 2.2 15.2 2.2 12s0-3.6.07-4.9c.06-1.2.26-2.1.5-2.6.3-.7.6-1.2 1.1-1.7.5-.5 1-.8 1.7-1.1.5-.2 1.4-.4 2.6-.5C8.4 2.2 8.8 2.2 12 2.2zm0 1.8c-3.15 0-3.52 0-4.76.07-1 .05-1.55.21-1.9.35-.48.19-.82.41-1.18.77-.36.36-.58.7-.77 1.18-.14.35-.3.9-.35 1.9C3 9.28 3 9.65 3 12.8v-1.6c0 3.15 0 3.52.07 4.76.05 1 .21 1.55.35 1.9.19.48.41.82.77 1.18.36.36.7.58 1.18.77.35.14.9.3 1.9.35 1.24.06 1.61.07 4.76.07s3.52 0 4.76-.07c1-.05 1.55-.21 1.9-.35.48-.19.82-.41 1.18-.77.36-.36.58-.7.77-1.18.14-.35.3-.9.35-1.9.06-1.24.07-1.61.07-4.76s0-3.52-.07-4.76c-.05-1-.21-1.55-.35-1.9-.19-.48-.41-.82-.77-1.18a3.2 3.2 0 0 0-1.18-.77c-.35-.14-.9-.3-1.9-.35C15.52 4 15.15 4 12 4zm0 3.4a4.6 4.6 0 1 1 0 9.2 4.6 4.6 0 0 1 0-9.2zm0 1.8a2.8 2.8 0 1 0 0 5.6 2.8 2.8 0 0 0 0-5.6zm4.8-2a1.08 1.08 0 1 1 0 2.16 1.08 1.08 0 0 1 0-2.16z"/></svg>
            </a>
          @endif
          @if ($redes['facebook'])
            <a href="{{ $redes['facebook'] }}" target="_blank" rel="noopener" aria-label="Facebook">
              <svg viewBox="0 0 24 24"><path d="M13.5 21v-7.9h2.66l.4-3.09h-3.06V8.05c0-.9.25-1.5 1.53-1.5h1.63V3.8c-.28-.04-1.25-.12-2.37-.12-2.35 0-3.96 1.43-3.96 4.06v2.27H7.7v3.09h2.63V21h3.17z"/></svg>
            </a>
          @endif
          @if ($redes['twitter'])
            <a href="{{ $redes['twitter'] }}" target="_blank" rel="noopener" aria-label="Twitter / X">
              <svg viewBox="0 0 24 24"><path d="M18.9 3H21l-6.5 7.4L22 21h-6.4l-5-6.4-5.7 6.4H2.7l6.9-7.9L2 3h6.6l4.5 5.9L18.9 3zm-1.1 16h1.6L7.3 4.9H5.6L17.8 19z"/></svg>
            </a>
          @endif
          @if ($redes['youtube'])
            <a href="{{ $redes['youtube'] }}" target="_blank" rel="noopener" aria-label="YouTube">
              <svg viewBox="0 0 24 24"><path d="M22 12s0-3.1-.4-4.6a2.8 2.8 0 0 0-2-2C17.9 5 12 5 12 5s-5.9 0-7.6.4a2.8 2.8 0 0 0-2 2C2 8.9 2 12 2 12s0 3.1.4 4.6c.2 1 1 1.7 2 2C6.1 19 12 19 12 19s5.9 0 7.6-.4a2.8 2.8 0 0 0 2-2C22 15.1 22 12 22 12zM10 15.5v-7l6 3.5-6 3.5z"/></svg>
            </a>
          @endif
          @if ($redes['linkedin'])
            <a href="{{ $redes['linkedin'] }}" target="_blank" rel="noopener" aria-label="LinkedIn">
              <svg viewBox="0 0 24 24"><path d="M6.94 8.5H3.56V20h3.38V8.5zM5.25 3.5a1.96 1.96 0 1 0 0 3.92 1.96 1.96 0 0 0 0-3.92zM20.5 20h-3.37v-5.9c0-1.4-.03-3.2-1.95-3.2-1.96 0-2.26 1.53-2.26 3.1V20H9.55V8.5h3.24v1.57h.05c.45-.86 1.56-1.77 3.21-1.77 3.43 0 4.06 2.26 4.06 5.2V20z"/></svg>
            </a>
          @endif
        </div>
      @endif
    </div>
    <p class="vd-footer__copy">{{ $negocio->direccion }}@if($negocio->direccion && $telefonoContacto) &middot; @endif{{ $telefonoContacto }}</p>
  </footer>

  <script>
    // Scroll-reveal liviano, sin dependencias -- respeta prefers-reduced-motion
    // (la clase .vd-reveal ya queda visible sin transición por CSS en ese caso).
    if ('IntersectionObserver' in window) {
      var obs = new IntersectionObserver(function (entradas) {
        entradas.forEach(function (entrada) {
          if (entrada.isIntersecting) {
            entrada.target.classList.add('is-visible');
            obs.unobserve(entrada.target);
          }
        });
      }, { threshold: 0.15 });
      document.querySelectorAll('.vd-reveal').forEach(function (el) { obs.observe(el); });
    } else {
      document.querySelectorAll('.vd-reveal').forEach(function (el) { el.classList.add('is-visible'); });
    }
  </script>

</body>

</html>
