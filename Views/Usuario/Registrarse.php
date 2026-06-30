<?php
require_once __DIR__ . '/../../Models/Seguridad.php';
session_start();
require_once '../../Api/soap/ClienteSOAP.php';
require_once '../../Models/Sesion.php';
require_once '../../Models/Navegacion.php';
require_once '../../Models/Genero.php';

$mensaje = Sesion::tomarMensaje('mensaje_error');

$clienteSOAP = new ClienteSOAP();
try {
    $generos = Genero::normalizarListado($clienteSOAP->listarGeneros());
} catch (Exception $e) {
    Navegacion::redirigirErrorBaseDatosVista('/elyra/registro', $_SERVER);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/elyra/Views/Usuario/">
    <link rel="stylesheet" href="../../Assets/Css/Variables.css?v=vidrio-global-20260630">
    <link rel="stylesheet" href="../../Assets/Css/Sesion.css?v=vidrio-global-20260630">
    <link rel="stylesheet" href="../../Assets/Css/switch.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="../../Assets/Images/logos/iconos/morado.ico" data-accent-favicon>
    <title>Registrarse</title>
</head>
<body>
    <div class="contenedor-principal2">
            <div class="switch">
                <label class="ui-switch">
                <input type="checkbox" id="toggle-theme">
                <div class="slider">
                    <div class="circle"></div>
                </div>
                </label>
            </div>

            <div class="encabezado">
            <img src="../../Assets/Images/logos/logos/morado.webp" alt="Logo" class="logo" data-accent-logo loading="lazy" decoding="async">

            <h1>ELYRA</h1>
            <p>Tu Mundo, Tu Historia</p>

            <h2>Registrarse</h2>
            <div class="mensaje-error"><?php if (!empty($mensaje)) { echo htmlspecialchars($mensaje); } ?></div>
        </div>

        <div class="formulario">
        <form method="POST"  action="../../Controller/ControladorUsuario.php">
                <?php echo Seguridad::campoCsrf(); ?>
        <!-- Usuario -->
        <label for="usuario">Usuario</label>
        <div class="input-box">
            <i class="fa-solid fa-user"></i>
            <input type="text" name="usuario" placeholder="Ingrese su usuario" id="usuario" pattern="\S+" title="El usuario no debe tener espacios. Ejemplo: kennethcarter" required>
        </div>

        <!-- Correo Electrónico -->
        <label for="email">Correo Electrónico</label>
        <div class="input-box">
            <i class="fa-solid fa-envelope"></i>
            <input type="email" name="email" placeholder="Ingrese su correo electrónico" id="email" required>
        </div>

        <!-- Contraseña -->
        <label for="password">Contraseña</label>
        <div class="input-box">
            <i class="fa-solid fa-lock"></i>
            <input type="password" name="password" id="password" placeholder="Ingrese su contraseña" minlength="15" pattern="(?=.*[A-ZÁÉÍÓÚÑ])(?=.*[a-záéíóúñ])(?=.*[0-9])(?=.*[^A-Za-z0-9ÁÉÍÓÚáéíóúÑñ]).{15,}" title="Debe tener mínimo 15 caracteres e incluir mayúsculas, minúsculas, números y caracteres especiales" required>
            <button type="button" class="toggle-password" data-password-toggle="password" aria-label="Mostrar contraseña">
                <i class="fa-regular fa-eye"></i>
            </button>
        </div>

        <!-- Confirmar Contraseña -->
        <label for="confirm_password">Confirmar Contraseña</label>
        <div class="input-box">
            <i class="fa-solid fa-lock"></i>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirme su contraseña" minlength="15" pattern="(?=.*[A-ZÁÉÍÓÚÑ])(?=.*[a-záéíóúñ])(?=.*[0-9])(?=.*[^A-Za-z0-9ÁÉÍÓÚáéíóúÑñ]).{15,}" title="Debe tener mínimo 15 caracteres e incluir mayúsculas, minúsculas, números y caracteres especiales" required>
            <button type="button" class="toggle-password" data-password-toggle="confirm_password" aria-label="Mostrar contraseña">
                <i class="fa-regular fa-eye"></i>
            </button>
        </div>

        

        <div class="fecha-nacimiento">

            <div class="campo">
                <label>Día</label>
                <select id="dia" name="dia" required>
                <option value="" disabled selected>Día</option>
                </select>
                
            </div>

            <div class="campo">
                <label>Mes</label>
                <select id="mes" name="mes" required>
                <option value="" disabled selected>Mes</option>
                <option value="0">Enero</option>
                <option value="1">Febrero</option>
                <option value="2">Marzo</option>
                <option value="3">Abril</option>
                <option value="4">Mayo</option>
                <option value="5">Junio</option>
                <option value="6">Julio</option>
                <option value="7">Agosto</option>
                <option value="8">Septiembre</option>
                <option value="9">Octubre</option>
                <option value="10">Noviembre</option>
                <option value="11">Diciembre</option>
                </select>
            </div>

            <div class="campo">
                <label>Año</label>

                <select id="anio" name="anio" required>
                <option value="" disabled selected>Año</option>
                </select>
                
            </div>

            </div>

        <p>Géneros Favoritos <strong>(Seleccione al menos 3)</strong></p>

        <div class="generos">
        <?php if (!empty($generos)) { ?>

            <?php foreach ($generos as $nombreGenero) { ?>
                <label class="card">
                    <input type="checkbox" name="generos[]" value="<?php echo htmlspecialchars($nombreGenero, ENT_QUOTES, 'UTF-8'); ?>">
                    <span><?php echo htmlspecialchars($nombreGenero, ENT_QUOTES, 'UTF-8'); ?></span>
                </label>
            <?php } ?>

        <?php } else { ?>

            <p>No hay géneros disponibles</p>

        <?php } ?>
        </div>
        <!-- Botón login -->
        <button type="submit" name="Registrarse" value="1">Registrarse</button>

    </form>

    <div class="separador">
        <span>o</span>
    </div>

    <div class="iniciar-sesion">
        <a href="/elyra/login">¿Ya tienes una cuenta? Inicia Sesión</a>
    </div>
</div>
</div>
<script src="../../Assets/Js/date.js"></script>
<script src="../../Assets/Js/password-toggle.js"></script>
<script src="../../Assets/Js/dark-mode.js?v=vidrio-global-20260630"></script>
</body>
</html>
