<?php
class PanelInicio {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function listarGestion() {
        return $this->obtenerListado("CALL sp_listar_paneles_inicio_gestion()", []);
    }

    public function crear($titulo, $descripcion, $orden, $activo) {
        return $this->obtenerResultado(
            "CALL sp_crear_panel_inicio(:titulo, :descripcion, :orden, :activo)",
            [
                ':titulo' => $titulo,
                ':descripcion' => $descripcion,
                ':orden' => $orden,
                ':activo' => $activo
            ],
            'No se pudo crear el panel'
        );
    }

    public function actualizar($idPanelInicio, $titulo, $descripcion, $orden, $activo) {
        return $this->obtenerResultado(
            "CALL sp_actualizar_panel_inicio(:idPanelInicio, :titulo, :descripcion, :orden, :activo)",
            [
                ':idPanelInicio' => $idPanelInicio,
                ':titulo' => $titulo,
                ':descripcion' => $descripcion,
                ':orden' => $orden,
                ':activo' => $activo
            ],
            'No se pudo actualizar el panel'
        );
    }

    public function eliminar($idPanelInicio) {
        return $this->obtenerResultado(
            "CALL sp_eliminar_panel_inicio(:idPanelInicio)",
            [
                ':idPanelInicio' => $idPanelInicio
            ],
            'No se pudo eliminar el panel'
        );
    }

    public function agregarContenido($idPanelInicio, $idPeliculaSerie, $orden) {
        return $this->obtenerResultado(
            "CALL sp_agregar_contenido_panel_inicio(:idPanelInicio, :idPeliculaSerie, :orden)",
            [
                ':idPanelInicio' => $idPanelInicio,
                ':idPeliculaSerie' => $idPeliculaSerie,
                ':orden' => $orden
            ],
            'No se pudo agregar el anime al panel'
        );
    }

    public function quitarContenido($idPanelInicio, $idPeliculaSerie) {
        return $this->obtenerResultado(
            "CALL sp_quitar_contenido_panel_inicio(:idPanelInicio, :idPeliculaSerie)",
            [
                ':idPanelInicio' => $idPanelInicio,
                ':idPeliculaSerie' => $idPeliculaSerie
            ],
            'No se pudo quitar el anime del panel'
        );
    }

    public function listarContenidoGestion($idPanelInicio) {
        return $this->obtenerListado(
            "CALL sp_listar_contenido_panel_inicio_gestion(:idPanelInicio)",
            [
                ':idPanelInicio' => $idPanelInicio
            ]
        );
    }

    public function buscarContenidoDisponible($busqueda) {
        return $this->obtenerListado(
            "CALL sp_buscar_contenido_panel_inicio(:busqueda)",
            [
                ':busqueda' => $busqueda
            ]
        );
    }

    public function obtenerPanelesInicio() {
        return $this->obtenerListado("CALL sp_listar_paneles_inicio_usuario()", []);
    }

    public function obtenerContenidoInicio($idPanelInicio, $usuario) {
        return $this->obtenerListado(
            "CALL sp_contenido_panel_inicio_usuario(:idPanelInicio, :usuario)",
            [
                ':idPanelInicio' => $idPanelInicio,
                ':usuario' => $usuario
            ]
        );
    }

    private function obtenerListado($procedimiento, $parametros) {
        $stmt = $this->conexion->prepare($procedimiento);
        $stmt->execute($parametros);
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [];
    }

    private function obtenerResultado($procedimiento, $parametros, $mensajeDefecto) {
        $stmt = $this->conexion->prepare($procedimiento);
        $stmt->execute($parametros);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [
            'exito' => 0,
            'mensaje' => $mensajeDefecto,
            'id_panel_inicio' => 0
        ];
    }

    public static function listaInicial()
    {
        return [];
    }

