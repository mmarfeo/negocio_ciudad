<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fase 5, Paso 2: colores por sección (header/body/footer) en vez de un
 * único color de fondo/letra para toda la página. Se guardan en una sola
 * columna JSON (`colores`) en vez de columnas sueltas por zona, para no
 * tener que migrar de nuevo si se agrega una zona -- ver
 * documentos/fase-5-personalizacion-visual.md, punto 05.
 *
 * No se tocan ni se borran `color_fondo`/`color_texto`: los negocios que ya
 * eligieron un color con el sistema viejo lo siguen viendo igual
 * (`propiedades_plantillas::estiloPersonalizado()` cae a esos campos si
 * `colores` viene vacío).
 */
class AddColoresJsonToPlantillasPropiedadesTable extends Migration
{
    public function up()
    {
        if (! Schema::hasColumn('plantillas_propiedades', 'colores')) {
            Schema::table('plantillas_propiedades', function (Blueprint $table) {
                $table->json('colores')->nullable()->after('color_texto');
            });
        }
    }

    public function down()
    {
        Schema::table('plantillas_propiedades', function (Blueprint $table) {
            if (Schema::hasColumn('plantillas_propiedades', 'colores')) {
                $table->dropColumn('colores');
            }
        });
    }
}
