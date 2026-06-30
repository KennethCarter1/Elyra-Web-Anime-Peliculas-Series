<?php

session_start();

require_once __DIR__ . '/Config/config.php';

if (isset($_SESSION['id_usuario']) && isset($_SESSION['rol'])) {
  
    if ($_SESSION['rol'] === 'usuario') {
        header("Location: /elyra/inicio");
        exit;
    } elseif ($_SESSION['rol'] === 'administrador') {
        header("Location: /elyra/admin");
        exit;
    }
} else {
    
    header("Location: /elyra/login");
    exit;
}
