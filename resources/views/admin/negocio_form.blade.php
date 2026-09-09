<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $negocio->exists ? 'Editar negocio' : 'Nuevo negocio' }} — Negocios en tu Ciudad</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin-form.css') }}" rel="stylesheet">
</head>
<body>
<div class="container py-4">

    <div class="na-topbar">
        <a href="/inicio" class="na-topbar__brand"><img src="{{ asset('img/logo.png') }}" height="40" alt="Negocios en tu Ciudad"></a>
        <a href="{{ route('negocios.mios') }}" class="na-topbar__back">&larr; Mis negocios</a>
    </div>

    <h1 class="na-title">{{ $negocio->exists ? 'Editar negocio: '.$negocio->nombre : 'Nuevo negocio' }}</h1>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ $negocio->exists ? route('negocios.update', $negocio) : route('negocios.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($negocio->exists)
            @method('PUT')
        @endif

        <div class="card mb-3">
            <div class="card-header">Datos del negocio</div>
            <div class="card-body row">
                <div class="form-group col-md-6">
                    <label>Nombre *</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $negocio->nombre) }}" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Slug (para la URL) *</label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug', $negocio->slug) }}" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Plantilla *</label>
                    <select name="plantilla_id" class="form-control" required>
                        <option value="">Elegir...</option>
                        @foreach ($plantillas as $id => $nombrePlantilla)
                            <option value="{{ $id }}" @selected(old('plantilla_id', $negocio->plantilla_id) == $id)>{{ $nombrePlantilla }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>Profesión / rubro visible</label>
                    <input type="text" name="profesion" class="form-control" value="{{ old('profesion', $negocio->profesion) }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $negocio->telefono) }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Celular / WhatsApp</label>
                    <input type="text" name="celular" class="form-control" value="{{ old('celular', $negocio->celular) }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $negocio->email) }}">
                </div>
                <div class="form-group col-md-8">
                    <label>Dirección</label>
                    <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $negocio->direccion) }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Horario</label>
                    <input type="text" name="horario" class="form-control" value="{{ old('horario', $negocio->horario) }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Ciudad</label>
                    <input type="text" name="ciudad" class="form-control" value="{{ old('ciudad', $negocio->ciudad) }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Provincia</label>
                    <input type="text" name="provincia" class="form-control" value="{{ old('provincia', $negocio->provincia) }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Zona</label>
                    <input type="text" name="zona" class="form-control" value="{{ old('zona', $negocio->zona) }}">
                </div>
                <div class="form-group col-md-6">
                    <label>Rubro</label>
                    <input type="text" name="rubro" class="form-control" value="{{ old('rubro', $negocio->rubro) }}">
                </div>
                <div class="form-group col-md-6">
                    <label>Producto / servicio (texto libre, para el buscador)</label>
                    <input type="text" name="producto_servicio" class="form-control" value="{{ old('producto_servicio', $negocio->producto_servicio) }}">
                </div>
                <div class="form-group col-md-8">
                    <label>Palabras clave</label>
                    <input type="text" name="palabras_clave" class="form-control" value="{{ old('palabras_clave', $negocio->palabras_clave) }}">
                </div>
                <div class="form-group col-md-6">
                    <label>Logo (tarjeta del index)</label>
                    <input type="file" name="nav_logo" class="form-control-file" accept="image/*">
                    @if ($negocio->exists && $negocio->nav_logo)
                        <img src="{{ $negocio->navLogoUrl() }}" height="60" class="mt-2 d-block">
                    @endif
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">Productos / servicios que vende (para el buscador "quién lo vende" y para la plantilla "Tienda")</div>
            <div class="card-body">
                <p class="text-muted small">El nombre alimenta el catálogo compartido del sitio: si otro negocio carga el mismo nombre, se vinculan al mismo producto y ambos aparecen cuando alguien lo busca en el index. La foto, la descripción y el stock son propios de tu negocio (no se comparten) y son los que usa la plantilla "Tienda" para armar el catálogo con carrito. Dejar el stock vacío = sin límite.</p>
                @for ($n = 1; $n <= 10; $n++)
                    @php $actual = $productosActuales[$n - 1] ?? null; @endphp
                    <div class="row na-item-row">
                        <div class="col-md-2">
                            @include('admin.partials.campo_imagen', ['campo' => "producto_imagen_{$n}", 'label' => "Foto {$n}", 'urlActual' => $actual['imagen_url'] ?? null])
                        </div>
                        <div class="form-group col-md-4">
                            <label>Producto {{ $n }}</label>
                            <input type="text" name="producto_nombre_{{ $n }}" class="form-control" placeholder="Ej: Pizza napolitana" value="{{ old("producto_nombre_{$n}", $actual['nombre'] ?? '') }}">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Precio</label>
                            <input type="text" name="producto_precio_{{ $n }}" class="form-control" value="{{ old("producto_precio_{$n}", $actual['precio'] ?? '') }}">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Stock</label>
                            <input type="number" min="0" name="producto_stock_{{ $n }}" class="form-control" placeholder="Sin límite" value="{{ old("producto_stock_{$n}", $actual['stock'] ?? '') }}">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Descripción</label>
                            <input type="text" name="producto_descripcion_{{ $n }}" class="form-control" value="{{ old("producto_descripcion_{$n}", $actual['descripcion'] ?? '') }}">
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">Header</div>
            <div class="card-body row">
                <div class="form-group col-md-4">
                    <label>Favicon</label>
                    <input type="file" name="favicon_logo" class="form-control-file" accept="image/*">
                </div>
                @for ($n = 1; $n <= 3; $n++)
                    <div class="col-md-4">
                        @include('admin.partials.campo_imagen', ['campo' => "header_img_{$n}", 'label' => "Imagen header {$n}"])
                    </div>
                    <div class="form-group col-md-4">
                        <label>Título header {{ $n }}</label>
                        <input type="text" name="header_titulo_{{ $n }}" class="form-control" value="{{ old("header_titulo_{$n}", $propiedades->{"header_titulo_{$n}"}) }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Subtítulo header {{ $n }}</label>
                        <input type="text" name="header_subtitulo_{{ $n }}" class="form-control" value="{{ old("header_subtitulo_{$n}", $propiedades->{"header_subtitulo_{$n}"}) }}">
                        <button type="button" class="btn btn-link btn-sm p-0 mt-1 btn-sugerir-texto" data-campo="header_subtitulo_{{ $n }}">✨ Sugerir texto</button>
                    </div>
                @endfor
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">Cuerpo</div>
            <div class="card-body row">
                <div class="form-group col-md-6">
                    <label>Título principal</label>
                    <input type="text" name="body_titulo" class="form-control" value="{{ old('body_titulo', $propiedades->body_titulo) }}">
                </div>
                <div class="form-group col-md-6">
                    <label>Subtítulo principal</label>
                    <input type="text" name="body_subtitulo" class="form-control" value="{{ old('body_subtitulo', $propiedades->body_subtitulo) }}">
                    <button type="button" class="btn btn-link btn-sm p-0 mt-1 btn-sugerir-texto" data-campo="body_subtitulo">✨ Sugerir texto</button>
                </div>
                @for ($n = 2; $n <= 4; $n++)
                    <div class="form-group col-md-4">
                        <label>Título sección {{ $n }}</label>
                        <input type="text" name="body_titulo_{{ $n }}" class="form-control" value="{{ old("body_titulo_{$n}", $propiedades->{"body_titulo_{$n}"}) }}">
                    </div>
                @endfor
                @for ($n = 1; $n <= 4; $n++)
                    <div class="form-group col-md-6">
                        <label>Párrafo sección {{ $n }}</label>
                        <textarea name="body_parrafo_{{ $n }}" class="form-control" rows="2">{{ old("body_parrafo_{$n}", $propiedades->{"body_parrafo_{$n}"}) }}</textarea>
                        <button type="button" class="btn btn-link btn-sm p-0 mt-1 btn-sugerir-texto" data-campo="body_parrafo_{{ $n }}">✨ Sugerir texto</button>
                    </div>
                @endfor
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">Tarjetas / productos (usadas por la plantilla "Productos")</div>
            <div class="card-body">
                @for ($n = 1; $n <= 8; $n++)
                    <div class="row na-item-row">
                        <div class="col-md-3">
                            @include('admin.partials.campo_imagen', ['campo' => "body_tarjeta_img_{$n}", 'label' => "Imagen {$n}"])
                        </div>
                        <div class="form-group col-md-3">
                            <label>Título {{ $n }}</label>
                            <input type="text" name="body_tarjeta_titulo_{{ $n }}" class="form-control" value="{{ old("body_tarjeta_titulo_{$n}", $propiedades->{"body_tarjeta_titulo_{$n}"}) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Descripción {{ $n }}</label>
                            <input type="text" name="body_tarjeta_parrafo_{{ $n }}" class="form-control" value="{{ old("body_tarjeta_parrafo_{$n}", $propiedades->{"body_tarjeta_parrafo_{$n}"}) }}">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Precio {{ $n }}</label>
                            <input type="text" name="body_tarjeta_precio_{{ $n }}" class="form-control" value="{{ old("body_tarjeta_precio_{$n}", $propiedades->{"body_tarjeta_precio_{$n}"}) }}">
                        </div>
                    </div>
                @endfor
                @for ($n = 9; $n <= 10; $n++)
                    <div class="col-md-4">
                        @include('admin.partials.campo_imagen', ['campo' => "body_tarjeta_img_{$n}", 'label' => "Imagen galería {$n}"])
                    </div>
                @endfor
                <div class="form-group col-md-6">
                    <label>Título "nuestros trabajos" (galería)</label>
                    <input type="text" name="body_titulo_nuestros_trabajos" class="form-control" value="{{ old('body_titulo_nuestros_trabajos', $propiedades->body_titulo_nuestros_trabajos) }}">
                </div>
                <div class="form-group col-md-6">
                    <label>Medios de pago</label>
                    <input type="text" name="body_tipos_medios_pago" class="form-control" value="{{ old('body_tipos_medios_pago', $propiedades->body_tipos_medios_pago) }}">
                </div>
                <div class="form-group col-md-12">
                    <label>Google Maps (URL de embed)</label>
                    <input type="text" name="body_maps" class="form-control" value="{{ old('body_maps', $propiedades->body_maps) }}">
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">Redes sociales</div>
            <div class="card-body row">
                <div class="form-group col-md-4">
                    <label>Facebook</label>
                    <input type="text" name="footer_redes_facebook" class="form-control" value="{{ old('footer_redes_facebook', $propiedades->footer_redes_facebook) }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Instagram</label>
                    <input type="text" name="footer_redes_instagram" class="form-control" value="{{ old('footer_redes_instagram', $propiedades->footer_redes_instagram) }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Twitter</label>
                    <input type="text" name="footer_redes_twitter" class="form-control" value="{{ old('footer_redes_twitter', $propiedades->footer_redes_twitter) }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Youtube</label>
                    <input type="text" name="footer_redes_youtube" class="form-control" value="{{ old('footer_redes_youtube', $propiedades->footer_redes_youtube) }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Linkedin</label>
                    <input type="text" name="footer_redes_linkedin" class="form-control" value="{{ old('footer_redes_linkedin', $propiedades->footer_redes_linkedin) }}">
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">Colores de la página, por sección</div>
            <div class="card-body">
                @php
                    $coloresGuardados = old('colores', $propiedades->colores ?? []);
                    $tieneColores = !empty($coloresGuardados) || $propiedades->color_fondo || $propiedades->color_texto;
                    $paletas = [
                        ['clasico', 'Clásico', '#ffffff', '#1f2937'],
                        ['oscuro', 'Oscuro', '#1f2937', '#f5f7fa'],
                        ['calido', 'Cálido', '#fff7f0', '#7a2e0e'],
                        ['marino', 'Marino', '#eaf2f5', '#003245'],
                        ['verde', 'Verde', '#f0f7f0', '#1b4332'],
                    ];
                @endphp
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="personalizar_colores" {{ $tieneColores ? 'checked' : '' }}
                           onchange="document.querySelectorAll('.color-zona-input').forEach(function(i){ i.disabled = !this.checked; }.bind(this))">
                    <label class="form-check-label" for="personalizar_colores">Personalizar los colores de mi página (si no lo marcás, se usan los de la plantilla por defecto)</label>
                </div>

                <p class="text-muted small mb-2">Aplicar la misma paleta a las 3 secciones de una:</p>
                <div class="mb-3">
                    @foreach ($paletas as [$clave, $label, $fondo, $texto])
                        <button type="button" class="btn btn-outline-secondary btn-sm mr-2 mb-2 btn-paleta"
                                style="border-left: 18px solid {{ $fondo }}; border-right: 4px solid {{ $texto }};"
                                data-fondo="{{ $fondo }}" data-texto="{{ $texto }}">{{ $label }}</button>
                    @endforeach
                </div>

                @foreach (\App\Models\propiedades_plantillas::ZONAS as $zona => $labelZona)
                    <div class="row mb-2 align-items-end">
                        <div class="col-md-3"><strong>{{ $labelZona }}</strong></div>
                        <div class="form-group col-md-4 mb-0">
                            <label class="d-block small">Color de fondo</label>
                            <input type="color" name="colores[{{ $zona }}][fondo]" class="color-zona-input" data-parte="fondo"
                                   value="{{ $coloresGuardados[$zona]['fondo'] ?? '#ffffff' }}" {{ $tieneColores ? '' : 'disabled' }}>
                        </div>
                        <div class="form-group col-md-4 mb-0">
                            <label class="d-block small">Color de letra</label>
                            <input type="color" name="colores[{{ $zona }}][texto]" class="color-zona-input" data-parte="texto"
                                   value="{{ $coloresGuardados[$zona]['texto'] ?? '#1f2937' }}" {{ $tieneColores ? '' : 'disabled' }}>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <script>
            document.querySelectorAll('.btn-paleta').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    document.getElementById('personalizar_colores').checked = true;
                    document.querySelectorAll('.color-zona-input').forEach(function (input) {
                        input.disabled = false;
                        input.value = input.dataset.parte === 'fondo' ? btn.dataset.fondo : btn.dataset.texto;
                    });
                });
            });
        </script>

        <div class="na-acciones">
            <button type="submit" class="btn btn-primary">Guardar</button>
            @if ($negocio->exists)
                <a href="{{ $negocio->urlPublica() }}" class="btn btn-outline-secondary" target="_blank">Ver página</a>
            @endif
        </div>
    </form>

    @if ($negocio->exists)
        {{-- Marketplace con Mercado Pago Connect (plantilla "Tienda"): conectar
             es una acción aparte, no un campo más del form de arriba -- cada
             negocio conecta SU PROPIA cuenta, el cobro le llega directo a él. --}}
        <div class="card mb-3">
            <div class="card-header">Cobros</div>
            <div class="card-body na-cobros">
                @if ($mercadoPagoConectado)
                    <p class="text-success mb-2">✅ Cuenta de Mercado Pago conectada.</p>
                    <form method="POST" action="{{ route('mp.desconectar', $negocio) }}" onsubmit="return confirm('¿Desconectar la cuenta de Mercado Pago de este negocio?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">Desconectar</button>
                    </form>
                @else
                    <p class="text-muted mb-2">Conectá tu cuenta de Mercado Pago para poder cobrar directo en la plantilla "Tienda". El dinero de cada venta te llega a vos, no a Negocios en tu Ciudad.</p>
                    <a href="{{ route('mp.conectar', $negocio) }}" class="btn btn-outline-info btn-sm">Conectar con Mercado Pago</a>
                @endif
            </div>
        </div>
    @endif

    {{-- Picker de la galería propia (Fase 5, Paso 3): un solo modal
         compartido por todos los campos de imagen de header/tarjetas. --}}
    <div id="galeriaOverlay" class="d-none" style="position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1050; display:flex; align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:8px; max-width:700px; width:90%; max-height:80vh; overflow:auto; padding:1.5rem;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Elegir foto de la galería</h5>
                <button type="button" id="galeriaCerrar" class="close" aria-label="Cerrar">&times;</button>
            </div>
            <select id="galeriaCategoria" class="form-control mb-3">
                @foreach (\App\Services\GaleriaFotos::categorias() as $clave => $nombreCategoria)
                    <option value="{{ $clave }}">{{ $nombreCategoria }}</option>
                @endforeach
            </select>
            <div id="galeriaGrid" class="d-flex flex-wrap" style="gap:.5rem; min-height:80px;"></div>
        </div>
    </div>
    {{-- Texto sugerido por Gemini (Fase 5, Paso 5): un botón "✨ Sugerir
         texto" por cada campo largo, ver NegocioAdminController::CAMPOS_SUGERIBLES.
         Siempre llena el campo para que el dueño lo revise/edite, nunca
         se guarda solo. --}}
    <script>
        (function () {
            var csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            var sugerirTextoUrl = @json(route('negocios.sugerirTexto'));

            document.querySelectorAll('.btn-sugerir-texto').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var campo = btn.dataset.campo;
                    var el = document.querySelector('[name="' + campo + '"]');
                    if (!el) return;

                    var textoOriginal = btn.textContent;
                    btn.disabled = true;
                    btn.textContent = 'Pensando...';

                    fetch(sugerirTextoUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: JSON.stringify({
                            campo: campo,
                            nombre: (document.querySelector('[name="nombre"]') || {}).value,
                            profesion: (document.querySelector('[name="profesion"]') || {}).value,
                            rubro: (document.querySelector('[name="rubro"]') || {}).value,
                            descripcion_actual: el.value,
                        }),
                    })
                        .then(function (r) { return r.json(); })
                        .then(function (data) {
                            if (data.texto) {
                                el.value = data.texto;
                            } else {
                                alert('No se pudo generar una sugerencia ahora. Probá de nuevo en un momento.');
                            }
                        })
                        .catch(function () {
                            alert('No se pudo generar una sugerencia ahora. Probá de nuevo en un momento.');
                        })
                        .finally(function () {
                            btn.disabled = false;
                            btn.textContent = textoOriginal;
                        });
                });
            });
        })();
    </script>
    <script>
        (function () {
            var overlay = document.getElementById('galeriaOverlay');
            var grid = document.getElementById('galeriaGrid');
            var select = document.getElementById('galeriaCategoria');
            var campoActivo = null;
            // Placeholder de categoría sustituido antes de cada fetch --
            // route() no puede resolver una categoría que todavía no se eligió.
            var urlBase = @json(route('negocios.galeria', ['categoria' => '__CAT__']));

            function cargarCategoria(categoria) {
                grid.innerHTML = '<p class="text-muted small">Cargando...</p>';
                fetch(urlBase.replace('__CAT__', categoria))
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        grid.innerHTML = '';
                        if (!data.imagenes.length) {
                            grid.innerHTML = '<p class="text-muted small">Todavía no hay fotos cargadas en esta categoría.</p>';
                            return;
                        }
                        data.imagenes.forEach(function (img) {
                            var el = document.createElement('img');
                            el.src = img.url;
                            el.className = 'border rounded';
                            el.style.cssText = 'height:90px; width:90px; object-fit:cover; cursor:pointer;';
                            el.addEventListener('click', function () { elegir(img); });
                            grid.appendChild(el);
                        });
                    });
            }

            function elegir(img) {
                if (!campoActivo) return;
                var hidden = document.querySelector('.campo-imagen-galeria[data-campo="' + campoActivo + '"]');
                if (hidden) hidden.value = img.ruta;
                var fileInput = document.querySelector('.campo-imagen-file[data-campo="' + campoActivo + '"]');
                if (fileInput) fileInput.value = '';
                var preview = document.querySelector('img[data-preview-for="' + campoActivo + '"]');
                if (preview) { preview.src = img.url; preview.classList.remove('d-none'); }
                cerrar();
            }

            function abrir(campo) {
                campoActivo = campo;
                overlay.classList.remove('d-none');
                cargarCategoria(select.value);
            }

            function cerrar() {
                overlay.classList.add('d-none');
                campoActivo = null;
            }

            document.querySelectorAll('.btn-abrir-galeria').forEach(function (btn) {
                btn.addEventListener('click', function () { abrir(btn.dataset.campo); });
            });
            document.querySelectorAll('.campo-imagen-file').forEach(function (input) {
                input.addEventListener('change', function () {
                    var campo = input.dataset.campo;
                    var hidden = document.querySelector('.campo-imagen-galeria[data-campo="' + campo + '"]');
                    if (hidden) hidden.value = '';
                    if (input.files && input.files[0]) {
                        var preview = document.querySelector('img[data-preview-for="' + campo + '"]');
                        if (preview) { preview.src = URL.createObjectURL(input.files[0]); preview.classList.remove('d-none'); }
                    }
                });
            });
            document.getElementById('galeriaCerrar').addEventListener('click', cerrar);
            overlay.addEventListener('click', function (e) { if (e.target === overlay) cerrar(); });
            select.addEventListener('change', function () { cargarCategoria(select.value); });
        })();
    </script>
</div>
</body>
</html>
