<?php
session_start();
require_once '../Config/config.php';
require_once '../Models/Contenido.php';

$contenidoModelo = new Contenido($conexion);


function agregar_contenido($titulo, $descripcion, $idGenero, $urlImagen, $modelo) {
    try {
        $modelo->agregar($titulo, $descripcion, $idGenero, $urlImagen);
        return "Contenido agregado correctamente";
    } catch (Exception $e) {
        return $e->getMessage();
    }
}


function editar_contenido($idContenido, $titulo, $descripcion, $idGenero, $urlImagen, $modelo) {
    try {
        $modelo->editar($idContenido, $titulo, $descripcion, $idGenero, $urlImagen);
        return "Contenido editado correctamente";
    } catch (Exception $e) {
        return $e->getMessage();
    }
}


function eliminar_contenido($idContenido, $modelo) {
    try {
        $modelo->eliminar($idContenido);
        return "Contenido eliminado correctamente";
    } catch (Exception $e) {
        return $e->getMessage();
    }
}


function listar_contenidos($modelo) {
    return $modelo->listar();
}


function obtener_contenido($idContenido, $modelo) {
    return $modelo->obtenerPorId($idContenido);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['agregar'])) {
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $idGenero = $_POST['idGenero'];
        $urlImagen = $_POST['urlImagen'];
        $mensaje = agregar_contenido($titulo, $descripcion, $idGenero, $urlImagen, $contenidoModelo);
    }

    if (isset($_POST['editar'])) {
        $idContenido = $_POST['idContenido'];
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $idGenero = $_POST['idGenero'];
        $urlImagen = $_POST['urlImagen'];
        $mensaje = editar_contenido($idContenido, $titulo, $descripcion, $idGenero, $urlImagen, $contenidoModelo);
    }

    if (isset($_POST['eliminar'])) {
        $idContenido = $_POST['idContenido'];
        $mensaje = eliminar_contenido($idContenido, $contenidoModelo);
    }
}