<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * La tabla `negocios` existe en producción (Hostinger) pero nunca tuvo una
 * migración propia en el repo -- se creó/editó directo por phpMyAdmin.
 * Esta migración documenta el esquema reconstruido a partir de lo que usan
 * el modelo App\Models\Product y las vistas (resources/views/*.blade.php),
 * para que un entorno nuevo (local o de test) se pueda levantar con
 * `php artisan migrate`. Para producción se generó un script SQL aparte
 * (ver database/sql/hostinger_fase1.sql) que NO borra ni recrea la tabla.
 */
class CreateNegociosTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('negocios')) {
            return;
        }

        Schema::create('negocios', function (Blueprint $table) {
            // La tabla real (Hostinger) tiene `id` int(11) CON signo, no
            // bigint/unsigned -- Laravel's increments() sería UNSIGNED,
            // así que se arma a mano para que matchee el tipo exacto y
            // cualquier FK futura hacia `negocios.id` sea válida.
            $table->integer('id', true, false);
            $table->unsignedInteger('plantilla_id')->nullable();
            $table->string('url')->nullable();
            $table->boolean('activo')->default(true);
            $table->string('nombre');
            $table->string('profesion')->nullable();
            $table->string('slug')->unique();
            $table->string('cuit')->nullable();
            $table->string('telefono')->nullable();
            $table->string('celular')->nullable();
            $table->string('horario')->nullable();
            $table->string('direccion')->nullable();
            $table->string('email')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('provincia')->nullable();
            $table->text('producto_servicio')->nullable();
            $table->string('rubro')->nullable();
            $table->string('zona')->nullable();
            $table->text('palabras_clave')->nullable();
            // Convención vieja de imágenes: img/{plantilla}/{dir_carpeta}/archivo
            $table->string('dir_carpeta')->nullable();
            // Logo chico usado en la tarjeta del index (independiente del logo de cada plantilla)
            $table->string('nav_logo')->nullable();
            // Dueño del negocio (fase de alta por chat / login) -- nullable, no rompe negocios sin usuario
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('negocios');
    }
}
