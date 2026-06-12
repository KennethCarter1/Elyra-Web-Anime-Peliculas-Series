USE elyra;

DELIMITER $$

CREATE PROCEDURE sp_agregar_calificacion(
    IN p_id_usuario INT,
    IN p_id_pelicula INT,
    IN p_calificacion INT
)
BEGIN
    INSERT INTO calificaciones(id_usuario, id_pelicula, calificacion)
    VALUES (p_id_usuario, p_id_pelicula, p_calificacion);
END $$

CREATE PROCEDURE sp_editar_calificacion(
    IN p_id_calificacion INT,
    IN p_calificacion INT
)
BEGIN
    UPDATE calificaciones SET calificacion = p_calificacion
    WHERE id_calificacion = p_id_calificacion;
END $$

CREATE PROCEDURE sp_eliminar_calificacion(IN p_id_calificacion INT)
BEGIN
    DELETE FROM calificaciones WHERE id_calificacion = p_id_calificacion;
END $$

CREATE PROCEDURE sp_promedio_calificacion(IN p_id_pelicula INT)
BEGIN
    SELECT AVG(calificacion) AS promedio
    FROM calificaciones
    WHERE id_pelicula = p_id_pelicula;
END $$

DELIMITER ;