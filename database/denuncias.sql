-- Base de datos para Sistema de Denuncias Municipales
-- Desarrollado por: Milenka Segundo Arteaga
-- Año: 2025

CREATE DATABASE IF NOT EXISTS denuncias_municipio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE denuncias_municipio;

CREATE TABLE IF NOT EXISTS denuncias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    ubicacion VARCHAR(150) NOT NULL,
    estado VARCHAR(20) NOT NULL DEFAULT 'Pendiente',
    ciudadano VARCHAR(100) NOT NULL,
    telefono_ciudadano VARCHAR(15) DEFAULT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_titulo (titulo),
    INDEX idx_ciudadano (ciudadano),
    INDEX idx_ubicacion (ubicacion),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos de ejemplo
INSERT INTO denuncias (titulo, descripcion, ubicacion, estado, ciudadano, telefono_ciudadano, fecha_registro) VALUES
('Bache en calle principal', 'Un bache grande en la intersección de la avenida principal con la calle 5.', 'Calle Lora y Cordero 172', 'Pendiente', 'Juan Pérez', '987654321', '2024-10-10 10:00:00'),
('Recolección de basura retrasada', 'La recolección de basura en mi zona no se ha hecho desde hace una semana.', 'Calle Junin 1045', 'En proceso', 'María López', '987654322', '2024-10-11 11:00:00'),
('Árbol caído en parque', 'Un árbol se ha caído en el parque central y bloquea el paso.', 'Calle Balta 514', 'Resuelto', 'Pedro Sánchez', '987654323', '2024-10-12 12:00:00'),
('Luminaria rota', 'La farola de la esquina está rota y la calle está muy oscura.', 'Calle Arica 113', 'Pendiente', 'Ana Torres', '987654324', '2024-10-12 13:00:00'),
('Basura acumulada en parque', 'Hay basura acumulada cerca de los juegos infantiles en el parque San Martin.', 'Calle Leoncio Prado 172', 'En proceso', 'Carlos Gómez', '987654325', '2024-10-13 14:00:00');

