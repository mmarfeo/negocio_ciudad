<?php

namespace App\Services;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;

/**
 * Fase 8, Bloque C: copia los assets estáticos de `negocio_ciudad/public/`
 * a `public_html/` (la carpeta hermana que realmente sirve el dominio en
 * Hostinger -- ver README.md). Reemplaza el "subir por FTP a mano" que
 * venía causando el bug de "edité el CSS y no se ve" (se olvidaba el paso
 * de copiarlo a la otra carpeta).
 *
 * A propósito NO toca `index.php`, `.htaccess`, `web.config`, `robots.txt`
 * ni `storage/` de `public_html/` -- esos son específicos de cada
 * carpeta (el shim de producción, la config de Apache) y no vienen del
 * build de Laravel.
 */
class AssetDeployer
{
    /** Carpetas de assets que sí se sincronizan tal cual. */
    private const CARPETAS = ['css', 'js', 'img', 'fonts', 'webfonts', 'sass'];

    /** Archivos sueltos de la raíz de public/ que también son build, no config del servidor. */
    private const ARCHIVOS_RAIZ = ['mix-manifest.json'];

    public function destinoConfigurado(): bool
    {
        return ! empty(config('deploy.public_html_path'));
    }

    /**
     * @return array{carpetas: array<string,int>, archivos_raiz: array<int,string>, total: int}
     */
    public function desplegar(): array
    {
        $destinoBase = config('deploy.public_html_path');

        if (empty($destinoBase)) {
            throw new RuntimeException('PUBLIC_HTML_PATH no está configurado en el .env.');
        }

        if (! is_dir($destinoBase) || ! is_writable($destinoBase)) {
            throw new RuntimeException("El destino no existe o no se puede escribir: {$destinoBase}");
        }

        $origenBase = public_path();
        $resultado = ['carpetas' => [], 'archivos_raiz' => [], 'total' => 0];

        foreach (self::CARPETAS as $carpeta) {
            $origen = $origenBase.DIRECTORY_SEPARATOR.$carpeta;

            if (! is_dir($origen)) {
                continue;
            }

            $copiados = $this->copiarDirectorio($origen, $destinoBase.DIRECTORY_SEPARATOR.$carpeta);
            $resultado['carpetas'][$carpeta] = $copiados;
            $resultado['total'] += $copiados;
        }

        foreach (self::ARCHIVOS_RAIZ as $archivo) {
            $origen = $origenBase.DIRECTORY_SEPARATOR.$archivo;

            if (is_file($origen) && @copy($origen, $destinoBase.DIRECTORY_SEPARATOR.$archivo)) {
                $resultado['archivos_raiz'][] = $archivo;
                $resultado['total']++;
            }
        }

        return $resultado;
    }

    private function copiarDirectorio(string $origen, string $destino): int
    {
        if (! is_dir($destino) && ! @mkdir($destino, 0755, true) && ! is_dir($destino)) {
            throw new RuntimeException("No se pudo crear el directorio destino: {$destino}");
        }

        $contador = 0;

        $items = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($origen, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($items as $item) {
            $rutaRelativa = substr($item->getPathname(), strlen($origen) + 1);
            $destinoItem = $destino.DIRECTORY_SEPARATOR.$rutaRelativa;

            if ($item->isDir()) {
                if (! is_dir($destinoItem)) {
                    @mkdir($destinoItem, 0755, true);
                }

                continue;
            }

            if (@copy($item->getPathname(), $destinoItem)) {
                $contador++;
            }
        }

        return $contador;
    }
}
