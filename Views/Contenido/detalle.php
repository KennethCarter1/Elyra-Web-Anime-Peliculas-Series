<?php
require_once __DIR__ . '/../../Models/Seguridad.php';
session_start();
require_once '../../Models/Inicio.php';
require_once '../../Models/Navegacion.php';
require_once '../../Models/Sesion.php';
require_once '../../Api/soap/ClienteSOAP.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: /elyra/login');
    exit;
}

$idPeliculaSerie = 0;
if (isset($_GET['id'])) {
    $idPeliculaSerie = (int)$_GET['id'];
}

if ($idPeliculaSerie <= 0) {
    header('Location: /elyra/error-404');
    exit;
}

$detalleContenido = Inicio::detalleInicial();
$mensajeFavorito = Sesion::tomarMensaje('mensaje_favorito');
$tipoMensajeFavorito = Sesion::tomarMensaje('tipo_mensaje_favorito', 'exito');

try {
    $clienteSOAP = new ClienteSOAP();
    $detalleContenido = Inicio::normalizarDetalle(
        $clienteSOAP->detalleContenidoUsuario($idPeliculaSerie, $_SESSION['usuario'])
    );
} catch (Exception $e) {
    Navegacion::redirigirErrorBaseDatosVista('/elyra/detalle?id=' . $idPeliculaSerie, $_SERVER);
}

if (empty($detalleContenido) || (int)$detalleContenido['id'] <= 0) {
    header('Location: /elyra/error-404');
    exit;
}

$hijosContenido = [];
$contenidoRelacionado = null;

if ((int)$detalleContenido['seriePadreId'] > 0) {
    $contenidoRelacionado = $detalleContenido;
}

try {
    $hijosContenido = $clienteSOAP->obtenerHijosPeliculaSerie((int)$detalleContenido['id']);
} catch (Exception $e) {
    $hijosContenido = [];
}

