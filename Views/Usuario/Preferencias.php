<?php
session_start();
require_once '../../Api/soap/ClienteSOAP.php';
require_once '../../Models/Sesion.php';
require_once '../../Models/Navegacion.php';
require_once '../../Models/Genero.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: IniciarSesion.php');
    exit;
}

$mensajePreferencias = Sesion::tomarMensaje('mensaje_preferencias');
$tipoMensajePreferencias = Sesion::tomarMensaje('tipo_mensaje_preferencias');

$clienteSOAP = new ClienteSOAP();

try {
    $generos = Genero::normalizarListado($clienteSOAP->listarGeneros());
} catch (Exception $e) {
    Navegacion::redirigirErrorBaseDatosVista('Preferencias.php', $_SERVER);
}
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
    <link rel="stylesheet" href="../../Assets/Css/Preferencias.css">
    <link rel="stylesheet" href="../../Assets/Css/switch.css">
    <title>Preferencias</title>
</head>
<body>
    <?php include '../Parciales/header.php'; ?>
    <?php include '../Parciales/sidebar.php'; ?>

    <main class="contenido-principal">
       <div class="preferencias-header">
            <h2>Preferencias</h2>
            <p>Personaliza tu experiencia en Elyra</p>
        </div> 

        <div class="contenedor-preferencias">
            <h3>Géneros favoritos</h3>
            <p>Selecciona tus géneros favoritos (3 o más).</p>

            <div class="mensaje-preferencias <?php echo htmlspecialchars($tipoMensajePreferencias, ENT_QUOTES, 'UTF-8'); ?>">
                <?php if (!empty($mensajePreferencias)) { echo htmlspecialchars($mensajePreferencias, ENT_QUOTES, 'UTF-8'); } ?>
            </div>

            <form method="POST" action="../../Controller/ControladorUsuario.php">
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

                <div class="acciones-preferencias">
                    <p>Puede seleccionar varios géneros</p>
                    <button type="submit" name="ActualizarPreferencias" value="1">Actualizar</button>
                </div>
            </form>
        </div>

    </main>

    <script src="../../Assets/Js/dark-mode.js"></script>
    
</body>
</html>
