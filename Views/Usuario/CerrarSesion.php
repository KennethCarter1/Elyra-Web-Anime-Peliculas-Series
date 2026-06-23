<?php
session_start();
require_once '../../Models/Sesion.php';

Sesion::cerrarSesion();

header("Location: IniciarSesion.php");
exit;
?>