$retornoFavorito = '/elyra/detalle?id=' . (int)$detalleContenido['id'];
$generosDetalle = Inicio::listaGenerosTexto($detalleContenido['generos']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/elyra/Views/Contenido/">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="../../Assets/Images/logos/iconos/morado.ico">
    <link rel="stylesheet" href="../../Assets/Css/Variables.css?v=vidrio-global-20260630">
    <link rel="stylesheet" href="../../Assets/Css/Parciales.css?v=vidrio-global-20260630">
    <link rel="stylesheet" href="../../Assets/Css/Inicio.css?v=vidrio-global-20260630">
    <link rel="stylesheet" href="../../Assets/Css/switch.css">
    <title><?php echo Inicio::valorSeguro($detalleContenido['titulo']); ?></title>
</head>
<body>
    <?php include '../Parciales/header.php'; ?>
    <?php include '../Parciales/sidebar.php'; ?>

    <main class="contenido-principal detalle-contenido-usuario">
        <?php if ($mensajeFavorito !== '') { ?>
            <div class="mensaje-inicio mensaje-<?php echo Inicio::valorSeguro($tipoMensajeFavorito); ?>">
                <?php echo Inicio::valorSeguro($mensajeFavorito); ?>
            </div>
        <?php } ?>

        <section class="banner-detalle-contenido">
            <div class="media-banner-detalle">
                <?php if ($detalleContenido['imagenBannerUrl'] !== '') { ?>
                    <img src="<?php echo Inicio::valorSeguro($detalleContenido['imagenBannerUrl']); ?>" alt="<?php echo Inicio::valorSeguro($detalleContenido['titulo']); ?>" loading="lazy" decoding="async">
                <?php } elseif ($detalleContenido['imagenPortadaUrl'] !== '') { ?>
                    <img src="<?php echo Inicio::valorSeguro($detalleContenido['imagenPortadaUrl']); ?>" alt="<?php echo Inicio::valorSeguro($detalleContenido['titulo']); ?>" loading="lazy" decoding="async">
                <?php } else { ?>
                    <div class="banner-detalle-vacio">
                        <i class="fa-solid fa-clapperboard"></i>
                    </div>
                <?php } ?>
            </div>

            <a href="/elyra/inicio" class="volver-detalle">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Volver</span>
            </a>

            <form method="POST" action="../../Controller/ControladorFavoritos.php" class="form-favorito-banner-detalle">
                <?php echo Seguridad::campoCsrf(); ?>
                <input type="hidden" name="id_pelicula_serie" value="<?php echo (int)$detalleContenido['id']; ?>">
                <input type="hidden" name="retorno" value="<?php echo Inicio::valorSeguro($retornoFavorito); ?>">
                <button type="submit" name="AlternarFavorito" value="1" class="btn-favoritos-banner-detalle <?php echo Inicio::valorSeguro($detalleContenido['favoritoClase']); ?>" aria-label="<?php echo Inicio::valorSeguro($detalleContenido['favoritoTexto']); ?>">
                    <i class="fa-solid fa-heart"></i>
                    <span>Favoritos</span>
                </button>
            </form>

            <div class="contenido-banner-detalle">
                <span class="etiqueta-destacado"><?php echo Inicio::valorSeguro($detalleContenido['tipo']); ?></span>
                <h1><?php echo Inicio::valorSeguro($detalleContenido['titulo']); ?></h1>

                <div class="meta-destacado">
                    <span><?php echo Inicio::valorSeguro(Inicio::textoMetaDetalle($detalleContenido)); ?></span>
                </div>

                <?php if ($detalleContenido['trailerUrl'] !== '') { ?>
                    <div class="acciones-banner-detalle">
                        <a href="<?php echo Inicio::valorSeguro($detalleContenido['trailerUrl']); ?>" class="btn-trailer-banner-detalle" target="_blank" rel="noopener noreferrer">
                            <i class="fa-brands fa-youtube"></i>
                            <span>Ver tráiler en YouTube</span>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </section>

        <section class="detalle-inferior-grid">
            <article class="sinopsis-detalle-contenido">
                <div class="encabezado-bloque-detalle">
                    <i class="fa-solid fa-book-open"></i>
                    <h2>Sinopsis</h2>
                </div>

                <p><?php echo Inicio::valorSeguro($detalleContenido['descripcion']); ?></p>
            </article>

            <article class="panel-datos-detalle">
                <div class="encabezado-bloque-detalle">
                    <i class="fa-solid fa-circle-info"></i>
                    <h2>Información</h2>
                </div>

                <div class="lista-datos-detalle">
                    <div class="fila-dato-detalle">
                        <i class="fa-solid fa-layer-group"></i>
                        <span>Tipo</span>
                        <strong><?php echo Inicio::valorSeguro($detalleContenido['tipo']); ?></strong>
                    </div>
                    <div class="fila-dato-detalle">
                        <i class="fa-solid fa-closed-captioning"></i>
                        <span>Título original</span>
                        <strong><?php echo Inicio::valorSeguro($detalleContenido['tituloOriginal']); ?></strong>
                    </div>
                    <div class="fila-dato-detalle">
                        <i class="fa-solid fa-calendar-days"></i>
                        <span>Año</span>
                        <strong><?php echo (int)$detalleContenido['anio']; ?></strong>
                    </div>
                    <div class="fila-dato-detalle">
                        <i class="fa-solid fa-signal"></i>
                        <span>Emisión</span>
                        <strong><?php echo Inicio::valorSeguro($detalleContenido['estadoEmision']); ?></strong>
                    </div>
                    <div class="fila-dato-detalle">
                        <i class="fa-solid fa-clock"></i>
                        <span>Duración</span>
                        <strong><?php echo (int)$detalleContenido['duracionMinutos']; ?> min</strong>
                    </div>
                    <?php if (strtolower($detalleContenido['tipo']) === 'serie') { ?>
                        <div class="fila-dato-detalle">
                            <i class="fa-solid fa-list-ol"></i>
                            <span>Temporadas</span>
                            <strong><?php echo (int)$detalleContenido['temporadas']; ?></strong>
                        </div>
                    <?php } ?>
                    <div class="fila-dato-detalle">
                        <i class="fa-solid fa-play"></i>
                        <span>Episodios</span>
                        <strong><?php echo (int)$detalleContenido['episodios']; ?></strong>
                    </div>
                    <div class="fila-dato-detalle fila-generos-detalle">
                        <i class="fa-solid fa-tags"></i>
                        <span>Géneros</span>
                        <div class="generos-detalle-lista">
                            <?php foreach ($generosDetalle as $generoDetalle) { ?>
                                <strong><?php echo Inicio::valorSeguro($generoDetalle); ?></strong>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </article>
        </section>

        <?php if ($contenidoRelacionado !== null || !empty($hijosContenido)) { ?>
            <section class="relacionados-detalle-seccion">
                <div class="encabezado-bloque-detalle">
                    <i class="fa-solid fa-link"></i>
                    <h2>Contenido relacionado</h2>
                </div>

                <div class="relacionados-detalle-grid">
                    <?php if ($contenidoRelacionado !== null) { ?>
                        <a href="/elyra/detalle?id=<?php echo (int)$contenidoRelacionado['seriePadreId']; ?>" class="tarjeta-relacionado">
                            <?php if ($contenidoRelacionado['padreImagenPortadaUrl'] !== '') { ?>
                                <img src="<?php echo Inicio::valorSeguro($contenidoRelacionado['padreImagenPortadaUrl']); ?>" alt="<?php echo Inicio::valorSeguro($contenidoRelacionado['padreTitulo']); ?>" loading="lazy" decoding="async">
                            <?php } else { ?>
                                <div class="tarjeta-relacionado-sin-imagen">
                                    <i class="fa-solid fa-clapperboard"></i>
                                </div>
                            <?php } ?>
                            <div class="tarjeta-relacionado-info">
                                <strong><?php echo Inicio::valorSeguro($contenidoRelacionado['padreTitulo']); ?></strong>
                                <span><?php echo (int)$contenidoRelacionado['padreAnio']; ?></span>
                            </div>
                        </a>
                    <?php } ?>

                    <?php foreach ($hijosContenido as $hijo) { ?>
                        <a href="/elyra/detalle?id=<?php echo (int)$hijo->idPeliculaSerie; ?>" class="tarjeta-relacionado">
                            <?php
                                $hijoImagen = '';
                                if (isset($hijo->imagenPortada) && $hijo->imagenPortada !== '') {
                                    $ruta = (string)$hijo->imagenPortada;
                                    if (strpos($ruta, 'http://') === 0 || strpos($ruta, 'https://') === 0 || strpos($ruta, '/') === 0 || strpos($ruta, '../') === 0) {
                                        $hijoImagen = $ruta;
                                    } else {
                                        $hijoImagen = '../../' . ltrim($ruta, '/');
                                    }
                                }
                            ?>
                            <?php if ($hijoImagen !== '') { ?>
                                <img src="<?php echo Inicio::valorSeguro($hijoImagen); ?>" alt="<?php echo Inicio::valorSeguro($hijo->titulo); ?>" loading="lazy" decoding="async">
                            <?php } else { ?>
                                <div class="tarjeta-relacionado-sin-imagen">
                                    <i class="fa-solid fa-clapperboard"></i>
                                </div>
                            <?php } ?>
                            <div class="tarjeta-relacionado-info">
                                <strong><?php echo Inicio::valorSeguro($hijo->titulo); ?></strong>
                                <span><?php echo (int)$hijo->anioLanzamiento; ?></span>
                            </div>
                        </a>
                    <?php } ?>
                </div>
            </section>
        <?php } ?>
    </main>

    <script src="../../Assets/Js/dark-mode.js?v=vidrio-global-20260630"></script>
</body>
</html>
