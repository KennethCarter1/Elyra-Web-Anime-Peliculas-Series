<?php
class ReportesEstadisticas {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerResumen() {
        $stmt = $this->conexion->prepare("CALL sp_resumen_reportes_estadisticas()");
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [
            'usuarios_totales' => 0,
            'usuarios_activos' => 0,
            'cuentas_bloqueadas' => 0,
            'peliculas_totales' => 0,
            'series_totales' => 0,
            'generos_totales' => 0,
            'contenidos_publicados' => 0,
            'contenidos_desactivados' => 0
        ];
    }

    public function obtenerUltimosUsuarios() {
        return $this->obtenerListado("CALL sp_ultimos_usuarios_reportes()");
    }

    public function obtenerUltimoContenido() {
        return $this->obtenerListado("CALL sp_ultimo_contenido_reportes()");
    }

    public function obtenerGenerosMasElegidos() {
        return $this->obtenerListado("CALL sp_generos_mas_elegidos_reportes()");
    }

    public function obtenerContenidoPorGenero() {
        return $this->obtenerListado("CALL sp_contenido_por_genero_reportes()");
    }

    public function obtenerDistribucionContenido() {
        return $this->obtenerListado("CALL sp_distribucion_contenido_reportes()");
    }

    public function obtenerEstadoContenido() {
        return $this->obtenerListado("CALL sp_estado_contenido_reportes()");
    }

    private function obtenerListado($procedimiento) {
        $stmt = $this->conexion->prepare($procedimiento);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [];
    }

    public static function resumenInicial()
    {
        return [
            'usuariosTotales' => 0,
            'usuariosActivos' => 0,
            'cuentasBloqueadas' => 0,
            'peliculasTotales' => 0,
            'seriesTotales' => 0,
            'generosTotales' => 0,
            'contenidosPublicados' => 0,
            'contenidosDesactivados' => 0
        ];
    }

    public static function listaInicial()
    {
        return [];
    }

    public static function normalizarResumen($resumen)
    {
        $resultado = self::resumenInicial();

        $resultado['usuariosTotales'] = (int)self::leerCampo($resumen, 'usuariosTotales', 'usuarios_totales');
        $resultado['usuariosActivos'] = (int)self::leerCampo($resumen, 'usuariosActivos', 'usuarios_activos');
        $resultado['cuentasBloqueadas'] = (int)self::leerCampo($resumen, 'cuentasBloqueadas', 'cuentas_bloqueadas');
        $resultado['peliculasTotales'] = (int)self::leerCampo($resumen, 'peliculasTotales', 'peliculas_totales');
        $resultado['seriesTotales'] = (int)self::leerCampo($resumen, 'seriesTotales', 'series_totales');
        $resultado['generosTotales'] = (int)self::leerCampo($resumen, 'generosTotales', 'generos_totales');
        $resultado['contenidosPublicados'] = (int)self::leerCampo($resumen, 'contenidosPublicados', 'contenidos_publicados');
        $resultado['contenidosDesactivados'] = (int)self::leerCampo($resumen, 'contenidosDesactivados', 'contenidos_desactivados');

        return $resultado;
    }

    public static function tarjetasResumen($resumen)
    {
        return [
            [
                'titulo' => 'Usuarios totales',
                'valor' => $resumen['usuariosTotales'],
                'icono' => 'fa-solid fa-users',
                'color' => 'morado'
            ],
            [
                'titulo' => 'Usuarios activos',
                'valor' => $resumen['usuariosActivos'],
                'icono' => 'fa-solid fa-user-check',
                'color' => 'verde'
            ],
            [
                'titulo' => 'Cuentas bloqueadas',
                'valor' => $resumen['cuentasBloqueadas'],
                'icono' => 'fa-solid fa-user-lock',
                'color' => 'rojo'
            ],
            [
                'titulo' => 'Películas',
                'valor' => $resumen['peliculasTotales'],
                'icono' => 'fa-solid fa-film',
                'color' => 'azul'
            ],
            [
                'titulo' => 'Series',
                'valor' => $resumen['seriesTotales'],
                'icono' => 'fa-solid fa-tv',
                'color' => 'verde'
            ],
            [
                'titulo' => 'Géneros',
                'valor' => $resumen['generosTotales'],
                'icono' => 'fa-solid fa-tags',
                'color' => 'morado'
            ],
            [
                'titulo' => 'Publicados',
                'valor' => $resumen['contenidosPublicados'],
                'icono' => 'fa-solid fa-circle-check',
                'color' => 'verde'
            ],
            [
                'titulo' => 'Desactivados',
                'valor' => $resumen['contenidosDesactivados'],
                'icono' => 'fa-solid fa-ban',
                'color' => 'gris'
            ]
        ];
    }

