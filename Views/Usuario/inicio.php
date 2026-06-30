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

$nombreInicio = Navegacion::nombreUsuario($_SESSION);
$destacados = Inicio::listaInicial();
$recomendaciones = Inicio::listaInicial();
$ultimosAgregados = Inicio::listaInicial();
$generosInicio = Inicio::listaInicial();
$panelesPersonalizados = Inicio::listaInicial();
$mensajeFavorito = Sesion::tomarMensaje('mensaje_favorito');
$tipoMensajeFavorito = Sesion::tomarMensaje('tipo_mensaje_favorito', 'exito');

try {
    $clienteSOAP = new ClienteSOAP();
    $datosInicio = $clienteSOAP->datosInicioUsuario($_SESSION['usuario']);

    $destacados = Inicio::normalizarListaContenido($datosInicio['destacados']);
    $recomendaciones = Inicio::normalizarListaContenido($datosInicio['recomendaciones']);
    $ultimosAgregados = Inicio::normalizarListaContenido($datosInicio['ultimos']);
    $generosInicio = Inicio::normalizarGeneros($datosInicio['generos']);
    $panelesPersonalizados = Inicio::normalizarPanelesPersonalizados($datosInicio['paneles']);
} catch (Exception $e) {
    Navegacion::redirigirErrorBaseDatosVista('/elyra/inicio', $_SERVER);
}

$retornoFavorito = '/elyra/inicio';
$urlDetalleContenido = '/elyra/detalle';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/elyra/Views/Usuario/">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="../../Assets/Images/logos/iconos/morado.ico">
    <link rel="stylesheet" href="../../Assets/Css/Variables.css?v=vidrio-global-20260630">
    <link rel="stylesheet" href="../../Assets/Css/Parciales.css?v=vidrio-global-20260630">
    <link rel="stylesheet" href="../../Assets/Css/Inicio.css?v=vidrio-global-20260630">
    <link rel="stylesheet" href="../../Assets/Css/switch.css">
    <title>Inicio</title>
