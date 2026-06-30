<?php
session_start();
require_once '../../Models/Inicio.php';
require_once '../../Models/Genero.php';
require_once '../../Models/Navegacion.php';
require_once '../../Models/Sesion.php';
require_once '../../Api/soap/ClienteSOAP.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: /elyra/login');
    exit;
}

$filtros = Inicio::filtrosGenero($_GET);
$filtrosBaseGenero = $filtros;
$filtrosBaseGenero['tipo'] = 'Todos';
$filtrosBaseGenero['anio'] = 0;
$filtrosBaseGenero['orden'] = 'ultimos';

$generosUsuario = Inicio::listaInicial();
$contenidoGenero = Inicio::listaInicial();
$contenidoBaseGenero = Inicio::listaInicial();
$aniosGenero = Inicio::listaInicial();
$mensajeFavorito = Sesion::tomarMensaje('mensaje_favorito');
$tipoMensajeFavorito = Sesion::tomarMensaje('tipo_mensaje_favorito', 'exito');

try {
    $clienteSOAP = new ClienteSOAP();
    $generosUsuario = Genero::normalizarUsuario($clienteSOAP->listarGenerosGestion());

    $contenidoBaseGenero = Inicio::normalizarListaContenido(
        $clienteSOAP->contenidoPorGeneroUsuario($_SESSION['usuario'], $filtrosBaseGenero)
    );

    if ($filtros['tipo'] !== 'Todos' || (int)$filtros['anio'] > 0 || $filtros['orden'] !== 'ultimos') {
        $contenidoGenero = Inicio::normalizarListaContenido(
            $clienteSOAP->contenidoPorGeneroUsuario($_SESSION['usuario'], $filtros)
        );
    } else {
        $contenidoGenero = $contenidoBaseGenero;
    }

    $aniosGenero = Inicio::aniosExplorar($contenidoBaseGenero);
} catch (Exception $e) {
    Navegacion::redirigirErrorBaseDatosVista('/elyra/generos', $_SERVER);
}

$nombreGeneroSeleccionado = Genero::nombreUsuarioPorId($generosUsuario, $filtros['genero']);
$retornoFavorito = Inicio::retornoFavoritoGenero($filtros);
$urlDetalleContenido = '/elyra/detalle';
$tiposGenero = Inicio::tiposExplorar();
$ordenesGenero = Inicio::ordenesExplorar();
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
    <link rel="stylesheet" href="../../Assets/Css/Generos.css?v=vidrio-global-20260630">
    <link rel="stylesheet" href="../../Assets/Css/switch.css">
    <title>Géneros</title>
</head>
<body>
    <?php include '../Parciales/header.php'; ?>
    <?php include '../Parciales/sidebar.php'; ?>

    <main class="contenido-principal generos-usuario">
        <section class="encabezado-generos">
            <h1>Géneros</h1>
            <p>Explora contenido por tus categorías favoritas.</p>
        </section>

        <?php if ($mensajeFavorito !== '') { ?>
            <div class="mensaje-inicio mensaje-<?php echo Inicio::valorSeguro($tipoMensajeFavorito); ?>">
                <?php echo Inicio::valorSeguro($mensajeFavorito); ?>
            </div>
        <?php } ?>

        <section class="panel-generos-usuario">
            <div class="lista-generos-usuario">
                <a href="generos.php" class="chip-genero-usuario <?php echo Inicio::valorSeguro(Inicio::claseGeneroSeleccionado($filtros['genero'], 0)); ?>">
                    <i class="fa-solid fa-layer-group"></i>
                    <span>Todos</span>
                </a>

                <?php foreach ($generosUsuario as $generoUsuario) { ?>
                    <a href="<?php echo Inicio::valorSeguro(Inicio::enlaceGeneroUsuario($generoUsuario['id'])); ?>" class="chip-genero-usuario <?php echo Inicio::valorSeguro(Inicio::claseGeneroSeleccionado($filtros['genero'], $generoUsuario['id'])); ?>">
                        <i class="<?php echo Inicio::valorSeguro($generoUsuario['icono']); ?>"></i>
                        <span><?php echo Inicio::valorSeguro($generoUsuario['nombre']); ?></span>
                    </a>
                <?php } ?>
            </div>
        </section>

        <section class="filtros-genero-usuario">
            <div class="titulo-genero-seleccionado">
                <i class="fa-solid fa-tags"></i>
                <div>
                    <h2><?php echo Inicio::valorSeguro($nombreGeneroSeleccionado); ?></h2>
                    <p>Filtra la categoría por tipo, año u orden.</p>
                </div>
            </div>

            <form method="GET" action="generos.php" class="form-filtros-genero">
                <input type="hidden" name="genero" value="<?php echo (int)$filtros['genero']; ?>">

                <div class="grupo-tipos-genero">
                    <span>Tipo</span>
                    <div>
                        <?php foreach ($tiposGenero as $tipoGenero) { ?>
                            <button type="submit" name="tipo" value="<?php echo Inicio::valorSeguro($tipoGenero); ?>" class="chip-tipo-genero <?php echo Inicio::valorSeguro(Inicio::claseFiltroExplorar($filtros['tipo'], $tipoGenero)); ?>">
                                <?php echo Inicio::valorSeguro($tipoGenero); ?>
                            </button>
                        <?php } ?>
                    </div>
                </div>

                <div class="campo-filtro-genero">
                    <label for="anio">Año</label>
                    <select name="anio" id="anio">
                        <option value="0">Todos</option>
                        <?php foreach ($aniosGenero as $anioGenero) { ?>
                            <option value="<?php echo (int)$anioGenero; ?>"<?php if ((int)$filtros['anio'] === (int)$anioGenero) { echo ' selected'; } ?>>
                                <?php echo (int)$anioGenero; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="campo-filtro-genero">
                    <label for="orden">Ordenar por</label>
                    <select name="orden" id="orden">
                        <?php foreach ($ordenesGenero as $valorOrden => $textoOrden) { ?>
                            <option value="<?php echo Inicio::valorSeguro($valorOrden); ?>"<?php if ($filtros['orden'] === $valorOrden) { echo ' selected'; } ?>>
                                <?php echo Inicio::valorSeguro($textoOrden); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <button type="submit" class="btn-aplicar-genero">
                    <i class="fa-solid fa-filter"></i>
                    <span>Filtrar</span>
                </button>

                <a href="<?php echo Inicio::valorSeguro(Inicio::enlaceFiltroGenero($filtros)); ?>" class="btn-limpiar-genero" aria-label="Limpiar filtros">
                    <i class="fa-solid fa-rotate-right"></i>
                </a>
            </form>
        </section>

        <section class="catalogo-genero-usuario">
            <?php if (!empty($contenidoGenero)) { ?>
                <div class="grid-genero-contenido">
                    <?php foreach ($contenidoGenero as $contenido) { ?>
                        <?php include 'parcial-card-contenido.php'; ?>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="genero-vacio">
                    <i class="fa-solid fa-clapperboard"></i>
                    <h2>No hay contenido para este género</h2>
                    <p>Prueba con otra categoría o limpia los filtros.</p>
                </div>
            <?php } ?>
        </section>
    </main>

    <script src="../../Assets/Js/dark-mode.js?v=vidrio-global-20260630"></script>
</body>
</html>
