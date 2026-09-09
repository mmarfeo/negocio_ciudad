<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Fase 4, Paso 1: el slug de negocio deja de ser único a nivel de todo el
 * sitio y pasa a ser único por (ciudad_slug, slug), para que la URL
 * `/{ciudad}/{slug}` no choque entre negocios de ciudades distintas.
 *
 * La tabla `negocios` se creó a mano en phpMyAdmin (ver comentario en
 * 2026_08_02_000001_create_negocios_table.php), así que el índice único de
 * `slug` puede no tener el nombre que le pondría Laravel por convención --
 * se busca por columna en vez de asumir un nombre fijo.
 */
class ChangeSlugUniqueToCompositeOnNegociosTable extends Migration
{
    public function up()
    {
        $indexesUnicosDeSlug = collect(DB::select("SHOW INDEX FROM negocios WHERE Column_name = 'slug' AND Non_unique = 0"))
            ->pluck('Key_name')
            ->unique()
            ->reject(fn ($nombre) => $nombre === 'PRIMARY');

        foreach ($indexesUnicosDeSlug as $nombreIndice) {
            DB::statement("ALTER TABLE negocios DROP INDEX `{$nombreIndice}`");
        }

        // El viejo UNIQUE(slug) global, en teoría, ya debería haber evitado
        // slugs repetidos -- pero en la base real de Hostinger había filas
        // con (ciudad_slug, slug) duplicados igual (la tabla se armó a mano,
        // ver comentario de arriba), lo que hace fallar el ADD UNIQUE de
        // abajo. Se resuelve sin borrar nada: a los duplicados (todos menos
        // el más viejo de cada grupo) se les agrega su propio id al slug.
        DB::statement(<<<'SQL'
            UPDATE negocios n
            JOIN (
                SELECT id, ROW_NUMBER() OVER (PARTITION BY ciudad_slug, slug ORDER BY id) AS posicion
                FROM negocios
            ) dup ON dup.id = n.id
            SET n.slug = CONCAT(IF(n.slug IS NULL OR n.slug = '', 'negocio', n.slug), '-', n.id)
            WHERE dup.posicion > 1
        SQL);

        Schema::table('negocios', function (Blueprint $table) {
            $table->unique(['ciudad_slug', 'slug'], 'negocios_ciudad_slug_slug_unique');
        });
    }

    public function down()
    {
        Schema::table('negocios', function (Blueprint $table) {
            $table->dropUnique('negocios_ciudad_slug_slug_unique');
            $table->unique('slug', 'negocios_slug_unique');
        });
    }
}
