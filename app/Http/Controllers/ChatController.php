<?php

namespace App\Http\Controllers;

use App\Models\OnboardingSesion;
use App\Models\Product;
use App\Models\propiedades_plantillas;
use App\Services\GeminiClient;
use App\Services\NegocioWriter;
use App\Support\Plantillas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Chat guiado (Fase 3) que arma la página de un negocio paso a paso.
 *
 * Diseño clave: Gemini NUNCA genera HTML ni escribe en la base. Su único
 * trabajo es, en cada paso, convertir la respuesta en lenguaje natural del
 * usuario en un JSON con los campos de ESE paso (App\Services\GeminiClient).
 * Lo que junta la conversación se guarda en `onboarding_sesiones` y recién
 * al confirmar se persiste de verdad, con App\Services\NegocioWriter --
 * el mismo camino que usa el formulario manual de /admin/negocios/crear.
 *
 * Si Gemini no responde (sin API key, cuota agotada, error de red), cada
 * paso cae a un fallback simple (tomar el mensaje tal cual) para que el
 * alta nunca quede bloqueada.
 */
class ChatController extends Controller
{
    /**
     * Orden fijo de la conversación. `campos` son los que le pedimos a
     * Gemini que extraiga; `principal` es el campo que se usa como
     * fallback (texto tal cual) si Gemini no está disponible.
     */
    private const PASOS = [
        'plantilla' => [
            'pregunta' => '¡Hola! Vamos a crear la página de tu negocio 🙂 Para arrancar, elegí el estilo que más se parece a lo que tenés en mente.',
        ],
        'nombre' => [
            'pregunta' => 'Buenísimo. ¿Cómo se llama tu negocio?',
            'campos' => ['nombre' => 'string'],
            'principal' => 'nombre',
        ],
        'rubro' => [
            'pregunta' => '¿A qué se dedica? (por ejemplo: pizzería, plomería, ropa, fotografía, contador...)',
            'campos' => ['rubro' => 'string'],
            'principal' => 'rubro',
        ],
        'descripcion' => [
            'pregunta' => 'Contame en pocas palabras qué hace especial a tu negocio (va a ser la descripción de tu página).',
            'campos' => ['descripcion' => 'string'],
            'principal' => 'descripcion',
        ],
        'ubicacion' => [
            'pregunta' => '¿Dónde están ubicados? Decime dirección, ciudad y zona/barrio.',
            'campos' => ['direccion' => 'string', 'ciudad' => 'string', 'zona' => 'string'],
            'principal' => 'direccion',
        ],
        'contacto' => [
            'pregunta' => '¿Cuál es tu teléfono o WhatsApp de contacto? Si querés, pasame también un email.',
            'campos' => ['telefono' => 'string', 'celular' => 'string', 'email' => 'string'],
            'principal' => 'telefono',
            'instrucciones_extra' => 'Los campos telefono y celular tienen que contener SOLO el número (dígitos, espacios o guiones), sin ninguna palabra alrededor como "mi whatsapp es" o "teléfono:".',
        ],
        'redes' => [
            'pregunta' => '¿Tenés Instagram o Facebook? Pasame el link (o escribí "no" si no tenés).',
            'campos' => ['instagram' => 'string', 'facebook' => 'string'],
            'principal' => 'instagram',
            'opcional' => true,
        ],
        'productos' => [
            'pregunta' => 'Ahora contame los productos o servicios que ofrecés, uno por mensaje (nombre, precio y una descripción corta si querés). Cuando termines, escribí "listo".',
            'campos' => ['nombre_producto' => 'string', 'precio' => 'number', 'descripcion' => 'string'],
            'principal' => 'nombre_producto',
            'repetible' => true,
        ],
        'colores' => [
            'pregunta' => '¿Querés elegir un estilo de colores para tu página? Elegí una opción, o escribí "no" para dejar el color por defecto de la plantilla.',
        ],
        'logo' => [
            'pregunta' => 'Por último, ¿querés subir el logo de tu negocio? Adjuntá una imagen, o escribí "no" para hacerlo más adelante.',
        ],
    ];

