<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Campos extra para que un dueño de negocio se registre y pueda crear/editar
 * sus propias páginas (login, Fase 3.5).
 */
class AddRegistroFieldsToUsersTable extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('users', 'dni')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('dni', 15)->unique()->after('name');
            $table->string('apellido')->after('name');
            $table->string('telefono', 30)->after('email');
            $table->string('nombre_negocio')->nullable()->after('telefono');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['dni', 'apellido', 'telefono', 'nombre_negocio']);
        });
    }
}