    public static function normalizarUsuarios($usuarios)
    {
        $resultado = [];

        foreach ($usuarios as $usuario) {
            $nombre = self::leerCampo($usuario, 'nombre', 'nombre');
            $usuarioCuenta = self::leerCampo($usuario, 'usuario', 'usuario');
            $genero = self::leerCampo($usuario, 'genero', 'genero');
            $rol = self::leerCampo($usuario, 'rol', 'rol');
            $activo = (int)self::leerCampo($usuario, 'activo', 'activo');

            $resultado[] = [
                'id' => (int)self::leerCampo($usuario, 'idUsuario', 'id_usuario'),
                'nombre' => $nombre,
                'usuario' => $usuarioCuenta,
                'correo' => self::leerCampo($usuario, 'correo', 'correo'),
                'genero' => self::textoGenero($genero),
                'rol' => self::textoRol($rol),
                'activo' => $activo,
                'estado' => self::textoEstadoUsuario($activo),
                'estadoClase' => self::claseEstadoUsuario($activo),
                'fecha' => self::leerCampo($usuario, 'fechaCreacion', 'fecha_creacion_formateada'),
                'iniciales' => self::iniciales($nombre, $usuarioCuenta)
            ];
        }

        return $resultado;
    }

    public static function normalizarContenido($contenidos)
    {
        $resultado = [];

        foreach ($contenidos as $contenido) {
            $imagen = self::leerCampo($contenido, 'imagen', 'imagen');
            $estado = self::leerCampo($contenido, 'estado', 'estado');

            $resultado[] = [
                'id' => (int)self::leerCampo($contenido, 'idPeliculaSerie', 'id_pelicula_serie'),
                'titulo' => self::leerCampo($contenido, 'titulo', 'titulo'),
                'tipo' => self::leerCampo($contenido, 'tipo', 'tipo'),
                'imagen' => $imagen,
                'imagenUrl' => self::rutaImagenContenido($imagen),
                'generos' => self::leerCampo($contenido, 'generos', 'generos'),
                'fecha' => self::leerCampo($contenido, 'fecha', 'fecha'),
                'estado' => $estado,
                'estadoClase' => self::claseEstadoContenido($estado),
                'activo' => (int)self::leerCampo($contenido, 'activo', 'activo')
            ];
        }

        return $resultado;
    }

    public static function normalizarGenerosElegidos($generos)
    {
        return self::normalizarBarras($generos, 'totalUsuarios', 'total_usuarios');
    }

    public static function normalizarContenidoPorGenero($generos)
    {
        return self::normalizarBarras($generos, 'totalContenido', 'total_contenido');
    }

    public static function normalizarDistribucion($filas)
    {
        return self::normalizarBarrasSimples($filas);
    }

    public static function normalizarEstadoContenido($filas)
    {
        return self::normalizarBarrasSimples($filas);
    }

    private static function normalizarBarras($filas, $campoObjetoTotal, $campoArregloTotal)
    {
        $resultado = [];
        $maximo = 0;

        foreach ($filas as $fila) {
            $total = (int)self::leerCampo($fila, $campoObjetoTotal, $campoArregloTotal);

            if ($total > $maximo) {
                $maximo = $total;
            }
        }

        foreach ($filas as $fila) {
            $total = (int)self::leerCampo($fila, $campoObjetoTotal, $campoArregloTotal);
            $porcentaje = self::porcentaje($total, $maximo);

            $resultado[] = [
                'nombre' => self::leerCampo($fila, 'nombreGenero', 'nombre_genero'),
                'total' => $total,
                'porcentaje' => $porcentaje
            ];
        }

        return $resultado;
    }

