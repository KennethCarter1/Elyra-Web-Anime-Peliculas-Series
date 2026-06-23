USE elyra;

DELIMITER $$

DROP PROCEDURE IF EXISTS sp_login_usuario $$

CREATE PROCEDURE sp_login_usuario(
    IN p_usuario VARCHAR(100)
)
BEGIN
    SELECT id_usuario, usuario, correo, contrasena_hash, rol, activo
    FROM usuarios
    WHERE usuario = p_usuario OR correo = p_usuario
    LIMIT 1;
END $$

DROP PROCEDURE IF EXISTS sp_registrar_usuario $$

CREATE PROCEDURE sp_registrar_usuario(
    IN p_usuario VARCHAR(50),
    IN p_correo VARCHAR(100),
    IN p_contrasena VARCHAR(255),
    IN p_fecha_nacimiento VARCHAR(100)
)
BEGIN
    IF EXISTS (
        SELECT 1
        FROM usuarios
        WHERE usuario = p_usuario OR correo = p_correo
    ) THEN
        SELECT 0 AS id_usuario;
    ELSE
        INSERT INTO usuarios(usuario, correo, contrasena_hash, fecha_nacimiento)
        VALUES (p_usuario, p_correo, p_contrasena, p_fecha_nacimiento);

        SELECT LAST_INSERT_ID() AS id_usuario;
    END IF;
END $$

DROP PROCEDURE IF EXISTS sp_obtener_usuario_por_usuario $$

CREATE PROCEDURE sp_obtener_usuario_por_usuario(
    IN p_usuario VARCHAR(50)
)
BEGIN
    SELECT nombre, usuario, correo, fecha_nacimiento, genero, rol, activo, fecha_creacion
    FROM usuarios
    WHERE usuario = p_usuario
    AND activo = 1
    LIMIT 1;
END $$

DROP PROCEDURE IF EXISTS sp_actualizar_usuario $$

CREATE PROCEDURE sp_actualizar_usuario(
    IN p_usuario_actual VARCHAR(50),
    IN p_nombre VARCHAR(100),
    IN p_usuario VARCHAR(50),
    IN p_correo VARCHAR(100),
    IN p_fecha_nacimiento VARCHAR(100),
    IN p_genero VARCHAR(50)
)
BEGIN
    DECLARE v_id_usuario INT DEFAULT 0;

    SELECT COALESCE((
        SELECT id_usuario
        FROM usuarios
        WHERE usuario = p_usuario_actual
        LIMIT 1
    ), 0) INTO v_id_usuario;

    IF v_id_usuario = 0 THEN
        SELECT 0 AS exito, 'Usuario no encontrado' AS mensaje, p_usuario_actual AS usuario;
    ELSEIF EXISTS (
        SELECT 1
        FROM usuarios
        WHERE usuario = p_usuario
        AND id_usuario <> v_id_usuario
    ) THEN
        SELECT 0 AS exito, 'El nombre de usuario ya existe' AS mensaje, p_usuario_actual AS usuario;
    ELSEIF EXISTS (
        SELECT 1
        FROM usuarios
        WHERE correo = p_correo
        AND id_usuario <> v_id_usuario
    ) THEN
        SELECT 0 AS exito, 'El correo electrónico ya existe' AS mensaje, p_usuario_actual AS usuario;
    ELSEIF p_genero NOT IN ('masculino', 'femenino', 'otaku') THEN
        SELECT 0 AS exito, 'El género no es válido' AS mensaje, p_usuario_actual AS usuario;
    ELSE
        UPDATE usuarios
        SET nombre = p_nombre,
            usuario = p_usuario,
            correo = p_correo,
            fecha_nacimiento = p_fecha_nacimiento,
            genero = p_genero
        WHERE id_usuario = v_id_usuario;

        SELECT 1 AS exito, 'Usuario actualizado correctamente' AS mensaje, p_usuario AS usuario;
    END IF;
END $$

DROP PROCEDURE IF EXISTS sp_cambiar_contrasena_usuario $$

CREATE PROCEDURE sp_cambiar_contrasena_usuario(
    IN p_usuario VARCHAR(50),
    IN p_contrasena_hash VARCHAR(255)
)
BEGIN
    DECLARE v_id_usuario INT DEFAULT 0;

    SELECT COALESCE((
        SELECT id_usuario
        FROM usuarios
        WHERE usuario = p_usuario
        LIMIT 1
    ), 0) INTO v_id_usuario;

    IF v_id_usuario = 0 THEN
        SELECT 0 AS exito, 'Usuario no encontrado' AS mensaje;
    ELSE
        UPDATE usuarios
        SET contrasena_hash = p_contrasena_hash
        WHERE id_usuario = v_id_usuario;

        SELECT 1 AS exito, 'Contraseña actualizada correctamente' AS mensaje;
    END IF;
END $$

DROP PROCEDURE IF EXISTS sp_listar_generos $$

CREATE PROCEDURE sp_listar_generos()
BEGIN
    SELECT id_genero, nombre_genero, activo
    FROM generos
    WHERE activo = 1
    ORDER BY nombre_genero;
END $$

DROP PROCEDURE IF EXISTS sp_listar_generos_gestion $$

CREATE PROCEDURE sp_listar_generos_gestion()
BEGIN
    SELECT
        g.id_genero,
        g.nombre_genero,
        g.activo,
        (
            SELECT COUNT(*)
            FROM peliculas_series_generos psg
            WHERE psg.id_genero = g.id_genero
        ) AS total_contenido,
        (
            SELECT COUNT(*)
            FROM preferencias_usuario pu
            WHERE pu.id_genero = g.id_genero
        ) AS total_preferencias
    FROM generos g
    ORDER BY g.activo DESC, g.nombre_genero;
END $$

DROP PROCEDURE IF EXISTS sp_crear_genero $$

CREATE PROCEDURE sp_crear_genero(
    IN p_nombre_genero VARCHAR(50)
)
BEGIN
    DECLARE v_nombre_genero VARCHAR(50) DEFAULT '';
    DECLARE v_id_genero INT DEFAULT 0;
    DECLARE v_activo TINYINT DEFAULT 1;

    SET v_nombre_genero = TRIM(p_nombre_genero);

    IF v_nombre_genero = '' THEN
        SELECT 0 AS exito, 'Ingresa el nombre del género' AS mensaje, 0 AS id_genero;
    ELSE
        SELECT COALESCE((
            SELECT id_genero
            FROM generos
            WHERE LOWER(nombre_genero) = LOWER(v_nombre_genero)
            LIMIT 1
        ), 0) INTO v_id_genero;

        IF v_id_genero > 0 THEN
            SELECT activo INTO v_activo
            FROM generos
            WHERE id_genero = v_id_genero;

            IF v_activo = 0 THEN
                UPDATE generos
                SET activo = 1
                WHERE id_genero = v_id_genero;

                SELECT 1 AS exito, 'El género existía y fue activado nuevamente' AS mensaje, v_id_genero AS id_genero;
            ELSE
                SELECT 0 AS exito, 'Ese género ya existe' AS mensaje, v_id_genero AS id_genero;
            END IF;
        ELSE
            INSERT INTO generos(nombre_genero, activo)
            VALUES (v_nombre_genero, 1);

            SELECT 1 AS exito, 'Género agregado correctamente' AS mensaje, LAST_INSERT_ID() AS id_genero;
        END IF;
    END IF;
END $$

DROP PROCEDURE IF EXISTS sp_actualizar_genero $$

CREATE PROCEDURE sp_actualizar_genero(
    IN p_id_genero INT,
    IN p_nombre_genero VARCHAR(50)
)
BEGIN
    DECLARE v_nombre_genero VARCHAR(50) DEFAULT '';
    DECLARE v_id_existente INT DEFAULT 0;

    SET v_nombre_genero = TRIM(p_nombre_genero);

    IF NOT EXISTS (
        SELECT 1
        FROM generos
        WHERE id_genero = p_id_genero
    ) THEN
        SELECT 0 AS exito, 'Género no encontrado' AS mensaje, p_id_genero AS id_genero;
    ELSEIF v_nombre_genero = '' THEN
        SELECT 0 AS exito, 'Ingresa el nombre del género' AS mensaje, p_id_genero AS id_genero;
    ELSE
        SELECT COALESCE((
            SELECT id_genero
            FROM generos
            WHERE LOWER(nombre_genero) = LOWER(v_nombre_genero)
            AND id_genero <> p_id_genero
            LIMIT 1
        ), 0) INTO v_id_existente;

        IF v_id_existente > 0 THEN
            SELECT 0 AS exito, 'Ya existe otro género con ese nombre' AS mensaje, p_id_genero AS id_genero;
        ELSE
            UPDATE generos
            SET nombre_genero = v_nombre_genero
            WHERE id_genero = p_id_genero;

            SELECT 1 AS exito, 'Género actualizado correctamente' AS mensaje, p_id_genero AS id_genero;
        END IF;
    END IF;
END $$

DROP PROCEDURE IF EXISTS sp_desactivar_genero $$

CREATE PROCEDURE sp_desactivar_genero(
    IN p_id_genero INT
)
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM generos
        WHERE id_genero = p_id_genero
    ) THEN
        SELECT 0 AS exito, 'Género no encontrado' AS mensaje, p_id_genero AS id_genero;
    ELSE
        UPDATE generos
        SET activo = 0
        WHERE id_genero = p_id_genero;

        SELECT 1 AS exito, 'Género desactivado correctamente' AS mensaje, p_id_genero AS id_genero;
    END IF;
END $$

DROP PROCEDURE IF EXISTS sp_activar_genero $$

CREATE PROCEDURE sp_activar_genero(
    IN p_id_genero INT
)
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM generos
        WHERE id_genero = p_id_genero
    ) THEN
        SELECT 0 AS exito, 'Género no encontrado' AS mensaje, p_id_genero AS id_genero;
    ELSE
        UPDATE generos
        SET activo = 1
        WHERE id_genero = p_id_genero;

        SELECT 1 AS exito, 'Género activado correctamente' AS mensaje, p_id_genero AS id_genero;
    END IF;
