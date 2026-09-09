<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fase 3: progreso del chat de alta de negocio con Gemini. `token` viaja
 * en el navegador del usuario (sin login) para poder retomar la
 * conversación si cierra la página antes de terminar.
 */
class CreateOnboardingSesionesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('onboarding_sesiones')) {
            return;
        }

        Schema::create('onboarding_sesiones', function (Blueprint $table) {
            $table->id();
            $table->uuid('token')->unique();
            $table->string('paso')->default('nombre');
            $table->string('estado')->default('en_progreso'); // en_progreso | completado
            $table->json('datos')->nullable();
            $table->json('productos')->nullable();
            // Sin FK: negocios.id es int(11) con signo (ver migraciones de Fase 1/2),
            // esta columna solo se completa al confirmar y crear el negocio.
            $table->integer('negocio_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('onboarding_sesiones');
    }
}
