<?php
require_once __DIR__ . '/../../Models/Seguridad.php';
session_start();
require_once '../../Models/Sesion.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: /elyra/login');
    exit;
}

$mensajeCambiarContrasena = Sesion::tomarMensaje('mensaje_cambiar_contrasena');
$tipoMensajeCambiarContrasena = Sesion::tomarMensaje('tipo_mensaje_cambiar_contrasena', 'error');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/elyra/Views/Usuario/">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="../../Assets/Images/logos/iconos/morado.ico">
    <link rel="stylesheet" href="../../Assets/Css/Variables.css?v=vidrio-global-20260630">
    <link rel="stylesheet" href="../../Assets/Css/Parciales.css?v=vidrio-global-20260630">
    <link rel="stylesheet" href="../../Assets/Css/CambiarContrasena.css">
    <link rel="stylesheet" href="../../Assets/Css/switch.css">
    <title>Cambiar Contraseña</title>
</head>
<body>
    <?php include '../Parciales/header.php'; ?>
    <?php include '../Parciales/sidebar.php'; ?>

    <main class="contenido-principal">
        <section class="contenedor-cambiar-contrasena">
            <div class="encabezado-cambiar-contrasena">
                <div class="icono-cambiar-contrasena">
                    <i class="fa-solid fa-lock"></i>
                </div>

                <div class="titulo-cambiar-contrasena">
                    <h2>Cambiar contraseña</h2>
                    <p>Cambia tu contraseña para mantener tu cuenta segura.</p>
                </div>
            </div>

            <?php if ($mensajeCambiarContrasena !== '') { ?>
                <div class="mensaje-cambiar-contrasena mensaje-<?php echo htmlspecialchars($tipoMensajeCambiarContrasena); ?>">
                    <?php echo htmlspecialchars($mensajeCambiarContrasena); ?>
                </div>
            <?php } ?>

            <form class="form-cambiar-contrasena" method="POST" action="../../Controller/ControladorUsuario.php">
                <?php echo Seguridad::campoCsrf(); ?>
                <div class="campo-contrasena">
                    <label for="contrasena_actual">Contraseña actual</label>
                    <div class="input-contrasena">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="contrasena_actual" name="contrasena_actual" placeholder="Ingresa tu contraseña actual" required>
                        <button type="button" class="toggle-password-cuenta" data-password-toggle="contrasena_actual" aria-label="Mostrar contraseña">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <p>Ingresa la contraseña que utilizas actualmente.</p>
                </div>

                <div class="campo-contrasena">
                    <label for="nueva_contrasena">Nueva contraseña</label>
                    <div class="input-contrasena">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="nueva_contrasena" name="nueva_contrasena" placeholder="Ingresa tu nueva contraseña" minlength="15" pattern="(?=.*[A-ZÁÉÍÓÚÑ])(?=.*[a-záéíóúñ])(?=.*[0-9])(?=.*[^A-Za-z0-9ÁÉÍÓÚáéíóúÑñ]).{15,}" title="Debe tener mínimo 15 caracteres e incluir mayúsculas, minúsculas, números y caracteres especiales" required>
                        <button type="button" class="toggle-password-cuenta" data-password-toggle="nueva_contrasena" aria-label="Mostrar contraseña">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <p>Debe tener mínimo 15 caracteres.</p>
                </div>

                <div class="campo-contrasena">
                    <label for="confirmar_contrasena">Confirmar nueva contraseña</label>
                    <div class="input-contrasena">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" placeholder="Vuelve a ingresar tu nueva contraseña" minlength="15" pattern="(?=.*[A-ZÁÉÍÓÚÑ])(?=.*[a-záéíóúñ])(?=.*[0-9])(?=.*[^A-Za-z0-9ÁÉÍÓÚáéíóúÑñ]).{15,}" title="Debe tener mínimo 15 caracteres e incluir mayúsculas, minúsculas, números y caracteres especiales" required>
                        <button type="button" class="toggle-password-cuenta" data-password-toggle="confirmar_contrasena" aria-label="Mostrar contraseña">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <p>Las contraseñas deben coincidir.</p>
                </div>

                <div class="requisitos-contrasena">
                    <h3>Tu nueva contraseña debe incluir:</h3>

                    <ul>
                        <li data-requisito="longitud"><i class="fa-regular fa-circle"></i><span>Al menos 15 caracteres</span></li>
                        <li data-requisito="mayuscula"><i class="fa-regular fa-circle"></i><span>Una letra mayúscula</span></li>
                        <li data-requisito="minuscula"><i class="fa-regular fa-circle"></i><span>Una letra minúscula</span></li>
                        <li data-requisito="numero"><i class="fa-regular fa-circle"></i><span>Un número</span></li>
                        <li data-requisito="especial"><i class="fa-regular fa-circle"></i><span>Un carácter especial</span></li>
                    </ul>
                </div>

                <div class="acciones-cambiar-contrasena">
                    <a href="Configuracion.php" class="btn-cancelar-contrasena">Cancelar</a>
                    <button type="submit" name="CambiarContrasena" value="1" class="btn-actualizar-contrasena">Actualizar contraseña</button>
                </div>
            </form>
        </section>
    </main>

    <script src="../../Assets/Js/password-toggle.js"></script>
    <script src="../../Assets/Js/password-requisitos.js"></script>
    <script src="../../Assets/Js/dark-mode.js?v=vidrio-global-20260630"></script>
</body>
</html>
