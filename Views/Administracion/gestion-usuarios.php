<?php
session_start();
require_once '../../Models/Usuario.php';
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

$filtros = Usuario::filtrosGestionDesdeSolicitud($_GET);
$resumenGestion = Usuario::resumenGestionInicial();
$usuariosGestion = Usuario::listaGestionInicial();
$detalleUsuario = Usuario::detalleGestionInicial();

$mensajeGestionUsuario = Sesion::tomarMensaje('mensaje_gestion_usuario');
$tipoMensajeGestionUsuario = Sesion::tomarMensaje('tipo_mensaje_gestion_usuario');

try {
    $clienteSOAP = new ClienteSOAP();
    $resumenGestion = Usuario::normalizarResumenGestion($clienteSOAP->resumenGestionUsuarios());
    $usuariosGestion = Usuario::normalizarListaGestion($clienteSOAP->listarUsuariosGestion($filtros));

    if ($filtros['id'] > 0) {
        $detalleUsuario = Usuario::normalizarDetalleGestion($clienteSOAP->obtenerDetalleUsuarioGestion($filtros['id']));
    }
} catch (Exception $e) {
    Navegacion::redirigirErrorBaseDatosVista('../Administracion/gestion-usuarios.php', $_SERVER);
}

$tarjetasResumen = Usuario::tarjetasResumenGestion($resumenGestion);
$opcionesRol = Usuario::opcionesRolGestion();
$opcionesRolAccion = Usuario::opcionesRolAccionGestion();
$opcionesGenero = Usuario::opcionesGeneroGestion();

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
    <link rel="stylesheet" href="../../Assets/Css/GestionUsuarios.css">
    <link rel="stylesheet" href="../../Assets/Css/switch.css">
    <title>Gestión de usuarios</title>
