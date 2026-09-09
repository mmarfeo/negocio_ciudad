-- =====================================================================
-- Marketplace con Mercado Pago Connect (plantilla "Tienda") -- script
-- para correr en phpMyAdmin de Hostinger, sobre la base ya existente
-- (u374453216_micomercio).
-- Equivale a las migraciones:
--   2026_08_26_000001_create_negocio_mercadopago_table
--   2026_08_26_000002_add_imagen_descripcion_stock_to_negocio_producto_table
--   2026_08_26_000003_create_pedidos_table
--   2026_08_26_000004_create_pedido_items_table
--
-- Requiere MySQL 8.0.29+ / MariaDB 10.5.2+ para "IF NOT EXISTS".
-- =====================================================================

CREATE TABLE IF NOT EXISTS negocio_mercadopago (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    negocio_id INT NOT NULL UNIQUE,
    mp_user_id VARCHAR(255) NULL,
    public_key TEXT NULL,
    access_token TEXT NULL,
    refresh_token TEXT NULL,
    token_expires_at TIMESTAMP NULL,
    conectado_en TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_negocio_mercadopago_negocio
        FOREIGN KEY (negocio_id) REFERENCES negocios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE negocio_producto
    ADD COLUMN IF NOT EXISTS imagen VARCHAR(255) NULL AFTER precio,
    ADD COLUMN IF NOT EXISTS descripcion TEXT NULL AFTER imagen,
    ADD COLUMN IF NOT EXISTS stock INT NULL AFTER descripcion;

CREATE TABLE IF NOT EXISTS pedidos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    negocio_id INT NOT NULL,
    nombre_cliente VARCHAR(255) NOT NULL,
    telefono_cliente VARCHAR(255) NOT NULL,
    email_cliente VARCHAR(255) NULL,
    tipo_entrega VARCHAR(255) NOT NULL,
    direccion_entrega VARCHAR(255) NULL,
    notas TEXT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    comision DECIMAL(10,2) NOT NULL DEFAULT 0,
    total DECIMAL(10,2) NOT NULL,
    estado VARCHAR(255) NOT NULL DEFAULT 'pendiente',
    mp_preference_id VARCHAR(255) NULL,
    mp_payment_id VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_pedidos_negocio_estado (negocio_id, estado),
    CONSTRAINT fk_pedidos_negocio
        FOREIGN KEY (negocio_id) REFERENCES negocios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS pedido_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pedido_id BIGINT UNSIGNED NOT NULL,
    producto_id BIGINT UNSIGNED NOT NULL,
    nombre_producto VARCHAR(255) NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    cantidad INT UNSIGNED NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_pedido_items_pedido
        FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    CONSTRAINT fk_pedido_items_producto
        FOREIGN KEY (producto_id) REFERENCES productos_catalogo(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------- Después de correr esto --------
-- 1. Agregar a .env: MERCADOPAGO_CLIENT_ID, MERCADOPAGO_CLIENT_SECRET,
--    MERCADOPAGO_WEBHOOK_SECRET (los tres salen de developers.mercadopago.com,
--    creando una "Aplicación" ahí).
-- 2. MERCADOPAGO_COMISION_PORCENTAJE=0.5 (o el valor que corresponda).

-- -------- Verificación opcional --------
-- SHOW CREATE TABLE negocio_mercadopago;
-- SHOW CREATE TABLE pedidos;
-- SHOW CREATE TABLE pedido_items;
-- SHOW CREATE TABLE negocio_producto;
