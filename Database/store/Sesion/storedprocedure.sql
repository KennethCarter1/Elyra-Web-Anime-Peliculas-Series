-- Seleccionar la base de datos
USE elyra;

-- Procedimiento almacenado para registrar usuarios
DELIMITER $$

CREATE PROCEDURE sp_registrar_usuario(
    IN p_nombre VARCHAR(100),
    IN p_usuario VARCHAR(50),
    IN p_correo VARCHAR(100),
    IN p_contrasena VARCHAR(255),
    IN p_fecha_nacimiento DATE
)
BEGIN
    DECLARE v_existente INT DEFAULT 0;

    -- Verificar si el usuario o correo ya existe
    SELECT COUNT(*) INTO v_existente 
    FROM usuarios 
    WHERE usuario = p_usuario OR correo = p_correo;

    IF v_existente > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El usuario o correo ya está registrado';
    ELSE
        INSERT INTO usuarios(nombre, usuario, correo, contrasena_hash, fecha_nacimiento, rol)
        VALUES (p_nombre, p_usuario, p_correo, p_contrasena, p_fecha_nacimiento, 'usuario');
    END IF;
END $$

DELIMITER ;

-- Procedimiento almacenado para login de usuarios
DELIMITER $$

CREATE PROCEDURE sp_login_usuario(
    IN p_usuario VARCHAR(50),
    IN p_rol ENUM('usuario','administrador')
)
BEGIN
    SELECT id_usuario, contrasena_hash
    FROM usuarios
    WHERE usuario = p_usuario
      AND rol = p_rol;
END $$

DELIMITER ;