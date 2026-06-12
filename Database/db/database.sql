-- Creación de la base de datos
CREATE DATABASE IF NOT EXISTS elyra;
USE elyra;

-- Tabla de usuarios 
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    correo VARCHAR(100) NOT NULL UNIQUE,
    contrasena_hash VARCHAR(255) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    rol ENUM('usuario','administrador') DEFAULT 'usuario',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de géneros
CREATE TABLE generos (
    id_genero INT AUTO_INCREMENT PRIMARY KEY,
    nombre_genero VARCHAR(50) NOT NULL
);

-- Tabla de películas/series
CREATE TABLE peliculas_series (
    id_pelicula INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT,
    id_genero INT,
    url_imagen VARCHAR(255),
    calificacion_promedio DECIMAL(2,1) DEFAULT 0,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_genero) REFERENCES generos(id_genero)
);

-- Tabla de calificaciones (sin comentarios)
CREATE TABLE calificaciones (
    id_calificacion INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_pelicula INT NOT NULL,
    calificacion INT NOT NULL CHECK (calificacion BETWEEN 1 AND 5),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_pelicula) REFERENCES peliculas_series(id_pelicula)
);

-- Tabla de historial de vistas
CREATE TABLE historial_vistas (
    id_historial INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_pelicula INT NOT NULL,
    fecha_vista DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_pelicula) REFERENCES peliculas_series(id_pelicula)
);

-- Tabla de preferencias del usuario
CREATE TABLE preferencias_usuario (
    id_preferencia INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_genero INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_genero) REFERENCES generos(id_genero)
);