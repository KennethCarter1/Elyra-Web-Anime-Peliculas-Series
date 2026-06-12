USE elyra;

DELIMITER $$

CREATE PROCEDURE sp_agregar_genero(IN p_nombre_genero VARCHAR(50))
BEGIN
    INSERT INTO generos(nombre_genero) VALUES (p_nombre_genero);
END $$

CREATE PROCEDURE sp_editar_genero(IN p_id_genero INT, IN p_nombre_genero VARCHAR(50))
BEGIN
    UPDATE generos SET nombre_genero = p_nombre_genero WHERE id_genero = p_id_genero;
END $$

CREATE PROCEDURE sp_eliminar_genero(IN p_id_genero INT)
BEGIN
    DELETE FROM generos WHERE id_genero = p_id_genero;
END $$

CREATE PROCEDURE sp_listar_generos()
BEGIN
    SELECT * FROM generos;
END $$

DELIMITER ;