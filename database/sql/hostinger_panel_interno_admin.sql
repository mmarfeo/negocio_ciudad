-- =====================================================================
-- Panel interno para socios (solo admin) — script para correr en
-- phpMyAdmin de Hostinger, sobre la base ya existente
-- (u374453216_micomercio).
-- Equivale a la migración:
--   2026_08_17_000001_add_es_admin_to_users_table
--
-- Agrega el booleano que distingue un admin de la plataforma (acceso al
-- panel interno) de un dueño de negocio común. Default false: nadie queda
-- admin por accidente.
--
-- Requiere MySQL 8.0.29+ / MariaDB 10.5.2+ para "IF NOT EXISTS".
-- =====================================================================

ALTER TABLE users
    ADD COLUMN IF NOT EXISTS es_admin TINYINT(1) NOT NULL DEFAULT 0 AFTER nombre_negocio;

-- -------- Después de correr esto, dar de alta al primer admin --------
-- UPDATE users SET es_admin = 1 WHERE email = 'tu-email@ejemplo.com';

-- -------- Verificación opcional --------
-- SHOW CREATE TABLE users;
