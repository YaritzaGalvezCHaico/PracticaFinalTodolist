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

-- Tabla para tareas (sin referencia a categorías)  
CREATE TABLE tareas (  
    id INT(11),  
    descripcion TEXT NOT NULL,  
    estado ENUM('pendiente', 'completada') DEFAULT 'pendiente',  
    usuario_id INT(11) NOT NULL,  
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  
    PRIMARY KEY (id, usuario_id),  -- Clave primaria compuesta  
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE  
);




