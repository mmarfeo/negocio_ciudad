<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Pedidos de la plantilla "Tienda" (marketplace con Mercado Pago Connect).
 * `comision` queda guardada como monto informativo del 0,5% de la
 * plataforma (config('services.mercadopago.comision_porcentaje')) al
 * momento del pedido -- si el % cambia después, los pedidos viejos no
 * deben recalcularse solos.
 */
class CreatePedidosTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('pedidos')) {
            return;
        }

        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            // `negocios.id` es `int(11)` con signo -- mismo criterio que
            // negocio_producto/negocio_mercadopago, no foreignId().
            $table->integer('negocio_id');
            $table->string('nombre_cliente');
            $table->string('telefono_cliente');
            $table->string('email_cliente')->nullable();
            $table->string('tipo_entrega'); // 'retiro' | 'envio'
            $table->string('direccion_entrega')->nullable();
            $table->text('notas')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('comision', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            // 'pendiente' | 'pagado' | 'cancelado' | 'entregado'
            $table->string('estado')->default('pendiente');
            $table->string('mp_preference_id')->nullable();
            $table->string('mp_payment_id')->nullable();
            $table->timestamps();

            $table->foreign('negocio_id')->references('id')->on('negocios')->cascadeOnDelete();
            $table->index(['negocio_id', 'estado']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
}
