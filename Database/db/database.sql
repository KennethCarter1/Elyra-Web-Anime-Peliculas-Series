-- Creación de la base de datos
DROP DATABASE IF EXISTS elyra;
CREATE DATABASE elyra;
USE elyra;

-- Tabla de usuarios 
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    usuario VARCHAR(50) NOT NULL UNIQUE,
    correo VARCHAR(100) NOT NULL UNIQUE,
    contrasena_hash VARCHAR(255) NOT NULL,
    fecha_nacimiento VARCHAR(100) NOT NULL, 
    genero VARCHAR(50) DEFAULT NULL,
    rol ENUM('usuario','administrador') DEFAULT 'usuario',
    activo TINYINT(1) DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de géneros
CREATE TABLE generos (
    id_genero INT AUTO_INCREMENT PRIMARY KEY,
    nombre_genero VARCHAR(50) NOT NULL UNIQUE,
    activo TINYINT(1) DEFAULT 1
);

-- Tabla de preferencias del usuario
CREATE TABLE preferencias_usuario (
    id_preferencia INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_genero INT NOT NULL,
    UNIQUE (id_usuario, id_genero),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_genero) REFERENCES generos(id_genero) ON DELETE CASCADE
);

-- Tabla de películas y series
CREATE TABLE peliculas_series (
    id_pelicula_serie INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    titulo_original VARCHAR(150),
    descripcion TEXT,
    tipo VARCHAR(30) NOT NULL,
    estado VARCHAR(50) DEFAULT 'Publicado',
    estado_emision VARCHAR(30) DEFAULT 'Finalizado',
    anio_lanzamiento INT,
    fecha_estreno DATE,
    duracion_minutos INT,
    temporadas INT,
    episodios INT,
    serie_padre_id INT DEFAULT NULL,
    numero_temporada INT DEFAULT NULL,
    tipo_relacion VARCHAR(20) DEFAULT NULL,
    imagen_portada VARCHAR(255),
    imagen_banner VARCHAR(255),
    trailer_url VARCHAR(255),
    destacado TINYINT(1) DEFAULT 0,
    activo TINYINT(1) DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla puente entre películas/series y géneros
CREATE TABLE peliculas_series_generos (
    id_pelicula_serie_genero INT AUTO_INCREMENT PRIMARY KEY,
    id_pelicula_serie INT NOT NULL,
    id_genero INT NOT NULL,
    UNIQUE (id_pelicula_serie, id_genero),
    FOREIGN KEY (id_pelicula_serie) REFERENCES peliculas_series(id_pelicula_serie) ON DELETE CASCADE,
    FOREIGN KEY (id_genero) REFERENCES generos(id_genero) ON DELETE CASCADE
);

ALTER TABLE peliculas_series
ADD CONSTRAINT fk_serie_padre
FOREIGN KEY (serie_padre_id) REFERENCES peliculas_series(id_pelicula_serie)
ON DELETE SET NULL ON UPDATE CASCADE;

-- Tabla de favoritos de usuarios
CREATE TABLE favoritos_usuario (
    id_favorito INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_pelicula_serie INT NOT NULL,
    fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (id_usuario, id_pelicula_serie),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_pelicula_serie) REFERENCES peliculas_series(id_pelicula_serie) ON DELETE CASCADE
);
