<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Creá tu página — Negocios en tu Ciudad</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="nc-chat-page">
<div class="nc-chat-layout">
    <div class="nc-chat-sidebar">
        <div class="container nc-chat-container">
            <div class="nc-chat-header">
                <a href="/inicio"><img src="{{ asset('img/logo.png') }}" height="50" alt="Negocios en tu Ciudad"></a>
                <h4 class="mt-2">Creá la página de tu negocio</h4>
                <p class="text-muted small mb-2">Contestá las preguntas del asistente y mirá la vista previa acá al lado.</p>
                @if (! $geminiDisponible)
                    <div class="alert alert-warning py-1 small">El asistente IA no está disponible ahora mismo, pero podés seguir completando los datos igual.</div>
                @endif
            </div>

            <div id="chat-box" class="nc-chat-box"></div>

            <div id="extra-paso" class="mt-2"></div>

            <form id="form-mensaje" class="nc-chat-form">
                <input type="text" id="input-mensaje" placeholder="Escribí tu respuesta..." autocomplete="off" autofocus>
                <button type="submit" id="btn-enviar">Enviar</button>
            </form>

            <div id="confirmacion" class="nc-chat-confirm" style="display:none">
                <button class="btn btn-accent" id="btn-confirmar">✅ Confirmar y publicar</button>
            </div>

            <div id="publicado" class="nc-chat-confirm" style="display:none">
                <a href="#" id="link-editar" class="btn btn-accent">🖼️ Subir logo y fotos</a>
                <a href="#" id="link-ver" class="btn btn-outline-info" target="_blank">Ver mi página</a>
            </div>

            <p class="nc-chat-alt-link">
                <a href="/admin/negocios/crear">¿Preferís cargar los datos en un formulario en vez del chat?</a>
            </p>
        </div>
    </div>

    <div class="nc-chat-preview">
        <iframe id="preview-frame" title="Vista previa de tu página"></iframe>
    </div>
</div>