END $$

DROP PROCEDURE IF EXISTS sp_obtener_id_genero_por_nombre $$

CREATE PROCEDURE sp_obtener_id_genero_por_nombre(
    IN p_nombre_genero VARCHAR(50)
)
BEGIN
    SELECT id_genero
    FROM generos
    WHERE nombre_genero = p_nombre_genero
    AND activo = 1
    LIMIT 1;
END $$

DROP PROCEDURE IF EXISTS sp_agregar_preferencia_usuario $$

CREATE PROCEDURE sp_agregar_preferencia_usuario(
    IN p_id_usuario INT,
    IN p_id_genero INT
)
BEGIN
    DECLARE v_existente INT DEFAULT 0;

    SELECT COUNT(*) INTO v_existente
    FROM preferencias_usuario
    WHERE id_usuario = p_id_usuario AND id_genero = p_id_genero;

    IF v_existente = 0 THEN
        INSERT INTO preferencias_usuario(id_usuario, id_genero)
        VALUES (p_id_usuario, p_id_genero);
    END IF;
END $$

DROP PROCEDURE IF EXISTS sp_actualizar_preferencias_usuario $$

CREATE PROCEDURE sp_actualizar_preferencias_usuario(
    IN p_usuario VARCHAR(50),
    IN p_generos TEXT
)
BEGIN
    DECLARE v_id_usuario INT DEFAULT 0;
    DECLARE v_generos TEXT DEFAULT '';
    DECLARE v_genero VARCHAR(100) DEFAULT '';
    DECLARE v_id_genero INT DEFAULT 0;
    DECLARE v_total INT DEFAULT 0;

    SELECT COALESCE((
        SELECT id_usuario
        FROM usuarios
        WHERE usuario = p_usuario
        LIMIT 1
    ), 0) INTO v_id_usuario;

    IF v_id_usuario = 0 THEN
        SELECT 0 AS exito, 'Usuario no encontrado' AS mensaje, 0 AS total;
    ELSE
        DELETE FROM preferencias_usuario
        WHERE id_usuario = v_id_usuario;

        SET v_generos = p_generos;

        IF v_generos IS NULL THEN
            SET v_generos = '';
        END IF;

        SET v_generos = TRIM(BOTH ',' FROM v_generos);

        WHILE v_generos <> '' DO
            SET v_genero = TRIM(SUBSTRING_INDEX(v_generos, ',', 1));

            IF LOCATE(',', v_generos) > 0 THEN
                SET v_generos = SUBSTRING(v_generos, LOCATE(',', v_generos) + 1);
            ELSE
                SET v_generos = '';
            END IF;

            SET v_id_genero = 0;

            IF v_genero <> '' THEN
                IF v_genero REGEXP '^[0-9]+$' THEN
                    SET v_id_genero = CAST(v_genero AS UNSIGNED);

                    IF NOT EXISTS (
                        SELECT 1
                        FROM generos
                        WHERE id_genero = v_id_genero
                        AND activo = 1
                    ) THEN
                        SET v_id_genero = 0;
                    END IF;
                ELSE
                    SELECT COALESCE((
                        SELECT id_genero
                        FROM generos
                        WHERE nombre_genero = v_genero
                        AND activo = 1
                        LIMIT 1
                    ), 0) INTO v_id_genero;
                END IF;

                IF v_id_genero > 0 THEN
                    INSERT INTO preferencias_usuario(id_usuario, id_genero)
                    SELECT v_id_usuario, v_id_genero
                    WHERE NOT EXISTS (
                        SELECT 1
                        FROM preferencias_usuario
                        WHERE id_usuario = v_id_usuario
                        AND id_genero = v_id_genero
                    );

                    IF ROW_COUNT() > 0 THEN
                        SET v_total = v_total + 1;
                    END IF;
                END IF;
            END IF;
        END WHILE;

        SELECT 1 AS exito, 'Preferencias actualizadas correctamente' AS mensaje, v_total AS total;
    END IF;
END $$

DROP PROCEDURE IF EXISTS sp_resumen_panel_administrador $$

CREATE PROCEDURE sp_resumen_panel_administrador()
BEGIN
    SELECT
        (SELECT COUNT(*) FROM usuarios) AS usuarios_totales,
        (SELECT COUNT(*) FROM peliculas_series WHERE activo = 1) AS peliculas_series_totales,
        (SELECT COUNT(*) FROM generos) AS generos_totales,
        (SELECT COUNT(*) FROM favoritos_usuario) AS favoritos_totales;
END $$

DROP PROCEDURE IF EXISTS sp_resumen_gestion_usuarios $$

CREATE PROCEDURE sp_resumen_gestion_usuarios()
BEGIN
    SELECT
        (SELECT COUNT(*) FROM usuarios) AS usuarios_totales,
        (SELECT COUNT(*) FROM usuarios WHERE rol = 'administrador') AS administradores,
        (SELECT COUNT(*) FROM usuarios WHERE rol = 'usuario') AS usuarios_normales,
        (SELECT COUNT(*) FROM usuarios WHERE fecha_creacion >= DATE_SUB(NOW(), INTERVAL 7 DAY)) AS nuevos_usuarios,
        (SELECT COUNT(*) FROM preferencias_usuario) AS preferencias_guardadas,
        (SELECT COUNT(*) FROM usuarios WHERE activo = 0) AS usuarios_desactivados;
END $$

DROP PROCEDURE IF EXISTS sp_listar_usuarios_gestion $$

CREATE PROCEDURE sp_listar_usuarios_gestion(
    IN p_busqueda VARCHAR(150),
    IN p_rol VARCHAR(30),
    IN p_genero VARCHAR(50)
)
BEGIN
    SELECT
        u.id_usuario,
        COALESCE(NULLIF(u.nombre, ''), u.usuario) AS nombre,
        u.usuario,
        u.correo,
        COALESCE(u.genero, '') AS genero,
        u.rol,
        DATE_FORMAT(u.fecha_creacion, '%d/%m/%Y') AS fecha_creacion_formateada,
        u.fecha_creacion,
        u.activo,
        COUNT(pu.id_preferencia) AS total_preferencias
    FROM usuarios u
    LEFT JOIN preferencias_usuario pu
        ON u.id_usuario = pu.id_usuario
    WHERE
        (
            p_busqueda IS NULL
            OR TRIM(p_busqueda) = ''
            OR u.nombre LIKE CONCAT('%', p_busqueda, '%')
            OR u.usuario LIKE CONCAT('%', p_busqueda, '%')
            OR u.correo LIKE CONCAT('%', p_busqueda, '%')
        )
        AND (
            p_rol IS NULL
            OR TRIM(p_rol) = ''
            OR LOWER(p_rol) = 'todos'
            OR LOWER(u.rol) = LOWER(p_rol)
        )
        AND (
            p_genero IS NULL
            OR TRIM(p_genero) = ''
            OR LOWER(p_genero) = 'todos'
            OR LOWER(COALESCE(u.genero, '')) = LOWER(p_genero)
        )
    GROUP BY
        u.id_usuario,
        u.nombre,
        u.usuario,
        u.correo,
        u.genero,
        u.rol,
        u.fecha_creacion,
        u.activo
    ORDER BY u.fecha_creacion DESC;
END $$

DROP PROCEDURE IF EXISTS sp_obtener_detalle_usuario_gestion $$

CREATE PROCEDURE sp_obtener_detalle_usuario_gestion(
    IN p_id_usuario INT
)
BEGIN
    SELECT
        u.id_usuario,
        COALESCE(NULLIF(u.nombre, ''), u.usuario) AS nombre,
        u.usuario,
        u.correo,
        u.fecha_nacimiento,
        COALESCE(u.genero, '') AS genero,
        u.rol,
        u.activo,
        DATE_FORMAT(u.fecha_creacion, '%d/%m/%Y %H:%i') AS fecha_creacion_formateada,
        u.fecha_creacion,
        COALESCE(GROUP_CONCAT(g.nombre_genero ORDER BY g.nombre_genero SEPARATOR ', '), '') AS preferencias,
        COUNT(DISTINCT pu.id_genero) AS total_preferencias
    FROM usuarios u
    LEFT JOIN preferencias_usuario pu
        ON u.id_usuario = pu.id_usuario
    LEFT JOIN generos g
        ON pu.id_genero = g.id_genero
    WHERE u.id_usuario = p_id_usuario
    GROUP BY
        u.id_usuario,
        u.nombre,
        u.usuario,
        u.correo,
        u.fecha_nacimiento,
        u.genero,
        u.rol,
        u.activo,
        u.fecha_creacion
    LIMIT 1;
END $$

DROP PROCEDURE IF EXISTS sp_actualizar_rol_usuario_gestion $$

CREATE PROCEDURE sp_actualizar_rol_usuario_gestion(
    IN p_id_usuario INT,
    IN p_rol VARCHAR(30)
)
BEGIN
    DECLARE v_rol_actual VARCHAR(30) DEFAULT '';
    DECLARE v_activo TINYINT DEFAULT 1;

    IF NOT EXISTS (
        SELECT 1
        FROM usuarios
        WHERE id_usuario = p_id_usuario
    ) THEN
        SELECT 0 AS exito, 'Usuario no encontrado' AS mensaje, p_id_usuario AS id_usuario;
    ELSEIF LOWER(p_rol) NOT IN ('usuario', 'administrador') THEN
        SELECT 0 AS exito, 'El rol no es válido' AS mensaje, p_id_usuario AS id_usuario;
    ELSE
        SELECT rol, activo
        INTO v_rol_actual, v_activo
        FROM usuarios
        WHERE id_usuario = p_id_usuario
        LIMIT 1;

        IF v_rol_actual = 'administrador'
            AND LOWER(p_rol) = 'usuario'
            AND v_activo = 1
            AND (SELECT COUNT(*) FROM usuarios WHERE rol = 'administrador' AND activo = 1) <= 1 THEN
            SELECT 0 AS exito, 'Debe existir al menos un administrador activo' AS mensaje, p_id_usuario AS id_usuario;
        ELSE
            UPDATE usuarios
            SET rol = LOWER(p_rol)
            WHERE id_usuario = p_id_usuario;

            SELECT 1 AS exito, 'Rol actualizado correctamente' AS mensaje, p_id_usuario AS id_usuario;
        END IF;
    END IF;
