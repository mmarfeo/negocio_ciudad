<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * `negocios.rubro` y `negocios.profesion` (tabla hecha a mano) están más
 * angostas de lo que el chat/formulario puede llegar a escribir -- Gemini
 * arma rubros descriptivos largos (ej. "Panaderia Artensanal de Focaccias y
 * Ciabata de masa madre", 57 caracteres) y `confirmar()` copia ese mismo
 * texto a `profesion` (ChatController.php linea 709: `'profesion' =>
 * $d['rubro'] ?? null`). MySQL en modo estricto corta el insert entero con
 * "Data too long for column 'rubro'" en vez de truncar en silencio, lo que
 * rompía la publicación desde el chat con un 500 (hallazgo 2026-08-29,
 * negocio real "Rustika"). Tercera sorpresa de esquema en esta tabla hecha a
 * mano (después de `updated_at` faltante y `celular` como `int(20)`).
 *
 * Se ensanchan ambas columnas a VARCHAR(255), que es lo que ya documenta
 * `2026_08_02_000001_create_negocios_table` (`$table->string(...)` sin
 * límite = 255) -- o sea, se las lleva al tamaño que el resto del código ya
 * asume que tienen.
 *
 * Sin doctrine/dbal instalado, así que SQL directo en vez del `->change()`
 * fluido de Laravel.
 */
class WidenRubroProfesionOnNegociosTable extends Migration
{
    public function up()
    {
        foreach (['rubro', 'profesion'] as $columna) {
            $info = DB::select("
                SELECT DATA_TYPE, CHARACTER_MAXIMUM_LENGTH FROM information_schema.COLUMNS
                WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'negocios' AND COLUMN_NAME = ?
            ", [$columna]);

            if (empty($info)) {
                continue; // la columna no existe por algún motivo raro -- no tocar nada
            }

            $tipo = strtolower($info[0]->DATA_TYPE);
            $largo = $info[0]->CHARACTER_MAXIMUM_LENGTH;

            // text/mediumtext/etc ya aguantan de sobra; solo hay que tocar
            // varchar angosto.
            if ($tipo === 'varchar' && $largo < 255) {
                DB::statement("ALTER TABLE negocios MODIFY COLUMN `{$columna}` VARCHAR(255) NULL DEFAULT NULL");
            }
        }
    }

    public function down()
    {
        // No se vuelve atrás a propósito: angostar de nuevo reintroduce
        // exactamente el bug que esto arregla.
    }
}
