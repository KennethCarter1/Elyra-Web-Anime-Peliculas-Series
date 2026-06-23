<?php
session_start();
require_once '../../Models/PeliculasSeries.php';
require_once '../../Models/Sesion.php';
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

$filtros = PeliculasSeries::filtrosDesdeSolicitud($_GET);
$resumenGestion = PeliculasSeries::resumenInicial();
$contenidoGestion = PeliculasSeries::listaInicial();
$contenidoCompleto = PeliculasSeries::listaInicial();
$generosGestion = PeliculasSeries::generosIniciales();
$detalleContenido = PeliculasSeries::detalleInicial();

$mensajeContenido = Sesion::tomarMensaje('mensaje_contenido');
$tipoMensajeContenido = Sesion::tomarMensaje('tipo_mensaje_contenido');

try {
    $clienteSOAP = new ClienteSOAP();
    $resumenGestion = $clienteSOAP->resumenGestionPeliculasSeries();
    $contenidoCompleto = PeliculasSeries::normalizarLista($clienteSOAP->listarPeliculasSeriesGestion(PeliculasSeries::filtrosVacios()));
    $contenidoGestion = PeliculasSeries::normalizarLista($clienteSOAP->listarPeliculasSeriesGestion($filtros));
    $generosGestion = PeliculasSeries::normalizarGenerosGestion($clienteSOAP->listarGenerosGestion());

    if ($filtros['id'] > 0) {
        $detalleContenido = PeliculasSeries::normalizarDetalle($clienteSOAP->obtenerDetallePeliculaSerie($filtros['id']));
    }
} catch (Exception $e) {
    Navegacion::redirigirErrorBaseDatosVista('../Administracion/gestion-peliculas-series.php', $_SERVER);
}

$tarjetasResumen = PeliculasSeries::tarjetasResumen($resumenGestion);
$opcionesTipo = PeliculasSeries::opcionesTipo();
$opcionesEstado = PeliculasSeries::opcionesEstado();
$aniosGestion = PeliculasSeries::aniosFiltro($contenidoCompleto);

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
    <link rel="stylesheet" href="../../Assets/Css/GestionPeliculasSeries.css">
    <link rel="stylesheet" href="../../Assets/Css/switch.css">
    <title>Gestión de Películas y Series</title>
