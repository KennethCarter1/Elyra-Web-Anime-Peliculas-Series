<?php
session_start();
require_once '../../Models/ReportesEstadisticas.php';
require_once '../../Models/Navegacion.php';
require_once '../../Api/soap/ClienteSOAP.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: ../Usuario/IniciarSesion.php');
    exit;
}

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    header('Location: ../Usuario/inicio.php');
    exit;
}

$resumenReportes = ReportesEstadisticas::resumenInicial();
$usuariosRecientes = ReportesEstadisticas::listaInicial();
$contenidoReciente = ReportesEstadisticas::listaInicial();
$generosElegidos = ReportesEstadisticas::listaInicial();
$contenidoPorGenero = ReportesEstadisticas::listaInicial();
$distribucionContenido = ReportesEstadisticas::listaInicial();
$estadoContenido = ReportesEstadisticas::listaInicial();

try {
    $clienteSOAP = new ClienteSOAP();
    $resumenReportes = ReportesEstadisticas::normalizarResumen($clienteSOAP->resumenReportesEstadisticas());
    $usuariosRecientes = ReportesEstadisticas::normalizarUsuarios($clienteSOAP->ultimosUsuariosReportes());
    $contenidoReciente = ReportesEstadisticas::normalizarContenido($clienteSOAP->ultimoContenidoReportes());
    $generosElegidos = ReportesEstadisticas::normalizarGenerosElegidos($clienteSOAP->generosMasElegidosReportes());
    $contenidoPorGenero = ReportesEstadisticas::normalizarContenidoPorGenero($clienteSOAP->contenidoPorGeneroReportes());
    $distribucionContenido = ReportesEstadisticas::normalizarDistribucion($clienteSOAP->distribucionContenidoReportes());
    $estadoContenido = ReportesEstadisticas::normalizarEstadoContenido($clienteSOAP->estadoContenidoReportes());
} catch (Exception $e) {
    Navegacion::redirigirErrorBaseDatosVista('../Administracion/reportes-estadisticas.php', $_SERVER);
}

$tarjetasResumen = ReportesEstadisticas::tarjetasResumen($resumenReportes);
$tipoSidebar = 'administracion';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="../../Assets/Images/logos/iconos/morado.ico">
    <link rel="stylesheet" href="../../Assets/Css/Variables.css">
    <link rel="stylesheet" href="../../Assets/Css/Parciales.css">
    <link rel="stylesheet" href="../../Assets/Css/ReportesEstadisticas.css">
    <link rel="stylesheet" href="../../Assets/Css/switch.css">
    <title>Reportes y estadísticas</title>
