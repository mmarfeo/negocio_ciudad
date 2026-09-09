<?php

namespace App\Services;

/**
 * Banco propio de fotos para elegir por sección (Fase 5, Paso 3), organizado
 * en carpetas por categoría bajo public/img/galeria/{categoria}/. No hay
 * tabla en base de datos: la fuente de verdad es el sistema de archivos,
 * para que cargar una foto nueva sea simplemente copiarla a la carpeta que
 * corresponda.
 */
class GaleriaFotos
{
    private const EXTENSIONES = ['jpg', 'jpeg', 'png', 'webp'];

    public static function categorias(): array
    {
        return config('galeria', []);
    }

    public static function imagenes(string $categoria): array
    {
        if (! array_key_exists($categoria, self::categorias())) {
            return [];
        }

        $carpeta = public_path("img/galeria/{$categoria}");
        if (! is_dir($carpeta)) {
            return [];
        }

        $archivos = glob($carpeta.'/*.{'.implode(',', self::EXTENSIONES).'}', GLOB_BRACE) ?: [];

        return collect($archivos)
            ->map(fn ($archivo) => self::datos($categoria, basename($archivo)))
            ->values()
            ->all();
    }

    /**
     * Valida que una ruta "galeria/{categoria}/{archivo}" recibida del
     * formulario corresponda a un archivo real del banco -- nunca hay que
     * confiar en el valor tal cual llega del cliente (viene de un input
     * hidden que se puede manipular).
     */
    public static function existe(string $ruta): bool
    {
        $extensiones = implode('|', self::EXTENSIONES);
        if (! preg_match('#^galeria/([a-z0-9-]+)/([A-Za-z0-9_-]+\.('.$extensiones.'))$#', $ruta, $m)) {
            return false;
        }

        $categoria = $m[1];

        return array_key_exists($categoria, self::categorias())
            && file_exists(public_path("img/{$ruta}"));
    }

    private static function datos(string $categoria, string $archivo): array
    {
        $ruta = "galeria/{$categoria}/{$archivo}";

        return ['ruta' => $ruta, 'url' => asset("img/{$ruta}")];
    }
}
