<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\propiedades_plantillas;
use App\Services\GaleriaFotos;
use App\Services\GeminiClient;
use App\Services\MercadoPagoService;
use App\Services\NegocioWriter;
use App\Support\Plantillas;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Alta/edición real de un negocio + sus propiedades de plantilla.
 * Reemplaza a ProductController@store/@editarProd, que apuntaban a la
 * tabla `products` vieja (incompatible con `negocios`).
 *
 * Todas las rutas de este controlador van detrás del middleware 'auth'
 * (ver routes/web.php). La persistencia real vive en App\Services\
 * NegocioWriter, que también usa el chat con Gemini (Fase 3) -- este
 * controlador solo valida el form, sube imágenes y chequea ownership.
 */
class NegocioAdminController extends Controller
{
    private $writer;
    private $gemini;
    private $mercadoPago;

    public function __construct(NegocioWriter $writer, GeminiClient $gemini, MercadoPagoService $mercadoPago)
    {
        $this->writer = $writer;
        $this->gemini = $gemini;
        $this->mercadoPago = $mercadoPago;
    }

    /**
     * Campos de texto largo del formulario manual con botón "Sugerir texto"
     * (Fase 5, Paso 5) -- cada uno con su propia instrucción de redacción,
     * ya que no es lo mismo una bajada corta de header que un párrafo de
     * "sobre nosotros". Nunca se escribe solo: la sugerencia llena el campo
     * y el dueño del negocio la revisa/edita antes de guardar, igual que el
     * resto de las sugerencias de este proyecto (ver GeminiClient::generar()).
     */
    public const CAMPOS_SUGERIBLES = [
        'header_subtitulo_1' => 'Redactá una bajada corta y llamativa (una sola frase) para el encabezado de la página de este negocio.',
        'header_subtitulo_2' => 'Redactá una bajada corta y llamativa (una sola frase) para el encabezado de la página de este negocio.',
        'header_subtitulo_3' => 'Redactá una bajada corta y llamativa (una sola frase) para el encabezado de la página de este negocio.',
        'body_subtitulo' => 'Redactá una descripción corta (1-2 frases) para la página de este negocio, tono cercano y profesional.',
        'body_parrafo_1' => 'Redactá un párrafo breve de "sobre nosotros" para la página de este negocio.',
        'body_parrafo_2' => 'Redactá un párrafo breve de "sobre nosotros" para la página de este negocio.',
        'body_parrafo_3' => 'Redactá un párrafo breve de "sobre nosotros" para la página de este negocio.',
        'body_parrafo_4' => 'Redactá un párrafo breve de "sobre nosotros" para la página de este negocio.',
    ];

    // Las plantillas ofrecidas viven en config/plantillas.php (fuente única,
    // ver App\Support\Plantillas). Antes había acá una constante
    // PLANTILLAS_DISPONIBLES que ChatController también importaba.

    public const CAMPOS_IMAGEN = [
        'favicon_logo', 'nav_logo', 'header_img_1', 'header_img_2', 'header_img_3',
        'body_tarjeta_img_1', 'body_tarjeta_img_2', 'body_tarjeta_img_3', 'body_tarjeta_img_4',
        'body_tarjeta_img_5', 'body_tarjeta_img_6', 'body_tarjeta_img_7', 'body_tarjeta_img_8',
        'body_tarjeta_img_9', 'body_tarjeta_img_10',
    ];

    public const CAMPOS_TEXTO = [
        'header_titulo_1', 'header_titulo_2', 'header_titulo_3',
        'header_subtitulo_1', 'header_subtitulo_2', 'header_subtitulo_3',
        'body_titulo', 'body_titulo_2', 'body_titulo_3', 'body_titulo_4',
        'body_parrafo_1', 'body_parrafo_2', 'body_parrafo_3', 'body_parrafo_4',
        'body_subtitulo',
        'body_tarjeta_titulo_1', 'body_tarjeta_titulo_2', 'body_tarjeta_titulo_3', 'body_tarjeta_titulo_4',
        'body_tarjeta_titulo_5', 'body_tarjeta_titulo_6', 'body_tarjeta_titulo_7', 'body_tarjeta_titulo_8',
        'body_tarjeta_parrafo_1', 'body_tarjeta_parrafo_2', 'body_tarjeta_parrafo_3', 'body_tarjeta_parrafo_4',
        'body_tarjeta_parrafo_5', 'body_tarjeta_parrafo_6', 'body_tarjeta_parrafo_7', 'body_tarjeta_parrafo_8',
        'body_tarjeta_precio_1', 'body_tarjeta_precio_2', 'body_tarjeta_precio_3', 'body_tarjeta_precio_4',
        'body_tarjeta_precio_5', 'body_tarjeta_precio_6', 'body_tarjeta_precio_7', 'body_tarjeta_precio_8',
        'body_titulo_nuestros_trabajos', 'body_tipos_medios_pago', 'body_maps',
        'footer_redes_facebook', 'footer_redes_instagram', 'footer_redes_youtube',
        'footer_redes_twitter', 'footer_redes_linkedin',
        'color_fondo', 'color_texto',
    ];

