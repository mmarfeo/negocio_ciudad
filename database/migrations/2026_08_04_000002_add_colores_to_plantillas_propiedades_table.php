<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Permite que cada negocio elija color de fondo y de letra de su página
 * (formulario de edición). Nullable: si no se personaliza, la plantilla
 * usa su color por defecto de siempre.
 */
class AddColoresToPlantillasPropiedadesTable extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('plantillas_propiedades', 'color_fondo')) {
            return;
        }

        Schema::table('plantillas_propiedades', function (Blueprint $table) {
            $table->string('color_fondo', 7)->nullable();
            $table->string('color_texto', 7)->nullable();
        });
    }

    public function down()
    {
        Schema::table('plantillas_propiedades', function (Blueprint $table) {
            $table->dropColumn(['color_fondo', 'color_texto']);
        });
    }
}
