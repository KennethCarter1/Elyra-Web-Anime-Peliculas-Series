<?php
class Navegacion {
    public static function nombreUsuario($sesion)
    {
        if (isset($sesion['nombre']) && $sesion['nombre'] !== '') {
            return $sesion['nombre'];
        }

        if (isset($sesion['usuario']) && $sesion['usuario'] !== '') {
            return $sesion['usuario'];
        }

        return 'Usuario';
    }

    public static function usuarioAutenticado($sesion)
    {
        if (isset($sesion['usuario']) && $sesion['usuario'] !== '') {
            return true;
        }

        return false;
    }

    public static function esAdministrador($sesion)
    {
        if (isset($sesion['rol']) && $sesion['rol'] === 'administrador') {
            return true;
        }

        return false;
    }

    public static function claseActiva($paginaActual, $paginaEnlace)
    {
        if ($paginaActual === $paginaEnlace) {
            return ' class="activo"';
        }

        return '';
    }

    public static function menuPrincipal($tipoSidebar, $sesion)
    {
        $sidebarAdministracion = false;

        if ($tipoSidebar === 'administracion') {
            $sidebarAdministracion = true;
        }

        if (self::esAdministrador($sesion)) {
            $sidebarAdministracion = true;
        }

        if ($sidebarAdministracion) {
            return self::menuAdministracion();
        }

        return self::menuUsuario();
    }

    public static function textoPerfil($tipoSidebar, $sesion)
    {
        if ($tipoSidebar === 'administracion' || self::esAdministrador($sesion)) {
            return 'Mi perfil';
        }

        return 'Perfil';
    }

    private static function menuAdministracion()
    {
        return [
            [
                'url' => '../Administracion/panel.php',
                'pagina' => 'panel.php',
                'icono' => 'fa-solid fa-house',
                'texto' => 'Panel'
            ],
            [
                'url' => '../Administracion/gestion-peliculas-series.php',
                'pagina' => 'gestion-peliculas-series.php',
                'icono' => 'fa-solid fa-clapperboard',
                'texto' => 'Gestión películas/series'
            ],
            [
                'url' => '../Administracion/gestion-generos.php',
                'pagina' => 'gestion-generos.php',
                'icono' => 'fa-solid fa-layer-group',
                'texto' => 'Gestión de géneros'
            ],
            [
                'url' => '../Administracion/gestion-usuarios.php',
                'pagina' => 'gestion-usuarios.php',
                'icono' => 'fa-solid fa-users',
                'texto' => 'Gestión de usuarios'
            ],
            [
                'url' => '../Administracion/reportes-estadisticas.php',
                'pagina' => 'reportes-estadisticas.php',
                'icono' => 'fa-solid fa-chart-line',
                'texto' => 'Reportes y estadísticas'
            ]
        ];
    }

    private static function menuUsuario()
    {
        return [
            [
                'url' => '../Usuario/inicio.php',
                'pagina' => 'inicio.php',
                'icono' => 'fa-solid fa-house',
                'texto' => 'Inicio'
            ],
            [
                'url' => '../Contenido/explorar.php',
                'pagina' => 'explorar.php',
                'icono' => 'fa-solid fa-compass',
                'texto' => 'Explorar'
            ],
            [
                'url' => '../Contenido/generos.php',
                'pagina' => 'generos.php',
                'icono' => 'fa-solid fa-layer-group',
                'texto' => 'Géneros'
            ],
            [
                'url' => '../Contenido/favoritos.php',
                'pagina' => 'favoritos.php',
                'icono' => 'fa-solid fa-heart',
                'texto' => 'Favoritos'
            ]
        ];
    }

    public static function obtenerBaseUrlProyecto($servidor)
    {
        $scriptName = '';
        if (isset($servidor['SCRIPT_NAME'])) {
            $scriptName = $servidor['SCRIPT_NAME'];
        }

        $partes = explode('/', trim($scriptName, '/'));

        if (isset($partes[0]) && $partes[0] !== '') {
            return '/' . $partes[0];
        }

        return '';
    }