END $$

DROP PROCEDURE IF EXISTS sp_desactivar_usuario_gestion $$

CREATE PROCEDURE sp_desactivar_usuario_gestion(
    IN p_id_usuario INT
)
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM usuarios
        WHERE id_usuario = p_id_usuario
    ) THEN
        SELECT 0 AS exito, 'Usuario no encontrado' AS mensaje, p_id_usuario AS id_usuario;
    ELSEIF EXISTS (
        SELECT 1
        FROM usuarios
        WHERE id_usuario = p_id_usuario
        AND rol = 'administrador'
        AND activo = 1
    )
    AND (SELECT COUNT(*) FROM usuarios WHERE rol = 'administrador' AND activo = 1) <= 1 THEN
        SELECT 0 AS exito, 'Debe existir al menos un administrador activo' AS mensaje, p_id_usuario AS id_usuario;
    ELSE
        UPDATE usuarios
        SET activo = 0
        WHERE id_usuario = p_id_usuario;

        SELECT 1 AS exito, 'Usuario desactivado correctamente' AS mensaje, p_id_usuario AS id_usuario;
    END IF;
END $$

DROP PROCEDURE IF EXISTS sp_activar_usuario_gestion $$

CREATE PROCEDURE sp_activar_usuario_gestion(
    IN p_id_usuario INT
)
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM usuarios
        WHERE id_usuario = p_id_usuario
    ) THEN
        SELECT 0 AS exito, 'Usuario no encontrado' AS mensaje, p_id_usuario AS id_usuario;
    ELSE
        UPDATE usuarios
        SET activo = 1
        WHERE id_usuario = p_id_usuario;

        SELECT 1 AS exito, 'Usuario activado correctamente' AS mensaje, p_id_usuario AS id_usuario;
    END IF;
END $$

DROP PROCEDURE IF EXISTS sp_resumen_reportes_estadisticas $$

CREATE PROCEDURE sp_resumen_reportes_estadisticas()
BEGIN
    SELECT
        (SELECT COUNT(*) FROM usuarios) AS usuarios_totales,
        (SELECT COUNT(*) FROM usuarios WHERE activo = 1) AS usuarios_activos,
        (SELECT COUNT(*) FROM usuarios WHERE activo = 0) AS cuentas_bloqueadas,
        (SELECT COUNT(*) FROM peliculas_series WHERE LOWER(tipo) IN ('pelicula', 'película')) AS peliculas_totales,
        (SELECT COUNT(*) FROM peliculas_series WHERE LOWER(tipo) = 'serie') AS series_totales,
        (SELECT COUNT(*) FROM generos WHERE activo = 1) AS generos_totales,
        (
            SELECT COUNT(*)
            FROM peliculas_series
            WHERE activo = 1
            AND LOWER(estado) = 'publicado'
        ) AS contenidos_publicados,
        (
            SELECT COUNT(*)
            FROM peliculas_series
            WHERE activo = 0
            OR LOWER(estado) = 'desactivado'
        ) AS contenidos_desactivados;
END $$

DROP PROCEDURE IF EXISTS sp_ultimos_usuarios_reportes $$

CREATE PROCEDURE sp_ultimos_usuarios_reportes()
BEGIN
    SELECT
        id_usuario,
        COALESCE(NULLIF(nombre, ''), usuario) AS nombre,
        usuario,
        correo,
        COALESCE(genero, '') AS genero,
        rol,
        activo,
        DATE_FORMAT(fecha_creacion, '%d/%m/%Y') AS fecha_creacion_formateada,
        fecha_creacion
    FROM usuarios
    ORDER BY fecha_creacion DESC
    LIMIT 6;
END $$

DROP PROCEDURE IF EXISTS sp_ultimo_contenido_reportes $$

CREATE PROCEDURE sp_ultimo_contenido_reportes()
BEGIN
    SELECT
        ps.id_pelicula_serie,
        ps.titulo,
        ps.tipo,
        COALESCE(ps.imagen_portada, '') AS imagen,
        COALESCE(GROUP_CONCAT(g.nombre_genero ORDER BY g.nombre_genero SEPARATOR ', '), 'Sin género') AS generos,
        DATE_FORMAT(ps.fecha_creacion, '%d/%m/%Y') AS fecha,
        CASE
            WHEN ps.activo = 0 THEN 'Desactivado'
            ELSE ps.estado
        END AS estado,
        ps.activo
    FROM peliculas_series ps
    LEFT JOIN peliculas_series_generos psg
        ON ps.id_pelicula_serie = psg.id_pelicula_serie
    LEFT JOIN generos g
        ON psg.id_genero = g.id_genero
    GROUP BY
        ps.id_pelicula_serie,
        ps.titulo,
        ps.tipo,
        ps.imagen_portada,
        ps.fecha_creacion,
        ps.estado,
        ps.activo
    ORDER BY ps.fecha_creacion DESC
    LIMIT 6;
END $$

DROP PROCEDURE IF EXISTS sp_generos_mas_elegidos_reportes $$

CREATE PROCEDURE sp_generos_mas_elegidos_reportes()
BEGIN
    SELECT
        g.id_genero,
        g.nombre_genero,
        COUNT(pu.id_preferencia) AS total_usuarios
    FROM generos g
    LEFT JOIN preferencias_usuario pu
        ON g.id_genero = pu.id_genero
    WHERE g.activo = 1
    GROUP BY g.id_genero, g.nombre_genero
    ORDER BY total_usuarios DESC, g.nombre_genero
    LIMIT 8;
END $$

DROP PROCEDURE IF EXISTS sp_contenido_por_genero_reportes $$

CREATE PROCEDURE sp_contenido_por_genero_reportes()
BEGIN
    SELECT
        g.id_genero,
        g.nombre_genero,
        COUNT(psg.id_pelicula_serie) AS total_contenido
    FROM generos g
    LEFT JOIN peliculas_series_generos psg
        ON g.id_genero = psg.id_genero
    LEFT JOIN peliculas_series ps
        ON psg.id_pelicula_serie = ps.id_pelicula_serie
    WHERE g.activo = 1
    GROUP BY g.id_genero, g.nombre_genero
    ORDER BY total_contenido DESC, g.nombre_genero
    LIMIT 8;
END $$

DROP PROCEDURE IF EXISTS sp_distribucion_contenido_reportes $$

CREATE PROCEDURE sp_distribucion_contenido_reportes()
BEGIN
    SELECT 'Películas' AS etiqueta, COUNT(*) AS total
    FROM peliculas_series
    WHERE LOWER(tipo) IN ('pelicula', 'película')

    UNION ALL

    SELECT 'Series' AS etiqueta, COUNT(*) AS total
    FROM peliculas_series
    WHERE LOWER(tipo) = 'serie';
END $$

DROP PROCEDURE IF EXISTS sp_estado_contenido_reportes $$

CREATE PROCEDURE sp_estado_contenido_reportes()
BEGIN
    SELECT 'Publicados' AS etiqueta, COUNT(*) AS total
    FROM peliculas_series
    WHERE activo = 1
    AND LOWER(estado) = 'publicado'

    UNION ALL

    SELECT 'Desactivados' AS etiqueta, COUNT(*) AS total
    FROM peliculas_series
    WHERE activo = 0
    OR LOWER(estado) = 'desactivado';
END $$

DROP PROCEDURE IF EXISTS sp_actividad_reciente_panel $$

CREATE PROCEDURE sp_actividad_reciente_panel()
BEGIN
    SELECT tipo_actividad, accion, referencia, imagen, fecha_actividad
    FROM (
        SELECT
            'usuario' AS tipo_actividad,
            'Nuevo usuario registrado' AS accion,
            usuario AS referencia,
            '' AS imagen,
            fecha_creacion AS fecha_actividad
        FROM usuarios

        UNION ALL

        SELECT
            'contenido' AS tipo_actividad,
            'Se agregó contenido' AS accion,
            titulo AS referencia,
            COALESCE(imagen_portada, '') AS imagen,
            fecha_creacion AS fecha_actividad
        FROM peliculas_series
    ) AS actividad
    ORDER BY fecha_actividad DESC
    LIMIT 4;
END $$

DROP PROCEDURE IF EXISTS sp_ultimo_contenido_agregado $$

CREATE PROCEDURE sp_ultimo_contenido_agregado()
BEGIN
    SELECT
        ps.id_pelicula_serie,
        ps.titulo,
        ps.tipo,
        COALESCE(ps.imagen_portada, '') AS imagen,
        COALESCE(GROUP_CONCAT(g.nombre_genero ORDER BY g.nombre_genero SEPARATOR ', '), 'Sin género') AS generos,
        DATE_FORMAT(ps.fecha_creacion, '%d/%m/%Y') AS fecha,
        ps.estado,
        ps.activo
    FROM peliculas_series ps
    LEFT JOIN peliculas_series_generos psg
        ON ps.id_pelicula_serie = psg.id_pelicula_serie
    LEFT JOIN generos g
        ON psg.id_genero = g.id_genero
    GROUP BY
        ps.id_pelicula_serie,
        ps.titulo,
        ps.tipo,
        ps.imagen_portada,
        ps.fecha_creacion,
        ps.estado,
        ps.activo
    ORDER BY ps.fecha_creacion DESC
    LIMIT 4;
END $$

DROP PROCEDURE IF EXISTS sp_resumen_gestion_peliculas_series $$

