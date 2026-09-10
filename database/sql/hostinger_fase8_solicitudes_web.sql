-- =====================================================================
-- Fase 8, Bloque D: convierte el chat de alta en un formulario de intake.
-- Script para correr en phpMyAdmin de Hostinger, sobre la base existente.
-- Equivale a la migración:
--   2026_09_10_000001_add_pipeline_fields_to_onboarding_sesiones_table
--
-- El chat deja de publicar la página. Cada conversación queda como una
-- "solicitud" que el desarrollador toma, arma y publica. Se reusa la tabla
-- `onboarding_sesiones` (ya guarda datos/productos/estado); se le agregan
-- columnas para el pipeline y el panel del desarrollador.
--
-- `estado`:  en_progreso -> enviada -> en_construccion -> publicada
--            (+ rechazada). Las filas viejas con 'completado' se muestran
--            como 'publicada'.
-- =====================================================================

ALTER TABLE onboarding_sesiones
  ADD COLUMN IF NOT EXISTS user_id            BIGINT UNSIGNED NULL AFTER token,
  ADD COLUMN IF NOT EXISTS nombre_negocio     VARCHAR(255)    NULL AFTER estado,
  ADD COLUMN IF NOT EXISTS contacto           VARCHAR(255)    NULL AFTER nombre_negocio,
  ADD COLUMN IF NOT EXISTS plantilla_sugerida INT UNSIGNED    NULL AFTER contacto,
  ADD COLUMN IF NOT EXISTS notas_dev          TEXT            NULL AFTER productos;

-- FK a users (opcional; el chat va detrás de login así que casi siempre hay user).
-- Si la FK ya existe, MySQL tira error 1826/1022 y se puede ignorar.
ALTER TABLE onboarding_sesiones
  ADD CONSTRAINT onboarding_sesiones_user_id_foreign
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL;

-- -------- Verificación opcional --------
-- SHOW CREATE TABLE onboarding_sesiones;
