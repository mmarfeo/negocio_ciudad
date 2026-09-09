<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductoCatalogo;
use App\Models\propiedades_plantillas;
use App\Support\Plantillas;
use Illuminate\Http\Request;

/**
 * Index / directorio de negocios + buscador + render de la página pública
 * de cada negocio según su plantilla.
 *
 * El alta/edición de negocios NO vive acá -- ver NegocioAdminController
 * (form manual) y ChatController (chat guiado). Los métodos viejos de CRUD
 * de la tabla `products` (store/editarProd/verProduct/ProductDestroy) se
 * borraron en la Fase 8: apuntaban a un esquema que ya no existe.
 */
class ProductController extends Controller
{
    /** Index: listado/directorio de negocios. Responde JSON para el filtro en vivo. */
    public function listado()
    {
        $query = Product::orderBy('id');

        // Fase 4, Paso 2: el filtro en vivo del index pide esta misma ruta
        // (sin término, para "limpiar" la búsqueda) por fetch() con
        // Accept: application/json -- mismo formato que Buscar().
        if (request()->wantsJson()) {
            return response()->json(
                $query->limit(24)->get()->map(fn (Product $p) => [
                    'nombre' => $p->nombre,
                    'ciudad' => $p->ciudad,
                    'url' => $p->urlPublica(),
                    'avatar' => $p->navLogoUrl(),
                ])
            );
        }

        return view('index', ['products' => $query->get()]);
    }

    /** Buscador del directorio. Responde JSON para el filtro en vivo del index. */
    public function Buscar($search = null)
    {
        if (! empty($search)) {
            $query = Product::where('nombre', 'LIKE', '%'.$search.'%')
                ->orWhere('url', 'LIKE', '%'.$search.'%')
                ->orWhere('profesion', 'LIKE', '%'.$search.'%')
                ->orWhere('horario', 'LIKE', '%'.$search.'%')
                ->orWhere('direccion', 'LIKE', '%'.$search.'%')
                ->orWhere('ciudad', 'LIKE', '%'.$search.'%')
                ->orWhere('provincia', 'LIKE', '%'.$search.'%')
                ->orWhere('producto_servicio', 'LIKE', '%'.$search.'%')
                ->orWhere('rubro', 'LIKE', '%'.$search.'%')
                ->orWhere('zona', 'LIKE', '%'.$search.'%')
                ->orWhere('palabras_clave', 'LIKE', '%'.$search.'%')
                // Fase 2: negocios vinculados a un producto del catálogo
                // que matchea la búsqueda (nombre o categoría).
                ->orWhereHas('productos', function ($q) use ($search) {
                    $q->where('nombre', 'LIKE', '%'.$search.'%')
                        ->orWhere('categoria', 'LIKE', '%'.$search.'%');
                })
                ->orderBy('id');
        } else {
            $query = Product::orderBy('id');
        }

        // Fase 4, Paso 2: filtro en vivo del index -- el buscador pide esta
        // misma URL por fetch() con Accept: application/json mientras el
        // usuario escribe, para no duplicar las condiciones de búsqueda de
        // arriba en dos lugares distintos. Sin paginar (una tanda corta
        // alcanza para ir tipeando); la página completa sigue paginada.
        if (request()->wantsJson()) {
            return response()->json(
                $query->limit(24)->get()->map(fn (Product $p) => [
                    'nombre' => $p->nombre,
                    'ciudad' => $p->ciudad,
                    'url' => $p->urlPublica(),
                    'avatar' => $p->navLogoUrl(),
                ])
            );
        }

        return view('index', ['products' => $query->paginate(20)]);
    }

    /**
     * Página pública de un negocio: /{ciudad}/{slug}.
     *
     * Fase 4: el slug es único por ciudad, no global -- el negocio se busca
     * por los dos segmentos juntos (ver Product::normalizarCiudadSlug /
     * migración change_slug_unique_to_composite).
     *
     * Fase 8: el mapeo plantilla_id -> vista salió del `switch` que estaba
     * acá y vive en config/plantillas.php (fuente única, ver App\Support\
     * Plantillas).
     */
    public function InfoPlantilla($ciudad, $slug)
    {
        $products = Product::where('ciudad_slug', $ciudad)->where('slug', $slug)->first();

        if (! $products) {
            abort(404);
        }

        $plantillaId = (int) $products->plantilla_id;
        $config = Plantillas::get($plantillaId);

        if (! $config) {
            abort(404);
        }

        if (! empty($config['redirect'])) {
            return redirect($config['redirect']);
        }

        if (Plantillas::pasaDatos($plantillaId)) {
            $propiedades = propiedades_plantillas::where('negocio_id', $products->id)->first();

            return view($config['vista'], compact('products', 'propiedades'));
        }

        return view($config['vista']);
    }

    /**
     * Fase 2: autocompletado del buscador contra el catálogo de productos.
     */
    public function autocompletarProductos(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $productos = ProductoCatalogo::where('nombre', 'LIKE', '%'.$q.'%')
            ->orderBy('nombre')
            ->limit(10)
            ->pluck('nombre');

        return response()->json($productos);
    }
}