    public static function retornoErrorBaseDatosVista($archivo, $servidor)
    {
        $retorno = $archivo;

        if (isset($servidor['REQUEST_URI']) && trim($servidor['REQUEST_URI']) !== '') {
            $retorno = $servidor['REQUEST_URI'];
        }

        return $retorno;
    }

    public static function redirigirErrorBaseDatosVista($archivo, $servidor)
    {
        $retorno = self::retornoErrorBaseDatosVista($archivo, $servidor);
        header('Location: ../Errores/Errorbd.php?retorno=' . urlencode($retorno));
        exit;
    }

    public static function datosErrorBaseDatos($sesion, $get, $servidor)
    {
        $usuarioAutenticado = self::usuarioAutenticado($sesion);
        $urlInicio = '../Usuario/IniciarSesion.php';
        $claseMain = 'pagina-bd-bd pagina-bd-publica';

        if ($usuarioAutenticado) {
            $urlInicio = '../Usuario/inicio.php';
            $claseMain = 'contenido-principal pagina-bd-bd';

            if (self::esAdministrador($sesion)) {
                $urlInicio = '../Administracion/panel.php';
            }
        }

        return [
            'usuarioAutenticado' => $usuarioAutenticado,
            'urlInicio' => $urlInicio,
            'claseMain' => $claseMain,
            'urlReintentar' => self::urlReintentoBaseDatos($get, $servidor)
        ];
    }

    public static function urlReintentoBaseDatos($get, $servidor)
    {
        $urlReintento = '../Usuario/IniciarSesion.php';

        if (!isset($get['retorno']) || trim($get['retorno']) === '') {
            return $urlReintento;
        }

        $retorno = trim($get['retorno']);

        if (strpos($retorno, 'Errorbd.php') !== false) {
            return $urlReintento;
        }

        if (substr($retorno, 0, 2) === '//') {
            return $urlReintento;
        }

        if (strpos($retorno, 'http://') === 0 || strpos($retorno, 'https://') === 0) {
            $partesUrl = parse_url($retorno);
            $hostActual = '';

            if (isset($servidor['HTTP_HOST'])) {
                $hostActual = $servidor['HTTP_HOST'];
            }

            if ($partesUrl && isset($partesUrl['host']) && $partesUrl['host'] === $hostActual) {
                return $retorno;
            }

            return $urlReintento;
        }

        if (substr($retorno, 0, 1) === '/') {
            return $retorno;
        }

        return $urlReintento;
    }

    public static function datosError404($sesion, $servidor)
    {
        $usuarioAutenticado = self::usuarioAutenticado($sesion);
        $baseUrl = self::obtenerBaseUrlProyecto($servidor);
        $baseHref = $baseUrl . '/Views/Errores/';
        $urlInicio = '../Usuario/IniciarSesion.php';
        $textoInicio = 'Volver al inicio';
        $urlSecundario = '../Usuario/Registrarse.php';
        $textoSecundario = 'Registrarse';
        $iconoSecundario = 'fa-solid fa-user-plus';
        $claseMain = 'pagina-error-404 pagina-error-publica';

        if ($usuarioAutenticado) {
            $urlInicio = '../Usuario/inicio.php';
            $urlSecundario = '../Contenido/explorar.php';
            $textoSecundario = 'Explorar';
            $iconoSecundario = 'fa-regular fa-compass';
            $claseMain = 'contenido-principal pagina-error-404';

            if (self::esAdministrador($sesion)) {
                $urlInicio = '../Administracion/panel.php';
            }
        }

        return [
            'usuarioAutenticado' => $usuarioAutenticado,
            'baseHref' => $baseHref,
            'urlInicio' => $urlInicio,
            'textoInicio' => $textoInicio,
            'urlSecundario' => $urlSecundario,
            'textoSecundario' => $textoSecundario,
            'iconoSecundario' => $iconoSecundario,
            'claseMain' => $claseMain
        ];
    }
}
?>
