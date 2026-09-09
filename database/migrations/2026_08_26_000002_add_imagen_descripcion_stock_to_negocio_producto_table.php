<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * El catálogo de productos (`productos_catalogo`) es compartido entre
 * negocios a propósito (Fase 2, búsqueda cruzada "qué negocios venden X")
 * -- nombre/slug/categoría son globales. Pero para la plantilla "Tienda"
 * cada negocio necesita su propia foto, descripción y stock del mismo
 * producto, igual que `precio`/`disponible` ya son por-negocio en este
 * pivot. No hace falta una tabla nueva de productos, se extiende esta.
 */
class AddImagenDescripcionStockToNegocioProductoTable extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('negocio_producto', 'imagen')) {
            return;
        }

        Schema::table('negocio_producto', function (Blueprint $table) {
            $table->string('imagen')->nullable()->after('precio');
            $table->text('descripcion')->nullable()->after('imagen');
            // null = sin control de stock (mismo criterio que Cookie Boss,
            // que ni siquiera lo intenta y deriva a "consultar por WhatsApp");
            // un número acá sí se descuenta al confirmarse un pedido pagado.
            $table->integer('stock')->nullable()->after('descripcion');
        });
    }

    public function down()
    {
        Schema::table('negocio_producto', function (Blueprint $table) {
            $table->dropColumn(['imagen', 'descripcion', 'stock']);
        });
    }
}
