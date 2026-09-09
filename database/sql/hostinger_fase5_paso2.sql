-- =====================================================================
-- Fase 5, Paso 2 (colores por sección) — script para correr en phpMyAdmin
-- de Hostinger, sobre la base ya existente (u374453216_micomercio).
-- Equivale a la migración:
--   2026_08_13_162433_add_colores_json_to_plantillas_propiedades_table
--
-- No toca ni borra las columnas viejas `color_fondo`/`color_texto` -- los
-- negocios que ya eligieron un color con el sistema anterior lo siguen
-- viendo igual (propiedades_plantillas::estiloPersonalizado() cae a esos
-- campos si la columna nueva `colores` viene vacía).
--
-- Requiere MySQL 8.0.29+ / MariaDB 10.5.2+ para "IF NOT EXISTS".
-- =====================================================================

ALTER TABLE plantillas_propiedades
    ADD COLUMN IF NOT EXISTS colores JSON NULL AFTER color_texto;

-- -------- Verificación opcional --------
-- SHOW CREATE TABLE plantillas_propiedades;
