<?php
session_start();
require_once '../Config/config.php';
require_once '../Models/Usuario.php';
require_once '../Models/Genero.php';
require_once '../Models/Contenido.php';
require_once '../Models/Calificacion.php';
require_once '../Models/Historial.php';

$usuarioModelo = new Usuario($conexion);
$generoModelo = new Genero($conexion);
$contenidoModelo = new Contenido($conexion);
$calificacionModelo = new Calificacion($conexion);
$historialModelo = new Historial($conexion);



function listar_usuarios($modelo) {
    $stmt = $modelo->conexion->query("SELECT id_usuario, nombre, usuario, correo, rol, fecha_creacion FROM usuarios");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function cambiar_rol($idUsuario, $rol, $modelo) {
    $stmt = $modelo->conexion->prepare("UPDATE usuarios SET rol = :rol WHERE id_usuario = :idUsuario");
    $stmt->bindParam(':rol', $rol);
    $stmt->bindParam(':idUsuario', $idUsuario);
    $stmt->execute();
}


function agregar_genero($nombreGenero, $modelo) {
    return $modelo->agregar($nombreGenero);
}

function editar_genero($idGenero, $nombreGenero, $modelo) {
    return $modelo->editar($idGenero, $nombreGenero);
}

function eliminar_genero($idGenero, $modelo) {
    return $modelo->eliminar($idGenero);
}

function listar_generos($modelo) {
    return $modelo->listar();
}

function generos_mas_populares($conexion) {
    $sql = "SELECT g.nombre_genero, COUNT(p.id_pelicula) AS cantidad 
            FROM generos g
            LEFT JOIN peliculas_series p ON g.id_genero = p.id_genero
            GROUP BY g.id_genero
            ORDER BY cantidad DESC";
    $stmt = $conexion->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function usuarios_mas_activos($conexion) {
    $sql = "SELECT u.nombre, u.usuario, COUNT(h.id_historial) AS vistas
            FROM usuarios u
            LEFT JOIN historial_vistas h ON u.id_usuario = h.id_usuario
            GROUP BY u.id_usuario
            ORDER BY vistas DESC";
    $stmt = $conexion->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}