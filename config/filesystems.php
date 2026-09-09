<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        // OJO -- en Hostinger el dominio se sirve desde una carpeta HERMANA
        // de negocio_ciudad llamada `public_html` (confirmado 2026-08-27;
        // mismo motivo por el que hay que subir los CSS ahí a mano). Antes
        // esto apuntaba siempre a `public_path('storage')`
        // (negocio_ciudad/public/storage) -- una carpeta que el dominio real
        // nunca sirve -- así que cualquier archivo subido en vivo (logo,
        // fotos de producto, header, etc.) quedaba guardado en un lugar
        // inalcanzable y daba 404 al mostrarlo, aunque la subida en sí
        // funcionara bien.
        //
        // PUBLIC_STORAGE_PATH es la ruta ABSOLUTA a la carpeta `storage`
        // real que sirve el dominio -- se define en el `.env` de
        // producción, ej. PUBLIC_STORAGE_PATH=/home/u374453216/domains/negociosentuciudad.com/public_html/storage
        // (buscar la ruta exacta en el panel de Hostinger -> Administrador
        // de archivos -> parada en la carpeta public_html -> suele
        // mostrarla en algún lado, o preguntarle a soporte de Hostinger).
        // Sin esa variable, cae al comportamiento de siempre
        // (public_path('storage')) -- así no se rompe el entorno local, que
        // no tiene esa carpeta hermana.
        'public' => [
            'driver' => 'local',
            'root' => env('PUBLIC_STORAGE_PATH', public_path('storage')),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