    private static function normalizarBarrasSimples($filas)
    {
        $resultado = [];
        $maximo = 0;

        foreach ($filas as $fila) {
            $total = (int)self::leerCampo($fila, 'total', 'total');

            if ($total > $maximo) {
                $maximo = $total;
            }
        }

        foreach ($filas as $fila) {
            $total = (int)self::leerCampo($fila, 'total', 'total');

            $resultado[] = [
                'nombre' => self::leerCampo($fila, 'etiqueta', 'etiqueta'),
                'total' => $total,
                'porcentaje' => self::porcentaje($total, $maximo)
            ];
        }

        return $resultado;
    }

    public static function formatearNumero($numero)
    {
        return number_format((int)$numero, 0, '.', ',');
    }

    public static function valorSeguro($valor)
    {
        return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
    }

    public static function anchoBarra($porcentaje)
    {
        return self::valorBarra($porcentaje) . '%';
    }

    public static function valorBarra($porcentaje)
    {
        $porcentaje = (int)$porcentaje;

        if ($porcentaje < 3) {
            $porcentaje = 3;
        }

        if ($porcentaje > 100) {
            $porcentaje = 100;
        }

        return $porcentaje;
    }

    private static function porcentaje($total, $maximo)
    {
        if ((int)$maximo <= 0) {
            return 0;
        }

        return (int)round(((int)$total * 100) / (int)$maximo);
    }

    private static function leerCampo($origen, $campoObjeto, $campoArreglo)
    {
        if (is_object($origen) && isset($origen->$campoObjeto)) {
            return $origen->$campoObjeto;
        }

        if (is_array($origen) && isset($origen[$campoArreglo])) {
            return $origen[$campoArreglo];
        }

        if (is_array($origen) && isset($origen[$campoObjeto])) {
            return $origen[$campoObjeto];
        }

        return '';
    }

    private static function textoGenero($genero)
    {
        $generoNormalizado = strtolower(trim($genero));

        if ($generoNormalizado === 'masculino') {
            return 'Masculino';
        }

        if ($generoNormalizado === 'femenino') {
            return 'Femenino';
        }

        if ($generoNormalizado === 'otaku') {
            return 'Otaku';
        }

        if ($generoNormalizado === '') {
            return 'No registrado';
        }

        return (string)$genero;
    }

    private static function textoRol($rol)
    {
        $rolNormalizado = strtolower(trim($rol));

        if ($rolNormalizado === 'administrador') {
            return 'Administrador';
        }

        return 'Usuario';
    }

    private static function textoEstadoUsuario($activo)
    {
        if ((int)$activo === 1) {
            return 'Activo';
        }

        return 'Bloqueado';
    }

    private static function claseEstadoUsuario($activo)
    {
        if ((int)$activo === 1) {
            return 'activo';
        }

        return 'bloqueado';
    }

    private static function claseEstadoContenido($estado)
    {
        $estadoNormalizado = strtolower(trim($estado));

        if ($estadoNormalizado === 'publicado') {
            return 'publicado';
        }

        if ($estadoNormalizado === 'desactivado') {
            return 'desactivado';
        }

        return 'general';
    }

    private static function rutaImagenContenido($imagen)
    {
        $rutaImagen = trim($imagen);

        if ($rutaImagen === '') {
            return '';
        }

        if (strpos($rutaImagen, 'http://') === 0 || strpos($rutaImagen, 'https://') === 0) {
            return $rutaImagen;
        }

        if (substr($rutaImagen, 0, 1) === '/') {
            return $rutaImagen;
        }

        if (strpos($rutaImagen, '../') === 0) {
            return $rutaImagen;
        }

        return '../../' . ltrim($rutaImagen, '/');
    }

    private static function iniciales($nombre, $usuario)
    {
        $texto = trim($nombre);

        if ($texto === '') {
            $texto = trim($usuario);
        }

        if ($texto === '') {
            return 'U';
        }

        $partes = preg_split('/\s+/', $texto);
        $iniciales = '';

        foreach ($partes as $parte) {
            if ($parte !== '') {
                $iniciales .= strtoupper(substr($parte, 0, 1));
            }

            if (strlen($iniciales) >= 2) {
                break;
            }
        }

        if ($iniciales === '') {
            return 'U';
        }

        return $iniciales;
    }
}
?>
