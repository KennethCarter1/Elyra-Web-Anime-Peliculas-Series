<?php
require_once __DIR__ . '/../../Models/Navegacion.php';

$nombreUsuario = Navegacion::nombreUsuario($_SESSION);
$mostrarBusquedaUsuario = !Navegacion::esAdministrador($_SESSION);
?>
<header class="header-principal">
    <div class="tema-switch">
        <label class="ui-switch">
            <input type="checkbox" id="toggle-theme">
            <div class="slider">
                <div class="circle"></div>
            </div>
        </label>
    </div>

    <?php if ($mostrarBusquedaUsuario) { ?>
        <form class="busqueda" autocomplete="off" data-busqueda-contenido data-endpoint-busqueda="../../Controller/ControladorBusquedaContenido.php">
            <input type="text" name="busqueda" placeholder="Buscar películas o series..." aria-label="Buscar películas o series" data-input-busqueda-contenido>
            <button type="button" class="btn-buscar" aria-label="Buscar">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
            <div class="resultados-busqueda-header" data-resultados-busqueda-contenido></div>
        </form>
    <?php } ?>

    <div class="usuario-menu">
        <i class="fa-solid fa-user logo-usuario"></i>
        <button class="btn-usuario" type="button">
            <?php echo htmlspecialchars($nombreUsuario); ?>
            <i class="fa-solid fa-chevron-down"></i>
        </button>

        <ul class="menu-desplegable">
            <li><a href="../Usuario/perfil.php">Perfil</a></li>
            <li><a href="../Usuario/CerrarSesion.php">Cerrar Sesión</a></li>
        </ul>
    </div>
</header>
<script src="../../Assets/Js/favoritos-ajax.js"></script>
<script src="../../Assets/Js/header-busqueda-contenido.js"></script>
