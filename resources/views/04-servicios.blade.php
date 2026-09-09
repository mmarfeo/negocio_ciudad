<?php $negocio = $products; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $negocio->nombre }}</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="{{ $propiedades->imagenUrl('favicon_logo') ?? asset('img/negocio.ico') }}"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@300;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/04-servicios/estilos.css') }}">
    @if ($propiedades->estiloPersonalizado())
        <style>{!! $propiedades->estiloPersonalizado() !!}</style>
    @endif
</head>

<body>
    @php $heroImg = $propiedades->imagenUrl('header_img_1'); @endphp
    <header class="hero"@if ($heroImg) style="background-image: url('{{ $heroImg }}')"@endif>
        <nav>
            <a href="#body_titulo_principal">Acerca de</a>
            @if ($propiedades->body_titulo_nuestros_trabajos)
                <a id="trabajos" href="#nuestros_trabajos">Trabajos</a>
            @endif
            <a href="#contenedor-telefono">Contacto</a>
        </nav>
        <div class="textos-hero">
            <h1>{{ $negocio->nombre }}</h1>
            @if ($propiedades->header_subtitulo_1)
                <p>{{ $propiedades->header_subtitulo_1 }}</p>
            @endif
            <a href="#contenedor-telefono">Contactame</a>
        </div>
        <div class="svg-hero" style="height: 150px; overflow: hidden;"><svg viewBox="0 0 500 150" preserveAspectRatio="none"
                style="height: 100%; width: 100%;">
                <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z"
                    style="stroke: none; fill: #fff;"></path>
            </svg></div>
    </header>
    @if ($propiedades->header_titulo_2 || $propiedades->header_subtitulo_2)
        <div>
            @if ($propiedades->header_titulo_2)
                <h2 class="titulo"> {{ $propiedades->header_titulo_2 }} </h2>
            @endif
            @if ($propiedades->header_subtitulo_2)
                <p class="subtitulo"> {{ $propiedades->header_subtitulo_2 }} </p>
            @endif
        </div>
    @endif

    @if ($propiedades->body_titulo || $propiedades->imagenUrl('body_tarjeta_img_1'))
        <section class="wave-contenedor website">
            @if ($propiedades->imagenUrl('body_tarjeta_img_1'))
                <img src="{{ $propiedades->imagenUrl('body_tarjeta_img_1') }}" alt="{{ $propiedades->body_titulo }}" onerror="this.onerror=null;this.src='{{ asset('img/placeholder.svg') }}'">
            @endif
            <div class="contenedor-textos-main">
                <h2 class="titulo left">{{ $propiedades->body_titulo }}</h2>
                <p class="parrafo">{{ $propiedades->body_parrafo_1 }}</p>
            </div>
        </section>
    @endif

    @if ($propiedades->body_titulo_2 || $propiedades->imagenUrl('body_tarjeta_img_2'))
        <section class="wave-contenedor website">
            <div class="contenedor-textos-main">
                <h2 class="titulo left">{{ $propiedades->body_titulo_2 }}</h2>
                <p class="parrafo">{{ $propiedades->body_parrafo_2 }}</p>
            </div>
            @if ($propiedades->imagenUrl('body_tarjeta_img_2'))
                <img src="{{ $propiedades->imagenUrl('body_tarjeta_img_2') }}" alt="{{ $propiedades->body_titulo_2 }}" onerror="this.onerror=null;this.src='{{ asset('img/placeholder.svg') }}'">
            @endif
        </section>
    @endif

    @if ($propiedades->body_titulo_3 || $propiedades->imagenUrl('body_tarjeta_img_3'))
        <section class="wave-contenedor website">
            <div class="contenedor last-section">
                @if ($propiedades->imagenUrl('body_tarjeta_img_3'))
                    <img src="{{ $propiedades->imagenUrl('body_tarjeta_img_3') }}" alt="{{ $propiedades->body_titulo_3 }}" onerror="this.onerror=null;this.src='{{ asset('img/placeholder.svg') }}'">
                @endif
                <div class="contenedor-textos-main">
                    <h2 class="titulo left">{{ $propiedades->body_titulo_3 }}</h2>
                    <p class="parrafo">{{ $propiedades->body_parrafo_3 }}</p>
                </div>
            </div>
        </section>
    @endif

    @if ($propiedades->body_titulo_4 || $propiedades->imagenUrl('body_tarjeta_img_4'))
        <section class="wave-contenedor website">
            <div class="contenedor last-section">
                <div class="contenedor-textos-main">
                    <h2 class="titulo left">{{ $propiedades->body_titulo_4 }}</h2>
                    <p class="parrafo">{{ $propiedades->body_parrafo_4 }}</p>
                </div>
                @if ($propiedades->imagenUrl('body_tarjeta_img_4'))
                    <img src="{{ $propiedades->imagenUrl('body_tarjeta_img_4') }}" alt="{{ $propiedades->body_titulo_4 }}" onerror="this.onerror=null;this.src='{{ asset('img/placeholder.svg') }}'">
                @endif
            </div>
        </section>
    @endif

    @if ($propiedades->body_titulo_nuestros_trabajos)
        <section id="nuestros_trabajos" class="galeria">
            <div class="contenedor">
                <h2 class="titulo">{{ $propiedades->body_titulo_nuestros_trabajos }}</h2>
                <article class="galeria-cont">
                    @for ($n = 5; $n <= 7; $n++)
                        @php $img = $propiedades->imagenUrl("body_tarjeta_img_{$n}"); @endphp
                        @if ($img)
                            <img src="{{ $img }}" alt="{{ $propiedades->body_titulo_nuestros_trabajos }}" onerror="this.onerror=null;this.src='{{ asset('img/placeholder.svg') }}'">
                        @endif
                    @endfor
                </article>
            </div>
        </section>
    @endif

    @if ($propiedades->body_maps)
        <div class="col" id="col-maps">
            <iframe src="{{ $propiedades->body_maps }}" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
        </div>
    @endif

    <footer>
        <div class="contenedor-footer">
            @if ($negocio->telefono)
                <div id="contenedor-telefono" class="content-foo">
                    <h4>Teléfono</h4>
                    <p>{{ $negocio->telefono }}</p>
                </div>
            @endif
            @if ($negocio->email)
                <div id="contenedor-email" class="content-foo">
                    <h4>Email</h4>
                    <p>{{ $negocio->email }}</p>
                </div>
            @endif
            @if ($negocio->direccion)
                <div id="contenedor-direccion" class="content-foo">
                    <h4>Dirección</h4>
                    <p>{{ $negocio->direccion }}</p>
                </div>
            @endif
        </div>
       <div class="col-12 col-sm-12 col-md-12 col-lg-4 offset-lg-4 align-self-center">
            <h2 class="titulo-final"> <center>Redes sociales</center> </h2>
            <div class="contenedor-redes col-lg-2">
                @if ($propiedades->footer_redes_facebook)
                    <a href="{{ $propiedades->footer_redes_facebook }}" target="_blank" class="redes"><i class="fab fa-facebook-f fa-lg"></i></a>
                @endif
                @if ($propiedades->footer_redes_instagram)
                    <a href="{{ $propiedades->footer_redes_instagram }}" target="_blank" class="redes"><i class="fab fa-instagram fa-lg"></i></a>
                @endif
                @if ($propiedades->footer_redes_twitter)
                    <a href="{{ $propiedades->footer_redes_twitter }}" target="_blank" class="redes"><i class="fab fa-twitter fa-lg"></i></a>
                @endif
                @if ($propiedades->footer_redes_youtube)
                    <a href="{{ $propiedades->footer_redes_youtube }}" target="_blank" class="redes"><i class="fab fa-youtube fa-lg"></i></a>
                @endif
                @if ($propiedades->footer_redes_linkedin)
                    <a href="{{ $propiedades->footer_redes_linkedin }}" target="_blank" class="redes"><i class="fab fa-linkedin fa-lg"></i></a>
                @endif
            </div>
       </div>
    </footer>


    <!-- jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://kit.fontawesome.com/c15b744a04.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
</body>

</html>
