<?php
session_start();
require_once '../../Models/Inicio.php';
require_once '../../Models/Navegacion.php';
require_once '../../Models/Sesion.php';
require_once '../../Api/soap/ClienteSOAP.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: /elyra/login');
    exit;
}

$filtros = Inicio::filtrosFavoritos($_GET);
$filtrosBase = Inicio::filtrosFavoritos([]);
$favoritosBase = Inicio::listaInicial();
$favoritos = Inicio::listaInicial();
$aniosFavoritos = Inicio::listaInicial();
$resumenFavoritos = [
    'total' => 0,
    'peliculas' => 0,
    'series' => 0
];
$mensajeFavorito = Sesion::tomarMensaje('mensaje_favorito');
$tipoMensajeFavorito = Sesion::tomarMensaje('tipo_mensaje_favorito', 'exito');

try {
    $clienteSOAP = new ClienteSOAP();
    $favoritosBase = Inicio::normalizarListaContenido(
        $clienteSOAP->favoritosUsuario($_SESSION['usuario'], $filtrosBase)
    );

    if (Inicio::hayFiltrosFavoritos($filtros)) {
        $favoritos = Inicio::normalizarListaContenido(
            $clienteSOAP->favoritosUsuario($_SESSION['usuario'], $filtros)
        );
    } else {
        $favoritos = $favoritosBase;
    }

    $resumenFavoritos = Inicio::resumenFavoritos($favoritosBase);
    $aniosFavoritos = Inicio::aniosExplorar($favoritosBase);
} catch (Exception $e) {
    Navegacion::redirigirErrorBaseDatosVista('/elyra/favoritos', $_SERVER);
}

$retornoFavorito = Inicio::retornoFavoritoFavoritos($filtros);
$urlDetalleContenido = '/elyra/detalle';
$tiposFavoritos = Inicio::tiposExplorar();
$ordenesFavoritos = Inicio::ordenesFavoritos();
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
    <link rel="stylesheet" href="../../Assets/Css/Favoritos.css?v=vidrio-global-20260630">
    <link rel="stylesheet" href="../../Assets/Css/switch.css">
    <title>Favoritos</title>
</head>
<body>
    <?php include '../Parciales/header.php'; ?>
    <?php include '../Parciales/sidebar.php'; ?>

    <main class="contenido-principal favoritos-usuario">
        <section class="encabezado-favoritos">
            <h1>Favoritos</h1>
            <p>Tus películas y series guardadas para ver después.</p>
        </section>

        <?php if ($mensajeFavorito !== '') { ?>
            <div class="mensaje-inicio mensaje-<?php echo Inicio::valorSeguro($tipoMensajeFavorito); ?>">
                <?php echo Inicio::valorSeguro($mensajeFavorito); ?>
            </div>
        <?php } ?>

        <section class="resumen-favoritos">
            <article>
                <i class="fa-solid fa-heart"></i>
                <span>Total de favoritos</span>
                <strong data-resumen-favoritos="total"><?php echo (int)$resumenFavoritos['total']; ?></strong>
            </article>

            <article>
                <i class="fa-solid fa-film"></i>
                <span>Películas guardadas</span>
                <strong data-resumen-favoritos="peliculas"><?php echo (int)$resumenFavoritos['peliculas']; ?></strong>
            </article>

            <article>
                <i class="fa-solid fa-tv"></i>
                <span>Series guardadas</span>
                <strong data-resumen-favoritos="series"><?php echo (int)$resumenFavoritos['series']; ?></strong>
            </article>
        </section>

        <section class="filtros-favoritos">
            <form method="GET" action="favoritos.php" class="form-filtros-favoritos">
                <div class="grupo-tipos-favoritos">
                    <span>Tipo</span>
                    <div>
                        <?php foreach ($tiposFavoritos as $tipoFavorito) { ?>
                            <button type="submit" name="tipo" value="<?php echo Inicio::valorSeguro($tipoFavorito); ?>" class="chip-tipo-favorito <?php echo Inicio::valorSeguro(Inicio::claseFiltroExplorar($filtros['tipo'], $tipoFavorito)); ?>">
                                <?php echo Inicio::valorSeguro($tipoFavorito); ?>
                            </button>
                        <?php } ?>
                    </div>
                </div>

                <div class="campo-filtro-favorito">
                    <label for="anio">Año</label>
                    <select name="anio" id="anio">
                        <option value="0">Todos</option>
                        <?php foreach ($aniosFavoritos as $anioFavorito) { ?>
                            <option value="<?php echo (int)$anioFavorito; ?>"<?php if ((int)$filtros['anio'] === (int)$anioFavorito) { echo ' selected'; } ?>>
                                <?php echo (int)$anioFavorito; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="campo-filtro-favorito">
                    <label for="orden">Ordenar por</label>
                    <select name="orden" id="orden">
                        <?php foreach ($ordenesFavoritos as $valorOrden => $textoOrden) { ?>
                            <option value="<?php echo Inicio::valorSeguro($valorOrden); ?>"<?php if ($filtros['orden'] === $valorOrden) { echo ' selected'; } ?>>
                                <?php echo Inicio::valorSeguro($textoOrden); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <button type="submit" class="btn-aplicar-favorito">
                    <i class="fa-solid fa-filter"></i>
                    <span>Filtrar</span>
                </button>

                <a href="favoritos.php" class="btn-limpiar-favorito" aria-label="Limpiar filtros">
                    <i class="fa-solid fa-rotate-right"></i>
                </a>
            </form>
        </section>

        <section class="catalogo-favoritos">
            <?php if (!empty($favoritos)) { ?>
                <div class="grid-favoritos-contenido" data-lista-favoritos>
                    <?php foreach ($favoritos as $contenido) { ?>
                        <?php include 'parcial-card-contenido.php'; ?>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="favoritos-vacio" data-favoritos-vacio>
                    <i class="fa-solid fa-heart-crack"></i>
                    <h2>Aún no tienes favoritos</h2>
                    <p>Guarda películas y series para encontrarlas rápido después.</p>
                    <a href="explorar.php">Explorar contenido</a>
                </div>
            <?php } ?>
        </section>
    </main>

    <script src="../../Assets/Js/dark-mode.js?v=vidrio-global-20260630"></script>
</body>
</html>
