<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title id="favicon_nombre"></title>
  <link rel="icon" type="image/x-icon" id="favicon_icon"/>

  <!-- Bootstrap core CSS -->
<!--   <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
 -->
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

  <!-- Custom styles for this template -->
  <link href="css/03-productos/estilos.css" rel="stylesheet">


</head>

<body id="plantilla_productos">

  <!-- Navigation -->
  <!-- <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top"> -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    
    <div class="container">
      
      <a class="navbar-brand" href="#"><img id="nav_logo" height="40"></a>
      <a  href="#"><h5 class="text-white" id="title_nombre"></h5></a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link active" href="#">Inicio
              <!-- <span class="sr-only">(current)</span> -->
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="#fila-2">Contacto</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <header>
    <div id="carouselExampleIndicators" class="carousel slide mb-0" data-ride="carousel">
      <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img class="d-block w-100" id="header_img_1" alt="First slide">
          <div class="carousel-caption d-none d-md-block">
            <h1 id="nombre"></h1>
            <p id="header_subtitulo_1"></p>
          </div>
        </div>
        <div class="carousel-item ">
          <img class="d-block w-100" id="header_img_2"  alt="First slide">
          <div class="carousel-caption d-none d-md-block">
            <h1 id="header_titulo_2"></h1>
            <p id="header_subtitulo_2"></p>
          </div>
        </div>
        <div class="carousel-item ">
          <img class="d-block w-100" id="header_img_3"  alt="First slide">
          <div class="carousel-caption d-none d-md-block">
            <h1 id="header_titulo_3"></h1>
            <p id="header_subtitulo_3"></p>
          </div>
        </div>
      </div>
    </div>
  </header>
  <!-- Page Content -->
  <div class="container">

    <!-- Jumbotron Header -->
