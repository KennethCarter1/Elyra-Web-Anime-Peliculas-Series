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

$filtros = Inicio::filtrosExplorar($_GET);
$filtrosBase = Inicio::filtrosExplorar([]);
$contenidoExplorar = Inicio::listaInicial();
$contenidoBaseExplorar = Inicio::listaInicial();
$generosExplorar = Inicio::listaInicial();
$aniosExplorar = Inicio::listaInicial();
$mensajeFavorito = Sesion::tomarMensaje('mensaje_favorito');
$tipoMensajeFavorito = Sesion::tomarMensaje('tipo_mensaje_favorito', 'exito');

try {
    $clienteSOAP = new ClienteSOAP();
    $contenidoBaseExplorar = Inicio::normalizarListaContenido(
        $clienteSOAP->explorarContenidoUsuario($_SESSION['usuario'], $filtrosBase)
    );

    if (Inicio::hayFiltrosExplorar($filtros)) {
        $contenidoExplorar = Inicio::normalizarListaContenido(
            $clienteSOAP->explorarContenidoUsuario($_SESSION['usuario'], $filtros)
        );
    } else {
        $contenidoExplorar = $contenidoBaseExplorar;
    }

    $generosExplorar = Genero::normalizarListado($clienteSOAP->listarGeneros());
    $aniosExplorar = Inicio::aniosExplorar($contenidoBaseExplorar);
} catch (Exception $e) {
    Navegacion::redirigirErrorBaseDatosVista('/elyra/explorar', $_SERVER);
}

$retornoFavorito = Inicio::retornoFavoritoExplorar($filtros);
$urlDetalleContenido = '/elyra/detalle';
$tiposExplorar = Inicio::tiposExplorar();
$ordenesExplorar = Inicio::ordenesExplorar();
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
    <link rel="stylesheet" href="../../Assets/Css/Explorar.css?v=vidrio-global-20260630">
    <link rel="stylesheet" href="../../Assets/Css/switch.css">
    <title>Explorar</title>
</head>
<body>
    <?php include '../Parciales/header.php'; ?>
    <?php include '../Parciales/sidebar.php'; ?>

    <main class="contenido-principal explorar-usuario">
        <section class="encabezado-explorar">
            <div>
                <h1>Explorar</h1>
                <p>Encuentra películas y series para ver hoy.</p>
            </div>
        </section>

        <?php if ($mensajeFavorito !== '') { ?>
            <div class="mensaje-inicio mensaje-<?php echo Inicio::valorSeguro($tipoMensajeFavorito); ?>">
                <?php echo Inicio::valorSeguro($mensajeFavorito); ?>
            </div>
        <?php } ?>

        <section class="panel-explorar">
            <form method="GET" action="explorar.php" class="form-explorar">
                <div class="buscador-explorar">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="busqueda" value="<?php echo Inicio::valorSeguro($filtros['busqueda']); ?>" placeholder="Buscar por título..." autocomplete="off">
                    <button type="submit" aria-label="Buscar">
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>

                <div class="bloque-filtros-explorar">
                    <div class="grupo-tipos-explorar">
                        <span>Tipo</span>
                        <div>
                            <?php foreach ($tiposExplorar as $tipoExplorar) { ?>
                                <button type="submit" name="tipo" value="<?php echo Inicio::valorSeguro($tipoExplorar); ?>" class="chip-tipo-explorar <?php echo Inicio::valorSeguro(Inicio::claseFiltroExplorar($filtros['tipo'], $tipoExplorar)); ?>">
                                    <?php echo Inicio::valorSeguro($tipoExplorar); ?>
                                </button>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="campo-filtro-explorar">
                        <label for="genero">Género</label>
                        <select name="genero" id="genero">
                            <option value="Todos">Todos</option>
                            <?php foreach ($generosExplorar as $generoExplorar) { ?>
                                <option value="<?php echo Inicio::valorSeguro($generoExplorar); ?>"<?php if ($filtros['genero'] === $generoExplorar) { echo ' selected'; } ?>>
                                    <?php echo Inicio::valorSeguro($generoExplorar); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="campo-filtro-explorar">
                        <label for="anio">Año</label>
                        <select name="anio" id="anio">
                            <option value="0">Todos</option>
                            <?php foreach ($aniosExplorar as $anioExplorar) { ?>
                                <option value="<?php echo (int)$anioExplorar; ?>"<?php if ((int)$filtros['anio'] === (int)$anioExplorar) { echo ' selected'; } ?>>
                                    <?php echo (int)$anioExplorar; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="campo-filtro-explorar">
                        <label for="orden">Ordenar por</label>
                        <select name="orden" id="orden">
                            <?php foreach ($ordenesExplorar as $valorOrden => $textoOrden) { ?>
                                <option value="<?php echo Inicio::valorSeguro($valorOrden); ?>"<?php if ($filtros['orden'] === $valorOrden) { echo ' selected'; } ?>>
                                    <?php echo Inicio::valorSeguro($textoOrden); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <button type="submit" class="btn-aplicar-explorar">
                        <i class="fa-solid fa-filter"></i>
                        <span>Filtrar</span>
                    </button>

                    <a href="explorar.php" class="btn-limpiar-explorar" aria-label="Limpiar filtros">
                        <i class="fa-solid fa-rotate-right"></i>
                    </a>
                </div>
            </form>
        </section>

        <section class="catalogo-explorar">
            <?php if (!empty($contenidoExplorar)) { ?>
                <div class="grid-explorar-contenido">
                    <?php foreach ($contenidoExplorar as $contenido) { ?>
                        <?php include 'parcial-card-contenido.php'; ?>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="explorar-vacio">
                    <i class="fa-solid fa-compass"></i>
                    <h2>No encontramos contenido</h2>
                    <p>Prueba con otro título, género o año.</p>
                </div>
            <?php } ?>
        </section>
    </main>

    <script src="../../Assets/Js/dark-mode.js?v=vidrio-global-20260630"></script>
</body>
</html>
