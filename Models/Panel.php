<?php
class Panel {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerResumenAdministrador() {
        $stmt = $this->conexion->prepare(
            "CALL sp_resumen_panel_administrador()"
        );
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [
            'usuarios_totales' => 0,
            'peliculas_series_totales' => 0,
            'generos_totales' => 0,
            'favoritos_totales' => 0
        ];
    }

    public function obtenerActividadReciente() {
        $stmt = $this->conexion->prepare(
            "CALL sp_actividad_reciente_panel()"
        );
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [];
    }

    public function obtenerUltimoContenidoAgregado() {
        $stmt = $this->conexion->prepare(
            "CALL sp_ultimo_contenido_agregado()"
        );
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
            'peliculasSeriesTotales' => 0,
            'generosTotales' => 0,
            'favoritosTotales' => 0
        ];
    }

    public static function actividadInicial()
    {
        return [];
    }

    public static function ultimoContenidoInicial()
    {
        return [];
    }

    public static function formatearNumero($numero)
    {
        return number_format((int)$numero, 0, '.', ',');
    }

    public static function tarjetasResumen($resumenPanel)
    {
        return [
            [
                'titulo' => 'Usuarios Totales',
                'valor' => $resumenPanel['usuariosTotales'],
                'icono' => 'fa-solid fa-users',
                'color' => 'morado',
                'texto' => 'Registrados'
            ],
            [
                'titulo' => 'Películas / Series',
                'valor' => $resumenPanel['peliculasSeriesTotales'],
                'icono' => 'fa-solid fa-circle-play',
                'color' => 'azul',
                'texto' => 'Publicadas'
            ],
            [
                'titulo' => 'Géneros',
                'valor' => $resumenPanel['generosTotales'],
                'icono' => 'fa-solid fa-tags',
                'color' => 'verde',
                'texto' => 'Disponibles'
            ],
            [
                'titulo' => 'Total de Favoritos',
                'valor' => $resumenPanel['favoritosTotales'],
                'icono' => 'fa-solid fa-heart',
                'color' => 'naranja',
                'texto' => 'Favoritos'
            ]
        ];
    }

    public static function accionesRapidas()
    {
        return [
            [
                'texto' => 'Agregar Película / Serie',
                'url' => 'gestion-peliculas-series.php',
                'icono' => 'fa-solid fa-clapperboard'
            ],
            [
                'texto' => 'Agregar Género',
                'url' => 'gestion-generos.php',
                'icono' => 'fa-solid fa-tags'
            ],
            [
                'texto' => 'Gestionar Usuarios',
                'url' => 'gestion-usuarios.php',
                'icono' => 'fa-solid fa-user-gear'
            ],
            [
                'texto' => 'Ver Reportes',
                'url' => 'reportes-estadisticas.php',
                'icono' => 'fa-solid fa-chart-column'
            ]
        ];
    }

    public static function normalizarActividad($actividadReciente)
    {
        $actividadNormalizada = [];

        foreach ($actividadReciente as $actividad) {
            $tipoActividad = self::leerCampoContenido($actividad, 'tipoActividad', 'tipo_actividad');
            $accionActividad = self::leerCampoContenido($actividad, 'accion', 'accion');
            $referenciaActividad = self::leerCampoContenido($actividad, 'referencia', 'referencia');
            $imagenActividad = self::leerCampoContenido($actividad, 'imagen', 'imagen');
            $fechaActividad = self::leerCampoContenido($actividad, 'fechaActividad', 'fecha_actividad');

            $actividadNormalizada[] = [
                'tipo' => $tipoActividad,
                'accion' => $accionActividad,
                'referencia' => $referenciaActividad,
                'fechaTexto' => self::tiempoActividad($fechaActividad),
                'imagen' => $imagenActividad,
                'imagenUrl' => self::rutaImagenContenido($imagenActividad),
                'inicial' => self::inicialActividad($referenciaActividad)
            ];
        }

        return $actividadNormalizada;
    }

    public static function normalizarUltimoContenido($contenidoReciente)
    {
        $contenidoNormalizado = [];

        foreach ($contenidoReciente as $contenido) {
            $idContenido = self::leerCampoContenido($contenido, 'idPeliculaSerie', 'id_pelicula_serie');
            $titulo = self::leerCampoContenido($contenido, 'titulo', 'titulo');
            $tipo = self::leerCampoContenido($contenido, 'tipo', 'tipo');
            $imagen = self::leerCampoContenido($contenido, 'imagen', 'imagen');
            $generos = self::leerCampoContenido($contenido, 'generos', 'generos');
            $fecha = self::leerCampoContenido($contenido, 'fecha', 'fecha');
            $estado = self::leerCampoContenido($contenido, 'estado', 'estado');

            $contenidoNormalizado[] = [
                'id' => (int)$idContenido,
                'titulo' => $titulo,
                'tipo' => $tipo,
                'imagen' => $imagen,
                'imagenUrl' => self::rutaImagenContenido($imagen),
                'generos' => $generos,
                'fecha' => $fecha,
                'estado' => $estado,
                'estadoClase' => self::claseEstadoContenido($estado)
            ];
        }

        return $contenidoNormalizado;
    }

    private static function leerCampoContenido($contenido, $campoObjeto, $campoArreglo)
    {
        if (is_object($contenido) && isset($contenido->$campoObjeto)) {
            return $contenido->$campoObjeto;
        }

        if (is_array($contenido) && isset($contenido[$campoArreglo])) {
            return $contenido[$campoArreglo];
        }

        if (is_array($contenido) && isset($contenido[$campoObjeto])) {
            return $contenido[$campoObjeto];
        }

        return '';
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

    private static function inicialActividad($referencia)
    {
        $texto = trim($referencia);

        if ($texto === '') {
            return 'E';
        }

        return strtoupper(substr($texto, 0, 1));
    }

    private static function claseEstadoContenido($estado)
    {
        $estadoNormalizado = strtolower(trim($estado));

        if ($estadoNormalizado === 'publicado') {
            return 'publicado';
        }

        if ($estadoNormalizado === 'borrador') {
            return 'borrador';
        }

        return 'general';
    }

    public static function tiempoActividad($fechaActividad)
    {
        if ($fechaActividad === '') {
            return '';
        }

        try {
            $zonaHoraria = new DateTimeZone('America/Panama');
            $fecha = new DateTime($fechaActividad, $zonaHoraria);
            $ahora = new DateTime('now', $zonaHoraria);
            $diferencia = $fecha->diff($ahora);

            if ($diferencia->days > 0) {
                if ($diferencia->days === 1) {
                    return 'Hace 1 día';
                }

                return 'Hace ' . $diferencia->days . ' días';
            }

            if ($diferencia->h > 0) {
                if ($diferencia->h === 1) {
                    return 'Hace 1 hora';
                }

                return 'Hace ' . $diferencia->h . ' horas';
            }

            if ($diferencia->i > 0) {
                if ($diferencia->i === 1) {
                    return 'Hace 1 minuto';
                }

                return 'Hace ' . $diferencia->i . ' minutos';
            }

            return 'Hace un momento';
        } catch (Exception $e) {
            return '';
        }
    }
}
?>
