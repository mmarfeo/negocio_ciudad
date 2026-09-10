<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fase 8, Bloque D: el chat de alta deja de publicar la página él mismo y
 * pasa a ser un formulario de intake -- junta los datos y queda una
 * "solicitud" que el desarrollador toma, arma la página y publica.
 *
 * Se reusa `onboarding_sesiones` (ya guarda `datos`/`productos`/`estado`)
 * en vez de una tabla nueva. Se le agregan:
 *  - `user_id`            quién pidió la página (el chat va detrás de 'auth')
 *  - `nombre_negocio`     denormalizado de `datos` para el listado del panel
 *  - `contacto`           idem (teléfono o email que dejó)
 *  - `plantilla_sugerida` la plantilla que eligió en el chat
 *  - `notas_dev`          notas de trabajo del desarrollador
 *
 * `estado` pasa de (en_progreso | completado) a:
 *   en_progreso -> enviada -> en_construccion -> publicada   (+ rechazada)
 * Las filas viejas con 'completado' se leen como 'publicada' (ver
 * OnboardingSesion::ESTADOS y el accessor).
 */
class AddPipelineFieldsToOnboardingSesionesTable extends Migration
{
    public function up()
    {
        Schema::table('onboarding_sesiones', function (Blueprint $table) {
            if (! Schema::hasColumn('onboarding_sesiones', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('token')->constrained('users')->nullOnDelete();
            }
            if (! Schema::hasColumn('onboarding_sesiones', 'nombre_negocio')) {
                $table->string('nombre_negocio')->nullable()->after('estado');
            }
            if (! Schema::hasColumn('onboarding_sesiones', 'contacto')) {
                $table->string('contacto')->nullable()->after('nombre_negocio');
            }
            if (! Schema::hasColumn('onboarding_sesiones', 'plantilla_sugerida')) {
                $table->unsignedInteger('plantilla_sugerida')->nullable()->after('contacto');
            }
            if (! Schema::hasColumn('onboarding_sesiones', 'notas_dev')) {
                $table->text('notas_dev')->nullable()->after('productos');
            }
        });
    }

    public function down()
    {
        Schema::table('onboarding_sesiones', function (Blueprint $table) {
            foreach (['nombre_negocio', 'contacto', 'plantilla_sugerida', 'notas_dev'] as $col) {
                if (Schema::hasColumn('onboarding_sesiones', $col)) {
                    $table->dropColumn($col);
                }
            }
            if (Schema::hasColumn('onboarding_sesiones', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
        });
    }
}
