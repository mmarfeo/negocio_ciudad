<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * `plantillas_propiedades` (tabla hecha a mano, ~65 columnas, ver
 * `2026_08_02_000002_add_columns_to_plantillas_propiedades_table`) tiene el
 * mismo problema que ya se encontró en `negocios` con `rubro`/`profesion`:
 * las columnas de texto libre se documentaron como `string()` sin límite
 * (VARCHAR 255), pero en la tabla real quedaron más angostas. Reventó por
 * primera vez con `body_subtitulo` (hallazgo 2026-08-29, negocio real
 * "Rustika" -- la descripción que arma Gemini para el header, "Rustika
 * ofrece las mejores focaccias y ciabattas de masa madre, hechas con la
 * pasion..." supera los 150/160 caracteres típicos de un VARCHAR angosto):
 * `SQLSTATE[22001]: Data too long for column 'body_subtitulo'`.
 *
 * En vez de ir arreglando columna por columna cada vez que el chat genera
 * un texto largo para un campo distinto (van a seguir aaareciendo, esta
 * tabla tiene decenas de campos de texto libre: `body_titulo*`,
 * `header_titulo_*`, `header_subtitulo_*`, `body_tarjeta_titulo_*`,
 * `footer_redes_*`, etc.), esta migración ensancha de una sola vez TODAS
 * las columnas VARCHAR de esta tabla que estén por debajo de 255 -- excepto
 * `color_fondo`/`color_texto`, que a propósito son VARCHAR(7) para guardar
 * un color hex (`#ffffff`) y no deben tocarse.
 *
 * Solo toca columnas que la introspección confirma como VARCHAR angosto
 * (nunca TEXT/JSON, que ya tienen capacidad de sobra) -- no hay riesgo de
 * truncar nada, achicar una columna, ni tocar una columna que no exista.
 *
 * Sin doctrine/dbal instalado, así que SQL directo en vez del `->change()`
 * fluido de Laravel.
 */
class WidenVarcharColumnsOnPlantillasPropiedadesTable extends Migration
{
    /** Únicas columnas VARCHAR angostas a propósito -- no ensanchar. */
    private const EXCLUIR = ['color_fondo', 'color_texto'];

    public function up()
    {
        if (! DB::getSchemaBuilder()->hasTable('plantillas_propiedades')) {
            return;
        }

        $columnas = DB::select("
            SELECT COLUMN_NAME FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'plantillas_propiedades'
              AND DATA_TYPE = 'varchar'
              AND CHARACTER_MAXIMUM_LENGTH < 255
        ");

        foreach ($columnas as $columna) {
            $nombre = $columna->COLUMN_NAME;

            if (in_array($nombre, self::EXCLUIR, true)) {
                continue;
            }

            DB::statement("ALTER TABLE plantillas_propiedades MODIFY COLUMN `{$nombre}` VARCHAR(255) NULL DEFAULT NULL");
        }
    }

    public function down()
    {
        // No se vuelve atrás a propósito: angostar de nuevo reintroduce
        // exactamente el bug que esto arregla.
    }
}
