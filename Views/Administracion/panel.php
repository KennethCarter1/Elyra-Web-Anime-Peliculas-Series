<?php
session_start();
require_once '../../Models/Panel.php';
require_once '../../Models/Navegacion.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: ../Usuario/IniciarSesion.php');
    exit;
}

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    header('Location: ../Usuario/inicio.php');
    exit;
}

require_once '../../Api/soap/ClienteSOAP.php';

$nombrePanel = Navegacion::nombreUsuario($_SESSION);
$resumenPanel = Panel::resumenInicial();
$actividadPanel = Panel::actividadInicial();
$ultimoContenidoPanel = Panel::ultimoContenidoInicial();

try {
    $clienteSOAP = new ClienteSOAP();
    $resumenPanel = $clienteSOAP->resumenPanelAdministrador();
    $actividadPanel = Panel::normalizarActividad($clienteSOAP->actividadRecientePanel());
    $ultimoContenidoPanel = Panel::normalizarUltimoContenido($clienteSOAP->ultimoContenidoAgregadoPanel());
} catch (Exception $e) {
    Navegacion::redirigirErrorBaseDatosVista('../Administracion/panel.php', $_SERVER);
}

$tarjetasResumen = Panel::tarjetasResumen($resumenPanel);
$accionesRapidas = Panel::accionesRapidas();

$tipoSidebar = 'administracion';
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
    <link rel="stylesheet" href="../../Assets/Css/Panel.css">
    <link rel="stylesheet" href="../../Assets/Css/switch.css">
    <title>Panel</title>
</head>
<body>
    <?php include '../Parciales/header.php'; ?>
    <?php include '../Parciales/sidebar.php'; ?>

    <main class="contenido-principal panel-administracion">
        <section class="encabezado-panel">
            <h2>¡Bienvenido de vuelta, <?php echo htmlspecialchars($nombrePanel, ENT_QUOTES, 'UTF-8'); ?>!</h2>
            <p>Aquí tienes un resumen de la actividad de tu plataforma.</p>
        </section>

        <section class="resumen-panel">
            <?php foreach ($tarjetasResumen as $tarjeta) { ?>
                <article class="tarjeta-resumen tarjeta-<?php echo htmlspecialchars($tarjeta['color'], ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="tarjeta-resumen-superior">
                        <span class="icono-resumen">
                            <i class="<?php echo htmlspecialchars($tarjeta['icono'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                        </span>
                        <h3><?php echo htmlspecialchars($tarjeta['titulo'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    </div>

                    <strong><?php echo Panel::formatearNumero($tarjeta['valor']); ?></strong>

                    <div class="tarjeta-resumen-pie">
                        <span>Actual</span>
                        <small><?php echo htmlspecialchars($tarjeta['texto'], ENT_QUOTES, 'UTF-8'); ?></small>
                    </div>
                </article>
            <?php } ?>
        </section>

        <section class="panel-medio-pagina">
            <article class="panel-medio-card gestion-rapida-panel">
                <h3>Gestión rápida</h3>

                <div class="lista-acciones-rapidas">
                    <?php foreach ($accionesRapidas as $accionRapida) { ?>
                        <a href="<?php echo htmlspecialchars($accionRapida['url'], ENT_QUOTES, 'UTF-8'); ?>" class="accion-rapida">
                            <span class="icono-accion-rapida">
                                <i class="<?php echo htmlspecialchars($accionRapida['icono'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                            </span>
                            <strong><?php echo htmlspecialchars($accionRapida['texto'], ENT_QUOTES, 'UTF-8'); ?></strong>
                            <i class="fa-solid fa-chevron-right flecha-accion"></i>
                        </a>
                    <?php } ?>
                </div>
            </article>

            <article class="panel-medio-card actividad-reciente-panel">
                <div class="panel-card-encabezado">
                    <h3>Actividad reciente</h3>
                    <a href="reportes-estadisticas.php">Ver todo</a>
                </div>

                <div class="lista-actividad-reciente">
                    <?php if (!empty($actividadPanel)) { ?>
                        <?php foreach ($actividadPanel as $actividad) { ?>
                            <div class="item-actividad">
                                <span class="icono-actividad">
                                    <?php if ($actividad['imagenUrl'] !== '') { ?>
                                        <img src="<?php echo htmlspecialchars($actividad['imagenUrl'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($actividad['referencia'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php } else { ?>
                                        <strong><?php echo htmlspecialchars($actividad['inicial'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                    <?php } ?>
                                </span>

                                <div class="texto-actividad">
                                    <p><?php echo htmlspecialchars($actividad['accion'], ENT_QUOTES, 'UTF-8'); ?></p>
                                    <strong><?php echo htmlspecialchars($actividad['referencia'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                </div>

                                <small><?php echo htmlspecialchars($actividad['fechaTexto'], ENT_QUOTES, 'UTF-8'); ?></small>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="actividad-vacia">Aún no hay actividad reciente.</p>
                    <?php } ?>
                </div>
            </article>
        </section>

        <section class="ultimo-contenido-panel">
            <div class="panel-card-encabezado ultimo-contenido-encabezado">
                <h3>Último contenido agregado</h3>
                <a href="gestion-peliculas-series.php">Ver todo <i class="fa-solid fa-arrow-right"></i></a>
            </div>

            <div class="tabla-ultimo-contenido">
                <div class="fila-contenido encabezado-tabla-contenido">
                    <span>Título</span>
                    <span>Tipo</span>
                    <span>Género</span>
                    <span>Fecha</span>
                    <span>Estado</span>
                </div>

                <?php if (!empty($ultimoContenidoPanel)) { ?>
                    <?php foreach ($ultimoContenidoPanel as $contenido) { ?>
                        <div class="fila-contenido">
                            <div class="titulo-contenido-panel">
                                <?php if ($contenido['imagenUrl'] !== '') { ?>
                                    <img src="<?php echo htmlspecialchars($contenido['imagenUrl'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($contenido['titulo'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php } else { ?>
                                    <span class="imagen-contenido-vacia">
                                        <i class="fa-solid fa-film"></i>
                                    </span>
                                <?php } ?>

                                <strong><?php echo htmlspecialchars($contenido['titulo'], ENT_QUOTES, 'UTF-8'); ?></strong>
                            </div>

                            <span><?php echo htmlspecialchars($contenido['tipo'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <span><?php echo htmlspecialchars($contenido['generos'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <span><?php echo htmlspecialchars($contenido['fecha'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <span>
                                <small class="estado-contenido estado-<?php echo htmlspecialchars($contenido['estadoClase'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($contenido['estado'], ENT_QUOTES, 'UTF-8'); ?>
                                </small>
                            </span>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p class="contenido-vacio-panel">Aún no hay contenido agregado.</p>
                <?php } ?>
            </div>
        </section>
    </main>

    <script src="../../Assets/Js/dark-mode.js"></script>
    
</body>
</html>
