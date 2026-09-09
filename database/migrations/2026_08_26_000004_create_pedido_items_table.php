<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Items de un pedido, con nombre/precio "congelados" al momento de la
 * compra (no se leen del catálogo después) -- si el negocio cambia el
 * precio de un producto más tarde, los pedidos ya hechos no deben moverse.
 * `pedidos` y `productos_catalogo` son tablas 100% nuevas/Laravel-nativas,
 * así que acá sí corresponde `foreignId()` (a diferencia de negocio_id).
 */
class CreatePedidoItemsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('pedido_items')) {
            return;
        }

        Schema::create('pedido_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos_catalogo');
            $table->string('nombre_producto');
            $table->decimal('precio_unitario', 10, 2);
            $table->unsignedInteger('cantidad');
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedido_items');
    }
}
