<?php

session_start();

require_once __DIR__ . '/Config/config.php';

if (isset($_SESSION['id_usuario']) && isset($_SESSION['rol'])) {
  
    if ($_SESSION['rol'] === 'usuario') {
        header("Location: Views/Usuario/inicio.php");
        exit;
    } elseif ($_SESSION['rol'] === 'administrador') {
        header("Location: Views/Administracion/panel.php");
        exit;
    }
} else {
    
    header("Location: Views/Usuario/IniciarSesion.php");
    exit;
}