</head>
<body>
    <?php include '../Parciales/header.php'; ?>
    <?php include '../Parciales/sidebar.php'; ?>

    <main class="contenido-principal gestion-contenido-admin">
        <section class="gestion-contenido-grid">
            <div class="gestion-contenido-izquierda">
                <section class="encabezado-gestion-contenido">
                    <div>
                        <h1>Gestión de Películas y Series</h1>
                        <p>Administra todo el contenido audiovisual de la plataforma.</p>
                    </div>

                    <a href="agregar-pelicula-serie.php" class="btn-agregar-contenido">
                        <i class="fa-solid fa-plus"></i>
                        <span>Agregar nueva</span>
                    </a>
                </section>

                <?php if ($mensajeContenido !== '') { ?>
                    <div class="mensaje-gestion-contenido mensaje-<?php echo htmlspecialchars($tipoMensajeContenido, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($mensajeContenido, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php } ?>

                <section class="resumen-gestion-contenido">
                    <?php foreach ($tarjetasResumen as $tarjeta) { ?>
                        <article class="tarjeta-gestion tarjeta-<?php echo htmlspecialchars($tarjeta['color'], ENT_QUOTES, 'UTF-8'); ?>">
                            <span class="icono-tarjeta-gestion">
                                <i class="<?php echo htmlspecialchars($tarjeta['icono'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                            </span>
                            <div>
                                <h2><?php echo htmlspecialchars($tarjeta['titulo'], ENT_QUOTES, 'UTF-8'); ?></h2>
                                <strong><?php echo PeliculasSeries::formatearNumero($tarjeta['valor']); ?></strong>
                            </div>
                        </article>
                    <?php } ?>
                </section>

                <section class="filtros-gestion-contenido">
                    <form method="GET" action="gestion-peliculas-series.php">
                        <div class="campo-busqueda-contenido">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" name="busqueda" placeholder="Buscar por título..." value="<?php echo PeliculasSeries::valorFiltro($filtros, 'busqueda'); ?>">
                </div>

                <div class="campo-filtro-contenido">
                    <label for="tipo">Tipo</label>
                    <select id="tipo" name="tipo">
                        <?php foreach ($opcionesTipo as $opcionTipo) { ?>
                            <option value="<?php echo htmlspecialchars($opcionTipo['valor'], ENT_QUOTES, 'UTF-8'); ?>"<?php echo PeliculasSeries::seleccionar($filtros['tipo'], $opcionTipo['valor']); ?>>
                                <?php echo htmlspecialchars($opcionTipo['texto'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="campo-filtro-contenido">
                    <label for="genero">Género</label>
                    <select id="genero" name="genero">
                        <option value="0"<?php echo PeliculasSeries::seleccionar($filtros['idGenero'], 0); ?>>Todos</option>
                        <?php foreach ($generosGestion as $genero) { ?>
                            <option value="<?php echo (int)$genero['id']; ?>"<?php echo PeliculasSeries::seleccionar($filtros['idGenero'], $genero['id']); ?>>
                                <?php echo htmlspecialchars($genero['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="campo-filtro-contenido">
                    <label for="estado">Estado</label>
                    <select id="estado" name="estado">
                        <?php foreach ($opcionesEstado as $opcionEstado) { ?>
                            <option value="<?php echo htmlspecialchars($opcionEstado['valor'], ENT_QUOTES, 'UTF-8'); ?>"<?php echo PeliculasSeries::seleccionar($filtros['estado'], $opcionEstado['valor']); ?>>
                                <?php echo htmlspecialchars($opcionEstado['texto'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="campo-filtro-contenido">
                    <label for="anio">Año</label>
                    <select id="anio" name="anio">
                        <option value="0"<?php echo PeliculasSeries::seleccionar($filtros['anio'], 0); ?>>Todos</option>
                        <?php foreach ($aniosGestion as $anioGestion) { ?>
                            <option value="<?php echo (int)$anioGestion; ?>"<?php echo PeliculasSeries::seleccionar($filtros['anio'], $anioGestion); ?>>
                                <?php echo (int)$anioGestion; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <button type="submit" class="btn-aplicar-filtros" aria-label="Aplicar filtros">
                    <i class="fa-solid fa-filter"></i>
                </button>

                <a href="gestion-peliculas-series.php" class="btn-limpiar-filtros" aria-label="Reiniciar filtros">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </form>
        </section>

                <article class="lista-gestion-contenido">
                <div class="encabezado-lista-contenido">
                    <h2>Lista de Películas / Series</h2>
                    <span><?php echo count($contenidoGestion); ?> resultados</span>
                </div>

                <div class="tabla-gestion-contenido">
                    <div class="fila-gestion-contenido encabezado-tabla-gestion">
                        <span>Título</span>
                        <span>Tipo</span>
                        <span>Géneros</span>
                        <span>Año</span>
                        <span>Estado</span>
                        <span>Tráiler</span>
                        <span>Información</span>
                    </div>

                    <?php if (!empty($contenidoGestion)) { ?>
                        <?php foreach ($contenidoGestion as $contenido) { ?>
                            <div class="fila-gestion-contenido">
                                <div class="titulo-tabla-contenido">
                                    <?php if ($contenido['imagenUrl'] !== '') { ?>
                                        <img src="<?php echo htmlspecialchars($contenido['imagenUrl'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($contenido['titulo'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php } else { ?>
                                        <span class="imagen-tabla-vacia">
                                            <i class="fa-solid fa-film"></i>
                                        </span>
                                    <?php } ?>

                                    <div>
                                        <strong><?php echo htmlspecialchars($contenido['titulo'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                        <small><?php echo htmlspecialchars($contenido['tituloOriginal'], ENT_QUOTES, 'UTF-8'); ?></small>
                                    </div>
                                </div>

                                <span><?php echo htmlspecialchars($contenido['tipo'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <span><?php echo htmlspecialchars($contenido['generos'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <span><?php echo (int)$contenido['anio']; ?></span>
                                <span>
                                    <small class="estado-gestion estado-<?php echo htmlspecialchars($contenido['estadoClase'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <?php echo htmlspecialchars($contenido['estado'], ENT_QUOTES, 'UTF-8'); ?>
                                    </small>
                                </span>
                                <span>
                                    <?php if ($contenido['trailerUrl'] !== '') { ?>
                                        <a href="<?php echo htmlspecialchars($contenido['trailerUrl'], ENT_QUOTES, 'UTF-8'); ?>" class="btn-trailer-contenido" target="_blank" rel="noopener noreferrer" aria-label="Ver tráiler">
                                            <i class="fa-solid fa-clapperboard"></i>
                                        </a>
                                    <?php } else { ?>
                                        <small class="sin-trailer">Sin tráiler</small>
                                    <?php } ?>
                                </span>
                                <span>
                                    <a href="<?php echo htmlspecialchars(PeliculasSeries::enlaceInformacion($filtros, $contenido['id']), ENT_QUOTES, 'UTF-8'); ?>" class="btn-info-contenido" aria-label="Ver información">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </a>
                                </span>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="contenido-gestion-vacio">No se encontró contenido con esos filtros.</p>
                    <?php } ?>
                </div>
                </article>
            </div>

            <aside class="panel-detalle-contenido">
                <div class="encabezado-detalle-contenido">
                    <h2>Información del contenido</h2>
                    <a href="<?php echo htmlspecialchars(PeliculasSeries::enlaceSinInformacion($filtros), ENT_QUOTES, 'UTF-8'); ?>" aria-label="Cerrar información">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>

                <?php if (!empty($detalleContenido) && $detalleContenido['id'] > 0) { ?>
                    <div class="detalle-contenido-superior">
                        <?php if ($detalleContenido['imagenPortadaUrl'] !== '') { ?>
                            <img src="<?php echo htmlspecialchars($detalleContenido['imagenPortadaUrl'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($detalleContenido['titulo'], ENT_QUOTES, 'UTF-8'); ?>">
                        <?php } else { ?>
                            <span class="imagen-detalle-vacia">
                                <i class="fa-solid fa-film"></i>
                            </span>
                        <?php } ?>

                        <div>
                            <h2><?php echo htmlspecialchars($detalleContenido['titulo'], ENT_QUOTES, 'UTF-8'); ?></h2>
                            <p><?php echo htmlspecialchars($detalleContenido['tituloOriginal'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <div class="chips-detalle-contenido">
                                <span><?php echo htmlspecialchars($detalleContenido['tipo'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <span class="estado-<?php echo htmlspecialchars($detalleContenido['estadoClase'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($detalleContenido['estado'], ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                                <span><?php echo htmlspecialchars($detalleContenido['estadoEmision'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </div>
                        </div>
                    </div>

                    <?php if ($detalleContenido['trailerUrl'] !== '') { ?>
                        <a class="btn-ver-trailer-detalle" href="<?php echo htmlspecialchars($detalleContenido['trailerUrl'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">
                            <i class="fa-solid fa-clapperboard"></i>
                            <span>Ver tráiler</span>
                        </a>
                    <?php } ?>

                    <div class="resumen-detalle-contenido">
                        <h3>Resumen</h3>
                        <p><?php echo htmlspecialchars($detalleContenido['descripcion'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>

                    <div class="datos-detalle-contenido">
                        <div><span>Tipo</span><strong><?php echo htmlspecialchars($detalleContenido['tipo'], ENT_QUOTES, 'UTF-8'); ?></strong></div>
                        <div><span>Estado de emisión</span><strong><?php echo htmlspecialchars($detalleContenido['estadoEmision'], ENT_QUOTES, 'UTF-8'); ?></strong></div>
                        <div><span>Año de lanzamiento</span><strong><?php echo (int)$detalleContenido['anio']; ?></strong></div>
                        <div><span>Fecha de estreno</span><strong><?php echo htmlspecialchars($detalleContenido['fechaEstreno'], ENT_QUOTES, 'UTF-8'); ?></strong></div>
                        <div><span>Duración</span><strong><?php echo (int)$detalleContenido['duracionMinutos']; ?> min</strong></div>
                        <div><span>Temporadas</span><strong><?php echo (int)$detalleContenido['temporadas']; ?></strong></div>
                        <div><span>Episodios</span><strong><?php echo (int)$detalleContenido['episodios']; ?></strong></div>
                        <div><span>Géneros</span><strong><?php echo htmlspecialchars($detalleContenido['generos'], ENT_QUOTES, 'UTF-8'); ?></strong></div>
                        <div><span>Fecha de creación</span><strong><?php echo htmlspecialchars($detalleContenido['fechaCreacion'], ENT_QUOTES, 'UTF-8'); ?></strong></div>
                        <div><span>Última actualización</span><strong><?php echo htmlspecialchars($detalleContenido['fechaActualizacion'], ENT_QUOTES, 'UTF-8'); ?></strong></div>
                    </div>

                    <div class="acciones-detalle-contenido">
                        <form method="POST" action="../../Controller/ControladorContenido.php">
                            <input type="hidden" name="id_pelicula_serie" value="<?php echo (int)$detalleContenido['id']; ?>">
                            <?php if ((int)$detalleContenido['activo'] === 1) { ?>
                                <button type="submit" name="DesactivarPeliculaSerie" value="1" class="btn-desactivar-contenido">
                                    <i class="fa-solid fa-ban"></i>
                                    <span>Desactivar</span>
                                </button>
                            <?php } else { ?>
                                <button type="submit" name="ActivarPeliculaSerie" value="1" class="btn-activar-contenido">
                                    <i class="fa-solid fa-check"></i>
                                    <span>Activar</span>
                                </button>
                            <?php } ?>
                        </form>

                        <a href="agregar-pelicula-serie.php?id=<?php echo (int)$detalleContenido['id']; ?>" class="btn-actualizar-contenido">
                            <i class="fa-solid fa-pen"></i>
                            <span>Actualizar</span>
                        </a>
                    </div>
                <?php } else { ?>
                    <div class="detalle-contenido-vacio">
                        <i class="fa-solid fa-circle-info"></i>
                        <h2>Información del contenido</h2>
                        <p>Selecciona una película o serie para ver todos sus detalles.</p>
                    </div>
                <?php } ?>
            </aside>
        </section>
    </main>

    <script src="../../Assets/Js/dark-mode.js"></script>
</body>
</html>
