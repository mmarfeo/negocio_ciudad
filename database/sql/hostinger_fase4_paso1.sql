-- =====================================================================
-- Fase 4, Paso 1 (URLs por ciudad) — script para correr en phpMyAdmin de
-- Hostinger, sobre la base ya existente (u374453216_micomercio).
-- Equivale a las migraciones:
--   2026_08_12_183031_add_ciudad_slug_to_negocios_table
--   2026_08_12_183341_change_slug_unique_to_composite_on_negocios_table
--
-- Corré esto ANTES de subir el código nuevo (PHP/Blade) -- el código viejo
-- ignora la columna ciudad_slug sin problema, pero el código nuevo la
-- necesita para poder resolver /{ciudad}/{slug}.
--
-- Requiere MySQL 8.0.29+ / MariaDB 10.5.2+ para "IF NOT EXISTS"/"IF EXISTS".
-- Si tu hosting corre una versión más vieja y alguna línea tira error de
-- sintaxis, corré primero el diagnóstico de abajo, pegame el resultado y
-- te paso una versión ajustada.
-- =====================================================================

-- -------- Diagnóstico opcional (solo lectura) --------
-- SHOW CREATE TABLE negocios;
-- SHOW INDEX FROM negocios WHERE Column_name = 'slug';

-- ---------- 1) Agregar ciudad_slug ----------
ALTER TABLE negocios
    ADD COLUMN IF NOT EXISTS ciudad_slug VARCHAR(255) NULL AFTER ciudad;

ALTER TABLE negocios
    ADD INDEX IF NOT EXISTS negocios_ciudad_slug_index (ciudad_slug);

-- Backfill de los negocios que ya existen. Ojo: esto NO reproduce
-- Str::slug() de PHP al 100% (no saca tildes ni caracteres raros) -- alcanza
-- porque los negocios de producción hoy son de prueba y se pueden volver a
-- crear; los negocios nuevos que se den de alta desde acá en adelante ya
-- van a tener el ciudad_slug bien calculado por el código PHP
-- (Product::normalizarCiudadSlug).
UPDATE negocios
    SET ciudad_slug = LOWER(REPLACE(TRIM(ciudad), ' ', '-'))
    WHERE ciudad IS NOT NULL AND TRIM(ciudad) != '';

UPDATE negocios
    SET ciudad_slug = 'sin-ciudad'
    WHERE ciudad_slug IS NULL OR TRIM(ciudad_slug) = '';

-- ---------- 2) Resolver duplicados antes de poder exigir unicidad ----------
-- Diagnóstico opcional, para ver qué filas están duplicadas antes de tocarlas:
-- SELECT ciudad_slug, slug, COUNT(*) AS cantidad, GROUP_CONCAT(id) AS ids
-- FROM negocios GROUP BY ciudad_slug, slug HAVING COUNT(*) > 1;
--
-- El viejo UNIQUE(slug) global, en teoría, ya debería haber evitado que
-- existan slugs repetidos -- pero al intentar crear el índice compuesto de
-- abajo salió "Entrada duplicada 'ezeiza-'" (ciudad_slug=ezeiza, slug
-- vacío), así que en los hechos esa restricción no estaba realmente activa
-- en esta tabla (probablemente porque se armó a mano en phpMyAdmin). No se
-- borra ningún negocio acá: a los duplicados (todos menos el primero de
-- cada grupo, el más viejo) se les agrega su propio id al slug para que
-- dejen de chocar. Si preferís borrarlos en vez de renombrarlos porque son
-- basura de pruebas, avisame y te paso la versión con DELETE.
UPDATE negocios n
JOIN (
    SELECT id, ROW_NUMBER() OVER (PARTITION BY ciudad_slug, slug ORDER BY id) AS posicion
    FROM negocios
) dup ON dup.id = n.id
SET n.slug = CONCAT(IF(n.slug IS NULL OR n.slug = '', 'negocio', n.slug), '-', n.id)
WHERE dup.posicion > 1;

-- ---------- 3) Slug único por ciudad, no global ----------
-- Se intentan los dos nombres de índice más probables para el UNIQUE de
-- slug (uno es el que pondría Laravel por convención, el otro el que arma
-- phpMyAdmin al tildar "único" a mano) -- el que no exista simplemente no
-- hace nada, no tira error.
ALTER TABLE negocios DROP INDEX IF EXISTS negocios_slug_unique;
ALTER TABLE negocios DROP INDEX IF EXISTS slug;

ALTER TABLE negocios
    ADD UNIQUE INDEX IF NOT EXISTS negocios_ciudad_slug_slug_unique (ciudad_slug, slug);

-- -------- Verificación opcional, después de correr todo lo de arriba --------
-- SHOW INDEX FROM negocios WHERE Column_name IN ('slug', 'ciudad_slug');
-- SELECT id, nombre, ciudad, ciudad_slug, slug FROM negocios LIMIT 20;
