<?php

namespace App\Http\Controllers;

use App\Services\AssetDeployer;
use RuntimeException;

/**
 * Fase 8, Bloque C: dispara App\Services\AssetDeployer desde el navegador,
 * para no depender de tener consola/SSH en Hostinger. Detrás de
 * 'auth'+'es_admin' (ver routes/web.php) -- es una herramienta de deploy,
 * no algo que un dueño de negocio deba ver ni poder tocar.
 */
class DeployController extends Controller
{
    public function panel(AssetDeployer $deployer)
    {
        return view('admin.deploy', [
            'destinoConfigurado' => $deployer->destinoConfigurado(),
        ]);
    }

    public function desplegarAssets(AssetDeployer $deployer)
    {
        try {
            $resultado = $deployer->desplegar();

            return back()->with('resultado', $resultado);
        } catch (RuntimeException $e) {
            return back()->withErrors(['deploy' => $e->getMessage()]);
        }
    }
}
