<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Fase 4, Paso 0: normalización de ciudad para poder armar URLs
 * `/{ciudad}/{negocio}`. Guarda un slug de la ciudad (`Str::slug(ciudad)`)
 * junto al campo `ciudad` de texto libre existente -- no reemplaza `ciudad`,
 * solo agrega la versión normalizada para usar en rutas.
 *
 * Idempotente a propósito: en Hostinger este mismo cambio también se puede
 * aplicar a mano por phpMyAdmin (ver database/sql/hostinger_fase4_paso1.sql),
 * así que `php artisan migrate` tiene que poder correr sin importar si la
 * columna/índice ya existen de una corrida manual previa.
 */
class AddCiudadSlugToNegociosTable extends Migration
{
    public function up()
    {
        if (! Schema::hasColumn('negocios', 'ciudad_slug')) {
            Schema::table('negocios', function (Blueprint $table) {
                $table->string('ciudad_slug')->nullable()->after('ciudad');
            });
        }

        // Todas las filas, no solo las que tienen ciudad: las que no tienen
        // quedan con el mismo fallback 'sin-ciudad' que usa
        // Product::normalizarCiudadSlug() para negocios nuevos, así el
        // lookup por ruta /{ciudad}/{slug} las encuentra igual. Se corre
        // siempre (no solo la primera vez) para que un negocio que cambió
        // de ciudad por fuera de la app quede al día igual.
        DB::table('negocios')->orderBy('id')->each(function ($negocio) {
            DB::table('negocios')->where('id', $negocio->id)->update([
                'ciudad_slug' => Str::slug((string) $negocio->ciudad) ?: 'sin-ciudad',
            ]);
        });

        $existeIndice = collect(DB::select("SHOW INDEX FROM negocios WHERE Key_name = 'negocios_ciudad_slug_index'"))->isNotEmpty();

        if (! $existeIndice) {
            Schema::table('negocios', function (Blueprint $table) {
                $table->index('ciudad_slug', 'negocios_ciudad_slug_index');
            });
        }
    }

    public function down()
    {
        Schema::table('negocios', function (Blueprint $table) {
            if (Schema::hasColumn('negocios', 'ciudad_slug')) {
                $table->dropIndex('negocios_ciudad_slug_index');
                $table->dropColumn('ciudad_slug');
            }
        });
    }
}
