<?php
session_start();
require_once '../Config/config.php';
require_once '../Models/Usuario.php';

$usuarioModelo = new Usuario($conexion);


function iniciar_sesion($usuario, $contrasena, $recordarme, $modelo) {
    $usuarioDB = $modelo->obtenerPorUsuario($usuario);

    if ($usuarioDB && password_verify($contrasena, $usuarioDB['contrasena_hash'])) {
  
        $_SESSION['id_usuario'] = $usuarioDB['id_usuario'];
        $_SESSION['rol'] = 'usuario';


        if ($recordarme) {
            setcookie('usuario_login', $usuarioDB['id_usuario'], time() + (86400 * 30), "/"); // 30 días
        }

        header("Location: ../Views/home/inicio.php");
        exit;
    } else {
        return "Usuario o contraseña incorrectos";
    }
}


function registrar_usuario($nombre, $usuario, $correo, $contrasena, $fechaNacimiento, $modelo) {
    try {
        $modelo->registrar($nombre, $usuario, $correo, $contrasena, $fechaNacimiento);
        return "Usuario registrado correctamente";
    } catch (Exception $e) {
        return $e->getMessage();
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $usuario = $_POST['usuario'];
        $contrasena = $_POST['contrasena'];
        $recordarme = isset($_POST['recordarme']);
        $error = iniciar_sesion($usuario, $contrasena, $recordarme, $usuarioModelo);
    }

    if (isset($_POST['registrarse'])) {
        $nombre = $_POST['nombre'];
        $usuario = $_POST['usuario'];
        $correo = $_POST['correo'];
        $contrasena = $_POST['contrasena'];
        $fechaNacimiento = $_POST['fechaNacimiento'];
        $mensaje = registrar_usuario($nombre, $usuario, $correo, $contrasena, $fechaNacimiento, $usuarioModelo);
    }
}