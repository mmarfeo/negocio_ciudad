<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductoCatalogo;
use App\Models\propiedades_plantillas;
use Illuminate\Support\Facades\Auth;

/**
 * Único camino de escritura para crear/editar un negocio + sus propiedades
 * de plantilla + sus productos del catálogo (Fase 2). Tanto el formulario
 * manual (NegocioAdminController, Fase 1) como el chat con Gemini
 * (ChatController, Fase 3) pasan por acá -- así el chat no es un sistema
 * paralelo, es solo otra forma de completar los mismos datos.
 */
class NegocioWriter
{
    public function guardarNegocio(array $datos, ?Product $negocio = null): Product
    {
        $negocio = $negocio ?? new Product();

        // El primero en tocar un negocio sin dueño lo "reclama". Los que ya
        // tienen dueño no cambian de mano acá -- eso lo valida el controller.
        if (empty($negocio->user_id) && Auth::check()) {
            $datos['user_id'] = Auth::id();
        }

        // Fase 4: la URL pública ahora lleva la ciudad (/{ciudad}/{slug}) --
        // se normaliza acá para que tanto el chat como el formulario manual
        // (los dos únicos llamadores de este método) queden consistentes
        // sin repetir la lógica en cada uno.
        if (array_key_exists('ciudad', $datos) || empty($negocio->ciudad_slug)) {
            $datos['ciudad_slug'] = Product::normalizarCiudadSlug($datos['ciudad'] ?? $negocio->ciudad);
        }

        $negocio->fill($datos);
        $negocio->activo = true;
        $negocio->save();

        return $negocio;
    }

    public function guardarPropiedades(Product $negocio, array $datosPropiedades): propiedades_plantillas
    {
        $propiedades = propiedades_plantillas::firstOrNew(['negocio_id' => $negocio->id]);
        $propiedades->negocio_id = $negocio->id;
        $propiedades->plantilla_id = $negocio->plantilla_id;

        foreach ($datosPropiedades as $campo => $valor) {
            $propiedades->{$campo} = $valor === '' ? null : $valor;
        }

        $propiedades->save();

        return $propiedades;
    }

    /**
     * @param array<int, array{nombre: ?string, precio: mixed, imagen?: ?string, descripcion?: ?string, stock?: mixed}> $productos
     *
     * `imagen`/`descripcion`/`stock` son por-negocio (Etapa 2 del
     * marketplace, plantilla "Tienda"), igual que ya eran precio/disponible
     * -- el catálogo (`productos_catalogo`) sigue compartiendo solo
     * nombre/slug/categoría entre negocios. `imagen` es la excepción: solo
     * se pisa si llega una nueva (string no vacío) -- si el llamador no
     * subió una foto nueva en este guardado, no hay que borrar la que ya
     * estaba, mismo criterio que el resto de los campos de imagen del form.
     */
    public function sincronizarProductos(Product $negocio, array $productos): void
    {
        $pivotData = [];

        foreach ($productos as $item) {
            $nombreProducto = trim((string) ($item['nombre'] ?? ''));

            if ($nombreProducto === '') {
                continue;
            }

            $producto = ProductoCatalogo::encontrarOCrear($nombreProducto, $negocio->rubro);
            $precio = $item['precio'] ?? null;

            $datosPivot = [
                'precio' => $precio !== null && $precio !== '' ? $precio : null,
                'disponible' => true,
            ];

            if (! empty($item['imagen'])) {
                $datosPivot['imagen'] = $item['imagen'];
            }

            if (array_key_exists('descripcion', $item)) {
                $datosPivot['descripcion'] = $item['descripcion'] !== '' ? $item['descripcion'] : null;
            }

            if (array_key_exists('stock', $item)) {
                $stock = $item['stock'];
                $datosPivot['stock'] = $stock !== null && $stock !== '' ? (int) $stock : null;
            }

            $pivotData[$producto->id] = $datosPivot;
        }

        $negocio->productos()->sync($pivotData);
    }
}
