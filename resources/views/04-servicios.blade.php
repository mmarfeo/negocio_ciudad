<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Landing Page HTML | AlexCG Design</title>
    <link href="https://fonts.googleapis.com/css?family=Raleway:200,300,400,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/04-servicios/estilos.css">
</head>

<body>
    <header class="hero" id="header_img_1">
        <nav>
            <!-- <a href="#">Inicio</a> -->
            <a href="#body_titulo_principal">Acerca de</a>
            <a id="trabajos" href="#nuestros_trabajos">Trabajos</a>
            <!-- <a href="#">Servicios</a> -->
            <a href="#contenedor-telefono">Contacto</a>
        </nav>
        <div class="textos-hero">
            <h1 id="title_nombre"></h1>
            <p id="header_subtitulo_1"></p>
            <a href="#contenedor-telefono">Contactame</a>
        </div>
        <div class="svg-hero" style="height: 150px; overflow: hidden;"><svg viewBox="0 0 500 150" preserveAspectRatio="none"
                style="height: 100%; width: 100%;">
                <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z"
                    style="stroke: none; fill: #fff;"></path>
            </svg></div>
    </header>
    <div>
        <h2 id="body_titulo_principal" class="titulo"> </h2>
        <p id="body_parrafo_principal" class="subtitulo"> </p>
    </div>

    <section class="wave-contenedor website">
        <img id="body_tarjeta_img_1" alt="">    
        <div class="contenedor-textos-main">
            <h2 id="body_titulo_1" class="titulo left"></h2>
            <p id="body_parrafo_1" class="parrafo"></p>
        </div>
    </section>

    <section class="wave-contenedor website">
        <div class="contenedor-textos-main">
            <h2 id="body_titulo_2" class="titulo left"></h2>
            <p id="body_parrafo_2" class="parrafo"></p>
        </div>
        <img id="body_tarjeta_img_2" alt="">
    </section>

    <section class="wave-contenedor website">
        <div class="contenedor last-section">
            <img id="body_tarjeta_img_3" alt="">
            <div class="contenedor-textos-main">
                <h2 id="body_titulo_3" class="titulo left"></h2>
                <p id="body_parrafo_3" class="parrafo"></p>
            </div>
        </div>
    </section>

   <section class="wave-contenedor website">
        <div class="contenedor last-section">
            
            <div class="contenedor-textos-main">
                <h2 id="body_titulo_4" class="titulo left"></h2>
                <p id="body_parrafo_4" class="parrafo"></p>
            </div>
            <img id="body_tarjeta_img_4" alt="">
        </div>
    </section>


<!--     <section class="info">
        <div class="contenedor">
            <h2 class="titulo left">Juntos podemos apoyar</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
        </div>
    </section> -->

    <section id="nuestros_trabajos" class="galeria">
        <div class="contenedor">
            <h2 id="body_titulo_nuestros_trabajos" class="titulo"></h2>
            <article class="galeria-cont">
                <img id="body_tarjeta_img_5" alt="">
                <img id="body_tarjeta_img_6" alt="">
                <img id="body_tarjeta_img_7" alt="">
<!--                 <img id="body_tarjeta_img_8" alt="">
                <img id="body_tarjeta_img_9" alt="">
                <img id="body_tarjeta_img_10" alt=""> -->

            </article>
        </div>
    </section>

    <div class="col" id="col-maps">
        <iframe id="body_maps" src="" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
    </div>

 <!--    <section class="info-last">
        <div class="svg-wave" style="height: 150px; overflow: hidden;"><svg viewBox="0 0 500 150" preserveAspectRatio="none"
            style="height: 100%; width: 100%;">
            <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z"
                style="stroke: none; fill: #f5576c;"></path>
        </svg></div>
    </section> -->

    <footer>
        <div class="contenedor-footer">
            <div id="contenedor-telefono" class="content-foo">
                <h4>Teléfono</h4>
                <p id="footer-telefono"></p>
            </div>
            <div id="contenedor-email" class="content-foo">
                <h4>Email</h4>
                <p id="footer-email" ></p>
            </div>
            <div id="contenedor-direccion" class="content-foo">
                <h4>Dirección</h4>
                <p id="footer-direccion"></p>
            </div>
        </div>
       <div class="col-12 col-sm-12 col-md-12 col-lg-4 offset-lg-4 align-self-center">
            <h2 class="titulo-final"> <center>Redes sociales</center> </h2>
            <div id="social-media" class="contenedor-redes col-lg-2">
                 
            </div>
       </div>
    </footer>

