-- =====================================================================
-- Arregla el tipo de `negocios.celular` -- script para correr en
-- phpMyAdmin de Hostinger, sobre la base ya existente
-- (u374453216_micomercio).
-- Equivale a la migración:
--   2026_08_28_000001_change_celular_type_on_negocios_table
--
-- `celular` estaba creada como `int(20)` (tabla hecha a mano) -- un tipo
-- numérico no puede guardar un teléfono real con "+", espacios o guión
-- (ej. "+54 9 11 5938-4279"), y eso rompía la publicación de un negocio
-- desde el chat con un error 500 ("Data truncated for column 'celular'",
-- hallazgo 2026-08-28 con el negocio real "Rustika"). `telefono` ya era
-- `varchar(20)` -- se deja `celular` con el mismo tipo y largo.
-- =====================================================================

ALTER TABLE negocios MODIFY COLUMN celular VARCHAR(20) NULL DEFAULT NULL;

-- -------- Verificación opcional --------
-- SHOW CREATE TABLE negocios;
