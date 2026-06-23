<?php
require_once __DIR__ . '/../../Models/Navegacion.php';

$tipoSidebarActual = '';
if (isset($tipoSidebar)) {
    $tipoSidebarActual = $tipoSidebar;
}
$paginaActual = basename($_SERVER['PHP_SELF']);
$menuPrincipal = Navegacion::menuPrincipal($tipoSidebarActual, $_SESSION);
$textoPerfil = Navegacion::textoPerfil($tipoSidebarActual, $_SESSION);
?>
<aside class="sidebar-principal">
    <!-- Logo y nombre de la página -->
    <div class="sidebar-logo">
        <img src="../../Assets/Images/logos/logos/morado.png" alt="Logo" class="logo-pagina" data-accent-logo>
        <div class="nombre-pagina">
            <h1>ELYRA</h1>
            <p>Tu mundo, tu historia</p>
        </div>
    </div>

    <hr>

    <!-- Menú principal -->
    <nav class="menu-principal">
        <ul>
            <?php foreach ($menuPrincipal as $itemMenu) { ?>
                <li>
                    <a href="<?php echo htmlspecialchars($itemMenu['url'], ENT_QUOTES, 'UTF-8'); ?>"<?php echo Navegacion::claseActiva($paginaActual, $itemMenu['pagina']); ?>>
                        <i class="<?php echo htmlspecialchars($itemMenu['icono'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                        <span><?php echo htmlspecialchars($itemMenu['texto'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </nav>

    <hr>

    <!-- Sección cuenta -->
    <p class="titulo-cuenta">Cuenta</p>
    <nav class="menu-cuenta">
        <ul>
            <li><a href="../Usuario/perfil.php"<?php echo Navegacion::claseActiva($paginaActual, 'perfil.php'); ?>><i class="fa-solid fa-user"></i><span><?php echo htmlspecialchars($textoPerfil, ENT_QUOTES, 'UTF-8'); ?></span></a></li>
            <li><a href="../Usuario/Preferencias.php"<?php echo Navegacion::claseActiva($paginaActual, 'Preferencias.php'); ?>><i class="fa-solid fa-sliders"></i><span>Preferencias</span></a></li>
            <li><a href="../Usuario/Configuracion.php"<?php echo Navegacion::claseActiva($paginaActual, 'Configuracion.php'); ?>><i class="fa-solid fa-gear"></i><span>Configuración</span></a></li>
            <li><a href="../Usuario/CerrarSesion.php"<?php echo Navegacion::claseActiva($paginaActual, 'CerrarSesion.php'); ?>><i class="fa-solid fa-right-from-bracket"></i><span>Cerrar Sesión</span></a></li>
        </ul>
    </nav>
</aside>