<script>
    const token = @json($token);
    const previewUrlBase = @json(route('chat.preview', ['token' => $token]));
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const chatBox = document.getElementById('chat-box');
    const form = document.getElementById('form-mensaje');
    const input = document.getElementById('input-mensaje');
    const btnEnviar = document.getElementById('btn-enviar');
    const confirmacionDiv = document.getElementById('confirmacion');
    const btnConfirmar = document.getElementById('btn-confirmar');
    const publicadoDiv = document.getElementById('publicado');
    const linkEditar = document.getElementById('link-editar');
    const linkVer = document.getElementById('link-ver');
    const extraPaso = document.getElementById('extra-paso');
    const previewFrame = document.getElementById('preview-frame');

    // Plantilla/paleta que el usuario tiene tildada mientras todavía está
    // comparando opciones -- no se manda al backend hasta que aprieta
    // "Continuar" (mismo patrón para los dos, ver mostrarExtraPaso()).
    let plantillaSeleccionada = null;
    let paletaSeleccionada = null;

    function refrescarPreview(plantillaIdOverride, paletaOverride) {
        // Cache-bust con timestamp: si no, el navegador podría reusar la
        // versión anterior del iframe en vez de pedir la nueva.
        let url = previewUrlBase + '?t=' + Date.now();
        if (plantillaIdOverride) {
            url += '&plantilla_id=' + plantillaIdOverride;
        }
        if (paletaOverride) {
            url += '&paleta=' + paletaOverride;
        }
        previewFrame.src = url;
    }

    const PALETAS = [
        ['clasico', 'Clásico', '#ffffff', '#1f2937'],
        ['oscuro', 'Oscuro', '#1f2937', '#f5f7fa'],
        ['calido', 'Cálido', '#fff7f0', '#7a2e0e'],
        ['marino', 'Marino', '#eaf2f5', '#003245'],
        ['verde', 'Verde', '#f0f7f0', '#1b4332'],
    ];

    const PLANTILLAS = @json($plantillas);
    const pasoInicial = @json($pasoInicial);

    function agregarMensaje(texto, quienEs) {
        const wrap = document.createElement('div');
        wrap.className = 'nc-msg-wrap' + (quienEs === 'user' ? ' nc-msg-wrap--user' : '');
        const bubble = document.createElement('div');
        bubble.className = 'nc-msg ' + (quienEs === 'user' ? 'nc-msg--user' : 'nc-msg--bot');
        bubble.textContent = texto;
        wrap.appendChild(bubble);
        chatBox.appendChild(wrap);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function fijarCargando(cargando) {
        input.disabled = cargando;
        btnEnviar.disabled = cargando;
    }

    function limpiarExtraPaso() {
        extraPaso.innerHTML = '';
    }

    function mostrarExtraPaso(paso, data) {
        limpiarExtraPaso();

        // La plantilla es una elección cerrada y obligatoria (no hay
        // fallback de texto libre) -- se oculta el campo de texto para no
        // invitar a escribir algo que no matchea. "Colores" ahora sigue el
        // mismo patrón (probar en la preview + botón Continuar), así que
        // también oculta el texto -- el botón "Sin personalizar" cubre el
        // caso de saltear el paso.
        form.style.display = (paso === 'plantilla' || paso === 'colores') ? 'none' : '';

        if (paso === 'plantilla') {
            plantillaSeleccionada = null;

            const btnContinuar = document.createElement('button');
            btnContinuar.type = 'button';
            btnContinuar.className = 'btn btn-accent d-block w-100 mt-1';
            btnContinuar.textContent = 'Continuar con esta plantilla';
            btnContinuar.disabled = true;

            PLANTILLAS.forEach(function (p) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-outline-info d-block w-100 text-left mb-2 py-2';
                btn.innerHTML = '<strong>' + p.label + '</strong><br>'
                    + '<span class="small text-muted">' + p.descripcion + '</span>';
                btn.addEventListener('click', function () {
                    plantillaSeleccionada = p;
                    extraPaso.querySelectorAll('button.btn-outline-info').forEach(function (b) {
                        b.classList.remove('active');
                    });
                    btn.classList.add('active');
                    btnContinuar.disabled = false;
                    btnContinuar.textContent = 'Continuar con "' + p.label + '"';
                    refrescarPreview(p.id);
                });
                extraPaso.appendChild(btn);
            });

            extraPaso.appendChild(btnContinuar);
            btnContinuar.addEventListener('click', function () {
                if (!plantillaSeleccionada) return;
                enviarMensaje(plantillaSeleccionada.label, String(plantillaSeleccionada.id));
            });
        }

        if (paso === 'colores') {
            paletaSeleccionada = null;

            const btnContinuar = document.createElement('button');
            btnContinuar.type = 'button';
            btnContinuar.className = 'btn btn-accent d-block w-100 mt-2';
            btnContinuar.textContent = 'Continuar con esta paleta';
            btnContinuar.disabled = true;

            PALETAS.forEach(function (p) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-outline-info btn-sm mr-2 mb-2';
                btn.style.borderLeft = '18px solid ' + p[2];
                btn.style.borderRight = '4px solid ' + p[3];
                btn.textContent = p[1];
                btn.addEventListener('click', function () {
                    paletaSeleccionada = p;
                    extraPaso.querySelectorAll('button.btn-outline-info').forEach(function (b) {
                        b.classList.remove('active');
                    });
                    btn.classList.add('active');
                    btnContinuar.disabled = false;
                    btnContinuar.textContent = 'Continuar con "' + p[1] + '"';
                    refrescarPreview(undefined, p[0]);
                });
                extraPaso.appendChild(btn);
            });

            extraPaso.appendChild(document.createElement('br'));

            const btnSinPersonalizar = document.createElement('button');
            btnSinPersonalizar.type = 'button';
            btnSinPersonalizar.className = 'btn btn-link btn-sm p-0 mb-2 d-block';
            btnSinPersonalizar.textContent = 'Sin personalizar (usar el diseño de la plantilla)';
            btnSinPersonalizar.addEventListener('click', function () {
                enviarMensaje('no');
            });
            extraPaso.appendChild(btnSinPersonalizar);

            extraPaso.appendChild(btnContinuar);
            btnContinuar.addEventListener('click', function () {
                if (!paletaSeleccionada) return;
                enviarMensaje(paletaSeleccionada[1]);
            });
        }

        if (paso === 'descripcion') {
            const btnSugerir = document.createElement('button');
            btnSugerir.type = 'button';
            btnSugerir.className = 'btn btn-outline-info btn-sm mb-2';
            btnSugerir.textContent = '✨ Sugerime una descripción';
            btnSugerir.addEventListener('click', function () {
                const textoOriginal = btnSugerir.textContent;
                btnSugerir.disabled = true;
                btnSugerir.textContent = 'Pensando...';

                fetch('/chat/negocio/sugerir-texto', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ token: token }),
                })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        if (data.texto) {
                            input.value = data.texto;
                            input.focus();
                        } else {
                            agregarMensaje('No se me ocurrió nada por ahora, pero contame vos con tus palabras 🙂', 'bot');
                        }
                    })
                    .catch(function () {
                        agregarMensaje('No pude generar una sugerencia, contame con tus palabras.', 'bot');
                    })
                    .finally(function () {
                        btnSugerir.disabled = false;
                        btnSugerir.textContent = textoOriginal;
                    });
            });
            extraPaso.appendChild(btnSugerir);
        }

        // Foto opcional para el ÚLTIMO producto cargado -- no bloquea ni
        // avanza el paso, conviven el input de texto (para seguir cargando
        // productos) y este uploader. Solo aparece si la última respuesta
        // trajo `ultimoProducto` (no en la carga inicial de la página).
        if (paso === 'productos' && data && data.ultimoProducto) {
            const wrapFoto = document.createElement('div');
            wrapFoto.className = 'mt-2 mb-2 p-2 border rounded';

            const labelFoto = document.createElement('div');
            labelFoto.className = 'small text-muted mb-1';
            labelFoto.textContent = '📷 Agregale una foto a "' + data.ultimoProducto + '" (opcional)';
            wrapFoto.appendChild(labelFoto);

            const inputFoto = document.createElement('input');
            inputFoto.type = 'file';
            inputFoto.accept = 'image/*';
            inputFoto.className = 'form-control-file';
            wrapFoto.appendChild(inputFoto);

            inputFoto.addEventListener('change', function () {
                if (!inputFoto.files[0]) return;

                const datosFoto = new FormData();
                datosFoto.append('token', token);
                datosFoto.append('foto', inputFoto.files[0]);
                labelFoto.textContent = 'Subiendo foto...';
                inputFoto.disabled = true;

                fetch('/chat/negocio/producto-foto', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: datosFoto,
                })
                    .then(function (r) { return r.json(); })
                    .then(function (respuesta) {
                        if (respuesta.ok) {
                            labelFoto.textContent = '✅ Foto agregada a "' + respuesta.producto + '"';
                        } else {
                            labelFoto.textContent = respuesta.error || 'No se pudo subir la foto, probá de nuevo.';
                            inputFoto.disabled = false;
                        }
                    })
                    .catch(function () {
                        labelFoto.textContent = 'No se pudo subir la foto, probá de nuevo.';
                        inputFoto.disabled = false;
                    });
            });

            extraPaso.appendChild(wrapFoto);
        }

        if (paso === 'logo') {
            const inputFile = document.createElement('input');
            inputFile.type = 'file';
            inputFile.accept = 'image/*';
            inputFile.className = 'form-control-file';
            inputFile.id = 'input-logo';
            extraPaso.appendChild(inputFile);

            inputFile.addEventListener('change', function () {
                if (!inputFile.files[0]) return;

                const datos = new FormData();
                datos.append('token', token);
                datos.append('logo', inputFile.files[0]);

                agregarMensaje('(subiendo imagen...)', 'user');
                fijarCargando(true);

                fetch('/chat/negocio/logo', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: datos,
                })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        if (data.ok) {
                            agregarMensaje('¡Logo subido! Sigamos.', 'bot');
                            enviarMensaje('logo subido');
                        } else {
                            agregarMensaje('No pude subir la imagen, probá con otra o escribí "no" para hacerlo después.', 'bot');
                            fijarCargando(false);
                        }
                    })
                    .catch(function () {
                        agregarMensaje('No pude subir la imagen, probá con otra o escribí "no" para hacerlo después.', 'bot');
                        fijarCargando(false);
                    });
            });
        }
    }

    // `mensajeMostrado` es lo que aparece en la burbuja del chat;
    // `mensajeEnviado` es lo que efectivamente recibe el backend -- para la
    // mayoría de los pasos son el mismo texto, pero el paso "plantilla"
    // muestra el nombre (ej. "Productos") y manda el id (ej. "3").
    function enviarMensaje(mensajeMostrado, mensajeEnviado) {
        agregarMensaje(mensajeMostrado, 'user');
        input.value = '';
        fijarCargando(true);

        fetch('/chat/negocio/mensaje', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ token: token, mensaje: mensajeEnviado ?? mensajeMostrado }),
        })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.error) {
                    agregarMensaje('Uy, algo falló: ' + data.error, 'bot');
                    return;
                }
                agregarMensaje(data.respuesta, 'bot');
                if (data.esperandoConfirmacion) {
                    limpiarExtraPaso();
                    form.style.display = 'none';
                    confirmacionDiv.style.display = 'block';
                } else {
                    mostrarExtraPaso(data.paso, data);
                }
                refrescarPreview();
            })
            .catch(function () {
                agregarMensaje('No pude conectarme al servidor, probá de nuevo.', 'bot');
            })
            .finally(function () {
                fijarCargando(false);
                input.focus();
            });
    }

    agregarMensaje(@json($primeraPregunta), 'bot');
    mostrarExtraPaso(pasoInicial);
    refrescarPreview();

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const mensaje = input.value.trim();
        if (!mensaje) return;
        enviarMensaje(mensaje);
    });

    btnConfirmar.addEventListener('click', function () {
        btnConfirmar.disabled = true;
        btnConfirmar.textContent = 'Publicando...';

        fetch('/chat/negocio/confirmar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ token: token }),
        })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.redirect) {
                    agregarMensaje('¡Tu página ya está publicada! Te recomiendo subir el logo y algunas fotos para que quede completa.', 'bot');
                    linkVer.href = data.redirect;
                    linkEditar.href = data.editarUrl;
                    confirmacionDiv.style.display = 'none';
                    publicadoDiv.style.display = 'block';
                    previewFrame.src = data.redirect; // ya no es un borrador, es la página real publicada
                } else if (data.reabrirChat) {
                    agregarMensaje(data.respuesta || data.error, 'bot');
                    confirmacionDiv.style.display = 'none';
                    form.style.display = '';
                    input.focus();
                } else {
                    agregarMensaje('Uy, algo falló al publicar: ' + (data.error || 'error desconocido'), 'bot');
                    btnConfirmar.disabled = false;
                    btnConfirmar.textContent = '✅ Confirmar y publicar';
                }
            })
            .catch(function () {
                agregarMensaje('No pude conectarme al servidor, probá de nuevo.', 'bot');
                btnConfirmar.disabled = false;
                btnConfirmar.textContent = '✅ Confirmar y publicar';
            });
    });
</script>
</body>
</html>
