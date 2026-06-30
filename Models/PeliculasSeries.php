<?php
class PeliculasSeries {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerResumenGestion() {
        $stmt = $this->conexion->prepare(
            "CALL sp_resumen_gestion_peliculas_series()"
        );
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [
            'total_contenido' => 0,
            'total_peliculas' => 0,
            'total_series' => 0,
            'total_publicados' => 0,
            'total_desactivados' => 0
        ];
    }

    public function listarGestion($busqueda, $tipo, $idGenero, $estado, $anio, $estadoEmision, $destacado) {
        $stmt = $this->conexion->prepare(
            "CALL sp_listar_peliculas_series_gestion(:busqueda, :tipo, :idGenero, :estado, :anio, :estadoEmision, :destacado)"
        );
        $stmt->execute([
            ':busqueda' => $busqueda,
            ':tipo' => $tipo,
            ':idGenero' => $idGenero,
            ':estado' => $estado,
            ':anio' => $anio,
            ':estadoEmision' => $estadoEmision,
            ':destacado' => $destacado
        ]);
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [];
    }

    public function obtenerDetalle($idPeliculaSerie) {
        $stmt = $this->conexion->prepare(
            "CALL sp_obtener_detalle_pelicula_serie(:idPeliculaSerie)"
        );
        $stmt->execute([
            ':idPeliculaSerie' => $idPeliculaSerie
        ]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return false;
    }

    public function listarSeriesPadre($excluirId) {
        $stmt = $this->conexion->prepare(
            "CALL sp_listar_series_padre(:excluirId)"
        );
        $stmt->execute([
            ':excluirId' => $excluirId
        ]);
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [];
    }

    public function obtenerHijos($idPeliculaSerie) {
        $stmt = $this->conexion->prepare(
            "CALL sp_obtener_hijos_pelicula_serie(:idPeliculaSerie)"
        );
        $stmt->execute([
            ':idPeliculaSerie' => $idPeliculaSerie
        ]);
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [];
    }

    public function desactivar($idPeliculaSerie) {
        $stmt = $this->conexion->prepare(
            "CALL sp_desactivar_pelicula_serie(:idPeliculaSerie)"
        );
        $stmt->execute([
            ':idPeliculaSerie' => $idPeliculaSerie
        ]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [
            'exito' => 0,
            'mensaje' => 'No se pudo desactivar el contenido'
        ];
    }

    public function activar($idPeliculaSerie) {
        $stmt = $this->conexion->prepare(
            "CALL sp_activar_pelicula_serie(:idPeliculaSerie)"
        );
        $stmt->execute([
            ':idPeliculaSerie' => $idPeliculaSerie
        ]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [
            'exito' => 0,
            'mensaje' => 'No se pudo activar el contenido'
        ];
    }

    public function crear($datos) {
        $stmt = $this->conexion->prepare(
            "CALL sp_crear_pelicula_serie(:titulo, :tituloOriginal, :descripcion, :tipo, :estado, :estadoEmision, :anioLanzamiento, :fechaEstreno, :duracionMinutos, :temporadas, :episodios, :imagenPortada, :imagenBanner, :trailerUrl, :generos, :destacado, :seriePadreId, :numeroTemporada, :tipoRelacion)"
        );
        $stmt->execute([
            ':titulo' => $datos['titulo'],
            ':tituloOriginal' => $datos['tituloOriginal'],
            ':descripcion' => $datos['descripcion'],
            ':tipo' => $datos['tipo'],
            ':estado' => $datos['estado'],
            ':estadoEmision' => $datos['estadoEmision'],
            ':anioLanzamiento' => $datos['anioLanzamiento'],
            ':fechaEstreno' => $datos['fechaEstreno'],
            ':duracionMinutos' => $datos['duracionMinutos'],
            ':temporadas' => $datos['temporadas'],
            ':episodios' => $datos['episodios'],
            ':imagenPortada' => $datos['imagenPortada'],
            ':imagenBanner' => $datos['imagenBanner'],
            ':trailerUrl' => $datos['trailerUrl'],
            ':generos' => $datos['generos'],
            ':destacado' => $datos['destacado'],
            ':seriePadreId' => $datos['seriePadreId'],
            ':numeroTemporada' => $datos['numeroTemporada'],
            ':tipoRelacion' => $datos['tipoRelacion']
        ]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [
            'exito' => 0,
            'mensaje' => 'No se pudo crear el contenido',
            'id_pelicula_serie' => 0
        ];
    }

    public function actualizar($idPeliculaSerie, $datos) {
        $stmt = $this->conexion->prepare(
            "CALL sp_actualizar_pelicula_serie(:idPeliculaSerie, :titulo, :tituloOriginal, :descripcion, :tipo, :estado, :estadoEmision, :anioLanzamiento, :fechaEstreno, :duracionMinutos, :temporadas, :episodios, :imagenPortada, :imagenBanner, :trailerUrl, :generos, :destacado, :seriePadreId, :numeroTemporada, :tipoRelacion)"
        );
        $stmt->execute([
            ':idPeliculaSerie' => $idPeliculaSerie,
            ':titulo' => $datos['titulo'],
            ':tituloOriginal' => $datos['tituloOriginal'],
            ':descripcion' => $datos['descripcion'],
            ':tipo' => $datos['tipo'],
            ':estado' => $datos['estado'],
            ':estadoEmision' => $datos['estadoEmision'],
            ':anioLanzamiento' => $datos['anioLanzamiento'],
            ':fechaEstreno' => $datos['fechaEstreno'],
            ':duracionMinutos' => $datos['duracionMinutos'],
            ':temporadas' => $datos['temporadas'],
            ':episodios' => $datos['episodios'],
            ':imagenPortada' => $datos['imagenPortada'],
            ':imagenBanner' => $datos['imagenBanner'],
            ':trailerUrl' => $datos['trailerUrl'],
            ':generos' => $datos['generos'],
            ':destacado' => $datos['destacado'],
            ':seriePadreId' => $datos['seriePadreId'],
            ':numeroTemporada' => $datos['numeroTemporada'],
            ':tipoRelacion' => $datos['tipoRelacion']
        ]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [
            'exito' => 0,
            'mensaje' => 'No se pudo actualizar el contenido',
            'id_pelicula_serie' => $idPeliculaSerie
        ];
    }

    public static function resumenInicial()
    {
        return [
            'totalContenido' => 0,
            'totalPeliculas' => 0,
            'totalSeries' => 0,
            'totalPublicados' => 0,
            'totalDesactivados' => 0
        ];
    }

    public static function filtrosVacios()
    {
        return [
            'busqueda' => '',
            'tipo' => 'Todos',
            'idGenero' => 0,
            'estado' => 'Todos',
            'anio' => 0,
            'estadoEmision' => 'Todos',
            'destacado' => 0,
            'id' => 0
        ];
    }

    public static function listaInicial()
    {
        return [];
    }

    public static function generosIniciales()
    {
        return [];
    }

    public static function detalleInicial()
    {
        return [];
    }

    public static function formularioInicial()
    {
        return [
            'id' => 0,
            'titulo' => '',
            'tituloOriginal' => '',
            'descripcion' => '',
            'tipo' => '',
            'estado' => 'Publicado',
            'estadoEmision' => 'Finalizado',
            'anio' => '',
            'fechaEstreno' => '',
            'duracionMinutos' => '',
            'temporadas' => '',
            'episodios' => '',
            'imagenPortada' => '',
            'imagenPortadaUrl' => '',
            'imagenBanner' => '',
            'imagenBannerUrl' => '',
            'trailerUrl' => '',
            'destacado' => 0,
            'idsGeneros' => [],
            'seriePadreId' => 0,
            'numeroTemporada' => '',
            'tipoRelacion' => ''
        ];
    }

    public static function formularioDesdeDetalle($detalle)
    {
        $formulario = self::formularioInicial();
        $detalleNormalizado = self::normalizarDetalle($detalle);

        if (empty($detalleNormalizado)) {
            return $formulario;
        }

        $idsGeneros = self::leerCampo($detalle, 'idsGeneros', 'ids_generos');

        $formulario['id'] = $detalleNormalizado['id'];
        $formulario['titulo'] = $detalleNormalizado['titulo'];
        $formulario['tituloOriginal'] = $detalleNormalizado['tituloOriginal'];
        $formulario['descripcion'] = $detalleNormalizado['descripcion'];
        $formulario['tipo'] = $detalleNormalizado['tipo'];
        $formulario['estado'] = $detalleNormalizado['estado'];
        $formulario['estadoEmision'] = $detalleNormalizado['estadoEmision'];
        $formulario['anio'] = $detalleNormalizado['anio'];
        $formulario['fechaEstreno'] = $detalleNormalizado['fechaEstreno'];
        $formulario['duracionMinutos'] = $detalleNormalizado['duracionMinutos'];
        $formulario['temporadas'] = $detalleNormalizado['temporadas'];
        $formulario['episodios'] = $detalleNormalizado['episodios'];
        $formulario['imagenPortada'] = $detalleNormalizado['imagenPortada'];
        $formulario['imagenPortadaUrl'] = $detalleNormalizado['imagenPortadaUrl'];
        $formulario['imagenBanner'] = $detalleNormalizado['imagenBanner'];
        $formulario['imagenBannerUrl'] = $detalleNormalizado['imagenBannerUrl'];
        $formulario['trailerUrl'] = $detalleNormalizado['trailerUrl'];
        $formulario['destacado'] = $detalleNormalizado['destacado'];
        $formulario['seriePadreId'] = $detalleNormalizado['seriePadreId'];
        $formulario['numeroTemporada'] = $detalleNormalizado['numeroTemporada'];
        $formulario['tipoRelacion'] = $detalleNormalizado['tipoRelacion'];
        $formulario['idsGeneros'] = self::idsGenerosDesdeCadena($idsGeneros);

        return $formulario;
    }

    public static function idsGenerosDesdeCadena($generos)
    {
        $ids = [];
        $partes = explode(',', (string)$generos);

        foreach ($partes as $parte) {
            $id = (int)trim($parte);
            if ($id > 0 && !in_array($id, $ids)) {
                $ids[] = $id;
            }
        }

        return $ids;
    }

    public static function estaSeleccionadoGenero($idsSeleccionados, $idGenero)
    {
        if (in_array((int)$idGenero, $idsSeleccionados)) {
            return ' checked';
        }

        return '';
    }

    public static function valorFormulario($formulario, $campo)
    {
        if (isset($formulario[$campo])) {
            return htmlspecialchars((string)$formulario[$campo], ENT_QUOTES, 'UTF-8');
        }

        return '';
    }

    public static function textoBotonFormulario($editando)
    {
        if ($editando) {
            return 'Actualizar';
        }

        return 'Agregar';
    }

    public static function filtrosDesdeSolicitud($get)
    {
        $filtros = self::filtrosVacios();

        if (isset($get['busqueda'])) {
            $filtros['busqueda'] = trim($get['busqueda']);
        }

        if (isset($get['tipo']) && trim($get['tipo']) !== '') {
            $filtros['tipo'] = trim($get['tipo']);
        }

        if (isset($get['genero'])) {
            $filtros['idGenero'] = (int)$get['genero'];
        }

        if (isset($get['estado']) && trim($get['estado']) !== '') {
            $filtros['estado'] = trim($get['estado']);
        }

        if (isset($get['anio'])) {
            $filtros['anio'] = (int)$get['anio'];
        }

        if (isset($get['estado_emision']) && trim($get['estado_emision']) !== '') {
            $filtros['estadoEmision'] = trim($get['estado_emision']);
        }

        if (isset($get['destacado'])) {
            $filtros['destacado'] = (int)$get['destacado'] === 1 ? 1 : 0;
        }

        if (isset($get['id'])) {
            $filtros['id'] = (int)$get['id'];
        }

        return $filtros;
    }

    public static function tarjetasResumen($resumen)
    {
        return [
            [
                'titulo' => 'Total de contenido',
                'valor' => $resumen['totalContenido'],
                'icono' => 'fa-solid fa-photo-film',
                'color' => 'morado'
            ],
            [
                'titulo' => 'Películas',
                'valor' => $resumen['totalPeliculas'],
                'icono' => 'fa-solid fa-circle-play',
                'color' => 'azul'
            ],
            [
                'titulo' => 'Series',
                'valor' => $resumen['totalSeries'],
                'icono' => 'fa-solid fa-tv',
                'color' => 'verde'
            ],
            [
                'titulo' => 'Publicados',
                'valor' => $resumen['totalPublicados'],
                'icono' => 'fa-solid fa-check',
                'color' => 'publicado'
            ],
            [
                'titulo' => 'Desactivados',
                'valor' => $resumen['totalDesactivados'],
                'icono' => 'fa-solid fa-ban',
                'color' => 'desactivado'
            ]
        ];
    }

    public static function opcionesTipo()
    {
        return [
            ['valor' => 'Todos', 'texto' => 'Todos'],
            ['valor' => 'Película', 'texto' => 'Película'],
            ['valor' => 'Serie', 'texto' => 'Serie']
        ];
    }

    public static function opcionesEstado()
    {
        return [
            ['valor' => 'Todos', 'texto' => 'Todos'],
            ['valor' => 'Publicado', 'texto' => 'Publicado'],
            ['valor' => 'Desactivado', 'texto' => 'Desactivado']
        ];
    }

    public static function opcionesEstadoEmision()
    {
        return [
            ['valor' => 'Todos', 'texto' => 'Todos'],
            ['valor' => 'En emisión', 'texto' => 'En emisión'],
            ['valor' => 'Finalizado', 'texto' => 'Finalizado'],
            ['valor' => 'Próximamente', 'texto' => 'Próximamente']
        ];
    }

    public static function opcionesDestacado()
    {
        return [
            ['valor' => 0, 'texto' => 'Todos'],
            ['valor' => 1, 'texto' => 'Destacados']
        ];
    }

    public static function normalizarResumen($resumen)
    {
        $resultado = self::resumenInicial();

        $resultado['totalContenido'] = (int)self::leerCampo($resumen, 'totalContenido', 'total_contenido');
        $resultado['totalPeliculas'] = (int)self::leerCampo($resumen, 'totalPeliculas', 'total_peliculas');
        $resultado['totalSeries'] = (int)self::leerCampo($resumen, 'totalSeries', 'total_series');
        $resultado['totalPublicados'] = (int)self::leerCampo($resumen, 'totalPublicados', 'total_publicados');
        $resultado['totalDesactivados'] = (int)self::leerCampo($resumen, 'totalDesactivados', 'total_desactivados');

        return $resultado;
    }

    public static function formatearNumero($numero)
    {
        return number_format((int)$numero, 0, '.', ',');
    }

    public static function normalizarLista($contenido)
    {
        $contenidoNormalizado = [];

        foreach ($contenido as $fila) {
            $id = self::leerCampo($fila, 'idPeliculaSerie', 'id_pelicula_serie');
            $titulo = self::leerCampo($fila, 'titulo', 'titulo');
            $tituloOriginal = self::leerCampo($fila, 'tituloOriginal', 'titulo_original');
            $imagen = self::leerCampo($fila, 'imagen', 'imagen');
            $tipo = self::leerCampo($fila, 'tipo', 'tipo');
            $generos = self::leerCampo($fila, 'generos', 'generos');
            $anio = self::leerCampo($fila, 'anio', 'anio');
            $estado = self::leerCampo($fila, 'estado', 'estado');
            $estadoEmision = self::leerCampo($fila, 'estadoEmision', 'estado_emision');
            $trailerUrl = self::leerCampo($fila, 'trailerUrl', 'trailer_url');
            $activo = self::leerCampo($fila, 'activo', 'activo');
            $destacado = self::leerCampo($fila, 'destacado', 'destacado');

            if ($estadoEmision === '') {
                $estadoEmision = 'Finalizado';
            }

            $contenidoNormalizado[] = [
                'id' => (int)$id,
                'titulo' => $titulo,
                'tituloOriginal' => $tituloOriginal,
                'imagen' => $imagen,
                'imagenUrl' => self::rutaImagenContenido($imagen),
                'tipo' => $tipo,
                'generos' => $generos,
                'anio' => (int)$anio,
                'estado' => $estado,
                'estadoEmision' => $estadoEmision,
                'estadoClase' => self::claseEstado($estado),
                'estadoEmisionClase' => self::claseEstadoEmision($estadoEmision),
                'trailerUrl' => $trailerUrl,
                'activo' => (int)$activo,
                'destacado' => (int)$destacado
            ];
        }

        return $contenidoNormalizado;
    }

    public static function normalizarDetalle($detalle)
    {
        if (!$detalle) {
            return [];
        }

        $imagenPortada = self::leerCampo($detalle, 'imagenPortada', 'imagen_portada');
        $imagenBanner = self::leerCampo($detalle, 'imagenBanner', 'imagen_banner');
        $estado = self::leerCampo($detalle, 'estado', 'estado');
        $estadoEmision = self::leerCampo($detalle, 'estadoEmision', 'estado_emision');

        $padreImagenPortada = self::leerCampo($detalle, 'padreImagenPortada', 'padre_imagen_portada');

        if ($estadoEmision === '') {
            $estadoEmision = 'Finalizado';
        }

        return [
            'id' => (int)self::leerCampo($detalle, 'idPeliculaSerie', 'id_pelicula_serie'),
            'titulo' => self::leerCampo($detalle, 'titulo', 'titulo'),
            'tituloOriginal' => self::leerCampo($detalle, 'tituloOriginal', 'titulo_original'),
            'descripcion' => self::leerCampo($detalle, 'descripcion', 'descripcion'),
            'tipo' => self::leerCampo($detalle, 'tipo', 'tipo'),
            'estado' => $estado,
            'estadoEmision' => $estadoEmision,
            'estadoClase' => self::claseEstado($estado),
            'anio' => (int)self::leerCampo($detalle, 'anioLanzamiento', 'anio_lanzamiento'),
            'fechaEstreno' => self::leerCampo($detalle, 'fechaEstreno', 'fecha_estreno'),
            'duracionMinutos' => (int)self::leerCampo($detalle, 'duracionMinutos', 'duracion_minutos'),
            'temporadas' => (int)self::leerCampo($detalle, 'temporadas', 'temporadas'),
            'episodios' => (int)self::leerCampo($detalle, 'episodios', 'episodios'),
            'imagenPortada' => $imagenPortada,
            'imagenPortadaUrl' => self::rutaImagenContenido($imagenPortada),
            'imagenBanner' => $imagenBanner,
            'imagenBannerUrl' => self::rutaImagenContenido($imagenBanner),
            'trailerUrl' => self::leerCampo($detalle, 'trailerUrl', 'trailer_url'),
            'destacado' => (int)self::leerCampo($detalle, 'destacado', 'destacado'),
            'activo' => (int)self::leerCampo($detalle, 'activo', 'activo'),
            'fechaCreacion' => self::leerCampo($detalle, 'fechaCreacion', 'fecha_creacion'),
            'fechaActualizacion' => self::leerCampo($detalle, 'fechaActualizacion', 'fecha_actualizacion'),
            'generos' => self::leerCampo($detalle, 'generos', 'generos'),
            'seriePadreId' => (int)self::leerCampo($detalle, 'seriePadreId', 'serie_padre_id'),
            'numeroTemporada' => (int)self::leerCampo($detalle, 'numeroTemporada', 'numero_temporada'),
            'tipoRelacion' => self::leerCampo($detalle, 'tipoRelacion', 'tipo_relacion'),
            'padreTitulo' => self::leerCampo($detalle, 'padreTitulo', 'padre_titulo'),
            'padreImagenPortada' => $padreImagenPortada,
            'padreImagenPortadaUrl' => self::rutaImagenContenido($padreImagenPortada),
            'padreAnio' => (int)self::leerCampo($detalle, 'padreAnio', 'padre_anio_lanzamiento')
        ];
    }

    public static function normalizarGenerosGestion($generos)
    {
        $generosNormalizados = [];

        foreach ($generos as $genero) {
            $idGenero = self::leerCampo($genero, 'idGenero', 'id_genero');
            $nombreGenero = self::leerCampo($genero, 'nombreGenero', 'nombre_genero');
            $activo = self::leerCampo($genero, 'activo', 'activo');

            if ($activo !== '' && (int)$activo === 0) {
                continue;
            }

            if ($nombreGenero !== '') {
                $generosNormalizados[] = [
                    'id' => (int)$idGenero,
                    'nombre' => $nombreGenero
                ];
            }
        }

        return $generosNormalizados;
    }

    public static function aniosFiltro($contenido)
    {
        $anios = [];

        foreach ($contenido as $fila) {
            $anio = 0;
            if (isset($fila['anio'])) {
                $anio = (int)$fila['anio'];
            }

            if ($anio > 0 && !in_array($anio, $anios)) {
                $anios[] = $anio;
            }
        }

        rsort($anios);

        return $anios;
    }

    public static function seleccionar($valorActual, $valorOpcion)
    {
        if ((string)$valorActual === (string)$valorOpcion) {
            return ' selected';
        }

        return '';
    }

    public static function marcar($valorActual, $valorOpcion)
    {
        if ((string)$valorActual === (string)$valorOpcion) {
            return ' checked';
        }

        return '';
    }

    public static function valorFiltro($filtros, $campo)
    {
        if (isset($filtros[$campo])) {
            return htmlspecialchars((string)$filtros[$campo], ENT_QUOTES, 'UTF-8');
        }

        return '';
    }

    public static function enlaceInformacion($filtros, $id)
    {
        $parametros = [
            'busqueda' => $filtros['busqueda'],
            'tipo' => $filtros['tipo'],
            'genero' => $filtros['idGenero'],
            'estado' => $filtros['estado'],
            'anio' => $filtros['anio'],
            'estado_emision' => $filtros['estadoEmision'],
            'destacado' => $filtros['destacado'],
            'id' => $id
        ];

        return 'gestion-peliculas-series.php?' . http_build_query($parametros);
    }

    public static function enlaceSinInformacion($filtros)
    {
        $parametros = [
            'busqueda' => $filtros['busqueda'],
            'tipo' => $filtros['tipo'],
            'genero' => $filtros['idGenero'],
            'estado' => $filtros['estado'],
            'anio' => $filtros['anio'],
            'estado_emision' => $filtros['estadoEmision'],
            'destacado' => $filtros['destacado']
        ];

        return 'gestion-peliculas-series.php?' . http_build_query($parametros);
    }

    public static function enlaceFiltroRapido($filtros, $cambios)
    {
        $parametros = [
            'busqueda' => $filtros['busqueda'],
            'tipo' => $filtros['tipo'],
            'genero' => $filtros['idGenero'],
            'estado' => $filtros['estado'],
            'anio' => $filtros['anio'],
            'estado_emision' => $filtros['estadoEmision'],
            'destacado' => $filtros['destacado']
        ];

        foreach ($cambios as $campo => $valor) {
            $parametros[$campo] = $valor;
        }

        return 'gestion-peliculas-series.php?' . http_build_query($parametros);
    }

    public static function claseFiltroRapido($activo)
    {
        if ($activo) {
            return ' activo';
        }

        return '';
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

    private static function claseEstado($estado)
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

    private static function claseEstadoEmision($estadoEmision)
    {
        $estadoNormalizado = strtolower(trim($estadoEmision));

        if ($estadoNormalizado === 'en emisión' || $estadoNormalizado === 'en emision') {
            return 'emision';
        }

        if ($estadoNormalizado === 'finalizado') {
            return 'finalizado';
        }

        if ($estadoNormalizado === 'próximamente' || $estadoNormalizado === 'proximamente') {
            return 'proximamente';
        }

        return 'general';
    }

}
?>