    // Sin "horario": ninguna de las 4 plantillas activas lo muestra en la
    // página (hallazgo 2026-08-13) -- se sacó del chat para no pedir un dato
    // que no se usa en ningún lado todavía.
    private const ORDEN = ['plantilla', 'nombre', 'rubro', 'descripcion', 'ubicacion', 'contacto', 'redes', 'productos', 'colores', 'logo', 'confirmar'];

    private const PALABRAS_SALTEAR = ['no', 'no tengo', 'paso', 'ninguna', 'ninguno', 'no hay', 'nada'];
    private const PALABRAS_TERMINAR_PRODUCTOS = ['listo', 'ya esta', 'ya está', 'no hay mas', 'no hay más', 'nada mas', 'nada más', 'terminar', 'fin', 'no'];

    /**
     * Paletas preseteadas para el paso "colores" del chat -- se eligen por
     * botón (no texto libre), así no dependemos de que Gemini interprete
     * bien un nombre de color y lo convierta en un hex razonable.
     */
    private const PALETAS = [
        'clasico' => ['label' => 'Clásico', 'fondo' => '#ffffff', 'texto' => '#1f2937'],
        'oscuro' => ['label' => 'Oscuro', 'fondo' => '#1f2937', 'texto' => '#f5f7fa'],
        'calido' => ['label' => 'Cálido', 'fondo' => '#fff7f0', 'texto' => '#7a2e0e'],
        'marino' => ['label' => 'Marino', 'fondo' => '#eaf2f5', 'texto' => '#003245'],
        'verde' => ['label' => 'Verde', 'fondo' => '#f0f7f0', 'texto' => '#1b4332'],
    ];

    // Las plantillas (id, label, descripción, vista, pasos a saltear) viven
    // en config/plantillas.php -- fuente única, ver App\Support\Plantillas.
    // Antes acá había PLANTILLAS_DESCRIPCION + VISTA_POR_PLANTILLA, y el
    // label salía de NegocioAdminController::PLANTILLAS_DISPONIBLES.

    private $gemini;
    private $writer;

    public function __construct(GeminiClient $gemini, NegocioWriter $writer)
    {
        $this->gemini = $gemini;
        $this->writer = $writer;
    }

    public function iniciar()
    {
        $pasoInicial = self::ORDEN[0];

        $sesion = OnboardingSesion::create([
            'token' => (string) Str::uuid(),
            'user_id' => auth()->id(),
            'paso' => $pasoInicial,
            'estado' => 'en_progreso',
            'datos' => [],
            'productos' => [],
        ]);

        $descripciones = Plantillas::descripciones();
        $plantillas = [];
        foreach (Plantillas::disponibles() as $id => $label) {
            $plantillas[] = [
                'id' => $id,
                'label' => $label,
                'descripcion' => $descripciones[$id] ?? '',
            ];
        }

        return view('chat.negocio', [
            'token' => $sesion->token,
            'primeraPregunta' => self::PASOS[$pasoInicial]['pregunta'],
            'pasoInicial' => $pasoInicial,
            'plantillas' => $plantillas,
            'geminiDisponible' => $this->gemini->disponible(),
        ]);
    }

