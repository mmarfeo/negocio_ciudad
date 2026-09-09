<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Distingue un admin de la plataforma (acceso al panel interno de
 * documentación/roadmap para socios) de un dueño de negocio común. No hay
 * paquete de roles instalado y no hace falta uno: hoy existe un solo rol
 * extra, alcanza con un booleano.
 */
class AddEsAdminToUsersTable extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('users', 'es_admin')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('es_admin')->default(false)->after('nombre_negocio');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('es_admin');
        });
    }
}
