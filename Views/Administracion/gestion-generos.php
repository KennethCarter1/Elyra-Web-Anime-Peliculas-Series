<?php
session_start();
require_once '../../Models/Genero.php';
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

$generosGestion = Genero::listadoGestionInicial();
$busquedaGenero = Genero::valorBusqueda($_GET);
$mensajeGenero = Sesion::tomarMensaje('mensaje_genero');
$tipoMensajeGenero = Sesion::tomarMensaje('tipo_mensaje_genero');

try {
    $clienteSOAP = new ClienteSOAP();
    $generosGestion = Genero::normalizarGestion($clienteSOAP->listarGenerosGestion());
} catch (Exception $e) {
    Navegacion::redirigirErrorBaseDatosVista('../Administracion/gestion-generos.php', $_SERVER);
}

$generosFiltrados = Genero::filtrarGestion($generosGestion, $busquedaGenero);
$totalGeneros = count($generosGestion);
$totalActivos = Genero::contarActivos($generosGestion);
$totalInactivos = Genero::contarInactivos($generosGestion);
$totalUsoContenido = Genero::totalUsoContenido($generosGestion);

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
    <link rel="stylesheet" href="../../Assets/Css/GestionGeneros.css">
    <link rel="stylesheet" href="../../Assets/Css/switch.css">
    <title>Gestión de géneros</title>
</head>
<body>
    <?php include '../Parciales/header.php'; ?>
    <?php include '../Parciales/sidebar.php'; ?>

    <main class="contenido-principal pagina-gestion-generos">
        <section class="encabezado-gestion-generos">
            <div>
                <h1>Gestión de géneros</h1>
                <p>Administra los géneros usados en películas, series y preferencias.</p>
            </div>
        </section>

        <?php if ($mensajeGenero !== '') { ?>
            <div class="mensaje-genero mensaje-<?php echo Genero::valorSeguro($tipoMensajeGenero); ?>">
                <?php echo Genero::valorSeguro($mensajeGenero); ?>
            </div>
        <?php } ?>

        <section class="resumen-generos">
            <article class="tarjeta-genero-resumen tarjeta-morado">
                <span><i class="fa-solid fa-layer-group"></i></span>
                <div>
                    <h2>Total de géneros</h2>
                    <strong><?php echo (int)$totalGeneros; ?></strong>
                </div>
            </article>

            <article class="tarjeta-genero-resumen tarjeta-verde">
                <span><i class="fa-solid fa-check"></i></span>
                <div>
                    <h2>Activos</h2>
                    <strong><?php echo (int)$totalActivos; ?></strong>
                </div>
            </article>

            <article class="tarjeta-genero-resumen tarjeta-gris">
                <span><i class="fa-solid fa-ban"></i></span>
                <div>
                    <h2>Desactivados</h2>
                    <strong><?php echo (int)$totalInactivos; ?></strong>
                </div>
            </article>

            <article class="tarjeta-genero-resumen tarjeta-azul">
                <span><i class="fa-solid fa-photo-film"></i></span>
                <div>
                    <h2>Usos en contenido</h2>
                    <strong><?php echo (int)$totalUsoContenido; ?></strong>
                </div>
            </article>
        </section>

        <section class="panel-superior-generos">
            <form method="POST" action="../../Controller/ControladorGenero.php" class="form-nuevo-genero">
                <label for="nombre_genero">Agregar género</label>
                <div>
                    <input id="nombre_genero" type="text" name="nombre_genero" placeholder="Ej: Suspenso">
                    <button type="submit" name="CrearGenero" value="1">
                        <i class="fa-solid fa-plus"></i>
                        <span>Agregar</span>
                    </button>
                </div>
            </form>

            <form method="GET" action="gestion-generos.php" class="form-buscar-genero">
                <label for="busqueda">Buscar género</label>
                <div>
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input id="busqueda" type="text" name="busqueda" placeholder="Buscar por nombre..." value="<?php echo Genero::valorSeguro($busquedaGenero); ?>">
                    <button type="submit" aria-label="Buscar género">
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                    <a href="gestion-generos.php" aria-label="Reiniciar búsqueda">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                </div>
            </form>
        </section>

        <section class="panel-lista-generos">
            <div class="encabezado-lista-generos">
                <h2>Lista de géneros</h2>
                <span><?php echo count($generosFiltrados); ?> resultados</span>
            </div>

            <div class="tabla-generos">
                <div class="fila-genero encabezado-tabla-genero">
                    <span>Género</span>
                    <span>Estado</span>
                    <span>Contenido</span>
                    <span>Preferencias</span>
                    <span>Acciones</span>
                </div>

                <?php if (!empty($generosFiltrados)) { ?>
                    <?php foreach ($generosFiltrados as $genero) { ?>
                        <div class="fila-genero">
                            <form method="POST" action="../../Controller/ControladorGenero.php" class="form-editar-genero">
                                <input type="hidden" name="id_genero" value="<?php echo (int)$genero['id']; ?>">
                                <input type="text" name="nombre_genero" value="<?php echo Genero::valorSeguro($genero['nombre']); ?>">
                                <button type="submit" name="ActualizarGenero" value="1" aria-label="Actualizar género">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                            </form>

                            <span>
                                <small class="estado-genero estado-<?php echo Genero::valorSeguro($genero['estadoClase']); ?>">
                                    <?php echo Genero::valorSeguro($genero['estado']); ?>
                                </small>
                            </span>

                            <span><?php echo (int)$genero['totalContenido']; ?></span>
                            <span><?php echo (int)$genero['totalPreferencias']; ?></span>

                            <div class="acciones-genero">
                                <form method="POST" action="../../Controller/ControladorGenero.php">
                                    <input type="hidden" name="id_genero" value="<?php echo (int)$genero['id']; ?>">
                                    <?php if ((int)$genero['activo'] === 1) { ?>
                                        <button type="submit" name="DesactivarGenero" value="1" class="btn-desactivar-genero">
                                            <i class="fa-solid fa-ban"></i>
                                            <span>Desactivar</span>
                                        </button>
                                    <?php } else { ?>
                                        <button type="submit" name="ActivarGenero" value="1" class="btn-activar-genero">
                                            <i class="fa-solid fa-check"></i>
                                            <span>Activar</span>
                                        </button>
                                    <?php } ?>
                                </form>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p class="generos-vacios">No se encontraron géneros con esa búsqueda.</p>
                <?php } ?>
            </div>
        </section>
    </main>

    <script src="../../Assets/Js/dark-mode.js"></script>
</body>
</html>