    public function mensaje(Request $request)
    {
        $request->validate([
            'token' => 'required|uuid',
            'mensaje' => 'required|string|max:1000',
        ]);

        $sesion = OnboardingSesion::where('token', $request->token)->firstOrFail();

        if (! $sesion->enProgreso()) {
            return response()->json(['error' => 'Esta conversación ya terminó.'], 409);
        }

        $paso = $sesion->paso;
        $mensaje = trim($request->mensaje);
        $mensajeNormalizado = Str::lower($mensaje);

        if ($paso === 'plantilla') {
            return $this->procesarPasoPlantilla($sesion, $mensaje);
        }

        if ($paso === 'productos') {
            return $this->procesarPasoProductos($sesion, $mensaje, $mensajeNormalizado);
        }

        if ($paso === 'colores') {
            return $this->procesarPasoColores($sesion, $mensajeNormalizado);
        }

        if ($paso === 'logo') {
            // La subida real de la imagen va por /chat/negocio/logo (multipart);
            // acá cualquier mensaje de texto (incluido "no") solo avanza el paso.
            return $this->avanzar($sesion);
        }

        $definicion = self::PASOS[$paso] ?? null;

        if (! $definicion) {
            return response()->json(['error' => 'Paso desconocido.'], 500);
        }

        if (! empty($definicion['opcional']) && in_array($mensajeNormalizado, self::PALABRAS_SALTEAR, true)) {
            return $this->avanzar($sesion);
        }

        $extraido = $this->gemini->extraer(
            "Extraé los siguientes datos de un negocio a partir del mensaje del usuario. ".($definicion['instrucciones_extra'] ?? ''),
            $mensaje,
            $definicion['campos']
        );

        if (! $extraido) {
            $extraido = [];
        }

        // El campo principal del paso nunca puede quedar vacío, pero el
        // respaldo (mensaje tal cual) solo tiene sentido cuando Gemini no
        // identificó NADA en el mensaje (falló o no está disponible). Si sí
        // identificó otros campos del mismo paso (ej. "ubicación" con
        // ciudad pero sin dirección), no hay que repetir el mensaje entero
        // en el campo principal -- ya está reflejado en esos otros campos,
        // y duplicarlo se ve como si el dato apareciera de la nada.
        $huboExtraccion = count(array_filter($extraido, fn ($valor) => $valor !== null && $valor !== '')) > 0;
        if (empty($extraido[$definicion['principal']]) && ! $huboExtraccion) {
            $extraido[$definicion['principal']] = $mensaje;
        }

        // El paso "contacto" pide "teléfono o WhatsApp" como una sola cosa,
        // pero el esquema tiene telefono/celular separados -- si Gemini
        // devolvió el mismo número en los dos, es un solo dato, no dos.
        if ($paso === 'contacto' && ! empty($extraido['telefono']) && ! empty($extraido['celular'])
            && trim($extraido['telefono']) === trim($extraido['celular'])) {
            unset($extraido['celular']);
        }

        $datos = $sesion->datos ?? [];
        foreach ($extraido as $campo => $valor) {
            if ($valor !== null && $valor !== '') {
                $datos[$campo] = $valor;
            }
        }
        $sesion->datos = $datos;
        $sesion->save();

        return $this->avanzar($sesion);
    }

    /**
     * A diferencia del resto de los pasos, la plantilla se elige por botón
     * (el frontend manda el id como mensaje) y no pasa por Gemini -- es una
     * elección entre opciones cerradas, no algo para interpretar de texto
     * libre. Es requerido: si no matchea ninguna opción válida, se vuelve a
     * preguntar en vez de avanzar con una plantilla sin elegir.
     */
    private function procesarPasoPlantilla(OnboardingSesion $sesion, string $mensaje)
    {
        $plantillaId = (int) trim($mensaje);

        if (! Plantillas::esDisponible($plantillaId)) {
            return response()->json([
                'respuesta' => 'Elegí una de las opciones para continuar 🙂',
                'paso' => 'plantilla',
                'esperandoConfirmacion' => false,
            ]);
        }

        $datos = $sesion->datos ?? [];
        $datos['plantilla_id'] = $plantillaId;
        $sesion->datos = $datos;
        $sesion->save();

        return $this->avanzar($sesion);
    }

