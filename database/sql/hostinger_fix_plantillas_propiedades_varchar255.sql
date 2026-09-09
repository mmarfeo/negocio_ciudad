-- =====================================================================
-- Ensancha a VARCHAR(255) TODAS las columnas de texto libre de
-- `plantillas_propiedades` que estén más angostas -- script para correr en
-- phpMyAdmin de Hostinger, sobre la base ya existente
-- (u374453216_micomercio).
-- Equivale a la migración:
--   2026_08_29_000002_widen_varchar_columns_on_plantillas_propiedades_table
--
-- Reventó por primera vez con `body_subtitulo` (hallazgo 2026-08-29,
-- negocio real "Rustika"): "Data too long for column 'body_subtitulo'" al
-- guardar la descripción larga que arma Gemini para el header. Esta tabla
-- (hecha a mano, ~65 columnas) tiene decenas de campos de texto libre con
-- el mismo problema de fondo (documentados como VARCHAR sin límite = 255,
-- pero creados más angostos en producción) -- en vez de ir arreglando uno
-- por uno cada vez que el chat genera un texto largo para un campo
-- distinto, se ensanchan todos de una vez acá.
--
-- NO toca `color_fondo`/`color_texto` (VARCHAR(7) a propósito, guardan un
-- color hex tipo #ffffff) ni ninguna columna TEXT/JSON (`body_parrafo_*`,
-- `body_tarjeta_parrafo_*`, `body_maps`, `colores`), que ya tienen
-- capacidad de sobra.
--
-- Si alguna columna de esta lista no existe en tu base (no debería, todas
-- vienen de `hostinger_fase1.sql`), esa línea puntual va a tirar error
-- "Unknown column" -- se puede ignorar esa línea y seguir con el resto.
-- =====================================================================

ALTER TABLE plantillas_propiedades MODIFY COLUMN nav_logo VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN favicon_logo VARCHAR(255) NULL DEFAULT NULL;

ALTER TABLE plantillas_propiedades MODIFY COLUMN header_img_1 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN header_titulo_1 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN header_subtitulo_1 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN header_img_2 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN header_titulo_2 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN header_subtitulo_2 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN header_img_3 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN header_titulo_3 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN header_subtitulo_3 VARCHAR(255) NULL DEFAULT NULL;

ALTER TABLE plantillas_propiedades MODIFY COLUMN body_titulo VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_titulo_2 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_titulo_3 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_titulo_4 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_subtitulo VARCHAR(255) NULL DEFAULT NULL;

ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tarjeta_img_1 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tarjeta_img_2 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tarjeta_img_3 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tarjeta_img_4 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tarjeta_img_5 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tarjeta_img_6 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tarjeta_img_7 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tarjeta_img_8 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tarjeta_img_9 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tarjeta_img_10 VARCHAR(255) NULL DEFAULT NULL;

ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tarjeta_titulo_1 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tarjeta_titulo_2 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tarjeta_titulo_3 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tarjeta_titulo_4 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tarjeta_titulo_5 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tarjeta_titulo_6 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tarjeta_titulo_7 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tarjeta_titulo_8 VARCHAR(255) NULL DEFAULT NULL;

ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tarjeta_precio_1 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tarjeta_precio_2 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tarjeta_precio_3 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tarjeta_precio_4 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tarjeta_precio_5 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tarjeta_precio_6 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tarjeta_precio_7 VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tarjeta_precio_8 VARCHAR(255) NULL DEFAULT NULL;

ALTER TABLE plantillas_propiedades MODIFY COLUMN body_titulo_nuestros_trabajos VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN body_tipos_medios_pago VARCHAR(255) NULL DEFAULT NULL;

ALTER TABLE plantillas_propiedades MODIFY COLUMN footer_redes_facebook VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN footer_redes_instagram VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN footer_redes_youtube VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN footer_redes_twitter VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE plantillas_propiedades MODIFY COLUMN footer_redes_linkedin VARCHAR(255) NULL DEFAULT NULL;

-- -------- Verificación opcional --------
-- SHOW CREATE TABLE plantillas_propiedades;