    public const MAX_PRODUCTOS_FORM = 10;

    public function create()
    {
        return view('admin.negocio_form', [
            'negocio' => new Product(),
            'propiedades' => new propiedades_plantillas(),
            'plantillas' => Plantillas::disponibles(),
            'productosActuales' => [],
            'mercadoPagoConectado' => false,
        ]);
    }

    public function misNegocios()
    {
        return view('admin.mis_negocios', [
            'negocios' => auth()->user()->negocios,
        ]);
    }

    public function edit(Product $negocio)
    {
        $this->verificarPropietario($negocio);

        return view('admin.negocio_form', [
            'negocio' => $negocio,
            'propiedades' => $negocio->propiedades ?? new propiedades_plantillas(),
            'plantillas' => Plantillas::disponibles(),
            'productosActuales' => $negocio->productos->map(fn ($p) => [
                'nombre' => $p->nombre,
                'precio' => $p->pivot->precio,
                'descripcion' => $p->pivot->descripcion,
                'stock' => $p->pivot->stock,
                'imagen_url' => \App\Models\ProductoCatalogo::resolverImagenPivot($p->pivot->imagen),
            ])->values()->all(),
            'mercadoPagoConectado' => $this->mercadoPago->negocioConectado($negocio),
        ]);
    }

    public function store(Request $request)
    {
        return $this->guardarNegocio($request, new Product());
    }

    public function update(Request $request, Product $negocio)
    {
        $this->verificarPropietario($negocio);

        return $this->guardarNegocio($request, $negocio);
    }

    /**
     * Un negocio sin dueño (creado antes de que existiera el login, o
     * huérfano) lo puede editar cualquier usuario logueado -- lo "reclama"
     * NegocioWriter::guardarNegocio() al guardar. Uno CON dueño solo lo
     * puede tocar ese mismo dueño.
     */
    private function verificarPropietario(Product $negocio): void
    {
        if ($negocio->user_id && $negocio->user_id !== auth()->id()) {
            abort(403, 'Ese negocio ya tiene otro dueño registrado.');
        }
    }