CREATE PROCEDURE sp_resumen_gestion_peliculas_series()
BEGIN
    SELECT
        COUNT(*) AS total_contenido,
        COALESCE(SUM(CASE WHEN LOWER(tipo) IN ('pelicula', 'película') THEN 1 ELSE 0 END), 0) AS total_peliculas,
        COALESCE(SUM(CASE WHEN LOWER(tipo) = 'serie' THEN 1 ELSE 0 END), 0) AS total_series,
        COALESCE(SUM(CASE WHEN activo = 1 AND LOWER(estado) = 'publicado' THEN 1 ELSE 0 END), 0) AS total_publicados,
        COALESCE(SUM(CASE WHEN activo = 0 OR LOWER(estado) = 'desactivado' THEN 1 ELSE 0 END), 0) AS total_desactivados
    FROM peliculas_series;
END $$

DROP PROCEDURE IF EXISTS sp_listar_peliculas_series_gestion $$

CREATE PROCEDURE sp_listar_peliculas_series_gestion(
    IN p_busqueda VARCHAR(150),
    IN p_tipo VARCHAR(30),
    IN p_id_genero INT,
    IN p_estado VARCHAR(50),
    IN p_anio INT
)
BEGIN
    SELECT
        ps.id_pelicula_serie,
        ps.titulo,
        ps.titulo_original,
        COALESCE(ps.imagen_portada, '') AS imagen,
        ps.tipo,
        COALESCE(GROUP_CONCAT(g.nombre_genero ORDER BY g.nombre_genero SEPARATOR ', '), 'Sin género') AS generos,
        ps.anio_lanzamiento AS anio,
        CASE
            WHEN ps.activo = 0 THEN 'Desactivado'
            ELSE ps.estado
        END AS estado,
        COALESCE(ps.trailer_url, '') AS trailer_url,
        ps.activo
    FROM peliculas_series ps
    LEFT JOIN peliculas_series_generos psg
        ON ps.id_pelicula_serie = psg.id_pelicula_serie
    LEFT JOIN generos g
        ON psg.id_genero = g.id_genero
    WHERE
        (
            p_busqueda IS NULL
            OR TRIM(p_busqueda) = ''
            OR ps.titulo LIKE CONCAT('%', p_busqueda, '%')
            OR ps.titulo_original LIKE CONCAT('%', p_busqueda, '%')
        )
        AND (
            p_tipo IS NULL
            OR TRIM(p_tipo) = ''
            OR LOWER(p_tipo) = 'todos'
            OR LOWER(ps.tipo) = LOWER(p_tipo)
            OR (
                LOWER(p_tipo) IN ('pelicula', 'película')
                AND LOWER(ps.tipo) IN ('pelicula', 'película')
            )
        )
        AND (
            p_id_genero IS NULL
            OR p_id_genero = 0
            OR EXISTS (
                SELECT 1
                FROM peliculas_series_generos filtro_genero
                WHERE filtro_genero.id_pelicula_serie = ps.id_pelicula_serie
                AND filtro_genero.id_genero = p_id_genero
            )
        )
        AND (
            p_estado IS NULL
            OR TRIM(p_estado) = ''
            OR LOWER(p_estado) = 'todos'
            OR (
                LOWER(p_estado) = 'publicado'
                AND ps.activo = 1
                AND LOWER(ps.estado) = 'publicado'
            )
            OR (
                LOWER(p_estado) = 'desactivado'
                AND (ps.activo = 0 OR LOWER(ps.estado) = 'desactivado')
            )
            OR LOWER(ps.estado) = LOWER(p_estado)
        )
        AND (
            p_anio IS NULL
            OR p_anio = 0
            OR ps.anio_lanzamiento = p_anio
        )
    GROUP BY
        ps.id_pelicula_serie,
        ps.titulo,
        ps.titulo_original,
        ps.imagen_portada,
        ps.tipo,
        ps.anio_lanzamiento,
        ps.estado,
        ps.trailer_url,
        ps.activo,
        ps.fecha_actualizacion,
        ps.fecha_creacion
    ORDER BY ps.fecha_actualizacion DESC, ps.fecha_creacion DESC;
END $$

DROP PROCEDURE IF EXISTS sp_obtener_detalle_pelicula_serie $$

CREATE PROCEDURE sp_obtener_detalle_pelicula_serie(
    IN p_id_pelicula_serie INT
)
BEGIN
    SELECT
        ps.id_pelicula_serie,
        ps.titulo,
        ps.titulo_original,
        ps.descripcion,
        ps.tipo,
        CASE
            WHEN ps.activo = 0 THEN 'Desactivado'
            ELSE ps.estado
        END AS estado,
        COALESCE(ps.estado_emision, 'Finalizado') AS estado_emision,
        ps.anio_lanzamiento,
        ps.fecha_estreno,
        ps.duracion_minutos,
        ps.temporadas,
        ps.episodios,
        COALESCE(ps.imagen_portada, '') AS imagen_portada,
        COALESCE(ps.imagen_banner, '') AS imagen_banner,
        COALESCE(ps.trailer_url, '') AS trailer_url,
        ps.destacado,
        ps.activo,
        ps.fecha_creacion,
        ps.fecha_actualizacion,
        COALESCE(GROUP_CONCAT(g.nombre_genero ORDER BY g.nombre_genero SEPARATOR ', '), 'Sin género') AS generos,
        COALESCE(GROUP_CONCAT(g.id_genero ORDER BY g.nombre_genero SEPARATOR ','), '') AS ids_generos,
        ps.serie_padre_id,
        ps.numero_temporada,
        ps.tipo_relacion,
        COALESCE(padre.titulo, '') AS padre_titulo,
        COALESCE(padre.imagen_portada, '') AS padre_imagen_portada,
        COALESCE(padre.anio_lanzamiento, 0) AS padre_anio_lanzamiento
    FROM peliculas_series ps
    LEFT JOIN peliculas_series padre
        ON ps.serie_padre_id = padre.id_pelicula_serie
    LEFT JOIN peliculas_series_generos psg
        ON ps.id_pelicula_serie = psg.id_pelicula_serie
    LEFT JOIN generos g
        ON psg.id_genero = g.id_genero
    WHERE ps.id_pelicula_serie = p_id_pelicula_serie
    GROUP BY
        ps.id_pelicula_serie,
        ps.titulo,
        ps.titulo_original,
        ps.descripcion,
        ps.tipo,
        ps.estado,
        ps.estado_emision,
        ps.anio_lanzamiento,
        ps.fecha_estreno,
        ps.duracion_minutos,
        ps.temporadas,
        ps.episodios,
        ps.imagen_portada,
        ps.imagen_banner,
        ps.trailer_url,
        ps.destacado,
        ps.activo,
        ps.fecha_creacion,
        ps.fecha_actualizacion,
        ps.serie_padre_id,
        ps.numero_temporada,
        ps.tipo_relacion,
        padre.titulo,
        padre.imagen_portada,
        padre.anio_lanzamiento
    LIMIT 1;
END $$

DROP PROCEDURE IF EXISTS sp_crear_pelicula_serie $$

CREATE PROCEDURE sp_crear_pelicula_serie(
    IN p_titulo VARCHAR(150),
    IN p_titulo_original VARCHAR(150),
    IN p_descripcion TEXT,
    IN p_tipo VARCHAR(30),
    IN p_estado VARCHAR(50),
    IN p_estado_emision VARCHAR(30),
    IN p_anio_lanzamiento INT,
    IN p_fecha_estreno DATE,
    IN p_duracion_minutos INT,
    IN p_temporadas INT,
    IN p_episodios INT,
    IN p_imagen_portada VARCHAR(255),
    IN p_imagen_banner VARCHAR(255),
    IN p_trailer_url VARCHAR(255),
    IN p_generos TEXT,
    IN p_destacado TINYINT,
    IN p_serie_padre_id INT,
    IN p_numero_temporada INT,
    IN p_tipo_relacion VARCHAR(20)
)
BEGIN
    DECLARE v_id_pelicula_serie INT DEFAULT 0;
    DECLARE v_activo TINYINT DEFAULT 1;
    DECLARE v_estado VARCHAR(50) DEFAULT 'Publicado';
    DECLARE v_estado_emision VARCHAR(30) DEFAULT 'Finalizado';
    DECLARE v_generos TEXT DEFAULT '';
    DECLARE v_genero VARCHAR(100) DEFAULT '';
    DECLARE v_id_genero INT DEFAULT 0;

    IF p_estado IS NOT NULL AND TRIM(p_estado) <> '' THEN
        SET v_estado = p_estado;
    END IF;

    IF LOWER(v_estado) = 'desactivado' THEN
        SET v_activo = 0;
    END IF;

    IF p_estado_emision IS NOT NULL AND TRIM(p_estado_emision) <> '' THEN
        SET v_estado_emision = TRIM(p_estado_emision);
    END IF;

    IF v_estado_emision <> 'Finalizado'
        AND v_estado_emision <> 'En emisión'
        AND v_estado_emision <> 'Próximamente' THEN
        SET v_estado_emision = 'Finalizado';
    END IF;

    INSERT INTO peliculas_series(
        titulo,
        titulo_original,
        descripcion,
        tipo,
        estado,
        estado_emision,
        anio_lanzamiento,
        fecha_estreno,
        duracion_minutos,
        temporadas,
        episodios,
        imagen_portada,
        imagen_banner,
        trailer_url,
        destacado,
        activo,
        serie_padre_id,
        numero_temporada,
        tipo_relacion
    )
    VALUES (
        p_titulo,
        p_titulo_original,
        p_descripcion,
        p_tipo,
        v_estado,
        v_estado_emision,
        p_anio_lanzamiento,
        p_fecha_estreno,
        p_duracion_minutos,
        p_temporadas,
        p_episodios,
        p_imagen_portada,
        p_imagen_banner,
        p_trailer_url,
        CASE WHEN p_destacado = 1 THEN 1 ELSE 0 END,
        v_activo,
        CASE WHEN p_serie_padre_id IS NOT NULL AND p_serie_padre_id > 0 THEN p_serie_padre_id ELSE NULL END,
        CASE WHEN p_numero_temporada IS NOT NULL AND p_numero_temporada > 0 THEN p_numero_temporada ELSE NULL END,
        CASE WHEN p_tipo_relacion IS NOT NULL AND TRIM(p_tipo_relacion) <> '' THEN TRIM(p_tipo_relacion) ELSE NULL END
    );

    SET v_id_pelicula_serie = LAST_INSERT_ID();
    SET v_generos = p_generos;

    IF v_generos IS NULL THEN
        SET v_generos = '';
    END IF;

    SET v_generos = TRIM(BOTH ',' FROM v_generos);

    WHILE v_generos <> '' DO
        SET v_genero = TRIM(SUBSTRING_INDEX(v_generos, ',', 1));

        IF LOCATE(',', v_generos) > 0 THEN
            SET v_generos = SUBSTRING(v_generos, LOCATE(',', v_generos) + 1);
        ELSE
            SET v_generos = '';
        END IF;

        SET v_id_genero = 0;

        IF v_genero <> '' THEN
            IF v_genero REGEXP '^[0-9]+$' THEN
                SET v_id_genero = CAST(v_genero AS UNSIGNED);
            ELSE
                SELECT COALESCE((
                    SELECT id_genero
                    FROM generos
                    WHERE nombre_genero = v_genero
                    AND activo = 1
                    LIMIT 1
                ), 0) INTO v_id_genero;
            END IF;

            IF v_id_genero > 0 THEN
                INSERT INTO peliculas_series_generos(id_pelicula_serie, id_genero)
                SELECT v_id_pelicula_serie, v_id_genero
                WHERE EXISTS (
                    SELECT 1
                    FROM generos
                    WHERE id_genero = v_id_genero
                    AND activo = 1
                )
                AND NOT EXISTS (
                    SELECT 1
                    FROM peliculas_series_generos
                    WHERE id_pelicula_serie = v_id_pelicula_serie
                    AND id_genero = v_id_genero
                );
            END IF;
        END IF;
    END WHILE;

    SELECT 1 AS exito, 'Contenido creado correctamente' AS mensaje, v_id_pelicula_serie AS id_pelicula_serie;
