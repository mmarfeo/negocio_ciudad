<?php

/*
|--------------------------------------------------------------------------
| Plantillas de página de negocio -- FUENTE ÚNICA
|--------------------------------------------------------------------------
|
| Antes esto estaba partido en 4 lugares que había que mantener en sync a
| mano: el `switch ($plantilla_id)` de ProductController::InfoPlantilla(),
| NegocioAdminController::PLANTILLAS_DISPONIBLES, y
| ChatController::PLANTILLAS_DESCRIPCION + ::VISTA_POR_PLANTILLA. Agregar
| una plantilla implicaba tocar los 4 y era fácil olvidarse de uno (pasó
| con la Vidriera, id 10). Ahora todo vive acá y se consume por
| App\Support\Plantillas.
|
| Campos por plantilla:
|  - label:        nombre visible en el form de alta y en el chat.
|  - disponible:   true = se ofrece en el alta (form + chat). Las legacy
|                  (negocios viejos ya publicados) van con false: no se
|                  ofrecen, pero InfoPlantilla las tiene que seguir
|                  renderizando.
|  - descripcion:  bajada de una línea para el paso "plantilla" del chat.
|  - vista:        nombre de la vista Blade (resources/views/<vista>.blade.php).
|  - redirect:     URL a la que redirigir en vez de renderizar (id 9).
|  - pasa_datos:   true  -> view($vista, compact('products', 'propiedades'))
|                  false -> view($vista)  (plantilla estática, no usa datos)
|  - saltea_pasos: pasos del chat que no aplican a esta plantilla
|                  (ej. Tarjeta no muestra productos).
|  - tarjetas_desde_productos: true -> el chat llena body_tarjeta_* con los
|                  productos cargados (hoy solo la plantilla "Productos").
|
*/

return [

    1 => [
        'label' => 'Misia Paula (legacy)',
        'disponible' => false,
        'vista' => '01-misia-paula',
        'pasa_datos' => false,
    ],

    2 => [
        'label' => 'Independiente (portfolio)',
        'disponible' => true,
        'descripcion' => 'Portfolio creativo: fotografía, diseño, oficios de imagen',
        'vista' => '02-independiente',
        'pasa_datos' => true,
        'saltea_pasos' => [],
    ],

    3 => [
        'label' => 'Productos',
        'disponible' => true,
        'descripcion' => 'Venta de productos: comida, indumentaria, kioscos',
        'vista' => '03-productos',
        'pasa_datos' => true,
        'saltea_pasos' => [],
        'tarjetas_desde_productos' => true,
    ],

    4 => [
        'label' => 'Servicios',
        'disponible' => true,
        'descripcion' => 'Oficios y profesionales: gasista, contador, salud',
        'vista' => '04-servicios',
        'pasa_datos' => true,
        'saltea_pasos' => [],
    ],

    5 => [
        'label' => 'Tarjeta',
        'disponible' => true,
        'descripcion' => 'Tarjeta simple: solo contacto y ubicación',
        'vista' => '05-tarjeta',
        'pasa_datos' => true,
        // Su vista no muestra productos/servicios cargados.
        'saltea_pasos' => ['productos'],
    ],

    6 => [
        'label' => 'Sugary (legacy)',
        'disponible' => false,
        'vista' => '06-sugary',
        'pasa_datos' => false,
    ],

    7 => [
        'label' => 'MN Contabilidad (legacy)',
        'disponible' => false,
        'vista' => '07-mncontabilidad',
        'pasa_datos' => false,
    ],

    8 => [
        'label' => 'Pizza Nico (legacy)',
        'disponible' => false,
        'vista' => '08-pizza-nico',
        'pasa_datos' => false,
    ],

    9 => [
        'label' => 'Cookie Boss (redirect legacy)',
        'disponible' => false,
        'redirect' => 'https://negociosentuciudad.com/cookie-boss-tienda',
    ],

    10 => [
        'label' => 'Vidriera (gastronomía con foto y precio)',
        'disponible' => true,
        'descripcion' => 'Vidriera: gastronomía artesanal, carta con foto y precio',
        'vista' => '10-vidriera',
        'pasa_datos' => true,
        'saltea_pasos' => [],
    ],

    11 => [
        'label' => 'Tienda (carrito y cobro con Mercado Pago)',
        'disponible' => true,
        'descripcion' => 'Tienda: catálogo con carrito y cobro online',
        'vista' => '11-tienda',
        'pasa_datos' => true,
        'saltea_pasos' => [],
    ],

];