    private function procesarPasoProductos(OnboardingSesion $sesion, string $mensaje, string $mensajeNormalizado)
    {
        // "listo" termina el paso en cualquier momento, incluso si el
        // último producto se quedó sin precio (no se lo forzamos a
        // contestar esa pregunta puntual para poder terminar).
        if (in_array($mensajeNormalizado, self::PALABRAS_TERMINAR_PRODUCTOS, true)) {
            return $this->avanzar($sesion);
        }

        $productos = $sesion->productos ?? [];
        $ultimoIndice = count($productos) - 1;
        // `empty()` a propósito, no `=== null`: Gemini a veces devuelve el
        // precio como string vacío ("") en vez de null cuando no lo
        // menciona -- con `=== null` ese caso no se reconocía como "todavía
        // le falta precio" y la respuesta a esta pregunta se procesaba como
        // si fuera un producto nuevo (bug reportado por el dueño del
        // proyecto: "Focaccia..." -> pregunta precio -> "$25.500" se
        // guardaba como otro producto en vez de como la respuesta).
        $faltaPrecioDelUltimo = $ultimoIndice >= 0 && empty($productos[$ultimoIndice]['precio']);

        // Si el producto anterior se quedó sin precio, este mensaje contesta
        // esa pregunta puntual -- no es un producto nuevo. A pedido
        // explícito: el chat no debe dejar pasar un producto sin precio en
        // silencio, tiene que preguntarlo.
        if ($faltaPrecioDelUltimo) {
            if (! in_array($mensajeNormalizado, self::PALABRAS_SALTEAR, true)) {
                $precioContestado = $this->extraerPrecio($mensaje);
                $productos[$ultimoIndice]['precio'] = ! empty($precioContestado) ? $precioContestado : null;
            }
            $sesion->productos = $productos;
            $sesion->save();

            return response()->json([
                'respuesta' => 'Buenísimo. Contame otro producto/servicio, o escribí "listo" si ya terminaste.',
                'paso' => 'productos',
                'ultimoProducto' => $productos[$ultimoIndice]['nombre'],
                'esperandoConfirmacion' => false,
            ]);
        }

        $extraido = $this->gemini->extraer(
            'El usuario está describiendo UN solo producto o servicio para vender, en un mensaje que puede tener varias partes separadas por comas u otro separador. Separá cada dato en su propio campo, sin mezclarlos: '
            .'"nombre" es solo el nombre corto del producto, SIN el precio ni la descripción incluidos ahí adentro. '
            .'"precio" es únicamente el número (si lo menciona). '
            .'"descripcion" es cualquier detalle extra que agregue (ingredientes, tamaño, material, para qué sirve, etc.), sin repetir el nombre ni el precio.',
            $mensaje,
            self::PASOS['productos']['campos']
        );

        if (! $extraido || empty($extraido['nombre_producto'])) {
            $extraido = ['nombre_producto' => $mensaje, 'precio' => null, 'descripcion' => null];
        }

        $productos[] = [
            'nombre' => $extraido['nombre_producto'],
            'precio' => ! empty($extraido['precio']) ? $extraido['precio'] : null,
            'descripcion' => $extraido['descripcion'] ?? null,
        ];
        $sesion->productos = $productos;
        $sesion->save();

        // Sin precio todavía: se pregunta puntual en vez de seguir de largo
        // pidiendo el próximo producto -- mismo pedido de arriba, que no
        // quede un producto sin precio sin que nadie lo haya contestado.
        if (empty($extraido['precio'])) {
            return response()->json([
                'respuesta' => 'Anotado: '.$extraido['nombre_producto'].'. ¿Cuál es el precio? (si no tiene, escribí "no")',
                'paso' => 'productos',
                'ultimoProducto' => $extraido['nombre_producto'],
                'esperandoConfirmacion' => false,
            ]);
        }

        return response()->json([
            'respuesta' => 'Anotado: '.$extraido['nombre_producto'].' ($'.$extraido['precio'].'). Contame otro producto/servicio, o escribí "listo" si ya terminaste.',
            'paso' => 'productos',
            // Para que el frontend sepa a qué producto etiquetar el
            // uploader de fotos opcional (ver subirFotoProducto()) -- se
            // asocia siempre al último cargado.
            'ultimoProducto' => $extraido['nombre_producto'],
            'esperandoConfirmacion' => false,
        ]);
    }

