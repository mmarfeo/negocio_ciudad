<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Credenciales de Mercado Pago Connect (OAuth) por negocio -- marketplace:
 * cada negocio conecta su propia cuenta y el cobro le llega directo a él,
 * no a una cuenta central de la plataforma. `access_token`/`refresh_token`
 * se guardan cifrados (ver App\Models\NegocioMercadopago), Laravel 8 no
 * tiene el cast `encrypted` nativo (llegó en Laravel 9).
 */
class CreateNegocioMercadopagoTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('negocio_mercadopago')) {
            return;
        }

        Schema::create('negocio_mercadopago', function (Blueprint $table) {
            $table->id();
            // `negocios.id` es `int(11)` con signo (tabla creada a mano) --
            // no usar foreignId()/bigint acá, mismo criterio que negocio_producto.
            $table->integer('negocio_id')->unique();
            $table->string('mp_user_id')->nullable();
            $table->text('public_key')->nullable();
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->timestamp('conectado_en')->nullable();
            $table->timestamps();

            $table->foreign('negocio_id')->references('id')->on('negocios')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('negocio_mercadopago');
    }
}
