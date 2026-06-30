<?php
session_start();
require_once '../../Models/Navegacion.php';
http_response_code(404);

$datosError = Navegacion::datosError404($_SESSION, $_SERVER);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <base href="<?php echo htmlspecialchars($datosError['baseHref'], ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="../../Assets/Images/logos/iconos/morado.ico">
    <link rel="stylesheet" href="../../Assets/Css/Variables.css?v=vidrio-global-20260630">
    <link rel="stylesheet" href="../../Assets/Css/Parciales.css?v=vidrio-global-20260630">
    <link rel="stylesheet" href="../../Assets/Css/switch.css">
    <link rel="stylesheet" href="../../Assets/Css/Error404.css">
    <title>Error 404</title>
</head>
<body>
    <?php if ($datosError['usuarioAutenticado']) { ?>
        <?php include '../Parciales/header.php'; ?>
        <?php include '../Parciales/sidebar.php'; ?>
    <?php } ?>

    <main class="<?php echo htmlspecialchars($datosError['claseMain'], ENT_QUOTES, 'UTF-8'); ?>">
        <section class="contenido-error-404">
            <h1>404</h1>
            <h2>¡Ups! Esta página se perdió en otro mundo</h2>
            <p>La página que buscas no existe o ha sido movida. Pero no te preocupes, hay mucho más por descubrir.</p>

            <div class="acciones-error-404">
                <a href="<?php echo htmlspecialchars($datosError['urlInicio'], ENT_QUOTES, 'UTF-8'); ?>" class="btn-error-primario">
                    <i class="fa-solid fa-house"></i>
                    <span><?php echo htmlspecialchars($datosError['textoInicio'], ENT_QUOTES, 'UTF-8'); ?></span>
                </a>

                <a href="<?php echo htmlspecialchars($datosError['urlSecundario'], ENT_QUOTES, 'UTF-8'); ?>" class="btn-error-secundario">
                    <i class="<?php echo htmlspecialchars($datosError['iconoSecundario'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                    <span><?php echo htmlspecialchars($datosError['textoSecundario'], ENT_QUOTES, 'UTF-8'); ?></span>
                </a>
            </div>

            <div class="nota-error-404">
                <i class="fa-solid fa-star"></i>
                <span>Incluso los mejores héroes se pierden a veces.</span>
            </div>
        </section>
    </main>

    <script src="../../Assets/Js/dark-mode.js?v=vidrio-global-20260630"></script>
</body>
</html>
