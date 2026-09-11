<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * Fase 8, Bloque B: copia todo lo que hay HOY en el disco `public` (local,
 * atado a public_html/storage en Hostinger -- ver config/filesystems.php)
 * al disco `s3` explícito (R2/B2, configurado con las variables AWS_*).
 *
 * No toca la base de datos: las rutas guardadas (`negocios/{id}/...`) son
 * las mismas en los dos discos, lo único que cambia es quién las sirve.
 * Se corre UNA vez, antes de poner `PUBLIC_DISK_DRIVER=s3` -- después de
 * eso, `Storage::disk('public')` ya escribe directo en S3 y este comando
 * no hace falta de nuevo (salvo para volver a sincronizar algo puntual).
 */
class MigrarStorageAS3 extends Command
{
    protected $signature = 'storage:migrar-a-s3 {--dry-run : Solo contar/listar, no copiar nada}';

    protected $description = 'Copia los archivos del disco local "public" al disco "s3" (R2/B2), sin tocar la base de datos';

    public function handle(): int
    {
        if (empty(config('filesystems.disks.s3.bucket'))) {
            $this->error('Faltan las variables AWS_* (bucket) en el .env -- no hay nada configurado para migrar.');

            return 1;
        }

        $origen = Storage::disk('public');
        $destino = Storage::disk('s3');
        $dryRun = (bool) $this->option('dry-run');

        $archivos = $origen->allFiles();
        $this->info(count($archivos).' archivos encontrados en el disco local.');

        if (empty($archivos)) {
            return 0;
        }

        $barra = $this->output->createProgressBar(count($archivos));
        $barra->start();

        $copiados = 0;
        $yaExistian = 0;
        $fallidos = 0;

        foreach ($archivos as $ruta) {
            if ($destino->exists($ruta)) {
                $yaExistian++;
                $barra->advance();

                continue;
            }

            if ($dryRun) {
                $copiados++;
                $barra->advance();

                continue;
            }

            try {
                $destino->put($ruta, $origen->get($ruta), 'public');
                $copiados++;
            } catch (Throwable $e) {
                $fallidos++;
                $this->newLine();
                $this->warn("Falló \"{$ruta}\": {$e->getMessage()}");
            }

            $barra->advance();
        }

        $barra->finish();
        $this->newLine(2);

        $resumen = $dryRun ? 'copiarían' : 'copiados';
        $this->info("{$resumen}: {$copiados} · ya existían en S3: {$yaExistian} · fallidos: {$fallidos}");

        if ($dryRun) {
            $this->comment('Modo --dry-run: no se copió nada de verdad.');
        } elseif ($fallidos === 0 && $copiados > 0) {
            $this->comment('Listo. Ahora podés poner PUBLIC_DISK_DRIVER=s3 en el .env y correr config:clear.');
        }

        return $fallidos > 0 ? 1 : 0;
    }
}
