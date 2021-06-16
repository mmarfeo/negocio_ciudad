<!doctype html>
<html lang="es">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <!-- <link href="css/all.css" rel="stylesheet"> -->
    <link href='{{asset("/css/all.css")}}' rel="stylesheet">
    
    <title>Negocios en tu ciudad</title>
        <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="img/negocio.ico"/>

    <!-- Add the slick-theme.css if you want default styling -->
   <!--  <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/> -->
    <!-- Add the slick-theme.css if you want default styling -->
    <!-- <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/> -->

    <style type="text/css">
        .home_header
        {
            background: url('{{asset("img/background.png")}}') no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            color: #ffffff;
            padding-bottom: 2em;
        }
        .home_header a {
            color: #ffffff;
        }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 32 32' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(255,255,255, 0.5)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 8h24M4 16h24M4 24h24'/%3E%3C/svg%3E");
        }
        .navbar-toggler {
            border-color: rgb(255,255,255);
        }
        .home_title {
            padding-top: 0.5em;
            padding-bottom:2em;
        }
        .home_title img {
            margin-bottom: 1em;
        }
        .home_search .input-group-text {
            background-color: #ffffff;
        }
        .home_business {
            padding-top: 2em;
            padding-bottom: 2em;
        }

        .business_container {
            padding: 1em;
            text-align: center;
            height: 270px;
        }

        .business_img {
            height: 180px;
            display: flex;
            justify-content: center;
        }

        .home_contact {
            background-color: #003245;
            color: #8c8c8c;
            padding-top: 1em;
            padding-bottom: 0.3em;
        }

        .modal_contact {
            background: url(img/background.png) no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            color: #ffffff;
        }
        .modal_contact a {
            color: #ffffff;
        }

        .texto-footer{
            color: white;
            font-size: 16px;
        }
        
        #texto-footer-mail{
            color: white;
            text-decoration: none;
        }

        #texto-footer-mail:hover{
            text-decoration: none;
            font-size: 16.3px;
        }

        #etiqueta-producto{
            text-decoration: none;
            color: #003245;
        }

/*         @media screen and (max-width:768){
        
            .business_img{
                width: 50px !important;
            }
        } */

/*         .products-container{
            margin
        } */

        #button-submit{
            border-radius: 0px !important; 
        }

        #buscar{
            border-radius: 0px !important;
        }

        #buscar{
            height: 35px;
        }
        #buscar::placeholder{
            font-size: 16px;
        }

        #buscar{
            font-size: 16px;
        }

        #button-submit{
            height: 35px;
        
        }

        .card-img-top{
            max-height: 90% !important;
            border-radius: 50%;
            /* object-fit: cover; */
            object-fit: contain;
        /* object-fit: fill; */
        /* object-fit: scale-down; */
        /* object-fit: initial; */
        min-height: 165 px;
         min-width: 165 px;
        
        }
        #logo{
            min-height: 165 px;
         min-width: 165 px;
        }
        
    </style>
    <title>Negocios en tu ciudad</title>
</head>
<body>
<div class="home_header">
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="/inicio"><img src='{{asset("img/logo_nav.png")}}' height="40"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="planes.html">Nuestros planes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="" data-toggle="modal" data-target="#exampleModal">Quiero mi Web</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container text-center">
        <div class="row home_title">
            <div class="col">
                <img src='{{asset("img/logo.png")}}' height="100">
                <h1>¿Qué negocio buscas?</h1>
            </div>
        </div>
        <div class="row home_search">
            <div class="col-md-8 offset-md-2">
 
                         <form  method="GET" action="{{ route('Buscar') }}" id="Buscador">
                                <div class="input-group input-group-lg">
                                    <input class="form-control" type="text" id="buscar" placeholder="Busca por rubro, por nombre, por categoría" aria-label="Search" />
                                    <button class="btn btn-light" id="button-submit" type="submit"><i class="fas fa-search"></i></button>
                                </div>
                        </form>
                              
            </div>
        </div>
    </div>
</div>

<div class="container text-center" id="loading" style="display: none; padding: 6em;">
    <div class="row">
        <div class="col">
            <div class="spinner-grow text-info" role="status">
                <span class="sr-only">Cargando...</span>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col">
            <div class="home_business" id="home_business">

            </div>
        </div>
    </div>
</div>




<div class="products-container col-12 row mb-5">
        @forelse ($products as $product)

         
                <div class=" col-xs-10 col-md-6 col-lg-2 business_container">
                    <div class="business_img">
                        <img id="logo" src="{{asset($product->nav_logo)}}" class="card-img-top" alt="{{asset($product->nav_logo)}}" onerror="this.onerror=null; this.src='{{asset('img/negocio.png')}}'" >
                    </div>
                    <div class="business_title">
                        <h5> {{$product->nombre}}</h5>
                    </div>
                    <div class="business_description"></div>
                  
                    <div class="business_button">
                        <a href="{{$product->url}}{{$product->slug}}" id="btn-entrar" class="btn btn-outline-info" target="_blank" onerror="this.onerror=null; this.href='{{$product->url}}'" ><i class="fas fa-paper-plane"></i> Entrar</a>
                    </div>
                   
                </div>
           <!--  </a> -->

        @empty
            <p>No se encontraron productos</p>
        @endforelse
