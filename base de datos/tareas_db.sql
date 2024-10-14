-- Eliminar la base de datos si existe
DROP DATABASE IF EXISTS tareas_db;

-- Crear la nueva base de datos
CREATE DATABASE tareas_db;

-- Usar la nueva base de datos
USE tareas_db;

-- Tabla para usuarios
CREATE TABLE usuarios (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    correo VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    nombre_usuario VARCHAR(50) NOT NULL UNIQUE, -- Campo para nombre de usuario
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla para tareas
CREATE TABLE tareas (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,  -- Auto-incremental para ser único
    descripcion TEXT NOT NULL,
    estado ENUM('pendiente', 'completada') DEFAULT 'pendiente',
    prioridad ENUM('baja', 'media', 'alta') DEFAULT 'baja',  -- Nueva columna para prioridad
    fecha_vencimiento DATE,  -- Nueva columna para la fecha de vencimiento
    favorito TINYINT(1) DEFAULT 0,  -- Nueva columna para marcar una tarea como favorita
    usuario_id INT(11) NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla para notas
CREATE TABLE notas (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,  -- ID de la nota
    contenido TEXT NOT NULL,  -- Contenido de la nota
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Fecha de creación
    usuario_id INT(11) NOT NULL,  -- ID del usuario que creó la nota
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla para categorías
CREATE TABLE categorias (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nombre ENUM('estudios', 'trabajo', 'hogar') DEFAULT 'estudios',
    usuario_id INT(11) NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla para asignar tareas a categorías
CREATE TABLE tarea_categoria (
    tarea_id INT(11) NOT NULL,
    categoria_id INT(11) NOT NULL,
    PRIMARY KEY (tarea_id, categoria_id),
    FOREIGN KEY (tarea_id) REFERENCES tareas(id) ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE
);


CREATE USER 'usuario'@'%' IDENTIFIED BY 'tu_contraseña';

GRANT ALL PRIVILEGES ON *.* TO 'usuario'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;