END $$

DROP PROCEDURE IF EXISTS sp_actualizar_pelicula_serie $$

CREATE PROCEDURE sp_actualizar_pelicula_serie(
    IN p_id_pelicula_serie INT,
    IN p_titulo VARCHAR(150),
    IN p_titulo_original VARCHAR(150),
    IN p_descripcion TEXT,
    IN p_tipo VARCHAR(30),
    IN p_estado VARCHAR(50),
    IN p_estado_emision VARCHAR(30),
    IN p_anio_lanzamiento INT,
    IN p_fecha_estreno DATE,
    IN p_duracion_minutos INT,
    IN p_temporadas INT,
    IN p_episodios INT,
    IN p_imagen_portada VARCHAR(255),
    IN p_imagen_banner VARCHAR(255),
    IN p_trailer_url VARCHAR(255),
    IN p_generos TEXT,
    IN p_destacado TINYINT,
    IN p_serie_padre_id INT,
    IN p_numero_temporada INT,
    IN p_tipo_relacion VARCHAR(20)
)
BEGIN
    DECLARE v_activo TINYINT DEFAULT 1;
    DECLARE v_estado VARCHAR(50) DEFAULT 'Publicado';
    DECLARE v_estado_emision VARCHAR(30) DEFAULT 'Finalizado';
    DECLARE v_generos TEXT DEFAULT '';
    DECLARE v_genero VARCHAR(100) DEFAULT '';
    DECLARE v_id_genero INT DEFAULT 0;

    IF NOT EXISTS (
        SELECT 1
        FROM peliculas_series
        WHERE id_pelicula_serie = p_id_pelicula_serie
    ) THEN
        SELECT 0 AS exito, 'Contenido no encontrado' AS mensaje, p_id_pelicula_serie AS id_pelicula_serie;
    ELSE
        IF p_estado IS NOT NULL AND TRIM(p_estado) <> '' THEN
            SET v_estado = p_estado;
        END IF;

        IF LOWER(v_estado) = 'desactivado' THEN
            SET v_activo = 0;
        END IF;

        IF p_estado_emision IS NOT NULL AND TRIM(p_estado_emision) <> '' THEN
            SET v_estado_emision = TRIM(p_estado_emision);
        END IF;

        IF v_estado_emision <> 'Finalizado'
            AND v_estado_emision <> 'En emisión'
            AND v_estado_emision <> 'Próximamente' THEN
            SET v_estado_emision = 'Finalizado';
        END IF;

        UPDATE peliculas_series
        SET titulo = p_titulo,
            titulo_original = p_titulo_original,
            descripcion = p_descripcion,
            tipo = p_tipo,
            estado = v_estado,
            estado_emision = v_estado_emision,
            anio_lanzamiento = p_anio_lanzamiento,
            fecha_estreno = p_fecha_estreno,
            duracion_minutos = p_duracion_minutos,
            temporadas = p_temporadas,
            episodios = p_episodios,
            imagen_portada = p_imagen_portada,
            imagen_banner = p_imagen_banner,
            trailer_url = p_trailer_url,
            destacado = CASE WHEN p_destacado = 1 THEN 1 ELSE 0 END,
            activo = v_activo,
            serie_padre_id = CASE WHEN p_serie_padre_id IS NOT NULL AND p_serie_padre_id > 0 THEN p_serie_padre_id ELSE NULL END,
            numero_temporada = CASE WHEN p_numero_temporada IS NOT NULL AND p_numero_temporada > 0 THEN p_numero_temporada ELSE NULL END,
            tipo_relacion = CASE WHEN p_tipo_relacion IS NOT NULL AND TRIM(p_tipo_relacion) <> '' THEN TRIM(p_tipo_relacion) ELSE NULL END
        WHERE id_pelicula_serie = p_id_pelicula_serie;

        DELETE FROM peliculas_series_generos
        WHERE id_pelicula_serie = p_id_pelicula_serie;

        SET v_generos = p_generos;

        IF v_generos IS NULL THEN
            SET v_generos = '';
        END IF;

        SET v_generos = TRIM(BOTH ',' FROM v_generos);

        WHILE v_generos <> '' DO
            SET v_genero = TRIM(SUBSTRING_INDEX(v_generos, ',', 1));

            IF LOCATE(',', v_generos) > 0 THEN
                SET v_generos = SUBSTRING(v_generos, LOCATE(',', v_generos) + 1);
            ELSE
                SET v_generos = '';
            END IF;

            SET v_id_genero = 0;

            IF v_genero <> '' THEN
                IF v_genero REGEXP '^[0-9]+$' THEN
                    SET v_id_genero = CAST(v_genero AS UNSIGNED);
                ELSE
                SELECT COALESCE((
                    SELECT id_genero
                    FROM generos
                    WHERE nombre_genero = v_genero
                    AND activo = 1
                    LIMIT 1
                ), 0) INTO v_id_genero;
            END IF;

            IF v_id_genero > 0 THEN
                    INSERT INTO peliculas_series_generos(id_pelicula_serie, id_genero)
                    SELECT p_id_pelicula_serie, v_id_genero
                    WHERE EXISTS (
                    SELECT 1
                    FROM generos
                    WHERE id_genero = v_id_genero
                    AND activo = 1
                )
                AND NOT EXISTS (
                        SELECT 1
                        FROM peliculas_series_generos
                        WHERE id_pelicula_serie = p_id_pelicula_serie
                        AND id_genero = v_id_genero
                    );
                END IF;
            END IF;
        END WHILE;

        SELECT 1 AS exito, 'Contenido actualizado correctamente' AS mensaje, p_id_pelicula_serie AS id_pelicula_serie;
    END IF;
END $$

DROP PROCEDURE IF EXISTS sp_desactivar_pelicula_serie $$

CREATE PROCEDURE sp_desactivar_pelicula_serie(
    IN p_id_pelicula_serie INT
)
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM peliculas_series
        WHERE id_pelicula_serie = p_id_pelicula_serie
    ) THEN
        SELECT 0 AS exito, 'Contenido no encontrado' AS mensaje;
    ELSE
        UPDATE peliculas_series
        SET activo = 0,
            estado = 'Desactivado'
        WHERE id_pelicula_serie = p_id_pelicula_serie;

        SELECT 1 AS exito, 'Contenido desactivado correctamente' AS mensaje;
    END IF;
END $$

DROP PROCEDURE IF EXISTS sp_activar_pelicula_serie $$

CREATE PROCEDURE sp_activar_pelicula_serie(
    IN p_id_pelicula_serie INT
)
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM peliculas_series
        WHERE id_pelicula_serie = p_id_pelicula_serie
    ) THEN
        SELECT 0 AS exito, 'Contenido no encontrado' AS mensaje;
    ELSE
        UPDATE peliculas_series
        SET activo = 1,
            estado = 'Publicado'
        WHERE id_pelicula_serie = p_id_pelicula_serie;

        SELECT 1 AS exito, 'Contenido activado correctamente' AS mensaje;
    END IF;
END $$

DROP PROCEDURE IF EXISTS sp_inicio_destacados_usuario $$

