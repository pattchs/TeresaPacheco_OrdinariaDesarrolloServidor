-- ============================================================
-- NavaPark2 - Script de creación de base de datos
-- ============================================================

CREATE DATABASE IF NOT EXISTS navapark2 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE navapark2;

-- Tabla de usuarios (clientes y admins)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    rol ENUM('cliente', 'admin') NOT NULL DEFAULT 'cliente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de atracciones
CREATE TABLE atracciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    tematica VARCHAR(100) NOT NULL,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de viajes (relación entre usuarios y atracciones)
CREATE TABLE viajes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    atraccion_id INT NOT NULL,
    hora DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (atraccion_id) REFERENCES atracciones(id) ON DELETE CASCADE
);

-- ============================================================
-- Datos de ejemplo
-- ============================================================

-- Admin por defecto (password: password)
INSERT INTO usuarios (nombre, email, password, fecha_nacimiento, rol) VALUES
('Admin NavaPark', 'admin@navapark2.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1985-06-15', 'admin');

-- Atracciones de ejemplo
INSERT INTO atracciones (nombre, tematica, descripcion) VALUES
('La Montaña Rusa del Gredos', 'Aventura', 'La montaña rusa más alta de la sierra, con vistas espectaculares a la Nava.'),
('El Tiovivo Medieval', 'Historia', 'Un clásico tiovivo ambientado en la época medieval de Navarredonda.'),
('Rafting del Río Tormes', 'Naturaleza', 'Emocionante descenso de aguas bravas por el río Tormes.'),
('La Casa del Terror de la Sierra', 'Halloween', 'Casa de los horrores ambientada en leyendas de la sierra de Gredos.'),
('Karting del Valle', 'Velocidad', 'Circuito de karting con coches eléctricos para todas las edades.');

-- Viajes de ejemplo (necesitas crear usuarios primero vía registro)
