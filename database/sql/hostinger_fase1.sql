-- =====================================================================
-- Fase 1 (plantillas dinámicas) — script para correr en phpMyAdmin de
-- Hostinger, sobre la base ya existente (u374453216_micomercio).
-- NO borra ni recrea nada: solo agrega columnas si faltan.
--
-- Requiere MySQL 8.0.29+ / MariaDB 10.5.2+ para "ADD COLUMN IF NOT EXISTS".
-- Si tu hosting corre una versión más vieja y alguna línea tira error de
-- sintaxis en "IF NOT EXISTS", corré primero el bloque de diagnóstico de
-- abajo, pegame el resultado y te paso una versión sin esa cláusula.
-- =====================================================================

-- -------- Diagnóstico opcional (solo lectura, no hace falta correrlo
-- -------- si el script de abajo termina sin errores) --------
-- SHOW CREATE TABLE negocios;
-- SHOW CREATE TABLE plantillas_propiedades;

-- ---------- negocios ----------
-- dir_carpeta y nav_logo casi seguro ya existen (el sitio los usa hoy);
-- se agregan igual por las dudas, no pisan nada si ya están.
ALTER TABLE negocios
    ADD COLUMN IF NOT EXISTS dir_carpeta VARCHAR(255) NULL AFTER palabras_clave,
    ADD COLUMN IF NOT EXISTS nav_logo VARCHAR(255) NULL AFTER dir_carpeta,
    ADD COLUMN IF NOT EXISTS user_id BIGINT UNSIGNED NULL AFTER nav_logo;

-- Índice para user_id (sin FK estricta a propósito: algunos hostings
-- restringen privilegios de FOREIGN KEY en cuentas compartidas).
ALTER TABLE negocios
    ADD INDEX IF NOT EXISTS negocios_user_id_index (user_id);

-- ---------- plantillas_propiedades ----------
-- Estas columnas casi seguro ya existen (las plantillas 02/03/04/05 las
-- usan en producción); el bloque queda como red de seguridad idempotente.
ALTER TABLE plantillas_propiedades
    ADD COLUMN IF NOT EXISTS plantilla_id INT UNSIGNED NULL AFTER id,
    ADD COLUMN IF NOT EXISTS negocio_id BIGINT UNSIGNED NULL AFTER plantilla_id,
    ADD COLUMN IF NOT EXISTS nav_logo VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS favicon_logo VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS header_img_1 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS header_titulo_1 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS header_subtitulo_1 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS header_img_2 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS header_titulo_2 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS header_subtitulo_2 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS header_img_3 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS header_titulo_3 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS header_subtitulo_3 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_titulo VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_titulo_2 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_titulo_3 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_titulo_4 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_parrafo_1 TEXT NULL,
    ADD COLUMN IF NOT EXISTS body_parrafo_2 TEXT NULL,
    ADD COLUMN IF NOT EXISTS body_parrafo_3 TEXT NULL,
    ADD COLUMN IF NOT EXISTS body_parrafo_4 TEXT NULL,
    ADD COLUMN IF NOT EXISTS body_subtitulo VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_img_1 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_img_2 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_img_3 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_img_4 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_img_5 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_img_6 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_img_7 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_img_8 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_img_9 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_img_10 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_titulo_1 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_parrafo_1 TEXT NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_precio_1 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_titulo_2 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_parrafo_2 TEXT NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_precio_2 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_titulo_3 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_parrafo_3 TEXT NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_precio_3 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_titulo_4 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_parrafo_4 TEXT NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_precio_4 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_titulo_5 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_parrafo_5 TEXT NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_precio_5 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_titulo_6 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_parrafo_6 TEXT NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_precio_6 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_titulo_7 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_parrafo_7 TEXT NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_precio_7 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_titulo_8 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_parrafo_8 TEXT NULL,
    ADD COLUMN IF NOT EXISTS body_tarjeta_precio_8 VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_titulo_nuestros_trabajos VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_tipos_medios_pago VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS body_maps TEXT NULL,
    ADD COLUMN IF NOT EXISTS footer_redes_facebook VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS footer_redes_instagram VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS footer_redes_youtube VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS footer_redes_twitter VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS footer_redes_linkedin VARCHAR(255) NULL;

-- ---------- registrar estas migraciones como "ya corridas" ----------
-- Como el cambio real en Hostinger se aplicó a mano con este script (no
-- con `php artisan migrate`), hay que decirle a Laravel que no las vuelva
-- a correr. Ajustá el número de "batch" al que corresponda en tu tabla
-- `migrations` (podés ver el máximo actual con: SELECT MAX(batch) FROM migrations;)
-- INSERT INTO migrations (migration, batch) VALUES
--   ('2026_08_02_000001_create_negocios_table', <batch>),
--   ('2026_08_02_000002_add_columns_to_plantillas_propiedades_table', <batch>);