CREATE PROCEDURE sp_inicio_destacados_usuario(
    IN p_usuario VARCHAR(50)
)
BEGIN
    DECLARE v_id_usuario INT DEFAULT 0;

    SELECT COALESCE((
        SELECT id_usuario
        FROM usuarios
        WHERE usuario = p_usuario
        LIMIT 1
    ), 0) INTO v_id_usuario;

    SELECT
        ps.id_pelicula_serie,
        ps.titulo,
        COALESCE(ps.titulo_original, '') AS titulo_original,
        COALESCE(ps.descripcion, '') AS descripcion,
        ps.tipo,
        COALESCE(GROUP_CONCAT(g.nombre_genero ORDER BY g.nombre_genero SEPARATOR ', '), 'Sin género') AS generos,
        COALESCE(ps.imagen_portada, '') AS imagen_portada,
        COALESCE(ps.imagen_banner, '') AS imagen_banner,
        COALESCE(ps.trailer_url, '') AS trailer_url,
        COALESCE(ps.anio_lanzamiento, 0) AS anio_lanzamiento,
        COALESCE(ps.duracion_minutos, 0) AS duracion_minutos,
        COALESCE(ps.temporadas, 0) AS temporadas,
        COALESCE(ps.episodios, 0) AS episodios,
        ps.destacado,
        CASE
            WHEN EXISTS (
                SELECT 1
                FROM favoritos_usuario fu
                WHERE fu.id_usuario = v_id_usuario
                AND fu.id_pelicula_serie = ps.id_pelicula_serie
            ) THEN 1
            ELSE 0
        END AS favorito
    FROM peliculas_series ps
    LEFT JOIN peliculas_series_generos psg
        ON ps.id_pelicula_serie = psg.id_pelicula_serie
    LEFT JOIN generos g
        ON psg.id_genero = g.id_genero
    WHERE ps.activo = 1
    AND LOWER(ps.estado) = 'publicado'
    AND (
        ps.destacado = 1
        OR NOT EXISTS (
            SELECT 1
            FROM peliculas_series ps_destacado
            WHERE ps_destacado.activo = 1
            AND LOWER(ps_destacado.estado) = 'publicado'
            AND ps_destacado.destacado = 1
        )
    )
    GROUP BY
        ps.id_pelicula_serie,
        ps.titulo,
        ps.titulo_original,
        ps.descripcion,
        ps.tipo,
        ps.imagen_portada,
        ps.imagen_banner,
        ps.trailer_url,
        ps.anio_lanzamiento,
        ps.duracion_minutos,
        ps.temporadas,
        ps.episodios,
        ps.destacado,
        ps.fecha_actualizacion,
        ps.fecha_creacion
    ORDER BY ps.destacado DESC, ps.fecha_actualizacion DESC, ps.fecha_creacion DESC
    LIMIT 5;
END $$

DROP PROCEDURE IF EXISTS sp_inicio_recomendaciones_usuario $$

CREATE PROCEDURE sp_inicio_recomendaciones_usuario(
    IN p_usuario VARCHAR(50)
)
BEGIN
    DECLARE v_id_usuario INT DEFAULT 0;
    DECLARE v_total_preferencias INT DEFAULT 0;

    SELECT COALESCE((
        SELECT id_usuario
        FROM usuarios
        WHERE usuario = p_usuario
        LIMIT 1
    ), 0) INTO v_id_usuario;

    SELECT COUNT(*)
    INTO v_total_preferencias
    FROM preferencias_usuario
    WHERE id_usuario = v_id_usuario;

    SELECT
        ps.id_pelicula_serie,
        ps.titulo,
        COALESCE(ps.titulo_original, '') AS titulo_original,
        COALESCE(ps.descripcion, '') AS descripcion,
        ps.tipo,
        COALESCE(GROUP_CONCAT(DISTINCT g.nombre_genero ORDER BY g.nombre_genero SEPARATOR ', '), 'Sin género') AS generos,
        COALESCE(ps.imagen_portada, '') AS imagen_portada,
        COALESCE(ps.imagen_banner, '') AS imagen_banner,
        COALESCE(ps.trailer_url, '') AS trailer_url,
        COALESCE(ps.anio_lanzamiento, 0) AS anio_lanzamiento,
        COALESCE(ps.duracion_minutos, 0) AS duracion_minutos,
        COALESCE(ps.temporadas, 0) AS temporadas,
        COALESCE(ps.episodios, 0) AS episodios,
        ps.destacado,
        COUNT(DISTINCT pu.id_genero) AS coincidencias,
        CASE
            WHEN EXISTS (
                SELECT 1
                FROM favoritos_usuario fu
                WHERE fu.id_usuario = v_id_usuario
                AND fu.id_pelicula_serie = ps.id_pelicula_serie
            ) THEN 1
            ELSE 0
        END AS favorito
    FROM peliculas_series ps
    LEFT JOIN peliculas_series_generos psg
        ON ps.id_pelicula_serie = psg.id_pelicula_serie
    LEFT JOIN generos g
        ON psg.id_genero = g.id_genero
    LEFT JOIN preferencias_usuario pu
        ON pu.id_usuario = v_id_usuario
        AND pu.id_genero = psg.id_genero
    WHERE ps.activo = 1
    AND LOWER(ps.estado) = 'publicado'
    GROUP BY
        ps.id_pelicula_serie,
        ps.titulo,
        ps.titulo_original,
        ps.descripcion,
        ps.tipo,
        ps.imagen_portada,
        ps.imagen_banner,
        ps.trailer_url,
        ps.anio_lanzamiento,
        ps.duracion_minutos,
        ps.temporadas,
        ps.episodios,
        ps.destacado,
        ps.fecha_actualizacion,
        ps.fecha_creacion
    HAVING v_total_preferencias = 0 OR coincidencias > 0
    ORDER BY coincidencias DESC, ps.fecha_actualizacion DESC, ps.fecha_creacion DESC
    LIMIT 8;
END $$

DROP PROCEDURE IF EXISTS sp_inicio_ultimos_agregados_usuario $$

CREATE PROCEDURE sp_inicio_ultimos_agregados_usuario(
    IN p_usuario VARCHAR(50)
)
BEGIN
    DECLARE v_id_usuario INT DEFAULT 0;

    SELECT COALESCE((
        SELECT id_usuario
        FROM usuarios
        WHERE usuario = p_usuario
        LIMIT 1
    ), 0) INTO v_id_usuario;

    SELECT
        ps.id_pelicula_serie,
        ps.titulo,
        COALESCE(ps.titulo_original, '') AS titulo_original,
        COALESCE(ps.descripcion, '') AS descripcion,
        ps.tipo,
        COALESCE(GROUP_CONCAT(g.nombre_genero ORDER BY g.nombre_genero SEPARATOR ', '), 'Sin género') AS generos,
        COALESCE(ps.imagen_portada, '') AS imagen_portada,
        COALESCE(ps.imagen_banner, '') AS imagen_banner,
        COALESCE(ps.trailer_url, '') AS trailer_url,
        COALESCE(ps.anio_lanzamiento, 0) AS anio_lanzamiento,
        COALESCE(ps.duracion_minutos, 0) AS duracion_minutos,
        COALESCE(ps.temporadas, 0) AS temporadas,
        COALESCE(ps.episodios, 0) AS episodios,
        ps.destacado,
        CASE
            WHEN EXISTS (
                SELECT 1
                FROM favoritos_usuario fu
                WHERE fu.id_usuario = v_id_usuario
                AND fu.id_pelicula_serie = ps.id_pelicula_serie
            ) THEN 1
            ELSE 0
        END AS favorito
    FROM peliculas_series ps
    LEFT JOIN peliculas_series_generos psg
        ON ps.id_pelicula_serie = psg.id_pelicula_serie
    LEFT JOIN generos g
        ON psg.id_genero = g.id_genero
    WHERE ps.activo = 1
    AND LOWER(ps.estado) = 'publicado'
    GROUP BY
        ps.id_pelicula_serie,
        ps.titulo,
        ps.titulo_original,
        ps.descripcion,
        ps.tipo,
        ps.imagen_portada,
        ps.imagen_banner,
        ps.trailer_url,
        ps.anio_lanzamiento,
        ps.duracion_minutos,
        ps.temporadas,
        ps.episodios,
        ps.destacado,
        ps.fecha_actualizacion,
        ps.fecha_creacion
    ORDER BY ps.fecha_creacion DESC
    LIMIT 8;
END $$

DROP PROCEDURE IF EXISTS sp_inicio_generos_usuario $$

CREATE PROCEDURE sp_inicio_generos_usuario()
BEGIN
    SELECT id_genero, nombre_genero
    FROM generos
    WHERE activo = 1
    ORDER BY nombre_genero
    LIMIT 8;
END $$

DROP PROCEDURE IF EXISTS sp_detalle_contenido_usuario $$

