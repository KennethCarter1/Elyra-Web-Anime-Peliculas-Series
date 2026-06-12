USE elyra;

DELIMITER $$

CREATE PROCEDURE sp_agregar_historial(
    IN p_id_usuario INT,
    IN p_id_pelicula INT
)
BEGIN
    INSERT INTO historial_vistas(id_usuario, id_pelicula)
    VALUES (p_id_usuario, p_id_pelicula);
END $$

CREATE PROCEDURE sp_listar_historial(IN p_id_usuario INT)
BEGIN
    SELECT h.*, p.titulo
    FROM historial_vistas h
    JOIN peliculas_series p ON h.id_pelicula = p.id_pelicula
    WHERE h.id_usuario = p_id_usuario;
END $$

DELIMITER ;