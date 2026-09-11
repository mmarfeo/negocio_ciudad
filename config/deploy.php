<?php

/*
 * Fase 8, Bloque C: deploy de assets (CSS/JS/imágenes de plantillas).
 *
 * En Hostinger el dominio se sirve desde `public_html/`, una carpeta
 * HERMANA de este proyecto (ver README.md) -- los archivos de
 * `negocio_ciudad/public/` no se sirven solos, hay que copiarlos ahí.
 * Históricamente eso se hacía a mano por FTP, y es la causa de bugs tipo
 * "subí el CSS y no se ve" (se editó acá pero no se copió allá).
 *
 * `public_html_path` es la ruta ABSOLUTA a esa carpeta (la misma cuenta
 * que ya usa PUBLIC_STORAGE_PATH, sin el "/storage" final). La usa
 * App\Services\AssetDeployer (comando `deploy:assets` y la ruta
 * /admin/desplegar-assets). Vacío en local -- ahí no hace falta.
 */

return [
    'public_html_path' => env('PUBLIC_HTML_PATH'),
];