<!--     <footer class="py-5 bg-primary">
        <div class="footer-contenedor">
          <div class="row" id="fila-1">
    
            <div class="contenedor-redes col-3 col-md-2 offset-5">
              <h4><center><u>Redes sociales</u></center></h4>
              <div id="social-media" class="social-media">
                <center>
                </center>      
              </div>
              <hr/>
          </div>
        </div>

          <div class="row" id="fila-2">
            <div class="col-3 col-md-4 offset-4">
              <center>
                <h6 id="footer_info_negocio"></h6>
                <h6 id="footer-email"></h6>
              </center>
            </div>
          </div>
       
      </footer>
     -->

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
      $('#title_nombre').html(negocio.nombre);
      $('#nombre').html(negocio.nombre);
      $('#telefono').html(negocio.telefono);

      if(negocio.telefono == null){ 
          $('#footer-telefono').remove('#contenedor-telefono');
       }else{
          $('#footer-telefono').html(negocio.telefono);
      }

      if(negocio.email == null){ 
          $('#footer-email').remove('#contenedor-email');
       }else{
          $('#footer-email').html(negocio.email);
      }

      if(negocio.direccion == null){ 
          $('#footer-direccion').remove('#contenedor-direccion');
       }else{
          $('#footer-direccion').html(negocio.direccion);
      }


      /* variables para el nav */
      let ruta_img = "img/04-servicios/" + negocio.slug + "/"

       if(propiedades.nav_logo == null){ 
          $('#nav_logo').remove('a');
       }else{
        $('#nav_logo').prop("src",  ruta_img + propiedades.nav_logo);
      }
 
       /* variables para el header */
      /*  $('#header_img_1').prop("src",  ruta_img + propiedades.header_img_1); */

       /* $('#header_img_1').css('backgroundImage', 'url(img/tres.jpg');  */
       $('#header_img_1').css('backgroundImage', 'url('+ruta_img + propiedades.header_img_1 +')'); 

       if(propiedades.header_subtitulo_1 == null){ 
          $('#header_subtitulo_1').remove('p');
       }else{
          $('#header_subtitulo_1').html(propiedades.header_subtitulo_1);
      }


       /* variables para el body*/
       
       if(propiedades.body_titulo_1 == null){ 
          $('#body_titulo_1').remove('h2');
       }else{
          $('#body_titulo_1').html(propiedades.body_titulo_1);
      }

       $('#body_subtitulo_1').html(propiedades.body_subtitulo_1);
       if(propiedades.body_subtitulo == null){ 
          $('#body_subtitulo_1').remove('p');
       }else{
          $('#body_subtitulo_1').html(propiedades.body_subtitulo_1);
      }

      /* Tarjetas del body */
      /* Tarjeta 1 */
      if(propiedades.body_tarjeta_img_1 == null){ 
          $('#body_tarjeta_img_1').remove('img');
       }else{
        $('#body_tarjeta_img_1').prop("src",  ruta_img + propiedades.body_tarjeta_img_1);
      }

      if(propiedades.body_titulo_1 == null){ 
          $('#body_titulo_1').remove('p');
       }else{
          $('#body_titulo_1').html(propiedades.body_titulo_1);
      }
      
       if(propiedades.body_parrafo_1 == null){ 
          $('#body_parrafo_1').remove('p');
       }else{
          $('#body_parrafo_1').html(propiedades.body_parrafo_1);
      }


      /* Tarjeta 2 */
      if(propiedades.body_tarjeta_img_2 == null){ 
          $('#body_tarjeta_img_2').remove('img');
       }else{
        $('#body_tarjeta_img_2').prop("src",  ruta_img + propiedades.body_tarjeta_img_2);
      }

      if(propiedades.body_titulo_2 == null){ 
          $('#body_titulo_2').remove('p');
       }else{
          $('#body_titulo_2').html(propiedades.body_titulo_2);
      }
      
       if(propiedades.body_parrafo_2 == null){ 
          $('#body_parrafo_2').remove('p');
       }else{
          $('#body_parrafo_2').html(propiedades.body_parrafo_2);
      }


      /* Tarjeta 3 */
      if(propiedades.body_tarjeta_img_3 == null){ 
          $('#body_tarjeta_img_3').remove('img');
       }else{
        $('#body_tarjeta_img_3').prop("src",  ruta_img + propiedades.body_tarjeta_img_3);
      }

      if(propiedades.body_titulo_3 == null){ 
          $('#body_titulo_3').remove('p');
       }else{
          $('#body_titulo_3').html(propiedades.body_titulo_3);
      }
      
       if(propiedades.body_parrafo_3 == null){ 
          $('#body_parrafo_3').remove('p');
       }else{
          $('#body_parrafo_3').html(propiedades.body_parrafo_3);
      }


        /* Tarjeta 4 */
     if(propiedades.body_tarjeta_img_4 == null){ 
          $('#body_tarjeta_img_4').remove('img');
       }else{
        $('#body_tarjeta_img_4').prop("src",  ruta_img + propiedades.body_tarjeta_img_4);
      }

      if(propiedades.body_titulo_4 == null){ 
          $('#body_titulo_4').remove('p');
       }else{
          $('#body_titulo_4').html(propiedades.body_titulo_4);
      }
      
       if(propiedades.body_parrafo_4 == null){ 
          $('#body_parrafo_4').remove('p');
       }else{
          $('#body_parrafo_4').html(propiedades.body_parrafo_4);
      }

      /* Fin tarjetas del body */

      /* Inicio de imagenes de la seccion nuestros trabajos */
      
      if(propiedades.body_titulo_nuestros_trabajos == null){ 
          $('#nuestros_trabajos').remove('#nuestros_trabajos');
          $('#nuestros_trabajos').remove('#trabajos');
       }else{
        $('#body_titulo_nuestros_trabajos').html(propiedades.body_titulo_nuestros_trabajos);
      }

      if(propiedades.body_tarjeta_img_5 == null){ 
          $('#body_tarjeta_img_5').remove('img');
       }else{
        $('#body_tarjeta_img_5').prop("src",  ruta_img + propiedades.body_tarjeta_img_5);
      }

      
      if(propiedades.body_tarjeta_img_6 == null){ 
          $('#body_tarjeta_img_6').remove('img');
       }else{
        $('#body_tarjeta_img_6').prop("src",  ruta_img + propiedades.body_tarjeta_img_6);
      }

      
      if(propiedades.body_tarjeta_img_7 == null){ 
          $('#body_tarjeta_img_7').remove('img');
       }else{
        $('#body_tarjeta_img_7').prop("src",  ruta_img + propiedades.body_tarjeta_img_7);
      }

      
      if(propiedades.body_tarjeta_img_8 == null){ 
          $('#body_tarjeta_img_8').remove('img');
       }else{
        $('#body_tarjeta_img_8').prop("src",  ruta_img + propiedades.body_tarjeta_img_8);
      }

      
      if(propiedades.body_tarjeta_img_9 == null){ 
          $('#body_tarjeta_img_9').remove('img');
       }else{
        $('#body_tarjeta_img_9').prop("src",  ruta_img + propiedades.body_tarjeta_img_9);
      }

      
      if(propiedades.body_tarjeta_img_10 == null){ 
          $('#body_tarjeta_img_10').remove('img');
       }else{
        $('#body_tarjeta_img_10').prop("src",  ruta_img + propiedades.body_tarjeta_img_10);
      }

      /* Fin de la seccion nuestros trabajos*/


      /* Inicio de seccion google maps */
       /* variables para google maps*/
       
       let estilos_map= "width='100%' height='450' frameborder='0' style='border:0;' allowfullscreen='' aria-hidden='false' tabindex='0'"
       
       if(propiedades.body_maps == null){ 
          $('#body_maps').remove('#col-maps');
       }else{
        $('#body_maps').prop("src", propiedades.body_maps + ' ' + estilos_map);
      }

       /* Fin de seccion google maps */


      
       /* variables de redes sociales para el footer */
      
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