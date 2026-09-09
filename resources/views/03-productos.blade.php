<?php $negocio = $products; ?>
<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>{{ $negocio->nombre }}</title>
  <link rel="icon" type="image/x-icon" href="{{ $propiedades->imagenUrl('favicon_logo') ?? asset('img/negocio.ico') }}"/>

  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

  <!-- Google fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="{{ asset('css/03-productos/estilos.css') }}" rel="stylesheet">
  <link href="{{ asset('css/03-productos/negocios-overrides.css') }}" rel="stylesheet">

  @if ($propiedades->estiloPersonalizado())
    <style>{!! $propiedades->estiloPersonalizado() !!}</style>
  @endif

</head>

<body id="plantilla_productos">

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">

    <div class="container">

      <a class="navbar-brand" href="#">
        @if ($propiedades->imagenUrl('nav_logo'))
          <img height="40" src="{{ $propiedades->imagenUrl('nav_logo') }}" alt="{{ $negocio->nombre }}">
        @endif
      </a>
      <a href="#"><h5 class="text-white">{{ $negocio->nombre }}</h5></a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link active" href="#">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="#fila-2">Contacto</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  @php
    // Solo se arman slides para las imágenes que realmente están cargadas --
    // antes se imprimían siempre las 3 aunque estuvieran vacías, dejando el
    // <img> con src="" (ícono roto) en la sección más visible de la página.
    $slidesHeader = [];
    for ($n = 1; $n <= 3; $n++) {
      $imgHeader = $propiedades->imagenUrl("header_img_{$n}");
      if ($imgHeader) {
        $slidesHeader[] = [
          'img' => $imgHeader,
          'titulo' => $n === 1 ? $negocio->nombre : $propiedades->{"header_titulo_{$n}"},
          'subtitulo' => $propiedades->{"header_subtitulo_{$n}"},
        ];
      }
    }
  @endphp
  <header>
    @if (count($slidesHeader) > 0)
      <div id="carouselExampleIndicators" class="carousel slide mb-0" data-ride="carousel">
        @if (count($slidesHeader) > 1)
          <ol class="carousel-indicators">
            @foreach ($slidesHeader as $i => $slide)
              <li data-target="#carouselExampleIndicators" data-slide-to="{{ $i }}" class="{{ $i === 0 ? 'active' : '' }}"></li>
            @endforeach
          </ol>
        @endif
        <div class="carousel-inner">
          @foreach ($slidesHeader as $i => $slide)
            <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
              <img class="d-block w-100" src="{{ $slide['img'] }}" alt="{{ $negocio->nombre }}">
              @if ($slide['titulo'] || $slide['subtitulo'])
                <div class="carousel-caption d-none d-md-block">
                  @if ($slide['titulo'])
                    <h1>{{ $slide['titulo'] }}</h1>
                  @endif
                  @if ($slide['subtitulo'])
                    <p>{{ $slide['subtitulo'] }}</p>
                  @endif
                </div>
              @endif
            </div>
          @endforeach
        </div>
      </div>
    @else
      <div class="jumbotron text-center mb-0 rounded-0 bg-dark text-white py-5">
        <h1 class="display-4">{{ $negocio->nombre }}</h1>
        @if ($propiedades->header_subtitulo_1)
          <p class="lead">{{ $propiedades->header_subtitulo_1 }}</p>
        @endif
      </div>
    @endif
  </header>
  <!-- Page Content -->
  <div class="container">

    <div class="jumbotron">
      <center><h1 class="display-3">{{ $propiedades->body_titulo }}</h1></center>
      <center><p class="lead">{{ $propiedades->body_subtitulo }}</p></center>
      <hr class="my-4" color="#fff">

      <!-- Page Features -->
      <div class="row text-center">

        @for ($n = 1; $n <= 8; $n++)
          @php $titulo = $propiedades->{"body_tarjeta_titulo_{$n}"}; @endphp
          @if ($titulo)
            <div class="col-lg-3 col-md-6 mb-4">
              <div class="card h-100">
                <img class="card-img-top" src="{{ $propiedades->imagenUrl("body_tarjeta_img_{$n}") }}" alt="{{ $titulo }}">
                <div class="card-body">
                  <h4 class="card-title">{{ $titulo }}</h4>
                  <p class="card-text">{{ $propiedades->{"body_tarjeta_parrafo_{$n}"} }}</p>
                </div>
                @if ($propiedades->{"body_tarjeta_precio_{$n}"})
                  <div class="card-footer">
                    <span class="badge badge-pill badge-primary">$ {{ $propiedades->{"body_tarjeta_precio_{$n}"} }}</span>
                  </div>
                @endif
              </div>
            </div>
          @endif
        @endfor
      </div>

      @if ($propiedades->body_tipos_medios_pago)
        <center>
          <h3 class="lead">Medios de pago</h3>
          <p>{{ $propiedades->body_tipos_medios_pago }}</p>
        </center>
      @endif

    </div>
  </div>
  <!-- /.container -->

  @if ($propiedades->body_maps)
    <div class="col" id="col-maps">
      <iframe src="{{ $propiedades->body_maps }}" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
    </div>
  @endif

  <footer class="py-5 bg-dark">
    <div class="footer-contenedor">
      <div class="row" id="fila-1">

        <div class="contenedor-redes col-12 col-sm-12 col-md-12 col-lg-4 offset-lg-4 align-self-center">
          <h4><center>Redes sociales</center></h4>
          <div class="social-media">
            <center>
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
            </center>
          </div>
          <hr/>
        </div>
      </div>
      <div class="row" id="fila-2">
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 offset-lg-3 align-self-center">
          <center>
            <h6>{{ $negocio->direccion }} - {{ $negocio->telefono }}</h6>
            <h6>{{ $negocio->email }}</h6>
          </center>
        </div>
      </div>

  </footer>

  <!-- jQuery and Bootstrap Bundle (includes Popper) -->
  <script src="https://kit.fontawesome.com/c15b744a04.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

</body>

</html>
