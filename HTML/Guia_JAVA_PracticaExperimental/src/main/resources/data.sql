-- Insertar categorías iniciales si no existen
INSERT INTO categorias (nombre, descripcion)
VALUES ('Electrónica', 'Dispositivos electrónicos, móviles, etc.')
ON CONFLICT (nombre) DO NOTHING;

INSERT INTO categorias (nombre, descripcion)
VALUES ('Ropa y Calzado', 'Prendas de vestir y calzado de moda.')
ON CONFLICT (nombre) DO NOTHING;

-- Insertar usuario administrador inicial (contraseña: admin123)
INSERT INTO users (nombre, email, password, role, created_at, updated_at)
VALUES ('Admin', 'admin@pfc.com', '$2a$12$KkQcObeY9G2bW88r1q9UOuVpxFpS7aX7Z/hJp/8G2Qx1qDkL2H0.O', 'ROLE_ADMIN', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
ON CONFLICT (email) DO NOTHING;
