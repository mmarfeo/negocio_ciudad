<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Gatea el panel interno (documentación + roadmap para socios), separado
 * del "admin" que ya usan las rutas /admin/negocios/* -- ese siempre
 * significó "el dueño gestionando su propio negocio", nunca un rol de
 * plataforma. Devuelve 404 en vez de 403 a propósito: alguien sin
 * `es_admin` no tiene por qué enterarse de que el panel existe.
 */
class EnsureEsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user() || ! $request->user()->es_admin) {
            abort(404);
        }

        return $next($request);
    }
}
