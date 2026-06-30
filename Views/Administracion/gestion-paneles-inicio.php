<?php
require_once __DIR__ . '/../../Models/Seguridad.php';
session_start();
require_once '../../Models/PanelInicio.php';
require_once '../../Models/Sesion.php';
require_once '../../Models/Navegacion.php';
require_once '../../Api/soap/ClienteSOAP.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: /elyra/login');
    exit;
}

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    header('Location: /elyra/inicio');
    exit;
}

$panelesInicio = PanelInicio::listaInicial();
$contenidoPanel = PanelInicio::listaInicial();
$contenidoDisponible = PanelInicio::listaInicial();
$mensajePanelInicio = Sesion::tomarMensaje('mensaje_panel_inicio');
$tipoMensajePanelInicio = Sesion::tomarMensaje('tipo_mensaje_panel_inicio');
$busquedaContenido = '';
$idPanelSolicitado = 0;

if (isset($_GET['panel'])) {
    $idPanelSolicitado = (int)$_GET['panel'];
}

if (isset($_GET['busqueda_contenido'])) {
    $busquedaContenido = trim((string)$_GET['busqueda_contenido']);
}

try {
    $clienteSOAP = new ClienteSOAP();
    $panelesInicio = PanelInicio::normalizarGestion($clienteSOAP->listarPanelesInicioGestion());
    $panelSeleccionado = PanelInicio::panelSeleccionado($panelesInicio, $idPanelSolicitado);
    $idPanelActual = (int)$panelSeleccionado['id'];

    if ($idPanelActual > 0) {
        $contenidoPanel = PanelInicio::normalizarContenidoGestion($clienteSOAP->listarContenidoPanelInicioGestion($idPanelActual));
    }

    $contenidoDisponible = PanelInicio::normalizarContenidoGestion($clienteSOAP->buscarContenidoPanelInicio($busquedaContenido));
} catch (Exception $e) {
    Navegacion::redirigirErrorBaseDatosVista('/elyra/admin/paneles-inicio', $_SERVER);
}

$totalPaneles = count($panelesInicio);
$totalActivos = PanelInicio::totalActivos($panelesInicio);
$totalContenido = PanelInicio::totalContenido($panelesInicio);
$tipoSidebar = 'administracion';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/elyra/Views/Administracion/">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="../../Assets/Images/logos/iconos/morado.ico">
    <link rel="stylesheet" href="../../Assets/Css/Variables.css?v=vidrio-global-20260630">
    <link rel="stylesheet" href="../../Assets/Css/Parciales.css?v=vidrio-global-20260630">
    <link rel="stylesheet" href="../../Assets/Css/GestionPanelesInicio.css">
    <link rel="stylesheet" href="../../Assets/Css/switch.css">
    <title>Gestión de paneles de inicio</title>