    private function guardarNegocio(Request $request, Product $negocio)
    {
        // Fase 4: el slug es único por ciudad, no global (ver
        // Product::normalizarCiudadSlug / NegocioWriter::guardarNegocio).
        $ciudadSlug = Product::normalizarCiudadSlug($request->input('ciudad', $negocio->ciudad));

        $datos = $request->validate([
            'nombre' => 'required|string|min:3',
            'slug' => [
                'required', 'string', 'min:3', 'alpha_dash',
                Rule::unique('negocios', 'slug')
                    ->where(fn ($query) => $query->where('ciudad_slug', $ciudadSlug))
                    ->ignore($negocio->id),
            ],
            'plantilla_id' => ['required', Rule::in(array_keys(Plantillas::disponibles()))],
            'profesion' => 'nullable|string',
            'cuit' => 'nullable|string',
            'telefono' => 'nullable|string',
            'celular' => 'nullable|string',
            'horario' => 'nullable|string',
            'direccion' => 'nullable|string',
            'email' => 'nullable|email',
            'ciudad' => 'nullable|string',
            'provincia' => 'nullable|string',
            'producto_servicio' => 'nullable|string',
            'rubro' => 'nullable|string',
            'zona' => 'nullable|string',
            'palabras_clave' => 'nullable|string',
            'url' => 'nullable|string',
            'nav_logo' => 'nullable|image|max:2048',
        ]);

        $negocio = $this->writer->guardarNegocio($datos, $negocio);

        // El nav_logo del negocio (tarjeta del index) se guarda en storage
        // igual que las imágenes de la plantilla, bajo la misma carpeta.
        $rutaNavLogoNegocio = $this->guardarImagenSubida($request, 'nav_logo', $negocio->id);
        if ($rutaNavLogoNegocio) {
            $negocio->nav_logo = $rutaNavLogoNegocio;
            $negocio->save();
        }

        $datosPropiedades = [];

        foreach (self::CAMPOS_TEXTO as $campo) {
            if ($request->has($campo)) {
                $datosPropiedades[$campo] = $request->input($campo) !== '' ? $request->input($campo) : null;
            }
        }

        foreach (self::CAMPOS_IMAGEN as $campo) {
            $ruta = $this->guardarImagenSubida($request, $campo, $negocio->id);
            if ($ruta) {
                $datosPropiedades[$campo] = $ruta;
            }
        }

        // Fase 5, Paso 2: colores por sección, uno por zona (header/body/
        // footer). El form manda `colores[zona][fondo|texto]` -- Laravel ya
        // lo arma como array anidado, acá solo se descartan las zonas sin
        // ningún color cargado.
        if ($request->has('colores')) {
            $coloresInput = $request->input('colores', []);
            $colores = [];
            foreach (propiedades_plantillas::ZONAS as $zona => $label) {
                $fondo = $coloresInput[$zona]['fondo'] ?? null;
                $texto = $coloresInput[$zona]['texto'] ?? null;
                if ($fondo || $texto) {
                    $colores[$zona] = array_filter(['fondo' => $fondo, 'texto' => $texto]);
                }
            }
            $datosPropiedades['colores'] = $colores ?: null;
        }

        $this->writer->guardarPropiedades($negocio, $datosPropiedades);

        $productos = [];
        for ($n = 1; $n <= self::MAX_PRODUCTOS_FORM; $n++) {
            $productos[] = [
                'nombre' => $request->input("producto_nombre_{$n}"),
                'precio' => $request->input("producto_precio_{$n}"),
                // null si no se subió archivo ni se eligió foto de la galería
                // -- sincronizarProductos() no toca la imagen ya guardada en
                // ese caso, mismo criterio que header_img_N y compañía.
                'imagen' => $this->guardarImagenSubida($request, "producto_imagen_{$n}", $negocio->id),
                'descripcion' => $request->input("producto_descripcion_{$n}", ''),
                'stock' => $request->input("producto_stock_{$n}"),
            ];
        }
        $this->writer->sincronizarProductos($negocio, $productos);

        return redirect($negocio->urlPublica())->with('status', 'Negocio guardado correctamente.');
    }

    /**
     * Un campo de imagen puede llenarse subiendo un archivo o eligiendo una
     * foto del banco propio (Fase 5, Paso 3, input oculto `galeria_{campo}`
     * armado por el picker de `admin/negocio_form.blade.php`). El archivo
     * subido gana si el usuario mandó los dos a la vez.
     */
    private function guardarImagenSubida(Request $request, string $campo, int $negocioId): ?string
    {
        if ($request->hasFile($campo)) {
            $archivo = $request->file($campo);
            if ($archivo->isValid()) {
                $nombre = $campo.'-'.Str::random(8).'.'.$archivo->getClientOriginalExtension();

                return $archivo->storeAs("negocios/{$negocioId}", $nombre, 'public');
            }
        }

        $ruta = $request->input("galeria_{$campo}");
        if ($ruta && GaleriaFotos::existe($ruta)) {
            return $ruta;
        }

        return null;
    }

    /**
     * JSON con las fotos disponibles del banco propio para una categoría
     * (Fase 5, Paso 3) -- consumido por el picker del formulario manual.
     */
    public function galeria(string $categoria)
    {
        return response()->json([
            'categorias' => GaleriaFotos::categorias(),
            'imagenes' => GaleriaFotos::imagenes($categoria),
        ]);
    }

    /**
     * Texto sugerido por Gemini para un campo largo del formulario manual
     * (Fase 5, Paso 5). El contexto viaja en el request tal cual está en
     * pantalla al momento del click (no se relee de la base), para que la
     * sugerencia use lo que el dueño del negocio ya escribió sin guardar.
     */
    public function sugerirTexto(Request $request)
    {
        $datos = $request->validate([
            'campo' => ['required', Rule::in(array_keys(self::CAMPOS_SUGERIBLES))],
            'nombre' => 'nullable|string',
            'profesion' => 'nullable|string',
            'rubro' => 'nullable|string',
            'descripcion_actual' => 'nullable|string',
        ]);

        $contexto = collect([
            'nombre' => $datos['nombre'] ?? null,
            'rubro/profesión' => $datos['profesion'] ?? $datos['rubro'] ?? null,
            'texto actual' => $datos['descripcion_actual'] ?? null,
        ])->filter()->map(fn ($valor, $clave) => "{$clave}: {$valor}")->implode('. ');

        $texto = $this->gemini->generar(
            self::CAMPOS_SUGERIBLES[$datos['campo']],
            $contexto !== '' ? $contexto : 'Sin datos adicionales todavía.'
        );

        return response()->json(['texto' => $texto]);
    }
}