</head>
<body>
    <?php include '../Parciales/header.php'; ?>
    <?php include '../Parciales/sidebar.php'; ?>

    <main class="contenido-principal inicio-usuario">
        <section class="encabezado-inicio">
            <h2>¡Bienvenido de vuelta, <?php echo Inicio::valorSeguro($nombreInicio); ?>!</h2>
            <p>¿Qué historia vas a vivir hoy?</p>
        </section>

        <?php if ($mensajeFavorito !== '') { ?>
            <div class="mensaje-inicio mensaje-<?php echo Inicio::valorSeguro($tipoMensajeFavorito); ?>">
                <?php echo Inicio::valorSeguro($mensajeFavorito); ?>
            </div>
        <?php } ?>

        <?php if (!empty($destacados)) { ?>
            <section class="carrusel-destacado" data-carrusel-destacado>
                <div class="slides-destacados">
                    <?php $indiceDestacado = 0; ?>
                    <?php foreach ($destacados as $destacado) { ?>
                        <?php
                            $claseSlide = 'slide-destacado';
                            if ($indiceDestacado === 0) {
                                $claseSlide .= ' activo';
                            }
                        ?>
                        <article class="<?php echo $claseSlide; ?>" data-slide-destacado>
                            <div class="fondo-slide-destacado">
                                <?php if ($destacado['trailerEmbedUrl'] !== '') { ?>
                                    <iframe data-video-src="<?php echo Inicio::valorSeguro($destacado['trailerEmbedUrl']); ?>" title="<?php echo Inicio::valorSeguro($destacado['titulo']); ?>" allow="autoplay; encrypted-media" referrerpolicy="strict-origin-when-cross-origin" loading="lazy"></iframe>
                                <?php } elseif ($destacado['imagenBannerUrl'] !== '') { ?>
                                    <img src="<?php echo Inicio::valorSeguro($destacado['imagenBannerUrl']); ?>" alt="<?php echo Inicio::valorSeguro($destacado['titulo']); ?>" loading="lazy" decoding="async">
                                <?php } elseif ($destacado['imagenPortadaUrl'] !== '') { ?>
                                    <img src="<?php echo Inicio::valorSeguro($destacado['imagenPortadaUrl']); ?>" alt="<?php echo Inicio::valorSeguro($destacado['titulo']); ?>" loading="lazy" decoding="async">
                                <?php } ?>
                            </div>

                            <div class="contenido-slide-destacado">
                                <span class="etiqueta-destacado">Destacado</span>
                                <h1><?php echo Inicio::valorSeguro($destacado['titulo']); ?></h1>
                                <p><?php echo Inicio::valorSeguro($destacado['descripcionCorta']); ?></p>

                                <div class="meta-destacado">
                                    <span><?php echo Inicio::valorSeguro(Inicio::textoMeta($destacado)); ?></span>
                                    <span><?php echo Inicio::valorSeguro($destacado['generos']); ?></span>
                                </div>

                                <div class="acciones-destacado">
                                    <a href="/elyra/detalle?id=<?php echo (int)$destacado['id']; ?>" class="btn-ver-detalle">
                                        <i class="fa-solid fa-circle-info"></i>
                                        <span>Ver detalles</span>
                                    </a>

                                    <form method="POST" action="../../Controller/ControladorFavoritos.php">
                <?php echo Seguridad::campoCsrf(); ?>
                                        <input type="hidden" name="id_pelicula_serie" value="<?php echo (int)$destacado['id']; ?>">
                                        <input type="hidden" name="retorno" value="<?php echo Inicio::valorSeguro($retornoFavorito); ?>">
                                        <button type="submit" name="AlternarFavorito" value="1" class="btn-favorito-destacado <?php echo Inicio::valorSeguro($destacado['favoritoClase']); ?>" aria-label="<?php echo Inicio::valorSeguro($destacado['favoritoTexto']); ?>">
                                            <i class="fa-solid fa-heart"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                        <?php $indiceDestacado++; ?>
                    <?php } ?>
                </div>

                <?php if (count($destacados) > 1) { ?>
                    <button type="button" class="control-destacado anterior" data-slide-anterior aria-label="Anterior">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <button type="button" class="control-destacado siguiente" data-slide-siguiente aria-label="Siguiente">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>

                    <div class="puntos-destacado">
                        <?php for ($i = 0; $i < count($destacados); $i++) { ?>
                            <?php
                                $clasePunto = 'punto-destacado';
                                if ($i === 0) {
                                    $clasePunto .= ' activo';
                                }
                            ?>
                            <button type="button" class="<?php echo $clasePunto; ?>" data-punto-destacado="<?php echo (int)$i; ?>" aria-label="Ir al destacado <?php echo (int)($i + 1); ?>"></button>
                        <?php } ?>
                    </div>
                <?php } ?>

                <button type="button" class="sonido-destacado activo" data-sonido-destacado aria-label="Silenciar tráiler">
                    <i class="fa-solid fa-volume-high"></i>
                </button>
            </section>
        <?php } ?>

        <section class="seccion-contenido-inicio">
            <div class="encabezado-seccion-inicio">
                <h3>Recomendaciones para ti</h3>
            </div>

            <div class="grid-contenido-inicio">
                <?php if (!empty($recomendaciones)) { ?>
                    <?php foreach ($recomendaciones as $contenido) { ?>
                        <?php include '../Contenido/parcial-card-contenido.php'; ?>
                    <?php } ?>
                <?php } else { ?>
                    <p class="contenido-inicio-vacio">Aún no tenemos recomendaciones para ti.</p>
                <?php } ?>
            </div>
        </section>

        <section class="seccion-contenido-inicio">
            <div class="encabezado-seccion-inicio">
                <h3>Últimos agregados</h3>
            </div>

            <div class="grid-contenido-inicio">
                <?php if (!empty($ultimosAgregados)) { ?>
                    <?php foreach ($ultimosAgregados as $contenido) { ?>
                        <?php include '../Contenido/parcial-card-contenido.php'; ?>
                    <?php } ?>
                <?php } else { ?>
                    <p class="contenido-inicio-vacio">Aún no hay contenido agregado.</p>
                <?php } ?>
            </div>
        </section>

        <?php foreach ($panelesPersonalizados as $panelInicio) { ?>
            <section class="seccion-contenido-inicio">
                <div class="encabezado-seccion-inicio">
                    <div>
                        <h3><?php echo Inicio::valorSeguro($panelInicio['titulo']); ?></h3>
                        <?php if ($panelInicio['descripcion'] !== '') { ?>
                            <p><?php echo Inicio::valorSeguro($panelInicio['descripcion']); ?></p>
                        <?php } ?>
                    </div>
                </div>

                <div class="grid-contenido-inicio">
                    <?php foreach ($panelInicio['contenido'] as $contenido) { ?>
                        <?php include '../Contenido/parcial-card-contenido.php'; ?>
                    <?php } ?>
                </div>
            </section>
        <?php } ?>

        <section class="explorar-generos-inicio">
            <div class="encabezado-seccion-inicio">
                <h3>Explorar por género</h3>
            </div>

            <div class="lista-generos-inicio">
                <?php foreach ($generosInicio as $generoInicio) { ?>
                    <a href="/elyra/generos?genero=<?php echo (int)$generoInicio['id']; ?>" class="genero-inicio">
                        <i class="<?php echo Inicio::valorSeguro($generoInicio['icono']); ?>"></i>
                        <span><?php echo Inicio::valorSeguro($generoInicio['nombre']); ?></span>
                    </a>
                <?php } ?>
            </div>
        </section>
    </main>

    <script src="../../Assets/Js/dark-mode.js?v=vidrio-global-20260630"></script>
    <script src="../../Assets/Js/inicio-carrusel.js"></script>
</body>
</html>
