<?php
session_start();

require_once '../../Config/config.php';
require_once '../../Models/Usuario.php';
$modeloUsuario = new Usuario($conexion);

if(!isset($_SESSION['usuario'])) {
    header('Location: IniciarSesion.php');
    exit;
}

$datosUsuario = $modeloUsuario->obtenerDatos($_SESSION['usuario']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="../../Assets/Images/logos/iconos/morado.ico">
    <link rel="stylesheet" href="../../Assets/Css/Variables.css">
    <link rel="stylesheet" href="../../Assets/Css/Parciales.css">
    <link rel="stylesheet" href="../../Assets/Css/Perfil.css">
    <link rel="stylesheet" href="../../Assets/Css/switch.css">
    <title>Perfil</title>
</head>
<body>
    <?php include '../Parciales/header.php'; ?>
    <?php include '../Parciales/sidebar.php'; ?>

    <main class="contenido-principal">
    
    <div class="header-perfil">
        <h1>Mi Perfil</h1>
        <p>Informacion de tu cuenta</p>
    </div>

    <div class="usuario-perfil">
        <div class="logo-Perfil">
            <span class="logo-usuario-Perfil"><?php echo $modeloUsuario->mostrarIniciales($datosUsuario); ?></span>
            <div class="datos-usuario-perfil">
                <h3><?php echo $modeloUsuario->mostrarDato($datosUsuario, 'nombre'); ?></h3>
                <p>@<?php echo $modeloUsuario->mostrarDato($datosUsuario, 'usuario'); ?></p>
            </div>
        </div>
    </div>

    <div class="informacion-Perfil">
        <h2>Información personal</h2>

        <div class="fila-informacion">
            <i class="fa-regular fa-user"></i>
            <span>Nombre</span>
            <strong><?php echo $modeloUsuario->mostrarDato($datosUsuario, 'nombre'); ?></strong>
        </div>

        <div class="fila-informacion">
            <i class="fa-solid fa-at"></i>
            <span>Usuario</span>
            <strong><?php echo $modeloUsuario->mostrarDato($datosUsuario, 'usuario'); ?></strong>
        </div>

        <div class="fila-informacion">
            <i class="fa-regular fa-envelope"></i>
            <span>Correo electrónico</span>
            <strong><?php echo $modeloUsuario->mostrarDato($datosUsuario, 'correo'); ?></strong>
        </div>

        <div class="fila-informacion">
            <i class="fa-regular fa-calendar"></i>
            <span>Fecha de nacimiento</span>
            <strong><?php echo $modeloUsuario->mostrarDato($datosUsuario, 'fecha_nacimiento'); ?></strong>
        </div>

        <div class="fila-informacion">
            <i class="fa-solid fa-venus-mars"></i>
            <span>Género</span>
            <strong><?php echo $modeloUsuario->mostrarGenero($datosUsuario); ?></strong>
        </div>

        <div class="fila-informacion">
            <i class="fa-solid fa-shield-halved"></i>
            <span>Rol</span>
            <strong><?php echo $modeloUsuario->mostrarDato($datosUsuario, 'rol'); ?></strong>
        </div>

        <div class="fila-informacion">
            <i class="fa-regular fa-clock"></i>
            <span>Fecha de creación de la cuenta</span>
            <strong><?php echo $modeloUsuario->mostrarFechaCreacion($datosUsuario); ?></strong>
        </div>
    </div>


        
    </main>

    <script src="../../Assets/Js/dark-mode.js"></script>
    
</body>
</html>
