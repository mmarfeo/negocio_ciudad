<?php $negocio = $products; ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>{{ $negocio->nombre }}</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="{{ $propiedades->imagenUrl('favicon_logo') ?? asset('img/negocio.ico') }}"/>
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="{{ asset('css/05-tarjeta/styles.css') }}" rel="stylesheet" />
        <link href="{{ asset('css/05-tarjeta/negocios-overrides.css') }}" rel="stylesheet" />
        @if ($propiedades->estiloPersonalizado())
            <style>{!! $propiedades->estiloPersonalizado() !!}</style>
        @endif
    </head>
    <body id="page-top" class="bg-info">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">
            <div class="container">
                <h4 class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger text-white">{{ $negocio->profesion }}</h4>

                <button class="navbar-toggler navbar-toggler-right text-uppercase font-weight-bold bg-info text-white rounded" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Redes Sociales
                    <i class="fas fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item mx-0 mx-lg-1">
                            <h5 class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger text-white">Mis Redes</h5>
                        </li>
                        <li class="nav-item mx-0 mx-lg-1">
                            @if ($propiedades->footer_redes_facebook)
                                <a href="{{ $propiedades->footer_redes_facebook }}" target="_blank" class="btn btn-outline-light btn-social mx-1"><i class="fab fa-fw fa-facebook-f"></i></a>
                            @endif
                            @if ($propiedades->footer_redes_instagram)
                                <a href="{{ $propiedades->footer_redes_instagram }}" target="_blank" class="btn btn-outline-light btn-social mx-1"><i class="fab fa-fw fa-instagram"></i></a>
                            @endif
                            @if ($propiedades->footer_redes_twitter)
                                <a href="{{ $propiedades->footer_redes_twitter }}" target="_blank" class="btn btn-outline-light btn-social mx-1"><i class="fab fa-fw fa-twitter"></i></a>
                            @endif
                            @if ($propiedades->footer_redes_youtube)
                                <a href="{{ $propiedades->footer_redes_youtube }}" target="_blank" class="btn btn-outline-light btn-social mx-1"><i class="fab fa-fw fa-youtube"></i></a>
                            @endif
                            @if ($propiedades->footer_redes_linkedin)
                                <a href="{{ $propiedades->footer_redes_linkedin }}" target="_blank" class="btn btn-outline-light btn-social mx-1"><i class="fab fa-fw fa-linkedin-in"></i></a>
                            @endif
                        </li>

                    </ul>
                </div>
            </div>
        </nav>


        <!-- About Section-->
        <header class="masthead bg-info text-white text-center">
            <div class="container text-left">
                <h2 class="page-section-heading text-center text-uppercase text-white">{{ $negocio->nombre }}</h2>

                <div class="divider-custom divider-light">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 ml-auto">
                        <img src="{{ $propiedades->imagenUrl('nav_logo') ?? asset('img/logo.png') }}" class="masthead-avatar mb-2 pt-3" alt="{{ $negocio->nombre }}"/>
                    </div>
                    <div class="col-lg-6 mr-auto">
                        <div class="row">
                            <div class="col-lg-10 mx-auto">

                                    <div class="control-group">
                                        <div class="form-group floating-label-form-group controls mb-0 pb-2  control-group">
                                            <h5>Profesión: {{ $negocio->profesion }}</h5>
                                        </div>
                                        <br>
                                    </div>

                                    <div class="control-group">
                                        <div class="form-group floating-label-form-group controls mb-0 pb-2  control-group">
                                            <h5>Nombre: {{ $negocio->nombre }}</h5>
                                        </div>
                                        <br>
                                    </div>

                                    @if ($negocio->telefono || $negocio->celular)
                                        <div class="control-group">
                                            <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                                <h5>Teléfono: {{ $negocio->telefono }} @if($negocio->telefono && $negocio->celular) - @endif {{ $negocio->celular }}</h5>
                                            </div>
                                            <br>
                                        </div>
                                    @endif

                                    <div class="control-group">
                                        <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                            <h5>Dirección: {{ $negocio->direccion }} - {{ $negocio->ciudad }}</h5>
                                        </div>
                                        <br>
                                    </div>

                                    @if ($negocio->email)
                                        <div class="control-group" id="control-group-email" >
                                            <div class="form-group floating-label-form-group controls mb-0 pb-2"  >
                                                <h5>Email: {{ $negocio->email }}</h5>
                                            </div>
                                            <br>
                                        </div>
                                    @endif

                            </div>
                        </div>
                    </div>

                </div>
                <div class="divider-custom divider-light">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>
            </div>

    </header>

        @if ($propiedades->body_maps)
            <div class="col" id="col-maps">
                <iframe src="{{ $propiedades->body_maps }}" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
            </div>
        @endif

        <!-- Copyright Section-->
        <div class="copyright py-4 text-center text-white bg-info">
            <div class="container"><h6>Sitio creado por Negocios en tu Ciudad</h6></div>
        </div>
        <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes)-->
        <div class="scroll-to-top d-lg-none position-fixed">
            <a class="js-scroll-trigger d-block text-center text-white rounded" href="#page-top"><i class="fa fa-chevron-up"></i></a>
        </div>

        <!-- Bootstrap core JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Third party plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    </body>
</html>