    /**
     * Interpreta la respuesta a "¿cuál es el precio?" (pregunta puntual de
     * seguimiento, no la extracción del producto completo). Primero intenta
     * con Gemini, igual que el resto del proyecto; si no está disponible o
     * no encuentra un número, cae a quedarse con los dígitos del mensaje
     * tal cual -- no intenta adivinar separador decimal, es un fallback
     * simple para no dejar el chat trabado, no una calculadora.
     */
    private function extraerPrecio(string $mensaje): ?float
    {
        $extraido = $this->gemini->extraer(
            'Extraé únicamente el precio (un número) mencionado en el mensaje del usuario, sin el símbolo de moneda.',
            $mensaje,
            ['precio' => 'number']
        );

        if ($extraido && isset($extraido['precio']) && $extraido['precio'] !== '' && $extraido['precio'] !== null) {
            return (float) $extraido['precio'];
        }

        preg_match('/[\d.,]+/', $mensaje, $coincidencia);
        if (empty($coincidencia[0])) {
            return null;
        }

        $numero = str_replace(['.', ','], '', $coincidencia[0]);

        return is_numeric($numero) ? (float) $numero : null;
    }

    private function procesarPasoColores(OnboardingSesion $sesion, string $mensajeNormalizado)
    {
        $clave = Str::slug($mensajeNormalizado, '');

        if (isset(self::PALETAS[$clave])) {
            $datos = $sesion->datos ?? [];
            // El chat ofrece una sola paleta global (no pregunta color por
            // sección, eso queda para el formulario manual -- Fase 5, Paso
            // 2) y esa elección se aplica igual a las 3 zonas editables.
            $colorZona = ['fondo' => self::PALETAS[$clave]['fondo'], 'texto' => self::PALETAS[$clave]['texto']];
            $datos['colores'] = [
                'header' => $colorZona,
                'body' => $colorZona,
                'footer' => $colorZona,
            ];
            $sesion->datos = $datos;
            $sesion->save();
        }

        // Si no matchea ninguna paleta conocida (incluye "no"/"paso"), se
        // avanza igual sin tocar los colores -- la plantilla usa su look
        // por defecto, como si nunca hubiese pasado por este paso.
        return $this->avanzar($sesion);
    }

    /**
     * Sube el logo mientras el chat todavía está en curso (el negocio ni
     * existe en la base todavía). Se guarda en una carpeta temporal ligada
     * al token de la sesión y recién se "muda" a negocios/{id}/ en confirmar().
     */
    public function subirLogo(Request $request)
    {
        $request->validate([
            'token' => 'required|uuid',
            'logo' => 'required|image|max:2048',
        ]);

        $sesion = OnboardingSesion::where('token', $request->token)->firstOrFail();

        $archivo = $request->file('logo');
        $nombre = 'logo-'.Str::random(8).'.'.$archivo->getClientOriginalExtension();
        $ruta = $archivo->storeAs('onboarding/'.$sesion->token, $nombre, 'public');

        $datos = $sesion->datos ?? [];
        $datos['logo_path'] = $ruta;
        $sesion->datos = $datos;
        $sesion->save();

        return response()->json([
            'ok' => true,
            'url' => Storage::disk('public')->url($ruta),
        ]);
    }

    /**
     * Sube una foto para el ÚLTIMO producto cargado en el paso "productos"
     * (mismo mecanismo que subirLogo(): carpeta temporal ligada al token,
     * recién se muda a negocios/{id}/ en confirmar()). Se guarda en la
     * clave `imagen` de ese item -- mismo nombre que ya espera
     * NegocioWriter::sincronizarProductos() desde la plantilla "Tienda".
     * No avanza ni cambia el paso: es opcional y no bloquea seguir
     * cargando productos por texto.
     */
    public function subirFotoProducto(Request $request)
    {
        $request->validate([
            'token' => 'required|uuid',
            'foto' => 'required|image|max:2048',
        ]);

        $sesion = OnboardingSesion::where('token', $request->token)->firstOrFail();

        $productos = $sesion->productos ?? [];
        $ultimoIndice = count($productos) - 1;

        if ($ultimoIndice < 0) {
            return response()->json([
                'ok' => false,
                'error' => 'Contame primero el nombre de un producto antes de subirle una foto.',
            ], 422);
        }

        $archivo = $request->file('foto');
        $nombre = 'producto-'.Str::random(8).'.'.$archivo->getClientOriginalExtension();
        $ruta = $archivo->storeAs('onboarding/'.$sesion->token, $nombre, 'public');

        $productos[$ultimoIndice]['imagen'] = $ruta;
        $sesion->productos = $productos;
        $sesion->save();

        return response()->json([
            'ok' => true,
            'url' => Storage::disk('public')->url($ruta),
            'producto' => $productos[$ultimoIndice]['nombre'],
        ]);
    }