CREATE PROCEDURE sp_detalle_contenido_usuario(
    IN p_id_pelicula_serie INT,
    IN p_usuario VARCHAR(50)
)
BEGIN
    DECLARE v_id_usuario INT DEFAULT 0;

    SELECT COALESCE((
        SELECT id_usuario
        FROM usuarios
        WHERE usuario = p_usuario
        LIMIT 1
    ), 0) INTO v_id_usuario;

    SELECT
        ps.id_pelicula_serie,
        ps.titulo,
        COALESCE(ps.titulo_original, '') AS titulo_original,
        COALESCE(ps.descripcion, '') AS descripcion,
        ps.tipo,
        CASE
            WHEN ps.activo = 0 THEN 'Desactivado'
            ELSE ps.estado
        END AS estado,
        COALESCE(ps.estado_emision, 'Finalizado') AS estado_emision,
        COALESCE(ps.anio_lanzamiento, 0) AS anio_lanzamiento,
        COALESCE(ps.fecha_estreno, '') AS fecha_estreno,
        COALESCE(ps.duracion_minutos, 0) AS duracion_minutos,
        COALESCE(ps.temporadas, 0) AS temporadas,
        COALESCE(ps.episodios, 0) AS episodios,
        COALESCE(ps.imagen_portada, '') AS imagen_portada,
        COALESCE(ps.imagen_banner, '') AS imagen_banner,
        COALESCE(ps.trailer_url, '') AS trailer_url,
        ps.activo,
        ps.destacado,
        COALESCE(GROUP_CONCAT(g.nombre_genero ORDER BY g.nombre_genero SEPARATOR ', '), 'Sin género') AS generos,
        CASE
            WHEN EXISTS (
                SELECT 1
                FROM favoritos_usuario fu
                WHERE fu.id_usuario = v_id_usuario
                AND fu.id_pelicula_serie = ps.id_pelicula_serie
            ) THEN 1
            ELSE 0
        END AS favorito,
        ps.serie_padre_id,
        ps.numero_temporada,
        ps.tipo_relacion,
        COALESCE(padre.titulo, '') AS padre_titulo,
        COALESCE(padre.imagen_portada, '') AS padre_imagen_portada,
        COALESCE(padre.anio_lanzamiento, 0) AS padre_anio_lanzamiento
    FROM peliculas_series ps
    LEFT JOIN peliculas_series padre
        ON ps.serie_padre_id = padre.id_pelicula_serie
    LEFT JOIN peliculas_series_generos psg
        ON ps.id_pelicula_serie = psg.id_pelicula_serie
    LEFT JOIN generos g
        ON psg.id_genero = g.id_genero
    WHERE ps.id_pelicula_serie = p_id_pelicula_serie
    AND ps.activo = 1
    AND LOWER(ps.estado) = 'publicado'
    GROUP BY
        ps.id_pelicula_serie,
        ps.titulo,
        ps.titulo_original,
        ps.descripcion,
        ps.tipo,
        ps.estado,
        ps.estado_emision,
        ps.anio_lanzamiento,
        ps.fecha_estreno,
        ps.duracion_minutos,
        ps.temporadas,
        ps.episodios,
        ps.imagen_portada,
        ps.imagen_banner,
        ps.trailer_url,
        ps.activo,
        ps.destacado,
        ps.serie_padre_id,
        ps.numero_temporada,
        ps.tipo_relacion,
        padre.titulo,
        padre.imagen_portada,
        padre.anio_lanzamiento
    LIMIT 1;
END $$

DROP PROCEDURE IF EXISTS sp_buscar_contenido_usuario $$

DROP PROCEDURE IF EXISTS sp_explorar_contenido_usuario $$

CREATE PROCEDURE sp_explorar_contenido_usuario(
    IN p_usuario VARCHAR(50),
    IN p_busqueda VARCHAR(150),
    IN p_tipo VARCHAR(20),
    IN p_genero VARCHAR(100),
    IN p_anio INT,
    IN p_orden VARCHAR(30)
)
BEGIN
    DECLARE v_id_usuario INT DEFAULT 0;
    DECLARE v_busqueda VARCHAR(150) DEFAULT '';
    DECLARE v_tipo VARCHAR(20) DEFAULT 'Todos';
    DECLARE v_genero VARCHAR(100) DEFAULT 'Todos';
    DECLARE v_orden VARCHAR(30) DEFAULT 'ultimos';

    SELECT COALESCE((
        SELECT id_usuario
        FROM usuarios
        WHERE usuario = p_usuario
        LIMIT 1
    ), 0) INTO v_id_usuario;

    SET v_busqueda = TRIM(COALESCE(p_busqueda, ''));
    SET v_tipo = TRIM(COALESCE(p_tipo, 'Todos'));
    SET v_genero = TRIM(COALESCE(p_genero, 'Todos'));
    SET v_orden = TRIM(COALESCE(p_orden, 'ultimos'));

    SELECT
        ps.id_pelicula_serie,
        ps.titulo,
        COALESCE(ps.titulo_original, '') AS titulo_original,
        COALESCE(ps.descripcion, '') AS descripcion,
        ps.tipo,
        COALESCE(GROUP_CONCAT(DISTINCT g.nombre_genero ORDER BY g.nombre_genero SEPARATOR ', '), 'Sin género') AS generos,
        COALESCE(ps.imagen_portada, '') AS imagen_portada,
        COALESCE(ps.imagen_banner, '') AS imagen_banner,
        COALESCE(ps.trailer_url, '') AS trailer_url,
        COALESCE(ps.anio_lanzamiento, 0) AS anio_lanzamiento,
        COALESCE(ps.duracion_minutos, 0) AS duracion_minutos,
        COALESCE(ps.temporadas, 0) AS temporadas,
        COALESCE(ps.episodios, 0) AS episodios,
        ps.estado,
        ps.activo,
        ps.destacado,
        CASE
            WHEN EXISTS (
                SELECT 1
                FROM favoritos_usuario fu
                WHERE fu.id_usuario = v_id_usuario
                AND fu.id_pelicula_serie = ps.id_pelicula_serie
            ) THEN 1
            ELSE 0
        END AS favorito
    FROM peliculas_series ps
    LEFT JOIN peliculas_series_generos psg
        ON ps.id_pelicula_serie = psg.id_pelicula_serie
    LEFT JOIN generos g
        ON psg.id_genero = g.id_genero
    WHERE ps.activo = 1
    AND LOWER(ps.estado) = 'publicado'
    AND (
        v_busqueda = ''
        OR ps.titulo LIKE CONCAT('%', v_busqueda, '%')
        OR ps.titulo_original LIKE CONCAT('%', v_busqueda, '%')
    )
    AND (
        v_tipo = ''
        OR v_tipo = 'Todos'
        OR ps.tipo = v_tipo
    )
    AND (
        p_anio IS NULL
        OR p_anio = 0
        OR ps.anio_lanzamiento = p_anio
    )
    AND (
        v_genero = ''
        OR v_genero = 'Todos'
        OR EXISTS (
            SELECT 1
            FROM peliculas_series_generos psg_filtro
            INNER JOIN generos g_filtro
                ON psg_filtro.id_genero = g_filtro.id_genero
            WHERE psg_filtro.id_pelicula_serie = ps.id_pelicula_serie
            AND g_filtro.nombre_genero = v_genero
        )
    )
    GROUP BY
        ps.id_pelicula_serie,
        ps.titulo,
        ps.titulo_original,
        ps.descripcion,
        ps.tipo,
        ps.imagen_portada,
        ps.imagen_banner,
        ps.trailer_url,
        ps.anio_lanzamiento,
        ps.duracion_minutos,
        ps.temporadas,
        ps.episodios,
        ps.estado,
        ps.activo,
        ps.destacado,
        ps.fecha_actualizacion,
        ps.fecha_creacion
    ORDER BY
        CASE WHEN v_orden = 'az' THEN ps.titulo END ASC,
        CASE WHEN v_orden = 'anio' THEN ps.anio_lanzamiento END DESC,
        CASE WHEN v_orden = 'antiguos' THEN ps.fecha_creacion END ASC,
        ps.fecha_creacion DESC,
        ps.fecha_actualizacion DESC;
END $$

DROP PROCEDURE IF EXISTS sp_contenido_por_genero_usuario $$

CREATE PROCEDURE sp_contenido_por_genero_usuario(
    IN p_usuario VARCHAR(50),
    IN p_id_genero INT,
    IN p_tipo VARCHAR(20),
    IN p_anio INT,
    IN p_orden VARCHAR(30)
)
BEGIN
    DECLARE v_id_usuario INT DEFAULT 0;
    DECLARE v_id_genero INT DEFAULT 0;
    DECLARE v_tipo VARCHAR(20) DEFAULT 'Todos';
    DECLARE v_orden VARCHAR(30) DEFAULT 'ultimos';

    SELECT COALESCE((
        SELECT id_usuario
        FROM usuarios
        WHERE usuario = p_usuario
        LIMIT 1
    ), 0) INTO v_id_usuario;

    SET v_id_genero = COALESCE(p_id_genero, 0);
    SET v_tipo = TRIM(COALESCE(p_tipo, 'Todos'));
    SET v_orden = TRIM(COALESCE(p_orden, 'ultimos'));

    SELECT
        ps.id_pelicula_serie,
        ps.titulo,
        COALESCE(ps.titulo_original, '') AS titulo_original,
        COALESCE(ps.descripcion, '') AS descripcion,
        ps.tipo,
        COALESCE(GROUP_CONCAT(DISTINCT g.nombre_genero ORDER BY g.nombre_genero SEPARATOR ', '), 'Sin género') AS generos,
        COALESCE(ps.imagen_portada, '') AS imagen_portada,
        COALESCE(ps.imagen_banner, '') AS imagen_banner,
        COALESCE(ps.trailer_url, '') AS trailer_url,
        COALESCE(ps.anio_lanzamiento, 0) AS anio_lanzamiento,
        COALESCE(ps.duracion_minutos, 0) AS duracion_minutos,
        COALESCE(ps.temporadas, 0) AS temporadas,
        COALESCE(ps.episodios, 0) AS episodios,
        ps.estado,
        ps.activo,
        ps.destacado,
        CASE
            WHEN EXISTS (
                SELECT 1
                FROM favoritos_usuario fu
                WHERE fu.id_usuario = v_id_usuario
                AND fu.id_pelicula_serie = ps.id_pelicula_serie
            ) THEN 1
            ELSE 0
        END AS favorito
    FROM peliculas_series ps
    LEFT JOIN peliculas_series_generos psg
        ON ps.id_pelicula_serie = psg.id_pelicula_serie
    LEFT JOIN generos g
        ON psg.id_genero = g.id_genero
        AND g.activo = 1
    WHERE ps.activo = 1
    AND LOWER(ps.estado) = 'publicado'
    AND (
        v_id_genero = 0
        OR EXISTS (
            SELECT 1
            FROM peliculas_series_generos psg_filtro
            INNER JOIN generos g_filtro
                ON psg_filtro.id_genero = g_filtro.id_genero
            WHERE psg_filtro.id_pelicula_serie = ps.id_pelicula_serie
            AND psg_filtro.id_genero = v_id_genero
            AND g_filtro.activo = 1
        )
    )
    AND (
        v_tipo = ''
        OR v_tipo = 'Todos'
        OR ps.tipo = v_tipo
    )
    AND (
        p_anio IS NULL
        OR p_anio = 0
        OR ps.anio_lanzamiento = p_anio
    )
    GROUP BY
        ps.id_pelicula_serie,
        ps.titulo,
        ps.titulo_original,
        ps.descripcion,
        ps.tipo,
        ps.imagen_portada,
        ps.imagen_banner,
        ps.trailer_url,
        ps.anio_lanzamiento,
        ps.duracion_minutos,
        ps.temporadas,
        ps.episodios,
        ps.estado,
        ps.activo,
        ps.destacado,
        ps.fecha_actualizacion,
        ps.fecha_creacion
    ORDER BY
        CASE WHEN v_orden = 'az' THEN ps.titulo END ASC,
        CASE WHEN v_orden = 'anio' THEN ps.anio_lanzamiento END DESC,
        CASE WHEN v_orden = 'antiguos' THEN ps.fecha_creacion END ASC,
        ps.fecha_creacion DESC,
        ps.fecha_actualizacion DESC;
