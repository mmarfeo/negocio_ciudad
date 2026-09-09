<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Encontrá negocios cerca tuyo: productos, servicios y comercios locales.">

    <title>Negocios en tu ciudad</title>
    <link rel="icon" type="image/x-icon" href="img/negocio.ico"/>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link href='{{asset("/css/all.css")}}' rel="stylesheet">
    <link href='{{asset("css/app.css")}}' rel="stylesheet">
</head>
<body>

<header class="nc-hero">
    <nav class="navbar navbar-expand-lg nc-navbar">
        <a class="navbar-brand" href="/inicio"><img src='{{asset("img/logo_nav.png")}}' height="40" alt="Negocios en tu Ciudad"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto"></ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="planes">Nuestros planes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="" data-toggle="modal" data-target="#exampleModal">Quiero mi Web</a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('negocios.mios') }}">Mis negocios</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Iniciar sesión</a>
                    </li>
                @endauth
            </ul>
        </div>
    </nav>
    <div class="container text-center">
        <img src='{{asset("img/logo.png")}}' height="90" alt="Negocios en tu Ciudad">
        <h1>¿Qué negocio buscás?</h1>
        <div class="nc-search">
            <form method="GET" action="{{ route('Buscar') }}" id="Buscador">
                <div class="input-group input-group-lg">
                    <input class="form-control" type="text" id="buscar" placeholder="Buscá por rubro, nombre o producto" aria-label="Buscar negocio" autocomplete="off" />
                    <div class="input-group-append">
                        <button class="btn" id="btn-limpiar-buscar" type="button" style="display:none" aria-label="Limpiar búsqueda">&times;</button>
                        <button class="btn" id="button-submit" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</header>

<section class="nc-section">
    <div class="container">
        <div class="row" id="grid-negocios">
            @forelse ($products as $product)
                <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-4">
                    <a href="{{ $product->urlPublica() }}" class="nc-business-card" target="_blank" rel="noopener">
                        <img src="{{$product->navLogoUrl()}}" class="nc-business-card__avatar" alt="{{$product->nombre}}" onerror="this.onerror=null; this.src='{{asset('img/negocio.png')}}'">
                        <div class="nc-business-card__title">{{$product->nombre}}</div>
                        @if ($product->ciudad)
                            <div class="nc-business-card__city">{{$product->ciudad}}</div>
                        @endif
                        <span class="nc-business-card__cta"><i class="fas fa-paper-plane"></i> Entrar</span>
                    </a>
                </div>
            @empty
                <div class="col-12 nc-empty">
                    <p>No se encontraron negocios.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="exampleModalLabel">Publicá con nosotros</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color:#fff">&times;</span>
                </button>
            </div>
            <div class="modal-body nc-modal-contact rounded">
                <div class="container">
                    <div class="row">
                        <div class="col text-center">
                            <img src='{{asset("img/logo.png")}}' height="55" alt="Negocios en tu Ciudad"><br><br>
                            <a href="{{ route('chat.negocio') }}" class="btn btn-accent mb-3">💬 Crear mi página con el asistente</a>
                            <h5>O si preferís, escribinos para crear tu página web o publicitar tu negocio:</h5>
                            <address class="mt-3">
                                <h5><a href="mailto:info@negociosentuciudad.com">info@negociosentuciudad.com</a></h5>
                                <h5 class="texto-footer mb-0">Teléfono</h5>
                                <h5 class="texto-footer">1551506536 - 1566733688</h5>
                            </address>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<footer class="nc-footer" id="home_contact">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-2 text-center mb-3 mb-md-0">
                <img src='{{asset("img/logo.png")}}' height="55" alt="Negocios en tu Ciudad">
            </div>
            <div class="col-6 col-md-5 text-center">
                <div><strong>Teléfono</strong></div>
                <div>1551506536 - 1566733688</div>
            </div>
            <div class="col-6 col-md-5 text-center">
                <div><strong>Email</strong></div>
                <div><a href="mailto:info@negociosentuciudad.com">info@negociosentuciudad.com</a></div>
            </div>
        </div>
    </div>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