<!--     <header class="jumbotron my-4"> -->


    <div class="jumbotron">
      <center><h1 class="display-3" id="body_titulo"></h1> </center> 
      <center><p class="lead" id="body_subtitulo"></p></center>
      <hr class="my-4" color="#fff">

          <!-- Page Features -->
    <div class="row text-center">


     
      <div class="col-lg-3 col-md-6 mb-4">
        <div class="card h-100">
          <img class="card-img-top" id="body_tarjeta_img_1" alt="">
          <div class="card-body">
            <h4 class="card-title" id="body_tarjeta_titulo_1"></h4>
            <p class="card-text" id="body_tarjeta_parrafo_1"></p>
          </div>
          <div class="card-footer">
            <span class="badge badge-pill badge-primary" id="body_tarjeta_precio_1"></span>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 mb-4">
        <div class="card h-100">
          <img class="card-img-top" id="body_tarjeta_img_2" alt="">
          <div class="card-body">
            <h4 class="card-title" id="body_tarjeta_titulo_2"></h4>
            <p class="card-text" id="body_tarjeta_parrafo_2"></p>
          </div>
          <div class="card-footer">
            <span class="badge badge-pill badge-primary" id="body_tarjeta_precio_2"></span>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 mb-4">
        <div class="card h-100">
          <img class="card-img-top" id="body_tarjeta_img_3" alt="">
          <div class="card-body">
            <h4 class="card-title" id="body_tarjeta_titulo_3"></h4>
            <p class="card-text" id="body_tarjeta_parrafo_3"></p>
          </div>
          <div class="card-footer">
            <span class="badge badge-pill badge-primary" id="body_tarjeta_precio_3"></span>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 mb-4">
        <div class="card h-100">
          <img class="card-img-top" id="body_tarjeta_img_4" alt="">
          <div class="card-body">
            <h4 class="card-title" id="body_tarjeta_titulo_4"></h4>
            <p class="card-text" id="body_tarjeta_parrafo_4"></p>
          </div>
          <div class="card-footer">
            <span class="badge badge-pill badge-primary" id="body_tarjeta_precio_4"></span>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 mb-4">
        <div class="card h-100">
          <img class="card-img-top" id="body_tarjeta_img_5" alt="">
          <div class="card-body">
            <h4 class="card-title" id="body_tarjeta_titulo_5"></h4>
            <p class="card-text" id="body_tarjeta_parrafo_5"></p>
          </div>
          <div class="card-footer">
            <span class="badge badge-pill badge-primary" id="body_tarjeta_precio_5"></span>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 mb-4">
        <div class="card h-100">
          <img class="card-img-top" id="body_tarjeta_img_6" alt="">
          <div class="card-body">
            <h4 class="card-title" id="body_tarjeta_titulo_6"></h4>
            <p class="card-text" id="body_tarjeta_parrafo_6"></p>
          </div>
          <div class="card-footer">
            <span class="badge badge-pill badge-primary" id="body_tarjeta_precio_6"></span>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 mb-4">
        <div class="card h-100">
          <img class="card-img-top" id="body_tarjeta_img_7" alt="">
          <div class="card-body">
            <h4 class="card-title" id="body_tarjeta_titulo_7"></h4>
            <p class="card-text" id="body_tarjeta_parrafo_7"></p>
          </div>
          <div class="card-footer">
            <span class="badge badge-pill badge-primary" id="body_tarjeta_precio_7"></span>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 mb-4">
        <div class="card h-100">
          <img class="card-img-top" id="body_tarjeta_img_8" alt="">
          <div class="card-body">
            <h4 class="card-title" id="body_tarjeta_titulo_8"></h4>
            <p class="card-text" id="body_tarjeta_parrafo_8"></p>
          </div>
          <div class="card-footer">
            <span class="badge badge-pill badge-primary" id="body_tarjeta_precio_8"></span>
          </div>
        </div>
      </div>
    </div>

    <center>
      <h3 class="lead" id="medios_pago">
        Medios de pago
      </h3>
      <p id="body_tipos_medios_pago">

      </p>
    </center>
    

    </div>
  </div>
  <!-- /.container -->

  <div class="col" id="col-maps">
    <iframe id="body_maps" src="" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
  </div>

  <footer class="py-5 bg-dark">
    <div class="footer-contenedor">
      <div class="row" id="fila-1">

        <div class="contenedor-redes col-12 col-sm-12 col-md-12 col-lg-4 offset-lg-4 align-self-center">
          <h4><center>Redes sociales</center></h4>
          <div id="social-media" class="social-media">
            <center>
            </center>      
          </div>
          <hr/>
      </div>
    </div>
      <div class="row" id="fila-2">
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 offset-lg-3 align-self-center">
          <center>
            <h6 id="footer_info_negocio"></h6>
            <h6 id="footer-email"></h6>
          </center>
        </div>
      </div>
   
  </footer>


  <!-- jQuery and Bootstrap Bundle (includes Popper) -->
  <script src="https://kit.fontawesome.com/c15b744a04.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
  <script src="../../js/config.js"></script>
	<script src="../../js/funciones.js"></script>


  <script>

  /* aca estoy convirtiendo una variable de php a js y esa variable la estoy usando en la vista */
    let negocio = @json($products);
    let propiedades = @json($propiedades);

      $('#favicon_nombre').html(negocio.nombre);
      $('#title_nombre').html(negocio.nombre);
      $('#nombre').html(negocio.nombre);
      $('#telefono').html(negocio.telefono);
      $('#footer_info_negocio').html(`${negocio.direccion} - ${negocio.telefono} `);
      $('#footer-email').html(negocio.email);


      let ruta_img = "img/03-productos/" + negocio.slug + "/"

      /* favicon */
      $('#favicon_icon').prop("href",  ruta_img + propiedades.favicon_logo);

      /* variables para el nav */
       if(propiedades.nav_logo == null || propiedades.nav_logo == "" ){ 
          $('#nav_logo').prop("src",  "img/logo.png");
       }else{
          $('#nav_logo').prop("src",  ruta_img + propiedades.nav_logo);
       }

 
       /* variables para el header */
       $('#header_img_1').prop("src",  ruta_img + propiedades.header_img_1);
       $('#header_subtitulo_1').html(propiedades.header_subtitulo_1);

       $('#header_img_2').prop("src",  ruta_img + propiedades.header_img_2);
       $('#header_titulo_2').html(propiedades.header_titulo_2);
       $('#header_subtitulo_2').html(propiedades.header_subtitulo_2);

       $('#header_img_3').prop("src",  ruta_img + propiedades.header_img_3);
       $('#header_titulo_3').html(propiedades.header_titulo_3);
       $('#header_subtitulo_3').html(propiedades.header_subtitulo_3);

       /* variables para el body*/
       $('#body_titulo').html(propiedades.body_titulo);
       $('#body_subtitulo').html(propiedades.body_subtitulo);

       /* variables para las tarjetas del body*/
       $('#body_tarjeta_img_1').prop("src",  ruta_img + propiedades.body_tarjeta_img_1);
       $('#body_tarjeta_titulo_1').html(propiedades.body_tarjeta_titulo_1);
       $('#body_tarjeta_parrafo_1').html(propiedades.body_tarjeta_parrafo_1);
       $('#body_tarjeta_precio_1').html("$ " + propiedades.body_tarjeta_precio_1);

       $('#body_tarjeta_img_2').prop("src",  ruta_img + propiedades.body_tarjeta_img_2);
       $('#body_tarjeta_titulo_2').html(propiedades.body_tarjeta_titulo_2);
       $('#body_tarjeta_parrafo_2').html(propiedades.body_tarjeta_parrafo_2);
       $('#body_tarjeta_precio_2').html("$ " + propiedades.body_tarjeta_precio_2);

       $('#body_tarjeta_img_3').prop("src",  ruta_img + propiedades.body_tarjeta_img_3);
       $('#body_tarjeta_titulo_3').html(propiedades.body_tarjeta_titulo_3);
       $('#body_tarjeta_parrafo_3').html(propiedades.body_tarjeta_parrafo_3);
       $('#body_tarjeta_precio_3').html("$ " + propiedades.body_tarjeta_precio_3);

       $('#body_tarjeta_img_4').prop("src",  ruta_img + propiedades.body_tarjeta_img_4);
       $('#body_tarjeta_titulo_4').html(propiedades.body_tarjeta_titulo_4);
       $('#body_tarjeta_parrafo_4').html(propiedades.body_tarjeta_parrafo_4);
       $('#body_tarjeta_precio_4').html("$ " + propiedades.body_tarjeta_precio_4);

       $('#body_tarjeta_img_5').prop("src",  ruta_img + propiedades.body_tarjeta_img_5);
       $('#body_tarjeta_titulo_5').html(propiedades.body_tarjeta_titulo_5);
       $('#body_tarjeta_parrafo_5').html(propiedades.body_tarjeta_parrafo_5);
       $('#body_tarjeta_precio_5').html("$ " + propiedades.body_tarjeta_precio_5);

       $('#body_tarjeta_img_6').prop("src",  ruta_img + propiedades.body_tarjeta_img_6);
       $('#body_tarjeta_titulo_6').html(propiedades.body_tarjeta_titulo_6);
       $('#body_tarjeta_parrafo_6').html(propiedades.body_tarjeta_parrafo_6);
       $('#body_tarjeta_precio_6').html("$ " + propiedades.body_tarjeta_precio_6);

       $('#body_tarjeta_img_7').prop("src",  ruta_img + propiedades.body_tarjeta_img_7);
       $('#body_tarjeta_titulo_7').html(propiedades.body_tarjeta_titulo_7);
       $('#body_tarjeta_parrafo_7').html(propiedades.body_tarjeta_parrafo_7);
       $('#body_tarjeta_precio_7').html("$ " + propiedades.body_tarjeta_precio_7);

       $('#body_tarjeta_img_8').prop("src",  ruta_img + propiedades.body_tarjeta_img_8);
       $('#body_tarjeta_titulo_8').html(propiedades.body_tarjeta_titulo_8);
       $('#body_tarjeta_parrafo_8').html(propiedades.body_tarjeta_parrafo_8);
       
       if(propiedades.body_tarjeta_precio_8 == null){ 
          $('#body_tarjeta_precio_8').remove('span');
       }else{
          $('#body_tarjeta_precio_8').html("$ " + propiedades.body_tarjeta_precio_8);
      }

      /* Medios de pago*/
      if(propiedades.body_tipos_medios_pago == null ){ 
          $('#medios_pago').remove('#medios_pago');
       }else{
          $('#body_tipos_medios_pago').html(propiedades.body_tipos_medios_pago);
      }

       /* variables para google maps*/
       let estilos_map= "width='100%' height='450' frameborder='0' style='border:0;' allowfullscreen='' aria-hidden='false' tabindex='0'"
       $('#body_maps').prop("src", propiedades.body_maps + ' ' + estilos_map);

       /* variables para el footer */
      
       if(propiedades.footer_redes_facebook == null){ 
          $('#footer_redes_facebook').remove('#footer_redes_facebook');
       }else{
          $('#social-media').append("<a id='footer_redes_facebook' target='_blank' class='redes'> <i  class='fab fa-facebook-f fa-lg'></i> </a>");
          $('#footer_redes_facebook').prop("href", propiedades.footer_redes_facebook);
      }

      if(propiedades.footer_redes_instagram == null){ 
          $('#footer_redes_instagram').remove('#footer_redes_instagram');
       }else{
          $('#social-media').append("<a id='footer_redes_instagram' target='_blank' class='redes'> <i  class='fab fa-instagram fa-lg'></i> </a>");
          $('#footer_redes_instagram').prop("href", propiedades.footer_redes_instagram);
      }

      if(propiedades.footer_redes_twitter == null){ 
          $('#footer_redes_twitter').remove('#footer_redes_twitter');
       }else{
          $('#social-media').append("<a id='footer_redes_twitter' target='_blank' class='redes'> <i  class='fab fa-twitter fa-lg'></i> </a>");
          $('#footer_redes_twitter').prop("href", propiedades.footer_redes_twitter);
      }
       
      if(propiedades.footer_redes_youtube == null){ 
          $('#footer_redes_youtube').remove('#footer_redes_youtube');
       }else{
          $('#social-media').append("<a id='footer_redes_youtube' target='_blank' class='redes'> <i  class='fab fa-youtube fa-lg'></i> </a>");
          $('#footer_redes_youtube').prop("href", propiedades.footer_redes_youtube);
      }

      if(propiedades.footer_redes_linkedin == null){ 
          $('#footer_redes_linkedin').remove('#footer_redes_youtube');
       }else{
          $('#social-media').append("<a id='footer_redes_linkedin' target='_blank' class='redes'> <i  class='fab fa-linkedin fa-lg'></i> </a>");
          $('#footer_redes_linkedin').prop("href", propiedades.footer_redes_linkedin);
      }
    
  </script>

</body>

</html>