    /**
     * Borrador de descripción sugerido por Gemini (Fase 5, Paso 5),
     * ofrecido después de la pregunta de descripción. Se devuelve como
     * texto suelto para que el frontend lo ponga en el input -- el usuario
     * decide si lo manda tal cual, lo edita o lo ignora y escribe el suyo.
     */
    public function sugerirTexto(Request $request)
    {
        $request->validate(['token' => 'required|uuid']);

        $sesion = OnboardingSesion::where('token', $request->token)->firstOrFail();
        $d = $sesion->datos ?? [];

        $contexto = collect([
            'nombre' => $d['nombre'] ?? null,
            'rubro' => $d['rubro'] ?? null,
        ])->filter()->map(fn ($valor, $clave) => "{$clave}: {$valor}")->implode('. ');

        $texto = $this->gemini->generar(
            'Redactá una descripción corta y atractiva (1-2 frases) para la página de este negocio, en español, tono cercano.',
            $contexto !== '' ? $contexto : 'Sin datos todavía.'
        );

        return response()->json(['texto' => $texto]);
    }

    private function avanzar(OnboardingSesion $sesion)
    {
        $indiceActual = array_search($sesion->paso, self::ORDEN, true);
        $siguientePaso = self::ORDEN[$indiceActual + 1] ?? 'confirmar';

        while ($siguientePaso !== 'confirmar' && $this->debeSaltearPaso($siguientePaso, $sesion)) {
            $indiceActual++;
            $siguientePaso = self::ORDEN[$indiceActual + 1] ?? 'confirmar';
        }

        $sesion->paso = $siguientePaso;
        $sesion->save();

        if ($siguientePaso === 'confirmar') {
            return response()->json([
                'respuesta' => $this->resumen($sesion),
                'paso' => 'confirmar',
                'esperandoConfirmacion' => true,
            ]);
        }

        return response()->json([
            'respuesta' => self::PASOS[$siguientePaso]['pregunta'],
            'paso' => $siguientePaso,
            'esperandoConfirmacion' => false,
        ]);
    }

    /**
     * Pasos que no aplican según la plantilla elegida en el paso 1 -- la
     * lista `saltea_pasos` de cada plantilla vive en config/plantillas.php
     * (ej. Tarjeta saltea "productos": su vista no los muestra).
     */
    private function debeSaltearPaso(string $paso, OnboardingSesion $sesion): bool
    {
        $plantillaId = (int) ($sesion->datos['plantilla_id'] ?? 0);

        return Plantillas::salteaPaso($plantillaId, $paso);
    }

    private function resumen(OnboardingSesion $sesion): string
    {
        $d = $sesion->datos ?? [];
        $productos = $sesion->productos ?? [];

        $lineas = [
            '¡Listo! Esto es lo que armé con tus datos:',
            '- Nombre: '.($d['nombre'] ?? '-'),
            '- Rubro: '.($d['rubro'] ?? '-'),
            '- Ubicación: '.trim(($d['direccion'] ?? '').' '.($d['ciudad'] ?? '').' '.($d['zona'] ?? '')),
            '- Contacto: '.trim(($d['telefono'] ?? $d['celular'] ?? '-')),
        ];

        if (! empty($productos)) {
            $lineas[] = '- Productos/servicios: '.count($productos).' cargados';
        }

        $lineas[] = '¿Enviamos tu pedido para que armemos la página?';

        return implode("\n", $lineas);
    }

