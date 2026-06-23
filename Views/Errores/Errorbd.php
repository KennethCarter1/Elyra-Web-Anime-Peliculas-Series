<?php
session_start();
require_once '../../Models/Navegacion.php';

$datosError = Navegacion::datosErrorBaseDatos($_SESSION, $_GET, $_SERVER);
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
    <link rel="stylesheet" href="../../Assets/Css/switch.css">
    <link rel="stylesheet" href="../../Assets/Css/Errorbd.css">
    <title>Error de Base de Datos</title>
</head>
<body>
    <?php if ($datosError['usuarioAutenticado']) { ?>
        <?php include '../Parciales/header.php'; ?>
        <?php include '../Parciales/sidebar.php'; ?>
    <?php } ?>

    <main class="<?php echo htmlspecialchars($datosError['claseMain'], ENT_QUOTES, 'UTF-8'); ?>">
        <section class="contenido-bd-bd">
            <div class="icono-bd-error">
                <i class="fa-solid fa-database icono-base-datos"></i>
                <i class="fa-solid fa-circle-exclamation icono-alerta-bd"></i>
            </div>

            <h2>¡Ups! No podemos conectar con la base de datos</h2>
            <p>Estamos teniendo problemas técnicos para cargar la información. Nuestro equipo ya fue notificado y está trabajando en ello.</p>

            <div class="acciones-bd-bd">
                <a href="<?php echo htmlspecialchars($datosError['urlInicio'], ENT_QUOTES, 'UTF-8'); ?>" class="btn-bd-primario">
                    <i class="fa-solid fa-house"></i>
                    <span>Volver al inicio</span>
                </a>

                <a href="<?php echo htmlspecialchars($datosError['urlReintentar'], ENT_QUOTES, 'UTF-8'); ?>" class="btn-bd-secundario">
                    <i class="fas fa-sync-alt"></i>
                    <span>Reintentar</span>
                </a>
            </div>

            <div class="nota-bd-bd">
                <i class="fa-solid fa-star"></i>
                <span>A veces hasta los mejores sistemas necesitan un pequeño descanso.</span>
            </div>
        </section>
    </main>

    <script src="../../Assets/Js/dark-mode.js"></script>
</body>
</html>
