<?php
class Navegacion {
    const BASE_URL = '/elyra';

    public static function url($ruta)
    {
        $ruta = trim($ruta, '/');

        if ($ruta === '') {
            return self::BASE_URL . '/';
        }

        return self::BASE_URL . '/' . $ruta;
    }

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
                'url' => self::url('admin'),
                'pagina' => 'panel.php',
                'icono' => 'fa-solid fa-house',
                'texto' => 'Panel'
            ],
            [
                'url' => self::url('admin/contenido'),
                'pagina' => 'gestion-peliculas-series.php',
                'icono' => 'fa-solid fa-clapperboard',
                'texto' => 'Gestión películas/series'
            ],
            [
                'url' => self::url('admin/generos'),
                'pagina' => 'gestion-generos.php',
                'icono' => 'fa-solid fa-layer-group',
                'texto' => 'Gestión de géneros'
            ],
            [
                'url' => self::url('admin/paneles-inicio'),
                'pagina' => 'gestion-paneles-inicio.php',
                'icono' => 'fa-solid fa-table-cells-large',
                'texto' => 'Paneles de inicio'
            ],
            [
                'url' => self::url('admin/usuarios'),
                'pagina' => 'gestion-usuarios.php',
                'icono' => 'fa-solid fa-users',
                'texto' => 'Gestión de usuarios'
            ],
            [
                'url' => self::url('admin/reportes'),
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
                'url' => self::url('inicio'),
                'pagina' => 'inicio.php',
                'icono' => 'fa-solid fa-house',
                'texto' => 'Inicio'
            ],
            [
                'url' => self::url('explorar'),
                'pagina' => 'explorar.php',
                'icono' => 'fa-solid fa-compass',
                'texto' => 'Explorar'
            ],
            [
                'url' => self::url('generos'),
                'pagina' => 'generos.php',
                'icono' => 'fa-solid fa-layer-group',
                'texto' => 'Géneros'
            ],
            [
                'url' => self::url('favoritos'),
                'pagina' => 'favoritos.php',
                'icono' => 'fa-solid fa-heart',
                'texto' => 'Favoritos'
            ]
        ];
    }

    public static function obtenerBaseUrlProyecto($servidor)
    {
        return self::BASE_URL;
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
        header('Location: ' . self::url('error-bd') . '?retorno=' . urlencode($retorno));
        exit;
    }

    public static function datosErrorBaseDatos($sesion, $get, $servidor)
    {
        $usuarioAutenticado = self::usuarioAutenticado($sesion);
        $urlInicio = self::url('login');
        $claseMain = 'pagina-bd-bd pagina-bd-publica';

        if ($usuarioAutenticado) {
            $urlInicio = self::url('inicio');
            $claseMain = 'contenido-principal pagina-bd-bd';

            if (self::esAdministrador($sesion)) {
                $urlInicio = self::url('admin');
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
        $urlReintento = self::url('login');

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
        $baseHref = self::BASE_URL . '/Views/Errores/';
        $urlInicio = self::url('login');
        $textoInicio = 'Volver al inicio';
        $urlSecundario = self::url('registro');
        $textoSecundario = 'Registrarse';
        $iconoSecundario = 'fa-solid fa-user-plus';
        $claseMain = 'pagina-error-404 pagina-error-publica';

        if ($usuarioAutenticado) {
            $urlInicio = self::url('inicio');
            $urlSecundario = self::url('explorar');
            $textoSecundario = 'Explorar';
            $iconoSecundario = 'fa-regular fa-compass';
            $claseMain = 'contenido-principal pagina-error-404';

            if (self::esAdministrador($sesion)) {
                $urlInicio = self::url('admin');
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
