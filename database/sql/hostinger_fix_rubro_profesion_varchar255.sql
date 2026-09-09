-- =====================================================================
-- Ensancha `negocios.rubro` y `negocios.profesion` -- script para correr en
-- phpMyAdmin de Hostinger, sobre la base ya existente
-- (u374453216_micomercio).
-- Equivale a la migración:
--   2026_08_29_000001_widen_rubro_profesion_on_negocios_table
--
-- Ambas columnas (tabla hecha a mano) están más angostas de lo que el chat
-- puede llegar a escribir -- Gemini arma rubros descriptivos largos (ej.
-- "Panaderia Artensanal de Focaccias y Ciabata de masa madre", 57
-- caracteres) y ese mismo texto se copia también a `profesion`. MySQL en
-- modo estricto corta el insert entero con "Data too long for column
-- 'rubro'" en vez de truncar en silencio, lo que rompía la publicación de
-- un negocio desde el chat con un error 500 (hallazgo 2026-08-29 con el
-- negocio real "Rustika"). Se las lleva a VARCHAR(255), el tamaño que ya
-- documenta la migración de referencia del proyecto.
-- =====================================================================

ALTER TABLE negocios MODIFY COLUMN rubro VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE negocios MODIFY COLUMN profesion VARCHAR(255) NULL DEFAULT NULL;

-- -------- Verificación opcional --------
-- SHOW CREATE TABLE negocios;
