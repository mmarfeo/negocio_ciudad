<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fase 2: catálogo de productos, independiente del negocio. Permite
 * responder "qué negocios venden X" en vez de depender del texto libre
 * de negocios.producto_servicio.
 */
class CreateProductosCatalogoTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('productos_catalogo')) {
            return;
        }

        Schema::create('productos_catalogo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->string('categoria')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('imagen')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('productos_catalogo');
    }
}
