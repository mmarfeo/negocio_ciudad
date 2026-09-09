<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Panel interno — Negocios en tu Ciudad</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        html { scroll-behavior: smooth; }
        section[id] { scroll-margin-top: 84px; }
        .pi-topbar {
            position: sticky; top: 0; z-index: 10;
            background: var(--color-primary); color: #fff;
            padding: .6rem 0;
        }
        .pi-topbar a { color: hsla(0,0%,100%,.85); }
        .pi-topbar a:hover { color: #fff; }
        .pi-nav { display: flex; flex-wrap: wrap; gap: .35rem; margin-top: .5rem; }
        .pi-nav a {
            font-size: .8rem; padding: .3rem .65rem; border-radius: var(--radius-pill);
            background: rgba(255,255,255,.12); white-space: nowrap;
        }
        .pi-nav a:hover { background: rgba(255,255,255,.22); text-decoration: none; }
        .pi-section { padding: 2.75rem 0; border-bottom: 1px solid var(--color-border); }
        .pi-eyebrow { font-size: .75rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--color-accent-dark); }
        .pi-mark { font-weight: 700; width: 1.1rem; display: inline-block; }
        .pi-mark--plus { color: #1f8a5f; }
        .pi-mark--minus { color: var(--color-accent-dark); }
        .pi-badge-p1 { background: #fde2df; color: #a3221b; }
        .pi-badge-p2 { background: #fff1cf; color: #8a5a00; }
        .pi-badge-p3 { background: #e6edf5; color: var(--color-primary); }
        .pi-badge-no { background: #eee; color: #666; text-decoration: line-through; }
        table.table td, table.table th { font-size: .92rem; vertical-align: middle; }
        .pi-note { border-left: 3px solid var(--color-accent); padding: .3rem 0 .3rem 1rem; background: var(--color-primary-50); font-size: .92rem; }
    </style>
</head>
<body>

<div class="pi-topbar">
    <div class="container d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center">
            <a href="/inicio" class="mr-3 d-flex align-items-center"><img src="{{ asset('img/logo_nav.png') }}" height="28" alt="Negocios en tu Ciudad"></a>
            <strong>Panel interno</strong>
            <span class="d-none d-sm-inline">&nbsp;— solo para admins de la plataforma</span>
        </div>
        <div>
            <a href="{{ route('negocios.mios') }}" class="mr-3">Mis negocios</a>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light">Cerrar sesión</button>
            </form>
        </div>
    </div>
    <div class="container pi-nav">
        <a href="#contexto">Contexto</a>
        <a href="#mision-vision">Misión y visión</a>
        <a href="#propuesta">Propuesta</a>
        <a href="#flujos">Flujos</a>
        <a href="#balance">Balance</a>
        <a href="#modelo">Modelo</a>
        <a href="#mercado">Mercado</a>
        <a href="#tamano">Tamaño</a>
        <a href="#tareas">Tareas</a>
        <a href="#recomendaciones">Recomendaciones</a>
    </div>
</div>

<div class="container" style="max-width: 900px;">

    <div class="pi-section" style="padding-top: 2.25rem;">
        <h1 class="h3 mb-2">Documentación y roadmap para hablar con socios</h1>
    </div>

    <section id="contexto" class="pi-section">
        <span class="pi-eyebrow">El problema</span>
        <h2 class="h4 mt-1 mb-3">La vidriera del barrio no llegó a internet</h2>
        <p>
            La mayoría de los comercios de barrio no tiene página propia: como mucho, una cuenta de Instagram y un
            WhatsApp que corre de boca en boca. Buscar "gasista en Morón" en Google no encuentra al gasista. No es
            falta de interés, es fricción: pagar un desarrollador o aprender una herramienta de arrastrar y soltar
            cuesta más tiempo y plata del que un comercio de barrio puede dedicarle.
        </p>
        <p class="mb-0">
            Negocios en tu Ciudad ataca esa fricción directamente: en vez de un editor, una conversación con IA que
            arma la página mientras el dueño contesta preguntas simples sobre su propio negocio.
        </p>
    </section>

    <section id="mision-vision" class="pi-section">
        <span class="pi-eyebrow">Rumbo</span>
        <h2 class="h4 mt-1 mb-3">Misión y visión</h2>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card h-100 p-3">
                    <strong class="text-muted small">MISIÓN</strong>
                    <p class="mb-0 mt-2">Que cualquier comerciante de barrio —sin saber de tecnología, sin
                        presupuesto de agencia y sin más de diez minutos disponibles— tenga una página propia en
                        internet, tan fácil de crear como mandar un mensaje.</p>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card h-100 p-3">
                    <strong class="text-muted small">VISIÓN</strong>
                    <p class="mb-0 mt-2">Ser el mapa comercial de referencia de cada ciudad argentina — el lugar al
                        que un vecino recurre para encontrar, en un solo sitio, quién vende qué y dónde.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="propuesta" class="pi-section">
        <span class="pi-eyebrow">Cómo se vende</span>
        <h2 class="h4 mt-1 mb-3">Tres formas de contar la misma idea</h2>
        <div class="table-responsive">
            <table class="table table-bordered bg-white">
                <thead class="thead-light"><tr><th style="width:15%">A quién</th><th style="width:35%">Mensaje</th><th>Por qué</th></tr></thead>
                <tbody>
                    <tr>
                        <td><strong>Dueño del negocio</strong></td>
                        <td>"Contá tu negocio como se lo contarías a un cliente nuevo."</td>
                        <td>El asistente arma la página mientras habla, en minutos queda publicada, gratis para siempre en el plan base.</td>
                    </tr>
                    <tr>
                        <td><strong>Quien busca</strong></td>
                        <td>"Encontrá el comercio real de tu barrio, no un grupo de Facebook de 2019."</td>
                        <td>Buscador filtrable por ciudad y rubro, contacto directo por WhatsApp.</td>
                    </tr>
                    <tr>
                        <td><strong>Un socio</strong></td>
                        <td>"Publicar un negocio nuevo no cuesta casi nada — y cada uno hace más valioso al resto."</td>
                        <td>Directorio de dos lados con efecto de red; costo marginal por negocio nuevo casi nulo.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <section id="flujos" class="pi-section">
        <span class="pi-eyebrow">Cómo funciona</span>
        <h2 class="h4 mt-1 mb-3">Tres caminos, un mismo sistema</h2>
        <div class="row">
            <div class="col-md-4 mb-3">
                <h3 class="h6">A · Alta de un negocio</h3>
                <ol class="pl-3 small mb-0">
                    <li>Entra al chat sin crear cuenta primero</li>
                    <li>Elige plantilla visual</li>
                    <li>Responde preguntas simples, con preview en vivo</li>
                    <li>Pide ayuda a la IA si hace falta</li>
                    <li>Confirma y queda publicado al instante</li>
                </ol>
            </div>
            <div class="col-md-4 mb-3">
                <h3 class="h6">B · Descubrimiento</h3>
                <ol class="pl-3 small mb-0">
                    <li>Llega por el buscador o desde Google</li>
                    <li>Filtra por texto, ciudad o rubro</li>
                    <li>Abre el negocio que le interesa</li>
                    <li>Contacta directo por WhatsApp</li>
                </ol>
            </div>
            <div class="col-md-4 mb-3">
                <h3 class="h6">C · Crecimiento / monetización</h3>
                <ol class="pl-3 small mb-0">
                    <li>Arranca en el plan Gratis</li>
                    <li>Pasa a Negocio (fotos, colores, sin marca)</li>
                    <li>Sube a Negocio Plus (estadísticas, prioridad)</li>
                    <li>O paga un Destacado puntual</li>
                </ol>
            </div>
        </div>
    </section>

    <section id="balance" class="pi-section">
        <span class="pi-eyebrow">Balance, sin maquillaje</span>
        <h2 class="h4 mt-1 mb-3">Lo bueno y lo que todavía falta</h2>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card h-100 p-3">
                    <h3 class="h6 mb-3">Ventajas</h3>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2"><span class="pi-mark pi-mark--plus">+</span> Alta sin fricción técnica</li>
                        <li class="mb-2"><span class="pi-mark pi-mark--plus">+</span> Costo marginal casi nulo</li>
                        <li class="mb-2"><span class="pi-mark pi-mark--plus">+</span> Efecto de red de dos lados</li>
                        <li class="mb-2"><span class="pi-mark pi-mark--plus">+</span> Vive donde el comercio ya vive (WhatsApp)</li>
                        <li class="mb-2"><span class="pi-mark pi-mark--plus">+</span> Un solo sistema de diseño</li>
                        <li class="mb-0"><span class="pi-mark pi-mark--plus">+</span> Terreno fértil para SEO</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card h-100 p-3">
                    <h3 class="h6 mb-3">Riesgos y pendientes</h3>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2"><span class="pi-mark pi-mark--minus">–</span> Arranque en frío del directorio</li>
                        <li class="mb-2"><span class="pi-mark pi-mark--minus">–</span> Compite con lo gratis que ya usan (Instagram, Linktree)</li>
                        <li class="mb-2"><span class="pi-mark pi-mark--minus">–</span> Depende de la capa gratuita de Gemini</li>
                        <li class="mb-2"><span class="pi-mark pi-mark--minus">–</span> Equipo de una sola persona</li>
                        <li class="mb-2"><span class="pi-mark pi-mark--minus">–</span> Todavía no es "producción" en sentido estricto</li>
                        <li class="mb-0"><span class="pi-mark pi-mark--minus">–</span> Sin ingresos reales todavía</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="modelo" class="pi-section">
        <span class="pi-eyebrow">Modelo de negocio</span>
        <h2 class="h4 mt-1 mb-3">Gratis de verdad, pago por valor</h2>
        <div class="table-responsive">
            <table class="table table-bordered bg-white">
                <thead class="thead-light"><tr><th>Plan</th><th>Precio</th><th>Incluye</th></tr></thead>
                <tbody>
                    <tr><td><strong>Gratis</strong></td><td>$0, para siempre</td><td>Plantilla estándar · hasta 5 productos · aparece en el buscador · con marca del sitio</td></tr>
                    <tr><td><strong>Negocio</strong></td><td>≈ 1 salida al cine/mes</td><td>Productos ilimitados con foto · colores propios · sin marca · todas las plantillas</td></tr>
                    <tr><td><strong>Negocio Plus</strong></td><td>≈ 2× plan Negocio</td><td>Todo lo anterior · estadísticas de visitas · prioridad en su rubro · soporte directo</td></tr>
                </tbody>
            </table>
        </div>
        <p class="small text-muted mb-0">Sin pesos concretos a propósito: lo que se fija es la <em>relación</em> entre planes, no un número que se desactualiza rápido en la Argentina. Más una idea de "Destacado puntual" (pago único por fecha clave) y mantener el plan Gratis realmente gratis.</p>
    </section>

    <section id="mercado" class="pi-section">
        <span class="pi-eyebrow">Análisis de mercado</span>
        <h2 class="h4 mt-1 mb-3">Qué hay ahí afuera, con números</h2>
        <p class="small text-muted">Investigación de agosto 2026: sitios de competidores visitados directamente, precios de referencia (hosting, IA, entrada de cine) y benchmarks externos de conversión freemium.</p>

        <h3 class="h6 mt-4">Competencia directa</h3>
        <div class="table-responsive">
            <table class="table table-sm table-bordered bg-white">
                <thead class="thead-light"><tr><th>Competidor</th><th>Precio</th><th>¿IA/chat?</th></tr></thead>
                <tbody>
                    <tr><td><strong>Todo Ezeiza</strong> (red de 20 sitios)</td><td>Gratis · $27.500–$45.000/mes</td><td>No, lo arma un equipo a pedido</td></tr>
                    <tr><td>Guía Comercial Arg., RedArgentina, MisterWhat, Dir.ar y similares</td><td>Gratis</td><td>No, solo ficha de contacto</td></tr>
                    <tr><td>Esta Abierto</td><td>Sin precio público</td><td>No, plantilla fija autoeditable</td></tr>
                </tbody>
            </table>
        </div>
        <p class="small text-muted"><code>todoezeiza.com/incluir</code> está operativo hoy (precios vigentes al momento de esta consulta). No hay forma de confirmar desde afuera si es rentable — no publican esos datos.</p>

        <h3 class="h6 mt-4">Competencia indirecta</h3>
        <div class="table-responsive">
            <table class="table table-sm table-bordered bg-white">
                <thead class="thead-light"><tr><th>Producto</th><th>Precio</th><th>Por qué no es lo mismo</th></tr></thead>
                <tbody>
                    <tr><td>Durable (EE. UU.)</td><td>USD 25–99/mes</td><td>Sin foco local ni directorio de búsqueda</td></tr>
                    <tr><td>Tiendanube</td><td>$0–USD 50/mes + comisión</td><td>Piensa en carrito de compra, no en "tener una página"</td></tr>
                </tbody>
            </table>
        </div>
        <p class="mb-0"><strong>Conclusión:</strong> nadie en el mercado argentino local junta alta instantánea por IA + gratis de verdad + directorio por ciudad al mismo tiempo.</p>

        <h3 class="h6 mt-4">Costos reales</h3>
        <ul class="small mb-3">
            <li><strong>Hosting:</strong> $2.000–$4.600/mes fijo.</li>
            <li><strong>IA (Gemini):</strong> nivel gratuito hoy (~500 solicitudes/día ≈ 40 altas/día de techo). Pasado ese techo, ≈ USD 0,01 por alta completa con Gemini 2.5 Flash.</li>
            <li><strong>Dominio/correo:</strong> unos pocos dólares por mes o año.</li>
        </ul>

        <h3 class="h6 mt-4">Proyección de ingresos <span class="text-muted font-weight-normal">— hipótesis, no promesa</span></h3>
        <div class="table-responsive">
            <table class="table table-sm table-bordered bg-white">
                <thead class="thead-light"><tr><th>Escenario</th><th>Negocios activos</th><th>Conversión</th><th>Ingreso mensual est.</th></tr></thead>
                <tbody>
                    <tr><td>Conservador</td><td>300</td><td>3%</td><td>≈ $108.000</td></tr>
                    <tr><td>Realista</td><td>1.500</td><td>6%</td><td>≈ $1.170.000</td></tr>
                    <tr><td>Optimista</td><td>5.000</td><td>10%</td><td>≈ $7.000.000</td></tr>
                </tbody>
            </table>
        </div>
        <div class="pi-note">La conversión usa el rango típico de productos freemium (2%–10%, benchmark externo). El costo operativo mensual ronda $10.000–$30.000 en los tres escenarios: el cuello de botella no es cubrir costos, es llegar a la cantidad de negocios activos.</div>
    </section>

    <section id="tamano" class="pi-section">
        <span class="pi-eyebrow">Tamaño del mercado potencial</span>
        <h2 class="h4 mt-1 mb-3">Cuántos clientes puede haber</h2>
        <p>
            No se pudo auditar Google Maps de forma sistemática (requiere la Places API de Google, con clave y
            facturación propias — no disponible en el entorno donde se hizo esta investigación). En cambio, se contó
            directamente lo que ya tiene cargado la competencia real.
        </p>
        <p>
            <strong>Todo Ezeiza es una de 20 guías</strong> de la misma red ("Grupo Todo"): 10 en zona sur del
            conurbano (Avellaneda, Cañuelas, Ezeiza, Lanús, Lomas de Zamora, Monte Grande, Quilmes, San Vicente,
            Varela, Almirante Brown) y 10 en la costa atlántica.
        </p>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered bg-white">
                        <caption class="small px-2">Población confirmada (INDEC, censo 2022) — 7 de 10 partidos de zona sur</caption>
                        <tbody>
                            <tr><td>Lomas de Zamora</td><td class="text-right">694.330</td></tr>
                            <tr><td>Almirante Brown</td><td class="text-right">585.852</td></tr>
                            <tr><td>Quilmes</td><td class="text-right">636.026</td></tr>
                            <tr><td>Lanús</td><td class="text-right">462.051</td></tr>
                            <tr><td>Avellaneda</td><td class="text-right">370.939</td></tr>
                            <tr><td>Ezeiza</td><td class="text-right">203.283</td></tr>
                            <tr><td>Cañuelas</td><td class="text-right">71.149</td></tr>
                            <tr class="font-weight-bold"><td>Total (7 de 10)</td><td class="text-right">≈ 3.023.630</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered bg-white">
                        <caption class="small px-2">Negocios ya cargados en Todo Ezeiza / Todo Adrogué (muestra real)</caption>
                        <tbody>
                            <tr><td>Abogados en Ezeiza (203 mil hab.)</td><td class="text-right">3</td></tr>
                            <tr><td>Inmobiliarias en Ezeiza</td><td class="text-right">53</td></tr>
                            <tr><td>Abogados en Adrogué (586 mil hab.)</td><td class="text-right">155</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="pi-note mb-0">
            Un partido casi 3 veces más poblado que Ezeiza tiene 50 veces más abogados cargados: ni el competidor con
            años de ventaja tiene el mercado cubierto. El escenario "optimista" de la sección Mercado (5.000
            negocios) es menos del 0,2% de la población de esta sola zona — techo bajo, no exagerado. Para un número
            exacto: Places API de Google Maps (clave propia, USD 200 gratis/mes de Google), portales de datos
            abiertos municipales, o el panel oficial de la Provincia de Buenos Aires (existe, pero es un dashboard
            interactivo no legible por este método).
        </div>
    </section>

    <section id="tareas" class="pi-section">
        <span class="pi-eyebrow">Roadmap para socios</span>
        <h2 class="h4 mt-1 mb-3">Tareas, ordenadas por prioridad</h2>
        <p class="text-muted small">Criterio propio, pensando en qué hace falta específicamente para poder sentarse a hablar con un socio sin que un problema evitable arruine la conversación — no es la misma prioridad que la del roadmap técnico general (ese vive completo en <code>documentos/checklist-general.md</code>).</p>

        <h3 class="h6 mt-4">✅ Ya hecho</h3>
        <ul class="small">
            <li>Chat guiado por IA (Gemini) de punta a punta: plantilla → datos → confirmación → página publicada</li>
            <li>Catálogo de productos, búsqueda y filtro en vivo del index</li>
            <li>Login/registro propio, URLs por ciudad, vista previa en vivo durante el chat</li>
            <li>4 plantillas con estilo unificado, colores personalizables por sección, texto sugerido por IA</li>
            <li>Infraestructura del banco de fotos por rubro (falta curar el contenido, ver abajo)</li>
            <li>Este documento: análisis de competencia, costos reales, y tamaño de mercado con datos concretos</li>
        </ul>

        <h3 class="h6 mt-4">Pendiente — Prioridad 1 <span class="badge pi-badge-p1">bloqueante antes de mostrar esto a un socio</span></h3>
        <ul class="small">
            <li><strong>Control de versiones real + backups confirmados.</strong> Hoy el trabajo vive solo en el hosting; un socio serio va a preguntar esto en los primeros cinco minutos.</li>
            <li><strong>Términos y Condiciones + Política de Privacidad reales.</strong> El registro pide DNI — no tener esto es una exposición legal visible de inmediato para cualquiera que audite el producto.</li>
            <li><strong>Límite de intentos de login y recuperación de contraseña por mail real.</strong> Hoy el correo de recuperación no envía nada de verdad: un negocio real que pierde el acceso queda afuera de su propia página.</li>
            <li><strong>Cargar 3–5 negocios reales (no de prueba) en una ciudad piloto.</strong> Mostrar el directorio vacío en una demo mata el argumento del efecto de red antes de empezar.</li>
        </ul>

        <h3 class="h6 mt-4">Pendiente — Prioridad 2 <span class="badge pi-badge-p2">importante, no bloqueante para una primera charla</span></h3>
        <ul class="small">
            <li>SEO básico por negocio: título, meta descripción, vista previa de WhatsApp (Open Graph)</li>
            <li>Filtros estructurados del index (ciudad/categoría) + paginación</li>
            <li>Completar el banco de fotos curado por rubro (infraestructura ya lista)</li>
            <li>Probar el circuito completo (registro → alta por chat → publicación → edición) en un celular real</li>
            <li>Conseguir un número exacto de mercado potencial (Places API o datos municipales) para reemplazar la estimación de la sección Tamaño</li>
        </ul>

        <h3 class="h6 mt-4">Pendiente — Prioridad 3 <span class="badge pi-badge-p3">roadmap, no urgente para hablar con socios</span></h3>
        <ul class="small">
            <li>PWA (manifest, íconos, service worker)</li>
            <li>Plantillas nuevas (Vidriera, Estudio, Consultorio, Menú)</li>
            <li>Estadísticas de visitas (para poder vender el plan Negocio Plus)</li>
            <li>Integración de cobro recurrente (ej. suscripciones de Mercado Pago)</li>
            <li>App nativa (solo si la PWA no alcanza)</li>
        </ul>

        <h3 class="h6 mt-4">No hace falta hacer ahora <span class="badge pi-badge-no">a propósito, no por olvido</span></h3>
        <ul class="small mb-0">
            <li>Catálogo cerrado de ciudades (hoy es texto libre normalizado a slug) — mejora de mediano plazo, no bloquea nada hoy</li>
            <li>Migrar o redirigir URLs de los negocios que están en producción hoy — son de prueba, se recrean directo cuando haga falta</li>
            <li>Instalar un paquete de roles/permisos (ej. Spatie) — hoy hay un solo rol extra (admin de plataforma), un booleano alcanza</li>
            <li>Rediseñar a fondo el formulario manual de alta de negocio — funciona, es un hallazgo de estética pendiente, no un bloqueante</li>
            <li>Limpiar métodos muertos del código o sumar tests automáticos completos — housekeeping real, pero no cambia en nada lo que un socio ve o evalúa</li>
            <li>Fijar precios en pesos concretos en este documento — a propósito se dejó como relación entre planes, no un número que se desactualiza en semanas</li>
            <li>App nativa antes de validar la PWA — saltar ese paso sería gastar esfuerzo en algo que todavía no se sabe si hace falta</li>
        </ul>
    </section>

    <section id="recomendaciones" class="pi-section" style="border-bottom: none;">
        <span class="pi-eyebrow">Antes de la reunión</span>
        <h2 class="h4 mt-1 mb-3">Recomendaciones para presentar esto a posibles socios</h2>
        <ul class="small mb-0">
            <li class="mb-2"><strong>Llevar una demo en vivo, no solo el documento.</strong> Crear un negocio real desde cero con el chat, en menos de 5 minutos, delante del socio, es el argumento más fuerte que existe — más que cualquier slide.</li>
            <li class="mb-2"><strong>Que el directorio no se vea vacío.</strong> Cargar unos pocos negocios reales en una ciudad piloto antes de la reunión (ver Prioridad 1) para que la demo muestre algo parecido a lo que sería el producto con tracción.</li>
            <li class="mb-2"><strong>Usar la comparación de precios de Todo Ezeiza como ancla.</strong> Que un competidor ya establecido cobre $27.500–$45.000/mes por algo peor (armado manual, sin IA) es el dato que más rápido justifica que esto tiene sentido de negocio.</li>
            <li class="mb-2"><strong>Nombrar los riesgos antes de que los pregunten.</strong> Arranque en frío, equipo de una sola persona, y que todavía no es "producción" en sentido estricto — decirlo primero genera más confianza que que el socio lo descubra solo.</li>
            <li class="mb-2"><strong>Tener claro qué se pide, concretamente, antes de entrar a la reunión.</strong> Manos de desarrollo, distribución local (cámaras de comercio/municipios), o capital para cerrar los pendientes de Prioridad 1 — elegir el foco de antemano, no dejarlo abierto para que lo defina el socio.</li>
            <li class="mb-2"><strong>Practicar la respuesta a "¿por qué no lo hace ya alguien más?".</strong> La respuesta corta: alguien parecido existe (Grupo Todo), pero cobra más y lo arma a mano — el hueco es "gratis + instantáneo + directorio" a la vez, que hoy nadie ofrece.</li>
            <li class="mb-0"><strong>No prometer los números de la proyección de ingresos como si fueran un hecho.</strong> Presentarlos como el modelo que son — construido con precios de mercado reales, pero sin validar con clientes propios pagando — evita quedar expuesto si un socio pide el respaldo de esos números.</li>
        </ul>
    </section>

</div>
</body>
</html>