<script>
    function urlBusqueda(termino) {
        return window.location.origin + '/inicio' + (termino ? '/' + encodeURIComponent(termino) : '');
    }

    window.addEventListener("load", function () {
        $("#Buscador").submit(function () {
            // Antes esto apuntaba a una URL fija (http://www..., sin encodear
            // el valor) que ya no coincide con el dominio real (https, sin
            // www) -- forzaba 2 redirects de más en cada búsqueda y el
            // navegador podía bloquear/advertir por enviar un form https a
            // una action http. window.location.origin siempre es el dominio
            // que sirvió la página, sea cual sea.
            $(this).attr('action', urlBusqueda($('#Buscador #buscar').val().trim()));
        });
    });
</script>

{{-- Filtro en vivo del index (Fase 4, Paso 2): la grilla se actualiza
     mientras se escribe, sin recargar la página. El submit de arriba sigue
     andando igual (Enter, o click en la lupa) como fallback de página
     completa. --}}
<script>
    (function () {
        var input = document.getElementById('buscar');
        var btnLimpiar = document.getElementById('btn-limpiar-buscar');
        var grid = document.getElementById('grid-negocios');
        var avatarFallback = @json(asset('img/negocio.png'));
        var debounceTimer = null;
        var pedidoActual = 0;

        function tarjetaNegocio(n) {
            var col = document.createElement('div');
            col.className = 'col-6 col-md-4 col-lg-3 col-xl-2 mb-4';

            var link = document.createElement('a');
            link.href = n.url;
            link.className = 'nc-business-card';
            link.target = '_blank';
            link.rel = 'noopener';

            var img = document.createElement('img');
            img.src = n.avatar;
            img.className = 'nc-business-card__avatar';
            img.alt = n.nombre;
            img.addEventListener('error', function () {
                img.onerror = null;
                img.src = avatarFallback;
            });
            link.appendChild(img);

            var titulo = document.createElement('div');
            titulo.className = 'nc-business-card__title';
            titulo.textContent = n.nombre; // textContent: nombre lo carga el dueño del negocio, nunca HTML crudo.
            link.appendChild(titulo);

            if (n.ciudad) {
                var ciudad = document.createElement('div');
                ciudad.className = 'nc-business-card__city';
                ciudad.textContent = n.ciudad;
                link.appendChild(ciudad);
            }

            var cta = document.createElement('span');
            cta.className = 'nc-business-card__cta';
            cta.innerHTML = '<i class="fas fa-paper-plane"></i> Entrar';
            link.appendChild(cta);

            col.appendChild(link);
            return col;
        }

        function renderizar(negocios) {
            grid.innerHTML = '';
            if (!negocios.length) {
                var vacio = document.createElement('div');
                vacio.className = 'col-12 nc-empty';
                vacio.innerHTML = '<p>No se encontraron negocios.</p>';
                grid.appendChild(vacio);
                return;
            }
            negocios.forEach(function (n) {
                grid.appendChild(tarjetaNegocio(n));
            });
        }

        function buscarEnVivo(termino) {
            var pedido = ++pedidoActual;
            fetch(urlBusqueda(termino), { headers: { 'Accept': 'application/json' } })
                .then(function (r) { return r.json(); })
                .then(function (negocios) {
                    // Si mientras esperaba la respuesta el usuario ya tipeó
                    // otra cosa (y disparó un pedido más nuevo), esta
                    // respuesta llegó tarde -- se descarta para no pisar el
                    // resultado del término actual con uno viejo.
                    if (pedido === pedidoActual) {
                        renderizar(negocios);
                    }
                })
                .catch(function () {});
        }

        input.addEventListener('input', function () {
            var termino = input.value.trim();
            btnLimpiar.style.display = termino ? '' : 'none';
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function () {
                buscarEnVivo(termino);
            }, 300);
        });

        btnLimpiar.addEventListener('click', function () {
            input.value = '';
            btnLimpiar.style.display = 'none';
            clearTimeout(debounceTimer);
            buscarEnVivo('');
            input.focus();
        });
    })();
</script>

</body>
</html>