</head>
<body>
    <?php include '../Parciales/header.php'; ?>
    <?php include '../Parciales/sidebar.php'; ?>

    <main class="contenido-principal reportes-estadisticas-admin">
        <section class="encabezado-reportes">
            <div>
                <h1>Reportes y estadísticas</h1>
                <p>Consulta el estado general de usuarios, contenido y géneros de la plataforma.</p>
            </div>
        </section>

        <section class="resumen-reportes">
            <?php foreach ($tarjetasResumen as $tarjeta) { ?>
                <article class="tarjeta-reporte tarjeta-<?php echo ReportesEstadisticas::valorSeguro($tarjeta['color']); ?>">
                    <span class="icono-reporte">
                        <i class="<?php echo ReportesEstadisticas::valorSeguro($tarjeta['icono']); ?>"></i>
                    </span>
                    <div>
                        <h2><?php echo ReportesEstadisticas::valorSeguro($tarjeta['titulo']); ?></h2>
                        <strong><?php echo ReportesEstadisticas::formatearNumero($tarjeta['valor']); ?></strong>
                    </div>
                </article>
            <?php } ?>
        </section>

        <section class="grid-reportes-principal">
            <article class="panel-reporte tabla-reporte-usuarios">
                <div class="encabezado-panel-reporte">
                    <h2>Últimos usuarios registrados</h2>
                    <span><?php echo count($usuariosRecientes); ?> recientes</span>
                </div>

                <div class="tabla-reportes tabla-usuarios-reportes">
                    <div class="fila-reporte fila-usuario-reporte encabezado-tabla-reporte">
                        <span>Usuario</span>
                        <span>Correo</span>
                        <span>Rol</span>
                        <span>Estado</span>
                        <span>Fecha</span>
                    </div>

                    <?php if (!empty($usuariosRecientes)) { ?>
                        <?php foreach ($usuariosRecientes as $usuario) { ?>
                            <div class="fila-reporte fila-usuario-reporte">
                                <div class="usuario-reporte-datos">
                                    <span class="avatar-reporte"><?php echo ReportesEstadisticas::valorSeguro($usuario['iniciales']); ?></span>
                                    <div>
                                        <strong><?php echo ReportesEstadisticas::valorSeguro($usuario['nombre']); ?></strong>
                                        <small>@<?php echo ReportesEstadisticas::valorSeguro($usuario['usuario']); ?></small>
                                    </div>
                                </div>
                                <span><?php echo ReportesEstadisticas::valorSeguro($usuario['correo']); ?></span>
                                <span><?php echo ReportesEstadisticas::valorSeguro($usuario['rol']); ?></span>
                                <span>
                                    <small class="estado-reporte estado-<?php echo ReportesEstadisticas::valorSeguro($usuario['estadoClase']); ?>">
                                        <?php echo ReportesEstadisticas::valorSeguro($usuario['estado']); ?>
                                    </small>
                                </span>
                                <span><?php echo ReportesEstadisticas::valorSeguro($usuario['fecha']); ?></span>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="reporte-vacio">No hay usuarios recientes.</p>
                    <?php } ?>
                </div>
            </article>

            <article class="panel-reporte tabla-reporte-contenido">
                <div class="encabezado-panel-reporte">
                    <h2>Último contenido agregado</h2>
                    <span><?php echo count($contenidoReciente); ?> recientes</span>
                </div>

                <div class="tabla-reportes tabla-contenido-reportes">
                    <div class="fila-reporte fila-contenido-reporte encabezado-tabla-reporte">
                        <span>Título</span>
                        <span>Tipo</span>
                        <span>Géneros</span>
                        <span>Fecha</span>
                        <span>Estado</span>
                    </div>

                    <?php if (!empty($contenidoReciente)) { ?>
                        <?php foreach ($contenidoReciente as $contenido) { ?>
                            <div class="fila-reporte fila-contenido-reporte">
                                <div class="contenido-reporte-datos">
                                    <?php if ($contenido['imagenUrl'] !== '') { ?>
                                        <img src="<?php echo ReportesEstadisticas::valorSeguro($contenido['imagenUrl']); ?>" alt="<?php echo ReportesEstadisticas::valorSeguro($contenido['titulo']); ?>">
                                    <?php } else { ?>
                                        <span class="imagen-reporte-vacia">
                                            <i class="fa-solid fa-film"></i>
                                        </span>
                                    <?php } ?>

                                    <strong><?php echo ReportesEstadisticas::valorSeguro($contenido['titulo']); ?></strong>
                                </div>
                                <span><?php echo ReportesEstadisticas::valorSeguro($contenido['tipo']); ?></span>
                                <span><?php echo ReportesEstadisticas::valorSeguro($contenido['generos']); ?></span>
                                <span><?php echo ReportesEstadisticas::valorSeguro($contenido['fecha']); ?></span>
                                <span>
                                    <small class="estado-reporte estado-<?php echo ReportesEstadisticas::valorSeguro($contenido['estadoClase']); ?>">
                                        <?php echo ReportesEstadisticas::valorSeguro($contenido['estado']); ?>
                                    </small>
                                </span>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="reporte-vacio">No hay contenido registrado.</p>
                    <?php } ?>
                </div>
            </article>
        </section>

        <section class="grid-reportes-secundario">
            <article class="panel-reporte">
                <div class="encabezado-panel-reporte">
                    <h2>Géneros más elegidos</h2>
                    <span>Preferencias</span>
                </div>

                <div class="lista-barras-reportes">
                    <?php if (!empty($generosElegidos)) { ?>
                        <?php foreach ($generosElegidos as $genero) { ?>
                            <div class="item-barra-reporte">
                                <div class="texto-barra-reporte">
                                    <strong><?php echo ReportesEstadisticas::valorSeguro($genero['nombre']); ?></strong>
                                    <span><?php echo ReportesEstadisticas::formatearNumero($genero['total']); ?> usuarios</span>
                                </div>
                                <div class="barra-reporte">
                                    <span style="width: <?php echo ReportesEstadisticas::anchoBarra($genero['porcentaje']); ?>;"></span>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="reporte-vacio">No hay preferencias guardadas.</p>
                    <?php } ?>
                </div>
            </article>

            <article class="panel-reporte">
                <div class="encabezado-panel-reporte">
                    <h2>Contenido por género</h2>
                    <span>Películas / Series</span>
                </div>

                <div class="lista-barras-reportes">
                    <?php if (!empty($contenidoPorGenero)) { ?>
                        <?php foreach ($contenidoPorGenero as $generoContenido) { ?>
                            <div class="item-barra-reporte">
                                <div class="texto-barra-reporte">
                                    <strong><?php echo ReportesEstadisticas::valorSeguro($generoContenido['nombre']); ?></strong>
                                    <span><?php echo ReportesEstadisticas::formatearNumero($generoContenido['total']); ?> contenidos</span>
                                </div>
                                <div class="barra-reporte">
                                    <span style="width: <?php echo ReportesEstadisticas::anchoBarra($generoContenido['porcentaje']); ?>;"></span>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="reporte-vacio">No hay contenido asociado a géneros.</p>
                    <?php } ?>
                </div>
            </article>

            <article class="panel-reporte">
                <div class="encabezado-panel-reporte">
                    <h2>Distribución de contenido</h2>
                    <span>Tipo</span>
                </div>

                <div class="lista-barras-reportes barras-grandes">
                    <?php foreach ($distribucionContenido as $distribucion) { ?>
                        <div class="item-barra-reporte">
                            <div class="texto-barra-reporte">
                                <strong><?php echo ReportesEstadisticas::valorSeguro($distribucion['nombre']); ?></strong>
                                <span><?php echo ReportesEstadisticas::formatearNumero($distribucion['total']); ?></span>
                            </div>
                            <div class="barra-reporte">
                                <span style="width: <?php echo ReportesEstadisticas::anchoBarra($distribucion['porcentaje']); ?>;"></span>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </article>

            <article class="panel-reporte">
                <div class="encabezado-panel-reporte">
                    <h2>Estado del contenido</h2>
                    <span>Visibilidad</span>
                </div>

                <div class="lista-barras-reportes barras-grandes">
                    <?php foreach ($estadoContenido as $estado) { ?>
                        <div class="item-barra-reporte">
                            <div class="texto-barra-reporte">
                                <strong><?php echo ReportesEstadisticas::valorSeguro($estado['nombre']); ?></strong>
                                <span><?php echo ReportesEstadisticas::formatearNumero($estado['total']); ?></span>
                            </div>
                            <div class="barra-reporte">
                                <span style="width: <?php echo ReportesEstadisticas::anchoBarra($estado['porcentaje']); ?>;"></span>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </article>
        </section>
    </main>

    <script src="../../Assets/Js/dark-mode.js"></script>
</body>
</html>
