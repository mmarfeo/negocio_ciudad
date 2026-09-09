<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * `negocios.celular` estaba creada como `int(20)` (tabla hecha a mano) --
 * un tipo numérico no puede guardar un teléfono real con "+", espacios o
 * guión (ej. "+54 9 11 5938-4279"), y MySQL tira "Data truncated for
 * column 'celular'" al intentarlo, lo que rompía la publicación desde el
 * chat con un 500 (hallazgo 2026-08-28, reportado por el dueño del
 * proyecto con el negocio real "Rustika"). `telefono` ya era `varchar(20)`
 * -- se deja `celular` igual, mismo tipo y largo.
 *
 * Sin doctrine/dbal instalado (confirmado: no está en composer.lock), así
 * que no se puede usar el `->change()` fluido de Laravel -- SQL directo.
 */
class ChangeCelularTypeOnNegociosTable extends Migration
{
    public function up()
    {
        $columna = DB::select("
            SELECT DATA_TYPE FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'negocios' AND COLUMN_NAME = 'celular'
        ");

        if (empty($columna) || strtolower($columna[0]->DATA_TYPE) === 'varchar') {
            return; // ya está bien (o la columna no existe por algún motivo raro) -- no tocar nada
        }

        DB::statement("ALTER TABLE negocios MODIFY COLUMN celular VARCHAR(20) NULL DEFAULT NULL");
    }

    public function down()
    {
        // No se vuelve atrás a propósito: volver a int(20) reintroduce
        // exactamente el bug que esto arregla en cuanto alguien cargue un
        // celular con "+"/espacios/guión.
    }
}
