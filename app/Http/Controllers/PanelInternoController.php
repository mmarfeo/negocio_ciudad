<?php

namespace App\Http\Controllers;

/**
 * Panel de solo lectura, detrás de 'auth' + 'es_admin' (ver routes/web.php):
 * documentación del proyecto (misión, visión, mercado) y el roadmap
 * ordenado por prioridad, pensado para prepararse antes de una charla con
 * posibles socios. Contenido estático a propósito -- es el mismo motivo
 * por el que el resto del sitio no usa un layout Blade compartido, y acá
 * además `documentos/` (de donde sale este contenido) vive fuera de la
 * carpeta que se sube a producción, así que no se puede leer en vivo.
 */
class PanelInternoController extends Controller
{
    public function index()
    {
        return view('panel_interno.index');
    }
}
