USE elyra;

DELIMITER $$

CREATE PROCEDURE sp_agregar_pelicula(
    IN p_titulo VARCHAR(150),
    IN p_descripcion TEXT,
    IN p_id_genero INT,
    IN p_url_imagen VARCHAR(255)
)
BEGIN
    INSERT INTO peliculas_series(titulo, descripcion, id_genero, url_imagen)
    VALUES (p_titulo, p_descripcion, p_id_genero, p_url_imagen);
END $$

CREATE PROCEDURE sp_editar_pelicula(
    IN p_id_pelicula INT,
    IN p_titulo VARCHAR(150),
    IN p_descripcion TEXT,
    IN p_id_genero INT,
    IN p_url_imagen VARCHAR(255)
)
BEGIN
    UPDATE peliculas_series
    SET titulo = p_titulo,
        descripcion = p_descripcion,
        id_genero = p_id_genero,
        url_imagen = p_url_imagen
    WHERE id_pelicula = p_id_pelicula;
END $$

CREATE PROCEDURE sp_eliminar_pelicula(IN p_id_pelicula INT)
BEGIN
    DELETE FROM peliculas_series WHERE id_pelicula = p_id_pelicula;
END $$

CREATE PROCEDURE sp_listar_peliculas()
BEGIN
    SELECT * FROM peliculas_series;
END $$

DELIMITER ;