END $$

DROP PROCEDURE IF EXISTS sp_favoritos_usuario $$

CREATE PROCEDURE sp_favoritos_usuario(
    IN p_usuario VARCHAR(50),
    IN p_tipo VARCHAR(20),
    IN p_anio INT,
    IN p_orden VARCHAR(30)
)
BEGIN
    DECLARE v_id_usuario INT DEFAULT 0;
    DECLARE v_tipo VARCHAR(20) DEFAULT 'Todos';
    DECLARE v_orden VARCHAR(30) DEFAULT 'ultimos';

    SELECT COALESCE((
        SELECT id_usuario
        FROM usuarios
        WHERE usuario = p_usuario
        LIMIT 1
    ), 0) INTO v_id_usuario;

    SET v_tipo = TRIM(COALESCE(p_tipo, 'Todos'));
    SET v_orden = TRIM(COALESCE(p_orden, 'ultimos'));

    SELECT
        ps.id_pelicula_serie,
        ps.titulo,
        COALESCE(ps.titulo_original, '') AS titulo_original,
        COALESCE(ps.descripcion, '') AS descripcion,
        ps.tipo,
        COALESCE(GROUP_CONCAT(DISTINCT g.nombre_genero ORDER BY g.nombre_genero SEPARATOR ', '), 'Sin género') AS generos,
        COALESCE(ps.imagen_portada, '') AS imagen_portada,
        COALESCE(ps.imagen_banner, '') AS imagen_banner,
        COALESCE(ps.trailer_url, '') AS trailer_url,
        COALESCE(ps.anio_lanzamiento, 0) AS anio_lanzamiento,
        COALESCE(ps.duracion_minutos, 0) AS duracion_minutos,
        COALESCE(ps.temporadas, 0) AS temporadas,
        COALESCE(ps.episodios, 0) AS episodios,
        ps.estado,
        ps.activo,
        ps.destacado,
        fu.fecha_agregado,
        1 AS favorito
    FROM favoritos_usuario fu
    INNER JOIN peliculas_series ps
        ON fu.id_pelicula_serie = ps.id_pelicula_serie
    LEFT JOIN peliculas_series_generos psg
        ON ps.id_pelicula_serie = psg.id_pelicula_serie
    LEFT JOIN generos g
        ON psg.id_genero = g.id_genero
        AND g.activo = 1
    WHERE fu.id_usuario = v_id_usuario
    AND ps.activo = 1
    AND LOWER(ps.estado) = 'publicado'
    AND (
        v_tipo = ''
        OR v_tipo = 'Todos'
        OR ps.tipo = v_tipo
    )
    AND (
        p_anio IS NULL
        OR p_anio = 0
        OR ps.anio_lanzamiento = p_anio
    )
    GROUP BY
        ps.id_pelicula_serie,
        ps.titulo,
        ps.titulo_original,
        ps.descripcion,
        ps.tipo,
        ps.imagen_portada,
        ps.imagen_banner,
        ps.trailer_url,
        ps.anio_lanzamiento,
        ps.duracion_minutos,
        ps.temporadas,
        ps.episodios,
        ps.estado,
        ps.activo,
        ps.destacado,
        fu.fecha_agregado,
        ps.fecha_creacion
    ORDER BY
        CASE WHEN v_orden = 'az' THEN ps.titulo END ASC,
        CASE WHEN v_orden = 'anio' THEN ps.anio_lanzamiento END DESC,
        CASE WHEN v_orden = 'antiguos' THEN fu.fecha_agregado END ASC,
        fu.fecha_agregado DESC,
        ps.fecha_creacion DESC;
END $$

CREATE PROCEDURE sp_buscar_contenido_usuario(
    IN p_busqueda VARCHAR(150)
)
BEGIN
    SELECT
        ps.id_pelicula_serie,
        ps.titulo,
        COALESCE(ps.titulo_original, '') AS titulo_original,
        COALESCE(ps.descripcion, '') AS descripcion,
        ps.tipo,
        COALESCE(GROUP_CONCAT(g.nombre_genero ORDER BY g.nombre_genero SEPARATOR ', '), 'Sin género') AS generos,
        COALESCE(ps.imagen_portada, '') AS imagen_portada,
        COALESCE(ps.imagen_banner, '') AS imagen_banner,
        COALESCE(ps.trailer_url, '') AS trailer_url,
        COALESCE(ps.anio_lanzamiento, 0) AS anio_lanzamiento,
        COALESCE(ps.duracion_minutos, 0) AS duracion_minutos,
        COALESCE(ps.temporadas, 0) AS temporadas,
        COALESCE(ps.episodios, 0) AS episodios,
        ps.estado,
        ps.activo,
        ps.destacado,
        0 AS favorito
    FROM peliculas_series ps
    LEFT JOIN peliculas_series_generos psg
        ON ps.id_pelicula_serie = psg.id_pelicula_serie
    LEFT JOIN generos g
        ON psg.id_genero = g.id_genero
    WHERE ps.activo = 1
    AND LOWER(ps.estado) = 'publicado'
    AND TRIM(COALESCE(p_busqueda, '')) <> ''
    AND (
        ps.titulo LIKE CONCAT('%', p_busqueda, '%')
        OR ps.titulo_original LIKE CONCAT('%', p_busqueda, '%')
    )
    GROUP BY
        ps.id_pelicula_serie,
        ps.titulo,
        ps.titulo_original,
        ps.descripcion,
        ps.tipo,
        ps.imagen_portada,
        ps.imagen_banner,
        ps.trailer_url,
        ps.anio_lanzamiento,
        ps.duracion_minutos,
        ps.temporadas,
        ps.episodios,
        ps.estado,
        ps.activo,
        ps.destacado,
        ps.fecha_actualizacion,
        ps.fecha_creacion
    ORDER BY ps.titulo ASC
    LIMIT 8;
END $$

DROP PROCEDURE IF EXISTS sp_alternar_favorito_usuario $$

CREATE PROCEDURE sp_alternar_favorito_usuario(
    IN p_usuario VARCHAR(50),
    IN p_id_pelicula_serie INT
)
BEGIN
    DECLARE v_id_usuario INT DEFAULT 0;

    SELECT COALESCE((
        SELECT id_usuario
        FROM usuarios
        WHERE usuario = p_usuario
        AND activo = 1
        LIMIT 1
    ), 0) INTO v_id_usuario;

    IF v_id_usuario = 0 THEN
        SELECT 0 AS exito, 'Usuario no encontrado' AS mensaje, 0 AS favorito;
    ELSEIF NOT EXISTS (
        SELECT 1
        FROM peliculas_series
        WHERE id_pelicula_serie = p_id_pelicula_serie
        AND activo = 1
        AND LOWER(estado) = 'publicado'
    ) THEN
        SELECT 0 AS exito, 'Contenido no disponible' AS mensaje, 0 AS favorito;
    ELSEIF EXISTS (
        SELECT 1
        FROM favoritos_usuario
        WHERE id_usuario = v_id_usuario
        AND id_pelicula_serie = p_id_pelicula_serie
    ) THEN
        DELETE FROM favoritos_usuario
        WHERE id_usuario = v_id_usuario
        AND id_pelicula_serie = p_id_pelicula_serie;

        SELECT 1 AS exito, 'Contenido eliminado de favoritos' AS mensaje, 0 AS favorito;
    ELSE
        INSERT INTO favoritos_usuario(id_usuario, id_pelicula_serie)
        VALUES (v_id_usuario, p_id_pelicula_serie);

        SELECT 1 AS exito, 'Contenido agregado a favoritos' AS mensaje, 1 AS favorito;
    END IF;
END $$

DROP PROCEDURE IF EXISTS sp_listar_series_padre $$

CREATE PROCEDURE sp_listar_series_padre(
    IN p_excluir_id INT
)
BEGIN
    SELECT
        ps.id_pelicula_serie,
        ps.titulo,
        COALESCE(ps.anio_lanzamiento, 0) AS anio_lanzamiento
    FROM peliculas_series ps
    WHERE ps.activo = 1
    AND LOWER(ps.estado) = 'publicado'
    AND LOWER(ps.tipo) = 'serie'
    AND (p_excluir_id IS NULL OR ps.id_pelicula_serie <> p_excluir_id)
    ORDER BY ps.titulo ASC;
END $$

DROP PROCEDURE IF EXISTS sp_obtener_hijos_pelicula_serie $$

CREATE PROCEDURE sp_obtener_hijos_pelicula_serie(
    IN p_id_pelicula_serie INT
)
BEGIN
    SELECT
        ps.id_pelicula_serie,
        ps.titulo,
        COALESCE(ps.imagen_portada, '') AS imagen_portada,
        COALESCE(ps.anio_lanzamiento, 0) AS anio_lanzamiento,
        ps.numero_temporada,
        ps.tipo_relacion,
        COALESCE(ps.tipo, '') AS tipo
    FROM peliculas_series ps
    WHERE ps.serie_padre_id = p_id_pelicula_serie
    AND ps.activo = 1
    AND LOWER(ps.estado) = 'publicado'
    ORDER BY ps.numero_temporada ASC, ps.anio_lanzamiento ASC, ps.titulo ASC;
END $$

DELIMITER ;