    /**
     * Fase 8, Bloque D: el chat ya NO publica la página. Al confirmar, la
     * conversación queda como una "solicitud" (estado `enviada`) que el
     * desarrollador toma desde el panel (SolicitudWebController) para armar
     * y publicar la página. Acá solo se cierran los datos y se mueven los
     * archivos subidos a una carpeta estable.
     */
    public function confirmar(Request $request)
    {
        $request->validate(['token' => 'required|uuid']);

        $sesion = OnboardingSesion::where('token', $request->token)->firstOrFail();

        if (! $sesion->enProgreso()) {
            return response()->json([
                'ok' => true,
                'yaEnviada' => true,
                'mensaje' => 'Ya recibimos tu pedido. Te vamos a avisar cuando la página esté lista.',
            ]);
        }

        $d = $sesion->datos ?? [];

        // No debería pasar (el paso "nombre" siempre cae al mensaje tal cual
        // si Gemini no lo identifica), pero por las dudas dejamos el chat
        // recuperable en vez de trabado si de última llega vacío igual.
        if (empty($d['nombre'])) {
            $sesion->paso = 'nombre';
            $sesion->save();

            return response()->json([
                'error' => 'Falta el nombre del negocio.',
                'reabrirChat' => true,
                'respuesta' => 'Me falta el nombre de tu negocio para poder enviar el pedido. ¿Cómo se llama?',
            ], 422);
        }

        // Los archivos se subieron a onboarding/{token}/ (temporal). Se
        // mueven a solicitudes/{token}/ -- una carpeta estable que el panel
        // del desarrollador puede mostrar, y desde donde la publicación
        // real (NegocioWriter::publicarSolicitud) los mueve a negocios/{id}/.
        $origen = 'onboarding/'.$sesion->token;
        $destino = 'solicitudes/'.$sesion->token;

        if (! empty($d['logo_path']) && Storage::disk('public')->exists($d['logo_path'])) {
            $ruta = $destino.'/'.basename($d['logo_path']);
            Storage::disk('public')->move($d['logo_path'], $ruta);
            $d['logo_path'] = $ruta;
        }

        $productos = $sesion->productos ?? [];
        foreach ($productos as &$producto) {
            if (! empty($producto['imagen']) && Storage::disk('public')->exists($producto['imagen'])) {
                $ruta = $destino.'/'.basename($producto['imagen']);
                Storage::disk('public')->move($producto['imagen'], $ruta);
                $producto['imagen'] = $ruta;
            }
        }
        unset($producto);

        Storage::disk('public')->deleteDirectory($origen);

        $sesion->datos = $d;
        $sesion->productos = $productos;
        $sesion->estado = 'enviada';
        $sesion->nombre_negocio = $d['nombre'] ?? null;
        $sesion->contacto = $d['telefono'] ?? $d['celular'] ?? $d['email'] ?? null;
        $sesion->plantilla_sugerida = $d['plantilla_id'] ?? null;
        $sesion->save();

        return response()->json([
            'ok' => true,
            'mensaje' => '¡Recibimos tu pedido! Vamos a armar tu página y te avisamos cuando esté lista. '
                .'Podés ver el estado en "Mis solicitudes".',
            'misSolicitudesUrl' => route('solicitudes.mias'),
        ]);
    }

    /**
     * Wrapper de vista previa sobre OnboardingSesion::mapearParaWriter():
     * agrega los overrides que solo tienen sentido mientras se elige (sin
     * persistir). `$plantillaIdOverride` para recorrer plantillas antes de
     * decidir; `$paletaOverride` (clave de self::PALETAS) para probar una
     * paleta antes de tocar "Continuar con esta paleta". Ninguno se guarda.
     *
     * @return array{0: array<string,mixed>, 1: array<string,mixed>, 2: int, 3: array}
     */
    private function datosDelPaso(OnboardingSesion $sesion, ?int $plantillaIdOverride = null, ?string $paletaOverride = null): array
    {
        // El mapeo sesión -> arrays de NegocioWriter vive en el modelo
        // (lo comparte con la publicación real desde el panel del dev).
        [$datosNegocio, $datosPropiedades, $plantillaId, $productos] = $sesion->mapearParaWriter($plantillaIdOverride);

        // `$paletaOverride` es exclusivo de la vista previa: probar una
        // paleta antes de tocar "Continuar con esta paleta" (que es lo
        // único que la persiste, en procesarPasoColores()). No va al modelo
        // porque nunca se guarda.
        if ($paletaOverride !== null && isset(self::PALETAS[$paletaOverride])) {
            $colorZona = ['fondo' => self::PALETAS[$paletaOverride]['fondo'], 'texto' => self::PALETAS[$paletaOverride]['texto']];
            $datosPropiedades['colores'] = ['header' => $colorZona, 'body' => $colorZona, 'footer' => $colorZona];
        }

        return [$datosNegocio, $datosPropiedades, $plantillaId, $productos];
    }