</head>
<body>
    <?php include '../Parciales/header.php'; ?>
    <?php include '../Parciales/sidebar.php'; ?>

    <main class="contenido-principal gestion-usuarios-admin">
        <section class="gestion-usuarios-grid">
            <div class="gestion-usuarios-izquierda">
                <section class="encabezado-gestion-usuarios">
                    <div>
                        <h1>Gestión de usuarios</h1>
                        <p>Administra las cuentas registradas en la plataforma.</p>
                    </div>
                </section>

                <?php if ($mensajeGestionUsuario !== '') { ?>
                    <div class="mensaje-gestion-usuario mensaje-<?php echo Usuario::valorSeguroGestion($tipoMensajeGestionUsuario); ?>">
                        <?php echo Usuario::valorSeguroGestion($mensajeGestionUsuario); ?>
                    </div>
                <?php } ?>

                <section class="resumen-gestion-usuarios">
                    <?php foreach ($tarjetasResumen as $tarjeta) { ?>
                        <article class="tarjeta-usuario-resumen tarjeta-<?php echo Usuario::valorSeguroGestion($tarjeta['color']); ?>">
                            <span class="icono-tarjeta-usuario">
                                <i class="<?php echo Usuario::valorSeguroGestion($tarjeta['icono']); ?>"></i>
                            </span>
                            <div>
                                <h2><?php echo Usuario::valorSeguroGestion($tarjeta['titulo']); ?></h2>
                                <strong><?php echo Usuario::formatearNumeroGestion($tarjeta['valor']); ?></strong>
                            </div>
                        </article>
                    <?php } ?>
                </section>

                <section class="filtros-gestion-usuarios">
                    <form method="GET" action="gestion-usuarios.php">
                        <div class="campo-busqueda-usuario">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" name="busqueda" placeholder="Buscar por nombre, usuario o correo..." value="<?php echo Usuario::valorFiltroGestion($filtros, 'busqueda'); ?>">
                        </div>

                        <div class="campo-filtro-usuario">
                            <label for="rol">Filtrar por rol</label>
                            <select id="rol" name="rol">
                                <?php foreach ($opcionesRol as $opcionRol) { ?>
                                    <option value="<?php echo Usuario::valorSeguroGestion($opcionRol['valor']); ?>"<?php echo Usuario::seleccionarGestion($filtros['rol'], $opcionRol['valor']); ?>>
                                        <?php echo Usuario::valorSeguroGestion($opcionRol['texto']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="campo-filtro-usuario">
                            <label for="genero">Filtrar por género</label>
                            <select id="genero" name="genero">
                                <?php foreach ($opcionesGenero as $opcionGenero) { ?>
                                    <option value="<?php echo Usuario::valorSeguroGestion($opcionGenero['valor']); ?>"<?php echo Usuario::seleccionarGestion($filtros['genero'], $opcionGenero['valor']); ?>>
                                        <?php echo Usuario::valorSeguroGestion($opcionGenero['texto']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <button type="submit" class="btn-aplicar-usuarios" aria-label="Aplicar filtros">
                            <i class="fa-solid fa-filter"></i>
                        </button>

                        <a href="gestion-usuarios.php" class="btn-limpiar-usuarios" aria-label="Reiniciar filtros">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    </form>
                </section>

                <article class="lista-gestion-usuarios">
                    <div class="encabezado-lista-usuarios">
                        <h2>Lista de usuarios</h2>
                        <span><?php echo count($usuariosGestion); ?> resultados</span>
                    </div>

                    <div class="tabla-gestion-usuarios">
                        <div class="fila-gestion-usuario encabezado-tabla-usuarios">
                            <span>Usuario</span>
                            <span>Correo</span>
                            <span>Rol</span>
                            <span>Género</span>
                            <span>Fecha</span>
                            <span>Estado</span>
                            <span>Información</span>
                        </div>

                        <?php if (!empty($usuariosGestion)) { ?>
                            <?php foreach ($usuariosGestion as $usuarioGestion) { ?>
                                <div class="fila-gestion-usuario">
                                    <div class="usuario-tabla-datos">
                                        <span class="avatar-usuario-tabla"><?php echo Usuario::valorSeguroGestion($usuarioGestion['iniciales']); ?></span>
                                        <div>
                                            <strong><?php echo Usuario::valorSeguroGestion($usuarioGestion['nombre']); ?></strong>
                                            <small>@<?php echo Usuario::valorSeguroGestion($usuarioGestion['usuario']); ?></small>
                                        </div>
                                    </div>

                                    <span><?php echo Usuario::valorSeguroGestion($usuarioGestion['correo']); ?></span>
                                    <span class="chip-usuario chip-rol-<?php echo Usuario::valorSeguroGestion($usuarioGestion['rolValor']); ?>">
                                        <?php echo Usuario::valorSeguroGestion($usuarioGestion['rol']); ?>
                                    </span>
                                    <span class="chip-genero-usuario genero-<?php echo Usuario::valorSeguroGestion($usuarioGestion['generoValor']); ?>">
                                        <?php echo Usuario::valorSeguroGestion($usuarioGestion['genero']); ?>
                                    </span>
                                    <span><?php echo Usuario::valorSeguroGestion($usuarioGestion['fechaCreacion']); ?></span>
                                    <span>
                                        <small class="estado-usuario estado-<?php echo Usuario::valorSeguroGestion($usuarioGestion['estadoClase']); ?>">
                                            <?php echo Usuario::valorSeguroGestion($usuarioGestion['estado']); ?>
                                        </small>
                                    </span>
                                    <span>
                                        <a href="<?php echo Usuario::valorSeguroGestion(Usuario::enlaceInformacionGestion($filtros, $usuarioGestion['id'])); ?>" class="btn-info-usuario" aria-label="Ver información">
                                            <i class="fa-solid fa-circle-info"></i>
                                        </a>
                                    </span>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <p class="usuarios-gestion-vacio">No se encontraron usuarios con esos filtros.</p>
                        <?php } ?>
                    </div>
                </article>
            </div>

            <aside class="panel-detalle-usuario">
                <div class="encabezado-detalle-usuario">
                    <h2>Información del usuario</h2>
                    <a href="<?php echo Usuario::valorSeguroGestion(Usuario::enlaceSinInformacionGestion($filtros)); ?>" aria-label="Cerrar información">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>

                <?php if (!empty($detalleUsuario) && $detalleUsuario['id'] > 0) { ?>
                    <div class="detalle-usuario-superior">
                        <span class="avatar-detalle-usuario"><?php echo Usuario::valorSeguroGestion($detalleUsuario['iniciales']); ?></span>
                        <div>
                            <h2><?php echo Usuario::valorSeguroGestion($detalleUsuario['nombre']); ?></h2>
                            <p>@<?php echo Usuario::valorSeguroGestion($detalleUsuario['usuario']); ?></p>
                            <span class="estado-usuario estado-<?php echo Usuario::valorSeguroGestion($detalleUsuario['estadoClase']); ?>">
                                <?php echo Usuario::valorSeguroGestion($detalleUsuario['estado']); ?>
                            </span>
                        </div>
                    </div>

                    <div class="datos-detalle-usuario">
                        <div><span>Nombre</span><strong><?php echo Usuario::valorSeguroGestion($detalleUsuario['nombre']); ?></strong></div>
                        <div><span>Usuario</span><strong>@<?php echo Usuario::valorSeguroGestion($detalleUsuario['usuario']); ?></strong></div>
                        <div><span>Correo</span><strong><?php echo Usuario::valorSeguroGestion($detalleUsuario['correo']); ?></strong></div>
                        <div><span>Fecha de nacimiento</span><strong><?php echo Usuario::valorSeguroGestion($detalleUsuario['fechaNacimiento']); ?></strong></div>
                        <div><span>Género</span><strong><?php echo Usuario::valorSeguroGestion($detalleUsuario['genero']); ?></strong></div>
                        <div><span>Fecha de creación</span><strong><?php echo Usuario::valorSeguroGestion($detalleUsuario['fechaCreacion']); ?></strong></div>
                    </div>

                    <div class="preferencias-detalle-usuario">
                        <h3>Preferencias del usuario</h3>
                        <?php if ($detalleUsuario['preferencias'] !== '') { ?>
                            <p><?php echo Usuario::valorSeguroGestion($detalleUsuario['preferencias']); ?></p>
                        <?php } else { ?>
                            <p>Sin preferencias guardadas</p>
                        <?php } ?>
                    </div>

                    <form method="POST" action="../../Controller/ControladorGestionUsuario.php" class="form-rol-usuario">
                        <input type="hidden" name="id_usuario" value="<?php echo (int)$detalleUsuario['id']; ?>">
                        <label for="rol_detalle">Rol</label>
                        <select id="rol_detalle" name="rol">
                            <?php foreach ($opcionesRolAccion as $opcionRolAccion) { ?>
                                <option value="<?php echo Usuario::valorSeguroGestion($opcionRolAccion['valor']); ?>"<?php echo Usuario::seleccionarGestion($detalleUsuario['rolValor'], $opcionRolAccion['valor']); ?>>
                                    <?php echo Usuario::valorSeguroGestion($opcionRolAccion['texto']); ?>
                                </option>
                            <?php } ?>
                        </select>
                        <button type="submit" name="ActualizarRolUsuarioGestion" value="1">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span>Actualizar rol</span>
                        </button>
                    </form>

                    <div class="acciones-detalle-usuario">
                        <form method="POST" action="../../Controller/ControladorGestionUsuario.php">
                            <input type="hidden" name="id_usuario" value="<?php echo (int)$detalleUsuario['id']; ?>">
                            <?php if ((int)$detalleUsuario['activo'] === 1) { ?>
                                <button type="submit" name="DesactivarUsuarioGestion" value="1" class="btn-desactivar-usuario">
                                    <i class="fa-solid fa-ban"></i>
                                    <span>Desactivar cuenta</span>
                                </button>
                            <?php } else { ?>
                                <button type="submit" name="ActivarUsuarioGestion" value="1" class="btn-activar-usuario">
                                    <i class="fa-solid fa-check"></i>
                                    <span>Activar cuenta</span>
                                </button>
                            <?php } ?>
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="detalle-usuario-vacio">
                        <i class="fa-solid fa-user"></i>
                        <h2>Información del usuario</h2>
                        <p>Selecciona un usuario para ver su información completa.</p>
                    </div>
                <?php } ?>
            </aside>
        </section>
    </main>

    <script src="../../Assets/Js/dark-mode.js"></script>
</body>
</html>
