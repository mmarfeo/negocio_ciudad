<?php

namespace App\Console\Commands;

use App\Services\AssetDeployer;
use Illuminate\Console\Command;
use RuntimeException;

class DeployAssets extends Command
{
    protected $signature = 'deploy:assets';

    protected $description = 'Copia los assets (css/js/img/fonts) de public/ a public_html/ en Hostinger';

    public function handle(AssetDeployer $deployer): int
    {
        try {
            $resultado = $deployer->desplegar();
        } catch (RuntimeException $e) {
            $this->error($e->getMessage());

            return 1;
        }

        foreach ($resultado['carpetas'] as $carpeta => $cantidad) {
            $this->info("{$carpeta}/: {$cantidad} archivos");
        }

        if (! empty($resultado['archivos_raiz'])) {
            $this->info('Raíz: '.implode(', ', $resultado['archivos_raiz']));
        }

        $this->comment("Total copiado: {$resultado['total']} archivos.");

        return 0;
    }
}