    /**
     * Vista previa en vivo del chat (Fase 4): arma la misma plantilla que
     * vería un visitante real, pero SIN guardar nada en la base -- `$negocio`
     * y `$propiedades` son instancias de Eloquent en memoria, nunca
     * ->save()adas. Los campos que el usuario todavía no contestó se
     * completan con contenido de relleno para que la preview nunca se vea
     * vacía, sobre todo en el paso 1 (recién elegida la plantilla).
     *
     * Acepta `?plantilla_id=` para el paso "plantilla" y `?paleta=` para el
     * paso "colores": el usuario puede ir probando varias opciones en la
     * preview antes de confirmar -- esos parámetros solo cambian lo que se
     * renderiza acá, nunca se guardan en la sesión (eso lo hacen únicamente
     * `procesarPasoPlantilla()`/`procesarPasoColores()`, cuando el usuario
     * aprieta "Continuar").
     */
    public function preview(Request $request, string $token)
    {
        $sesion = OnboardingSesion::where('token', $token)->firstOrFail();

        $plantillaIdOverride = $request->query('plantilla_id');
        $plantillaIdOverride = $plantillaIdOverride !== null ? (int) $plantillaIdOverride : null;
        $paletaOverride = $request->query('paleta');

        [$datosNegocio, $datosPropiedades, $plantillaId, $productos] = $this->datosDelPaso($sesion, $plantillaIdOverride, $paletaOverride);

        $negocio = new Product();
        $negocio->fill(array_filter($datosNegocio, fn ($valor) => $valor !== null && $valor !== ''));
        if (empty($negocio->nombre)) {
            $negocio->nombre = 'Tu Negocio';
        }

        $propiedades = new propiedades_plantillas();
        $propiedades->plantilla_id = $plantillaId;
        $propiedades->fill(array_merge(
            [
                'body_titulo' => 'Bienvenidos a '.$negocio->nombre,
                'body_subtitulo' => 'Acá va la descripción de tu negocio: contá qué te hace especial.',
            ],
            array_filter($datosPropiedades, fn ($valor) => $valor !== null && $valor !== '')
        ));

        // Sin productos cargados todavía, un par de ejemplo para que la
        // plantilla no se vea vacía (solo las que arman tarjetas desde
        // productos, hoy "Productos").
        if (Plantillas::tarjetasDesdeProductos($plantillaId) && empty($productos)) {
            foreach ([['nombre' => 'Producto de ejemplo', 'precio' => 1000], ['nombre' => 'Otro producto', 'precio' => 2500]] as $indice => $producto) {
                $n = $indice + 1;
                $propiedades->{"body_tarjeta_titulo_{$n}"} = $producto['nombre'];
                $propiedades->{"body_tarjeta_precio_{$n}"} = $producto['precio'];
            }
        }

        $vista = Plantillas::vista($plantillaId) ?? '03-productos';

        // La plantilla "Tienda" arma su catálogo consultando la base real
        // (negocio->productos()), que durante la preview siempre está vacía
        // porque el negocio todavía no existe -- se le manda además la
        // lista cruda de la sesión para que pueda mostrar algo mientras se
        // completa el chat. Las demás plantillas la ignoran sin problema.
        return view($vista, ['products' => $negocio, 'propiedades' => $propiedades, 'productosSesion' => $productos]);
    }
}
