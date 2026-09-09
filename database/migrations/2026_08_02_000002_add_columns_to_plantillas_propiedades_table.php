<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * La migración original de esta tabla (2021_06_11_014612) tiene un bug:
 * crea la tabla `propiedades_plantillas` pero el modelo
 * App\Models\propiedades_plantillas apunta a `plantillas_propiedades`
 * (nombre invertido) -- por eso nunca coincidieron y la tabla real que usa
 * la app se creó a mano en producción, con ~65 columnas que tampoco están
 * en ninguna migración. Esta migración crea la tabla correcta si hace
 * falta y documenta esas columnas. El equivalente para Hostinger está en
 * database/sql/hostinger_fase1.sql (ALTER TABLE, no destructivo).
 */
class AddColumnsToPlantillasPropiedadesTable extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('plantillas_propiedades')) {
            Schema::create('plantillas_propiedades', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });
        }

        Schema::table('plantillas_propiedades', function (Blueprint $table) {
            if (Schema::hasColumn('plantillas_propiedades', 'negocio_id')) {
                return;
            }

            $table->unsignedInteger('plantilla_id')->nullable()->after('id');
            // `negocios.id` es `int(11)` CON signo (tabla creada a mano) --
            // la FK tiene que matchear ese tipo exacto, no bigint/unsigned.
            $table->integer('negocio_id')->nullable()->after('plantilla_id');
            $table->foreign('negocio_id')->references('id')->on('negocios')->cascadeOnDelete();

            $table->string('nav_logo')->nullable();
            $table->string('favicon_logo')->nullable();

            foreach ([1, 2, 3] as $n) {
                $table->string("header_img_{$n}")->nullable();
                $table->string("header_titulo_{$n}")->nullable();
                $table->string("header_subtitulo_{$n}")->nullable();
            }

            $table->string('body_titulo')->nullable();
            $table->string('body_titulo_2')->nullable();
            $table->string('body_titulo_3')->nullable();
            $table->string('body_titulo_4')->nullable();
            $table->text('body_parrafo_1')->nullable();
            $table->text('body_parrafo_2')->nullable();
            $table->text('body_parrafo_3')->nullable();
            $table->text('body_parrafo_4')->nullable();
            $table->string('body_subtitulo')->nullable();

            for ($n = 1; $n <= 10; $n++) {
                $table->string("body_tarjeta_img_{$n}")->nullable();
            }
            for ($n = 1; $n <= 8; $n++) {
                $table->string("body_tarjeta_titulo_{$n}")->nullable();
                $table->text("body_tarjeta_parrafo_{$n}")->nullable();
                $table->string("body_tarjeta_precio_{$n}")->nullable();
            }

            $table->string('body_titulo_nuestros_trabajos')->nullable();
            $table->string('body_tipos_medios_pago')->nullable();
            $table->text('body_maps')->nullable();

            $table->string('footer_redes_facebook')->nullable();
            $table->string('footer_redes_instagram')->nullable();
            $table->string('footer_redes_youtube')->nullable();
            $table->string('footer_redes_twitter')->nullable();
            $table->string('footer_redes_linkedin')->nullable();
        });
    }

    public function down()
    {
        // Ver database/sql/hostinger_fase1.sql para el detalle de columnas;
        // no se define rollback automático para no arriesgar datos reales.
    }
}
