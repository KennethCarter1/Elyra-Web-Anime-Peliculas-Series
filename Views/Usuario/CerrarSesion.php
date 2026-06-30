<?php
session_start();
require_once '../../Models/Sesion.php';

Sesion::cerrarSesion();

header("Location: /elyra/login");
exit;
?>