</div>  



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Publicá con nosotros</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body modal_contact">
                <div class="container">
                    <div class="row">
                        <div class="col text-center">
                            <img src='{{asset("img/logo.png")}}' height="55"><br><br>
                            <center><h5>Para crear una página web para tu negocio o para publicitarlo, podes contactarnos al email:</h5></center>
                            <address>
                                <center><h5><a href="mailto:#">info@negociosentuciudad.com</a></h5></center>
                                <center>
                                    <h5 class="texto-footer">
                                        Teléfono
                                    </h5>
                                    <h5 class="texto-footer">
                                        1551506536 - 1566733688
                                    </h5>
                                </center>
                            </address>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<footer class="home_contact pt-5 pb-5" id="home_contact">
    <div class="container" >
        <div class="row">
            <div class="col-8 offset-2 col-sm-6 offset-sm-3	col-md-1 offset-md-0 col-lg-1 offset-lg-1 col-xl-2 offset-xl-0 text-center mb-2">
                <img src='{{asset("img/logo.png")}}' height="55">
            </div>

            <div class="col-8 offset-2 col-sm-6 offset-sm-0	col-md-4 offset-md-3 col-lg-3 offset-lg-2 col-xl-3 offset-xl-2 text-center">
                    <div class="texto-footer text-center">
                        <strong>Teléfono</strong>
                    </div>
                    <!-- <br> -->
                    <h5 class="texto-footer">
                        1551506536 - 1566733688
                    </h5>
            </div>

            <div class="col-8 offset-2 col-sm-6 offset-sm-0	col-md-3 offset-md-0 col-lg-3 offset-lg-2	col-xl-3 offset-xl-2 text-center">
                <address class="texto-footer">
                    <div class="text-center">
                        <strong>Email</strong>
                    </div>
                    <div class="text-center">
                        <a id="texto-footer-mail" href="mailto:#">info@negociosentuciudad.com</a>
                    </div>
                </address>
            </div>
        </div>
    </div>
</footer>

<!-- jQuery and Bootstrap Bundle (includes Popper) -->
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script> -->

<!-- <script src="js/config.js"></script>
<script src="js/funciones.js"></script>
<script src="js/home.js"></script> -->

<!-- <script type="application/javascript">
    let loading;
    $( document ).ready(function() {

        loading = $('#loading');
        slick_init();

        loading.show();
        let parametros = {
            c : 'negocios',
            a : 'listar'
        };
        get_data(parametros, mostrar_negocios);

        let input = document.getElementById('input_search');
        let timeout = null;
        input.addEventListener('keyup', function (e) {
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                buscar(input.value);
            }, 100);
        });

    });
</script> -->
<!-- </body>
</html> -->


<!-- jQuery and Bootstrap Bundle (includes Popper) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
<!-- <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script> -->

<script src="js/config.js"></script>
<script src="js/funciones.js"></script>
<script src="js/home.js"></script>

<!-- <script type="application/javascript">
    $( document ).ready(function() {

        $('.home_business').slick({
            dots: true,
            infinite: false,
            speed: 300,
            slidesToShow: 4,
            slidesToScroll: 4,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });

        let parametros = {
            c : 'negocios',
            a : 'listar'
        };
        get_data(parametros, mostrar_negocios);
    });
</script>
 -->
<script>
                var url= 'http://localhost:8000';

                function accion(){
                    document.getElementById("nav-java").style.display="none";
                //     for (var i = 0; i < ancla.length; i++){
                //         ancla[i].IdList.toogle("desaparece");
                //  var ancla =  }
                }

                window.addEventListener("load", function(){
                //Buscador
                $("#Buscador").submit(function () {
                   /*  $(this).attr('action', url + "/inicio/" + $('#Buscador #buscar').val()); */
                   $(this).attr('action', url + "/inicio/" + $('#Buscador #buscar').val());
                });
                })


             /*    $('#blogCarousel').carousel({
				interval: 5000
		        }); */

 </script>
    
<!--   <script>
    let negocios = @json($products);
         
    negocios.forEach(function(negocio){

        if(negocio.url == null || negocio.url == ""){ 
                $('#div-btn-entrar').append("<a id='btn-entrar' class='btn btn-outline-info' target='_blank' ><i class='fas fa-paper-plane'></i> Entrar</a>");
                $('#btn-entrar').prop("href", "/inicio/" + negocio.slug);
             }else{
                $('#div-btn-entrar').append("<a id='btn-entrar' class='btn btn-outline-info' target='_blank' ><i class='fas fa-paper-plane'></i> Entrar</a>");
                $('#btn-entrar').prop("href", negocio.url);
            }
    });
  </script>      -->   

</body>
</html>
