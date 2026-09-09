<?php

namespace App\Support;

/**
 * Acceso a la fuente única de plantillas (config/plantillas.php).
 *
 * La usan ProductController (qué vista renderizar por /{ciudad}/{slug}),
 * NegocioAdminController (opciones del form manual) y ChatController
 * (opciones + descripción + vista previa + pasos a saltear). Antes cada
 * uno tenía su propia copia parcial de esta info.
 */
class Plantillas
{
    /** Todas las plantillas: [id => config]. */
    public static function todas(): array
    {
        return config('plantillas', []);
    }

    /** Config de una plantilla, o null si el id no existe. */
    public static function get(int $id): ?array
    {
        return config("plantillas.$id");
    }

    public static function existe(int $id): bool
    {
        return config("plantillas.$id") !== null;
    }

    /**
     * Solo las plantillas ofrecidas en el alta (form + chat), como
     * [id => label] y en el orden del archivo de config. Reemplaza a
     * NegocioAdminController::PLANTILLAS_DISPONIBLES.
     */
    public static function disponibles(): array
    {
        return collect(config('plantillas', []))
            ->filter(fn ($p) => $p['disponible'] ?? false)
            ->map(fn ($p) => $p['label'])
            ->all();
    }

    /** true si esa plantilla se ofrece en el alta. */
    public static function esDisponible(int $id): bool
    {
        return (bool) (config("plantillas.$id.disponible") ?? false);
    }

    /** [id => descripcion] de las disponibles, para el paso "plantilla" del chat. */
    public static function descripciones(): array
    {
        return collect(config('plantillas', []))
            ->filter(fn ($p) => $p['disponible'] ?? false)
            ->map(fn ($p) => $p['descripcion'] ?? '')
            ->all();
    }

    /** Nombre de la vista Blade, o null. */
    public static function vista(int $id): ?string
    {
        return config("plantillas.$id.vista");
    }

    /** URL de redirección en vez de vista (id 9), o null. */
    public static function redirect(int $id): ?string
    {
        return config("plantillas.$id.redirect");
    }

    /** true si la vista recibe compact('products', 'propiedades'). */
    public static function pasaDatos(int $id): bool
    {
        return (bool) (config("plantillas.$id.pasa_datos") ?? false);
    }

    /** true si ese paso del chat no aplica a esta plantilla. */
    public static function salteaPaso(int $id, string $paso): bool
    {
        return in_array($paso, config("plantillas.$id.saltea_pasos", []), true);
    }

    /** true si el chat debe llenar body_tarjeta_* con los productos cargados. */
    public static function tarjetasDesdeProductos(int $id): bool
    {
        return (bool) (config("plantillas.$id.tarjetas_desde_productos") ?? false);
    }
}
