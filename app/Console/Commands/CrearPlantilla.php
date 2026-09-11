<?php

namespace App\Console\Commands;

use App\Support\Plantillas;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Fase 8, Bloque D3 (biblioteca de plantillas): clona la vista Blade y las
 * carpetas de assets (css/js/img) de una plantilla existente, como punto de
 * partida para armar una nueva -- en vez de escribirla de cero cada vez.
 *
 * Deja el trabajo restante bien acotado: ajustar el Blade clonado + su CSS,
 * y agregar la entrada nueva en config/plantillas.php (se imprime lista
 * para pegar). No toca config/plantillas.php solo -- es un array de PHP a
 * mano y romper su formato es peor que pedirle al desarrollador que pegue
 * 8 líneas.
 */
class CrearPlantilla extends Command
{
    protected $signature = 'plantilla:crear
        {id : Id numérico de la plantilla nueva (no debe existir en config/plantillas.php)}
        {desde : Id de la plantilla existente a clonar}
        {--label= : Nombre para mostrar en el alta (form/chat). Default: "Plantilla {id}"}';

    protected $description = 'Clona la vista y los assets de una plantilla existente como base para una nueva';

    public function handle(): int
    {
        $nuevoId = (int) $this->argument('id');
        $origenId = (int) $this->argument('desde');

        $origen = Plantillas::get($origenId);

        if (! $origen) {
            $this->error("No existe la plantilla de origen (id {$origenId}) en config/plantillas.php.");

            return 1;
        }

        if (Plantillas::existe($nuevoId)) {
            $this->error("Ya existe una plantilla con id {$nuevoId} en config/plantillas.php -- elegí otro id.");

            return 1;
        }

        $label = $this->option('label') ?: "Plantilla {$nuevoId}";
        $vistaOrigen = $origen['vista'];
        $vistaNueva = Str::slug($label) ?: "plantilla-{$nuevoId}";

        $rutaBladeOrigen = resource_path("views/{$vistaOrigen}.blade.php");
        $rutaBladeNueva = resource_path("views/{$vistaNueva}.blade.php");

        if (! File::exists($rutaBladeOrigen)) {
            $this->error("No encontré la vista de origen: resources/views/{$vistaOrigen}.blade.php");

            return 1;
        }

        if (File::exists($rutaBladeNueva)) {
            $this->error("Ya existe resources/views/{$vistaNueva}.blade.php -- elegí otro --label.");

            return 1;
        }

        // 1) Clonar el Blade, apuntando sus propios assets a la carpeta nueva
        //    (los <link>/<script> de estas plantillas usan el nombre de la
        //    vista como carpeta, ej. asset('css/03-productos/estilos.css')).
        $contenido = File::get($rutaBladeOrigen);
        $contenido = str_replace("/{$vistaOrigen}/", "/{$vistaNueva}/", $contenido);
        File::put($rutaBladeNueva, $contenido);
        $this->info("Vista clonada: resources/views/{$vistaNueva}.blade.php");

        // 2) Clonar las carpetas de assets que existan (css/js/img), mismo nombre nuevo.
        foreach (['css', 'js', 'img'] as $tipo) {
            $origenDir = public_path("{$tipo}/{$vistaOrigen}");
            $destinoDir = public_path("{$tipo}/{$vistaNueva}");

            if (File::isDirectory($origenDir) && ! File::isDirectory($destinoDir)) {
                File::copyDirectory($origenDir, $destinoDir);
                $this->info("Assets clonados: public/{$tipo}/{$vistaNueva}/");
            }
        }

        // 3) El bloque para pegar en config/plantillas.php -- a mano, no se
        //    edita el archivo solo (ver docblock de la clase).
        $this->newLine();
        $this->comment('Agregá esta entrada en config/plantillas.php (y subí las carpetas nuevas):');
        $this->line("
    {$nuevoId} => [
        'label' => '{$label}',
        'disponible' => true,
        'descripcion' => 'Descripción corta para el paso \"plantilla\" del chat',
        'vista' => '{$vistaNueva}',
        'pasa_datos' => true,
        'saltea_pasos' => [],
    ],
");
        $this->comment('Después: ajustá el Blade clonado y su CSS, y probá /admin/negocios/crear.');

        return 0;
    }
}