    public static function normalizarGestion($paneles)
    {
        $resultado = [];

        foreach ($paneles as $panel) {
            $activo = (int)self::leerCampo($panel, 'activo', 'activo');
            $resultado[] = [
                'id' => (int)self::leerCampo($panel, 'idPanelInicio', 'id_panel_inicio'),
                'titulo' => self::leerCampo($panel, 'titulo', 'titulo'),
                'descripcion' => self::leerCampo($panel, 'descripcion', 'descripcion'),
                'orden' => (int)self::leerCampo($panel, 'orden', 'orden'),
                'activo' => $activo,
                'estado' => self::textoEstado($activo),
                'estadoClase' => self::claseEstado($activo),
                'totalContenido' => (int)self::leerCampo($panel, 'totalContenido', 'total_contenido')
            ];
        }

        return $resultado;
    }

    public static function normalizarContenidoGestion($contenido)
    {
        $resultado = [];

        foreach ($contenido as $fila) {
            $imagen = self::leerCampo($fila, 'imagenPortada', 'imagen_portada');
            $resultado[] = [
                'id' => (int)self::leerCampo($fila, 'idPeliculaSerie', 'id_pelicula_serie'),
                'titulo' => self::leerCampo($fila, 'titulo', 'titulo'),
                'tituloOriginal' => self::leerCampo($fila, 'tituloOriginal', 'titulo_original'),
                'tipo' => self::leerCampo($fila, 'tipo', 'tipo'),
                'anio' => (int)self::leerCampo($fila, 'anioLanzamiento', 'anio_lanzamiento'),
                'imagenPortada' => $imagen,
                'imagenPortadaUrl' => self::rutaImagenContenido($imagen),
                'estado' => self::leerCampo($fila, 'estado', 'estado'),
                'estadoEmision' => self::leerCampo($fila, 'estadoEmision', 'estado_emision'),
                'generos' => self::leerCampo($fila, 'generos', 'generos'),
                'orden' => (int)self::leerCampo($fila, 'orden', 'orden')
            ];
        }

        return $resultado;
    }

    public static function panelSeleccionado($paneles, $idPanelInicio)
    {
        foreach ($paneles as $panel) {
            if ((int)$panel['id'] === (int)$idPanelInicio) {
                return $panel;
            }
        }

        if (!empty($paneles)) {
            return $paneles[0];
        }

        return [
            'id' => 0,
            'titulo' => '',
            'descripcion' => '',
            'orden' => 0,
            'activo' => 0,
            'estado' => '',
            'estadoClase' => '',
            'totalContenido' => 0
        ];
    }

    public static function totalActivos($paneles)
    {
        $total = 0;

        foreach ($paneles as $panel) {
            if ((int)$panel['activo'] === 1) {
                $total++;
            }
        }

        return $total;
    }

    public static function totalContenido($paneles)
    {
        $total = 0;

        foreach ($paneles as $panel) {
            $total += (int)$panel['totalContenido'];
        }

        return $total;
    }

    public static function clasePanelSeleccionado($idActual, $idPanel)
    {
        if ((int)$idActual === (int)$idPanel) {
            return ' activo';
        }

        return '';
    }

    public static function checkedActivo($activo)
    {
        if ((int)$activo === 1) {
            return ' checked';
        }

        return '';
    }

    public static function valorSeguro($valor)
    {
        return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
    }

    private static function textoEstado($activo)
    {
        if ((int)$activo === 1) {
            return 'Activo';
        }

        return 'Oculto';
    }

    private static function claseEstado($activo)
    {
        if ((int)$activo === 1) {
            return 'activo';
        }

        return 'oculto';
    }

    private static function leerCampo($origen, $campoObjeto, $campoArreglo)
    {
        if (is_object($origen) && isset($origen->$campoObjeto)) {
            return $origen->$campoObjeto;
        }

        if (is_array($origen) && isset($origen[$campoObjeto])) {
            return $origen[$campoObjeto];
        }

        if (is_array($origen) && isset($origen[$campoArreglo])) {
            return $origen[$campoArreglo];
        }

        return '';
    }

    private static function rutaImagenContenido($imagen)
    {
        $rutaImagen = trim((string)$imagen);

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
}
?>