</head>
<body>
    <?php include '../Parciales/header.php'; ?>
    <?php include '../Parciales/sidebar.php'; ?>

    <main class="contenido-principal pagina-paneles-inicio">
        <section class="encabezado-paneles-inicio">
            <div>
                <h1>Paneles de inicio</h1>
                <p>Organiza las secciones personalizadas que aparecen en inicio.</p>
            </div>
        </section>

        <?php if ($mensajePanelInicio !== '') { ?>
            <div class="mensaje-panel-inicio mensaje-<?php echo PanelInicio::valorSeguro($tipoMensajePanelInicio); ?>">
                <?php echo PanelInicio::valorSeguro($mensajePanelInicio); ?>
            </div>
        <?php } ?>

        <section class="resumen-paneles-inicio">
            <article>
                <span><i class="fa-solid fa-table-cells-large"></i></span>
                <div>
                    <h2>Paneles</h2>
                    <strong><?php echo (int)$totalPaneles; ?></strong>
                </div>
            </article>

            <article>
                <span><i class="fa-solid fa-eye"></i></span>
                <div>
                    <h2>Activos</h2>
                    <strong><?php echo (int)$totalActivos; ?></strong>
                </div>
            </article>

            <article>
                <span><i class="fa-solid fa-photo-film"></i></span>
                <div>
                    <h2>Animes asignados</h2>
                    <strong><?php echo (int)$totalContenido; ?></strong>
                </div>
            </article>
        </section>

        <section class="crear-panel-inicio">
            <form method="POST" action="../../Controller/ControladorPanelInicio.php">
                <?php echo Seguridad::campoCsrf(); ?>
                <div class="campo-panel-inicio campo-titulo-panel">
                    <label for="titulo_panel">Nuevo panel</label>
                    <input id="titulo_panel" type="text" name="titulo" placeholder="Ej: Animes de la semana">
                </div>

                <div class="campo-panel-inicio">
                    <label for="descripcion_panel">Descripción</label>
                    <input id="descripcion_panel" type="text" name="descripcion" placeholder="Opcional">
                </div>

                <div class="campo-panel-inicio campo-orden-panel">
                    <label for="orden_panel">Orden</label>
                    <input id="orden_panel" type="number" name="orden" min="0" placeholder="Auto">
                </div>

                <label class="toggle-panel-activo">
                    <input type="checkbox" name="activo" value="1" checked>
                    <span>Activo</span>
                </label>

                <button type="submit" name="CrearPanelInicio" value="1">
                    <i class="fa-solid fa-plus"></i>
                    <span>Crear</span>
                </button>
            </form>
        </section>

        <section class="layout-paneles-inicio">
            <aside class="lista-paneles-inicio">
                <div class="titulo-lista-paneles">
                    <h2>Paneles</h2>
                    <span><?php echo (int)$totalPaneles; ?></span>
                </div>

                <?php if (!empty($panelesInicio)) { ?>
                    <?php foreach ($panelesInicio as $panel) { ?>
                        <a href="/elyra/admin/paneles-inicio?panel=<?php echo (int)$panel['id']; ?>" class="item-panel-inicio<?php echo PanelInicio::clasePanelSeleccionado($idPanelActual, $panel['id']); ?>">
                            <span><?php echo PanelInicio::valorSeguro($panel['titulo']); ?></span>
                            <small class="estado-panel estado-<?php echo PanelInicio::valorSeguro($panel['estadoClase']); ?>">
                                <?php echo PanelInicio::valorSeguro($panel['estado']); ?>
                            </small>
                            <em><?php echo (int)$panel['totalContenido']; ?> animes</em>
                        </a>
                    <?php } ?>
                <?php } else { ?>
                    <p class="paneles-vacios">No hay paneles creados.</p>
                <?php } ?>
            </aside>

            <section class="detalle-panel-inicio">
                <?php if ((int)$idPanelActual > 0) { ?>
                    <div class="encabezado-detalle-panel">
                        <div>
                            <h2><?php echo PanelInicio::valorSeguro($panelSeleccionado['titulo']); ?></h2>
                            <p><?php echo (int)count($contenidoPanel); ?> animes en este panel</p>
                        </div>

                        <form method="POST" action="../../Controller/ControladorPanelInicio.php">
                <?php echo Seguridad::campoCsrf(); ?>
                            <input type="hidden" name="id_panel_inicio" value="<?php echo (int)$idPanelActual; ?>">
                            <button type="submit" name="EliminarPanelInicio" value="1" class="btn-eliminar-panel">
                                <i class="fa-solid fa-trash"></i>
                                <span>Eliminar</span>
                            </button>
                        </form>
                    </div>

                    <form method="POST" action="../../Controller/ControladorPanelInicio.php" class="form-editar-panel-inicio">
                <?php echo Seguridad::campoCsrf(); ?>
                        <input type="hidden" name="id_panel_inicio" value="<?php echo (int)$idPanelActual; ?>">

                        <div class="campo-panel-inicio">
                            <label for="titulo_editar_panel">Título</label>
                            <input id="titulo_editar_panel" type="text" name="titulo" value="<?php echo PanelInicio::valorSeguro($panelSeleccionado['titulo']); ?>">
                        </div>

                        <div class="campo-panel-inicio">
                            <label for="descripcion_editar_panel">Descripción</label>
                            <input id="descripcion_editar_panel" type="text" name="descripcion" value="<?php echo PanelInicio::valorSeguro($panelSeleccionado['descripcion']); ?>">
                        </div>

                        <div class="campo-panel-inicio campo-orden-panel">
                            <label for="orden_editar_panel">Orden</label>
                            <input id="orden_editar_panel" type="number" name="orden" min="1" value="<?php echo (int)$panelSeleccionado['orden']; ?>">
                        </div>

                        <label class="toggle-panel-activo">
                            <input type="checkbox" name="activo" value="1"<?php echo PanelInicio::checkedActivo($panelSeleccionado['activo']); ?>>
                            <span>Activo</span>
                        </label>

                        <button type="submit" name="ActualizarPanelInicio" value="1">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span>Guardar</span>
                        </button>
                    </form>

                    <div class="bloque-contenido-panel">
                        <div class="barra-contenido-panel">
                            <form method="GET" action="/elyra/admin/paneles-inicio" class="form-buscar-contenido-panel">
                                <input type="hidden" name="panel" value="<?php echo (int)$idPanelActual; ?>">
                                <label for="busqueda_contenido">Buscar anime</label>
                                <div>
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    <input id="busqueda_contenido" type="text" name="busqueda_contenido" placeholder="Buscar por título..." value="<?php echo PanelInicio::valorSeguro($busquedaContenido); ?>">
                                    <button type="submit" aria-label="Buscar">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </button>
                                    <a href="/elyra/admin/paneles-inicio?panel=<?php echo (int)$idPanelActual; ?>" aria-label="Limpiar búsqueda">
                                        <i class="fa-solid fa-rotate-left"></i>
                                    </a>
                                </div>
                            </form>

                            <form method="POST" action="../../Controller/ControladorPanelInicio.php" class="form-agregar-contenido-panel">
                <?php echo Seguridad::campoCsrf(); ?>
                                <input type="hidden" name="id_panel_inicio" value="<?php echo (int)$idPanelActual; ?>">

                                <label for="id_pelicula_serie">Agregar al panel</label>
                                <div>
                                    <select id="id_pelicula_serie" name="id_pelicula_serie">
                                        <option value="0">Selecciona un anime</option>
                                        <?php foreach ($contenidoDisponible as $contenidoDisponibleItem) { ?>
                                            <option value="<?php echo (int)$contenidoDisponibleItem['id']; ?>">
                                                <?php echo PanelInicio::valorSeguro($contenidoDisponibleItem['titulo']); ?> · <?php echo PanelInicio::valorSeguro($contenidoDisponibleItem['tipo']); ?> · <?php echo (int)$contenidoDisponibleItem['anio']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>

                                    <input type="number" name="orden_contenido" min="0" placeholder="Orden">

                                    <button type="submit" name="AgregarContenidoPanelInicio" value="1">
                                        <i class="fa-solid fa-plus"></i>
                                        <span>Agregar</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="lista-contenido-panel">
                            <?php if (!empty($contenidoPanel)) { ?>
                                <?php foreach ($contenidoPanel as $contenido) { ?>
                                    <article class="item-contenido-panel">
                                        <div class="miniatura-contenido-panel">
                                            <?php if ($contenido['imagenPortadaUrl'] !== '') { ?>
                                                <img src="<?php echo PanelInicio::valorSeguro($contenido['imagenPortadaUrl']); ?>" alt="<?php echo PanelInicio::valorSeguro($contenido['titulo']); ?>" loading="lazy" decoding="async">
                                            <?php } else { ?>
                                                <span><?php echo strtoupper(substr($contenido['titulo'], 0, 1)); ?></span>
                                            <?php } ?>
                                        </div>

                                        <div class="datos-contenido-panel">
                                            <strong><?php echo PanelInicio::valorSeguro($contenido['titulo']); ?></strong>
                                            <small><?php echo PanelInicio::valorSeguro($contenido['tipo']); ?> · <?php echo (int)$contenido['anio']; ?> · <?php echo PanelInicio::valorSeguro($contenido['generos']); ?></small>
                                        </div>

                                        <span class="orden-contenido-panel">#<?php echo (int)$contenido['orden']; ?></span>

                                        <form method="POST" action="../../Controller/ControladorPanelInicio.php">
                <?php echo Seguridad::campoCsrf(); ?>
                                            <input type="hidden" name="id_panel_inicio" value="<?php echo (int)$idPanelActual; ?>">
                                            <input type="hidden" name="id_pelicula_serie" value="<?php echo (int)$contenido['id']; ?>">
                                            <button type="submit" name="QuitarContenidoPanelInicio" value="1" class="btn-quitar-contenido-panel">
                                                <i class="fa-solid fa-xmark"></i>
                                                <span>Quitar</span>
                                            </button>
                                        </form>
                                    </article>
                                <?php } ?>
                            <?php } else { ?>
                                <p class="paneles-vacios">Este panel todavía no tiene animes.</p>
                            <?php } ?>
                        </div>
                    </div>
                <?php } else { ?>
                    <p class="paneles-vacios">Crea un panel para comenzar.</p>
                <?php } ?>
            </section>
        </section>
    </main>

    <script src="../../Assets/Js/dark-mode.js?v=vidrio-global-20260630"></script>
</body>
</html>
