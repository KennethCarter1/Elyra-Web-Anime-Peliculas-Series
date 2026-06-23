<?php
require_once '../../Models/Sesion.php';

$mensaje = '';

if (isset($_COOKIE[session_name()])) {
    session_start();
    $mensaje = Sesion::tomarMensaje('mensaje_error_login');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Assets/Css/Variables.css">
    <link rel="stylesheet" href="../../Assets/Css/Sesion.css">
    <link rel="stylesheet" href="../../Assets/Css/switch.css">
    <link rel="icon" type="image/x-icon" href="../../Assets/Images/logos/iconos/morado.ico" data-accent-favicon>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <title>Iniciar Sesion</title>
</head>
<body>
    <div class="contenedor-principal">
        <div class="switch">
            <label class="ui-switch">
            <input type="checkbox" id="toggle-theme">
            <div class="slider">
                <div class="circle"></div>
            </div>
            </label>
        </div>
        
    
        <div class="encabezado">
            <img src="../../Assets/Images/logos/logos/morado.png" alt="Logo" class="logo" data-accent-logo>

            <h1>ELYRA</h1>
            <p>Tu Mundo, Tu Historia</p>

            <h2>Iniciar Sesión</h2>
            <div class="mensaje-error"><?php if (!empty($mensaje)) { echo htmlspecialchars($mensaje); } ?></div>
        </div>

    <div class="formulario">
    <form method="POST" action="../../Controller/ControladorUsuario.php">

        <!-- Usuario -->
        <label for="usuario">Usuario</label>
        <div class="input-box">
            <i class="fa-solid fa-user"></i>
            <input type="text" name="usuario" placeholder="Ingrese su usuario" id="usuario" required>
        </div>

        <!-- Contraseña -->
        <label for="password">Contraseña</label>
        <div class="input-box">
            <i class="fa-solid fa-lock"></i>
            <input type="password" name="password" id="password" placeholder="Ingrese su contraseña" required>
            <button type="button" class="toggle-password" data-password-toggle="password" aria-label="Mostrar contraseña">
                <i class="fa-regular fa-eye"></i>
            </button>
        </div>

        <div class="opciones">
            <div class="recordar">
                <input type="checkbox" name="recordar" id="recordar">
                <label for="recordar">Recordarme</label>
            </div>

            <div class="olvidar">
                <a href="#">¿Olvidaste tu contraseña?</a>
            </div>
        </div>

        <!-- Botón login -->
        <button type="submit" name="login" value="1">Iniciar Sesión</button>

    </form>

    <div class="separador">
        <span>o</span>
    </div>

    <!-- Registro -->
    <button class="registro" type="button" onclick="location.href='Registrarse.php'">Registrarse</button>
</div>
<script src="../../Assets/Js/password-toggle.js"></script>
<script src="../../Assets/Js/dark-mode.js"></script>
</body>
</html>
