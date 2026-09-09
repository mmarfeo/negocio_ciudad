<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fase 2: tabla pivote negocio <-> producto_catalogo. Un mismo producto
 * del catálogo puede estar vinculado a varios negocios, cada uno con su
 * propio precio/disponibilidad.
 */
class CreateNegocioProductoTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('negocio_producto')) {
            return;
        }

        Schema::create('negocio_producto', function (Blueprint $table) {
            $table->id();
            // `negocios.id` es `int(11)` CON signo (tabla creada a mano,
            // no con Laravel) -- para que la FK sea válida esta columna
            // tiene que matchear ese tipo exacto, no bigint/unsigned.
            $table->integer('negocio_id');
            $table->foreignId('producto_id')->constrained('productos_catalogo')->cascadeOnDelete();
            $table->decimal('precio', 10, 2)->nullable();
            $table->boolean('disponible')->default(true);
            $table->timestamps();

            $table->foreign('negocio_id')->references('id')->on('negocios')->cascadeOnDelete();
            $table->unique(['negocio_id', 'producto_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('negocio_producto');
    }
}
