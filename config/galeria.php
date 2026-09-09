<?php

/*
 * Categorías del banco propio de fotos (Fase 5, Paso 3) -- lista cerrada,
 * distinta del campo `rubro` de texto libre que carga el dueño del negocio
 * (ese lo extrae Gemini del chat, este es solo para organizar la galería).
 *
 * Cada clave es también el nombre de la carpeta en public/img/galeria/ --
 * ver App\Services\GaleriaFotos. Arranca sin fotos reales cargadas (solo
 * la estructura de carpetas); la curaduría del set inicial por rubro,
 * verificando licencia de uso comercial, queda pendiente y es trabajo de
 * contenido, no de código.
 */

return [
    'gastronomia' => 'Gastronomía',
    'indumentaria' => 'Indumentaria',
    'oficios-servicios' => 'Oficios y servicios',
    'belleza' => 'Belleza',
    'otros' => 'Otros',
];
