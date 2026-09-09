<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

/**
 * Única forma de dar de alta un admin de la plataforma: por consola, no
 * por formulario. No hay UI para esto a propósito -- es un rol que hoy
 * usa una sola persona (el dueño del proyecto).
 */
class OtorgarAdmin extends Command
{
    protected $signature = 'admin:otorgar {email}';

    protected $description = 'Da (o saca) el acceso al panel interno a un usuario ya registrado, por email';

    public function handle()
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error("No existe ningún usuario registrado con ese email.");
            return 1;
        }

        if ($user->es_admin) {
            $this->info("{$user->email} ya es admin. ¿Sacarle el acceso? (y/N)");
            if ($this->confirm('Sacar acceso')) {
                $user->es_admin = false;
                $user->save();
                $this->info("Listo, {$user->email} ya no es admin.");
            }
            return 0;
        }

        $user->es_admin = true;
        $user->save();
        $this->info("Listo, {$user->email} ahora puede entrar a /panel-socios.");

        return 0;
    }
}
