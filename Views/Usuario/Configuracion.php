<?php
require_once __DIR__ . '/../../Models/Seguridad.php';
session_start();
require_once '../../Models/Sesion.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: /elyra/login');
    exit;
}

require_once '../../Config/config.php';
require_once '../../Models/Usuario.php';
$modeloUsuario = new Usuario($conexion);
$datosUsuario = $modeloUsuario->obtenerDatos($_SESSION['usuario']);

$mensajeConfiguracion = Sesion::tomarMensaje('mensaje_configuracion');
$tipoMensajeConfiguracion = Sesion::tomarMensaje('tipo_mensaje_configuracion', 'error');

$fechaNacimientoEditar = $modeloUsuario->obtenerFechaNacimientoEditar($datosUsuario);
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
    <link rel="stylesheet" href="../../Assets/Css/Configuracion.css?v=vidrio-global-20260630">
    <link rel="stylesheet" href="../../Assets/Css/switch.css">
    <title>Configuración</title>
</head>
<body>
    <?php include '../Parciales/header.php'; ?>
    <?php include '../Parciales/sidebar.php'; ?>

    <main class="contenido-principal">

    <div class="header-configuracion">
        <h2>Configuración</h2>
        <p>Administra los ajustes de tu cuenta.</p>
    </div>

    <?php if ($mensajeConfiguracion !== '') { ?>
        <div class="mensaje-configuracion mensaje-<?php echo htmlspecialchars($tipoMensajeConfiguracion); ?>">
            <?php echo htmlspecialchars($mensajeConfiguracion); ?>
        </div>
    <?php } ?>

    <div class="apariencia-perfil">
        <div class="titulo-apariencia">
            <div class="logo-apariencia">
                    <i class="fa-solid fa-palette"></i>
            </div>

            <div class="titulo-apariencia-datos">
                <h3>Apariencia</h3>
                <p>Personaliza como se ve la aplicacion.</p>
            </div>
            
        </div>

        <div class="tema-configuracion">
            <p>Tema</p>

            <div class="opciones-tema">
                <button type="button" class="opcion-tema" data-theme-option="light">
                    <i class="fa-regular fa-sun"></i>
                    <span>Claro</span>
                    <i class="fa-solid fa-circle-check check-tema"></i>
                </button>

                <button type="button" class="opcion-tema" data-theme-option="dark">
                    <i class="fa-regular fa-moon"></i>
                    <span>Oscuro</span>
                    <i class="fa-solid fa-circle-check check-tema"></i>
                </button>
            </div>
        </div>

        <div class="acento-configuracion">
            <p>Acento de color</p>

            <div class="opciones-acento">
                <button type="button" class="color-acento color-morado" data-accent="morado" aria-label="Morado"></button>
                <button type="button" class="color-acento color-azul" data-accent="azul" aria-label="Azul"></button>
                <button type="button" class="color-acento color-verde" data-accent="verde" aria-label="Verde"></button>
                <button type="button" class="color-acento color-rosa" data-accent="rosa" aria-label="Rosa"></button>
                <button type="button" class="color-acento color-naranja" data-accent="naranja" aria-label="Naranja"></button>
                <button type="button" class="color-acento color-cian" data-accent="cian" aria-label="Cian"></button>
            </div>
        </div>

        <div class="vidrio-configuracion">
            <p>Efecto vidrio</p>

            <label class="control-vidrio-configuracion" for="toggle-glass-effect">
                <span class="texto-vidrio-configuracion">
                    <strong>Usar efecto vidrio</strong>
                    <small>Desactívalo si prefieres una navegación más ligera.</small>
                </span>
                <input type="checkbox" id="toggle-glass-effect" data-glass-toggle>
                <span class="switch-vidrio-configuracion" aria-hidden="true">
                    <span></span>
                </span>
            </label>
        </div>

    </div>

    <div class="seguridad-configuracion">
        <div class="titulo-seguridad">
            <div class="logo-seguridad">
                <i class="fa-solid fa-lock"></i>
            </div>

            <div class="titulo-seguridad-datos">
                <h3>Cambiar contraseña</h3>
                <p>Cambia tu contraseña para mantener tu cuenta segura.</p>
            </div>
        </div>

        <div class="accion-seguridad">
            <div class="texto-accion-seguridad">
                <h4>Cambiar contraseña</h4>
                <p>Te recomendamos usar una contraseña segura que no uses en otros sitios.</p>
            </div>

            <a href="CambiarContrasena.php" class="btn-cambiar-contrasena">
                <i class="fa-solid fa-lock"></i>
                <span>Cambiar contraseña</span>
            </a>
        </div>
    </div>

    <div class="editar-perfil">
        <div class="titulo-editar-perfil">
            <div class="icono-editar-perfil">
                <i class="fa-regular fa-user"></i>
            </div>
            <div>
                <h2>Editar perfil</h2>
                <p>Actualiza tu información personal.</p>
            </div>
        </div>

        <form class="form-editar-perfil" method="POST" action="../../Controller/ControladorUsuario.php">
                <?php echo Seguridad::campoCsrf(); ?>
            <div class="campo-editar-perfil">
                <label for="nombre_editar">Nombre</label>
                <input type="text" id="nombre_editar" name="nombre" value="<?php echo $modeloUsuario->valorEditar($datosUsuario, 'nombre'); ?>" required>
            </div>

            <div class="campo-editar-perfil">
                <label for="usuario_editar">Nombre de usuario</label>
                <input type="text" id="usuario_editar" name="usuario" value="<?php echo $modeloUsuario->valorEditar($datosUsuario, 'usuario'); ?>" pattern="\S+" title="El usuario no debe tener espacios. Ejemplo: kennethcarter" required>
            </div>

            <div class="campo-editar-perfil">
                <label for="correo_editar">Correo electrónico</label>
                <input type="email" id="correo_editar" name="correo" value="<?php echo $modeloUsuario->valorEditar($datosUsuario, 'correo'); ?>" required>
            </div>

            <div class="campo-editar-perfil campo-fecha-editar">
                <label>Fecha de nacimiento</label>
                <div class="fecha-nacimiento">
                    <div class="campo">
                        <label for="dia">Día</label>
                        <select id="dia" name="dia" data-valor="<?php echo $fechaNacimientoEditar['dia']; ?>" required>
                            <option value="" disabled selected>Día</option>
                        </select>
                    </div>

                    <div class="campo">
                        <label for="mes">Mes</label>
                        <select id="mes" name="mes" data-valor="<?php echo $fechaNacimientoEditar['mes']; ?>" required>
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
                        <label for="anio">Año</label>
                        <select id="anio" name="anio" data-valor="<?php echo $fechaNacimientoEditar['anio']; ?>" required>
                            <option value="" disabled selected>Año</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="campo-editar-perfil campo-genero-editar">
                <label for="genero_editar">Género</label>
                <select id="genero_editar" name="genero" required>
                    <option value="" disabled<?php echo $modeloUsuario->seleccionarOpcionVacia($datosUsuario, 'genero'); ?>>Selecciona tu género</option>
                    <option value="masculino"<?php echo $modeloUsuario->seleccionarOpcion($datosUsuario, 'genero', 'masculino'); ?>>Masculino</option>
                    <option value="femenino"<?php echo $modeloUsuario->seleccionarOpcion($datosUsuario, 'genero', 'femenino'); ?>>Femenino</option>
                    <option value="otaku"<?php echo $modeloUsuario->seleccionarOpcion($datosUsuario, 'genero', 'otaku'); ?>>Otaku</option>
                </select>
            </div>

            <div class="acciones-editar-perfil">
                <button type="button" class="btn-cancelar-perfil" data-restaurar-formulario="editar-perfil">Cancelar</button>
                <button type="submit" name="ActualizarUsuario" value="1" class="btn-guardar-perfil">Guardar cambios</button>
            </div>
        </form>
    </div>



        
    </main>

    <script src="../../Assets/Js/date.js"></script>
    <script src="../../Assets/Js/dark-mode.js?v=vidrio-global-20260630"></script>
    
</body>
</html>
