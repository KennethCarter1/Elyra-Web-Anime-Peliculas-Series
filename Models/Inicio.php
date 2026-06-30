<?php
class Inicio {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerDestacados($usuario) {
        return $this->obtenerListado("CALL sp_inicio_destacados_usuario(:usuario)", [
            ':usuario' => $usuario
        ]);
    }

    public function obtenerRecomendaciones($usuario) {
        return $this->obtenerListado("CALL sp_inicio_recomendaciones_usuario(:usuario)", [
            ':usuario' => $usuario
        ]);
    }

    public function obtenerUltimosAgregados($usuario) {
        return $this->obtenerListado("CALL sp_inicio_ultimos_agregados_usuario(:usuario)", [
            ':usuario' => $usuario
        ]);
    }

    public function obtenerGenerosInicio() {
        return $this->obtenerListado("CALL sp_inicio_generos_usuario()", []);
    }

    public function obtenerDetalle($idPeliculaSerie, $usuario) {
        $stmt = $this->conexion->prepare(
            "CALL sp_detalle_contenido_usuario(:idPeliculaSerie, :usuario)"
        );
        $stmt->execute([
            ':idPeliculaSerie' => $idPeliculaSerie,
            ':usuario' => $usuario
        ]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return false;
    }

    public function buscarContenido($busqueda) {
        return $this->obtenerListado("CALL sp_buscar_contenido_usuario(:busqueda)", [
            ':busqueda' => $busqueda
        ]);
    }

    public function explorarContenido($usuario, $busqueda, $tipo, $genero, $anio, $orden) {
        return $this->obtenerListado(
            "CALL sp_explorar_contenido_usuario(:usuario, :busqueda, :tipo, :genero, :anio, :orden)",
            [
                ':usuario' => $usuario,
                ':busqueda' => $busqueda,
                ':tipo' => $tipo,
                ':genero' => $genero,
                ':anio' => $anio,
                ':orden' => $orden
            ]
        );
    }

    public function contenidoPorGenero($usuario, $idGenero, $tipo, $anio, $orden) {
        return $this->obtenerListado(
            "CALL sp_contenido_por_genero_usuario(:usuario, :idGenero, :tipo, :anio, :orden)",
            [
                ':usuario' => $usuario,
                ':idGenero' => $idGenero,
                ':tipo' => $tipo,
                ':anio' => $anio,
                ':orden' => $orden
            ]
        );
    }

    public function favoritosUsuario($usuario, $tipo, $anio, $orden) {
        return $this->obtenerListado(
            "CALL sp_favoritos_usuario(:usuario, :tipo, :anio, :orden)",
            [
                ':usuario' => $usuario,
                ':tipo' => $tipo,
                ':anio' => $anio,
                ':orden' => $orden
            ]
        );
    }

    public function alternarFavorito($usuario, $idPeliculaSerie) {
        $stmt = $this->conexion->prepare(
            "CALL sp_alternar_favorito_usuario(:usuario, :idPeliculaSerie)"
        );
        $stmt->execute([
            ':usuario' => $usuario,
            ':idPeliculaSerie' => $idPeliculaSerie
        ]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [
            'exito' => 0,
            'mensaje' => 'No se pudo actualizar favoritos',
            'favorito' => 0
        ];
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

    public static function listaInicial()
    {
        return [];
    }

    public static function detalleInicial()
    {
        return [];
    }

    public static function normalizarListaContenido($contenido)
    {
        $resultado = [];

        foreach ($contenido as $fila) {
            $resultado[] = self::normalizarContenido($fila);
        }

        return $resultado;
    }

    public static function normalizarDetalle($detalle)
    {
        if (!$detalle) {
            return [];
        }

        return self::normalizarContenido($detalle);
    }

    public static function normalizarGeneros($generos)
    {
        $resultado = [];

        foreach ($generos as $genero) {
            $idGenero = self::leerCampo($genero, 'idGenero', 'id_genero');
            $nombreGenero = self::leerCampo($genero, 'nombreGenero', 'nombre_genero');

            if ($nombreGenero !== '') {
                $resultado[] = [
                    'id' => (int)$idGenero,
                    'nombre' => $nombreGenero,
                    'icono' => self::iconoGenero($nombreGenero)
                ];
            }
        }

        return $resultado;
    }

    public static function normalizarPanelesPersonalizados($paneles)
    {
        $resultado = [];

        foreach ($paneles as $panel) {
            $contenido = self::leerCampoFlexible($panel, 'contenido');
            $contenidosJson = self::leerCampoFlexible($panel, 'contenidosJson');

            if (empty($contenido) && $contenidosJson !== '') {
                $contenidoDecodificado = json_decode((string)$contenidosJson, true);

                if (is_array($contenidoDecodificado)) {
                    $contenido = $contenidoDecodificado;
                }
            }

            if (!is_array($contenido)) {
                $contenido = [];
            }

            $contenidoNormalizado = self::normalizarListaContenido($contenido);

            if (!empty($contenidoNormalizado)) {
                $resultado[] = [
                    'id' => (int)self::leerCampoFlexible($panel, 'idPanelInicio'),
                    'titulo' => self::leerCampoFlexible($panel, 'titulo'),
                    'descripcion' => self::leerCampoFlexible($panel, 'descripcion'),
                    'orden' => (int)self::leerCampoFlexible($panel, 'orden'),
                    'totalContenido' => (int)self::leerCampoFlexible($panel, 'totalContenido'),
                    'contenido' => $contenidoNormalizado
                ];
            }
        }

        return $resultado;
    }

    public static function normalizarRespuestaFavorito($respuesta)
    {
        $resultado = [
            'exito' => false,
            'mensaje' => 'No se pudo actualizar favoritos',
            'favorito' => 0
        ];

        if (isset($respuesta->exito)) {
            $resultado['exito'] = (bool)$respuesta->exito;
        }

        if (isset($respuesta->mensaje)) {
            $resultado['mensaje'] = $respuesta->mensaje;
        }

        if (isset($respuesta->favorito)) {
            $resultado['favorito'] = (int)$respuesta->favorito;
        }

        return $resultado;
    }

    public static function filtrosExplorar($origen)
    {
        $filtros = [
            'busqueda' => '',
            'tipo' => 'Todos',
            'genero' => 'Todos',
            'anio' => 0,
            'orden' => 'ultimos'
        ];

        if (isset($origen['busqueda'])) {
            $filtros['busqueda'] = trim((string)$origen['busqueda']);
        }

        if (isset($origen['tipo'])) {
            $tipo = trim((string)$origen['tipo']);

            if ($tipo === 'Película' || $tipo === 'Pelicula' || $tipo === 'Serie' || $tipo === 'Todos') {
                if ($tipo === 'Pelicula') {
                    $tipo = 'Película';
                }

                $filtros['tipo'] = $tipo;
            }
        }

        if (isset($origen['genero'])) {
            $genero = trim((string)$origen['genero']);

            if ($genero !== '') {
                $filtros['genero'] = $genero;
            }
        }

        if (isset($origen['anio'])) {
            $anio = (int)$origen['anio'];

            if ($anio > 0) {
                $filtros['anio'] = $anio;
            }
        }

        if (isset($origen['orden'])) {
            $orden = trim((string)$origen['orden']);
            $ordenes = self::ordenesExplorar();

            if (isset($ordenes[$orden])) {
                $filtros['orden'] = $orden;
            }
        }

        return $filtros;
    }

    public static function filtrosGenero($origen)
    {
        $filtros = [
            'genero' => 0,
            'tipo' => 'Todos',
            'anio' => 0,
            'orden' => 'ultimos'
        ];

        if (isset($origen['genero'])) {
            $idGenero = (int)$origen['genero'];

            if ($idGenero > 0) {
                $filtros['genero'] = $idGenero;
            }
        }

        if (isset($origen['tipo'])) {
            $tipo = trim((string)$origen['tipo']);

            if ($tipo === 'Película' || $tipo === 'Pelicula' || $tipo === 'Serie' || $tipo === 'Todos') {
                if ($tipo === 'Pelicula') {
                    $tipo = 'Película';
                }

                $filtros['tipo'] = $tipo;
            }
        }

        if (isset($origen['anio'])) {
            $anio = (int)$origen['anio'];

            if ($anio > 0) {
                $filtros['anio'] = $anio;
            }
        }

        if (isset($origen['orden'])) {
            $orden = trim((string)$origen['orden']);
            $ordenes = self::ordenesExplorar();

            if (isset($ordenes[$orden])) {
                $filtros['orden'] = $orden;
            }
        }

        return $filtros;
    }

    public static function filtrosFavoritos($origen)
    {
        $filtros = [
            'tipo' => 'Todos',
            'anio' => 0,
            'orden' => 'ultimos'
        ];

        if (isset($origen['tipo'])) {
            $tipo = trim((string)$origen['tipo']);

            if ($tipo === 'Película' || $tipo === 'Pelicula' || $tipo === 'Serie' || $tipo === 'Todos') {
                if ($tipo === 'Pelicula') {
                    $tipo = 'Película';
                }

                $filtros['tipo'] = $tipo;
            }
        }

        if (isset($origen['anio'])) {
            $anio = (int)$origen['anio'];

            if ($anio > 0) {
                $filtros['anio'] = $anio;
            }
        }

        if (isset($origen['orden'])) {
            $orden = trim((string)$origen['orden']);
            $ordenes = self::ordenesFavoritos();

            if (isset($ordenes[$orden])) {
                $filtros['orden'] = $orden;
            }
        }

        return $filtros;
    }

    public static function tiposExplorar()
    {
        return ['Todos', 'Película', 'Serie'];
    }

    public static function ordenesExplorar()
    {
        return [
            'ultimos' => 'Últimos agregados',
            'az' => 'A-Z',
            'anio' => 'Año más reciente',
            'antiguos' => 'Más antiguos'
        ];
    }

    public static function ordenesFavoritos()
    {
        return [
            'ultimos' => 'Últimos guardados',
            'az' => 'A-Z',
            'anio' => 'Año más reciente',
            'antiguos' => 'Más antiguos'
        ];
    }

    public static function aniosExplorar($contenido)
    {
        $anios = [];

        foreach ($contenido as $item) {
            if (isset($item['anio']) && (int)$item['anio'] > 0) {
                $anios[(int)$item['anio']] = (int)$item['anio'];
            }
        }

        rsort($anios);

        return $anios;
    }

    public static function hayFiltrosExplorar($filtros)
    {
        if ($filtros['busqueda'] !== '') {
            return true;
        }

        if ($filtros['tipo'] !== 'Todos') {
            return true;
        }

        if ($filtros['genero'] !== 'Todos') {
            return true;
        }

        if ((int)$filtros['anio'] > 0) {
            return true;
        }

        if ($filtros['orden'] !== 'ultimos') {
            return true;
        }

        return false;
    }

    public static function hayFiltrosFavoritos($filtros)
    {
        if ($filtros['tipo'] !== 'Todos') {
            return true;
        }

        if ((int)$filtros['anio'] > 0) {
            return true;
        }

        if ($filtros['orden'] !== 'ultimos') {
            return true;
        }

        return false;
    }

    public static function retornoFavoritoExplorar($filtros)
    {
        $parametros = [];

        if ($filtros['busqueda'] !== '') {
            $parametros['busqueda'] = $filtros['busqueda'];
        }

        if ($filtros['tipo'] !== 'Todos') {
            $parametros['tipo'] = $filtros['tipo'];
        }

        if ($filtros['genero'] !== 'Todos') {
            $parametros['genero'] = $filtros['genero'];
        }

        if ((int)$filtros['anio'] > 0) {
            $parametros['anio'] = (int)$filtros['anio'];
        }

        if ($filtros['orden'] !== 'ultimos') {
            $parametros['orden'] = $filtros['orden'];
        }

        $retorno = '/elyra/explorar';

        if (!empty($parametros)) {
            $retorno .= '?' . http_build_query($parametros);
        }

        return $retorno;
    }

    public static function retornoFavoritoGenero($filtros)
    {
        $parametros = [];

        if ((int)$filtros['genero'] > 0) {
            $parametros['genero'] = (int)$filtros['genero'];
        }

        if ($filtros['tipo'] !== 'Todos') {
            $parametros['tipo'] = $filtros['tipo'];
        }

        if ((int)$filtros['anio'] > 0) {
            $parametros['anio'] = (int)$filtros['anio'];
        }

        if ($filtros['orden'] !== 'ultimos') {
            $parametros['orden'] = $filtros['orden'];
        }

        $retorno = '/elyra/generos';

        if (!empty($parametros)) {
            $retorno .= '?' . http_build_query($parametros);
        }

        return $retorno;
    }

    public static function retornoFavoritoFavoritos($filtros)
    {
        $parametros = [];

        if ($filtros['tipo'] !== 'Todos') {
            $parametros['tipo'] = $filtros['tipo'];
        }

        if ((int)$filtros['anio'] > 0) {
            $parametros['anio'] = (int)$filtros['anio'];
        }

        if ($filtros['orden'] !== 'ultimos') {
            $parametros['orden'] = $filtros['orden'];
        }

        $retorno = '/elyra/favoritos';

        if (!empty($parametros)) {
            $retorno .= '?' . http_build_query($parametros);
        }

        return $retorno;
    }

    public static function resumenFavoritos($contenido)
    {
        $resumen = [
            'total' => 0,
            'peliculas' => 0,
            'series' => 0
        ];

        foreach ($contenido as $item) {
            $resumen['total']++;

            if (isset($item['tipo']) && $item['tipo'] === 'Película') {
                $resumen['peliculas']++;
            }

            if (isset($item['tipo']) && $item['tipo'] === 'Serie') {
                $resumen['series']++;
            }
        }

        return $resumen;
    }

    public static function enlaceGeneroUsuario($idGenero)
    {
        $enlace = 'generos.php';

        if ((int)$idGenero > 0) {
            $enlace .= '?genero=' . (int)$idGenero;
        }

        return $enlace;
    }

    public static function enlaceFiltroGenero($filtros)
    {
        $parametros = [];

        if ((int)$filtros['genero'] > 0) {
            $parametros['genero'] = (int)$filtros['genero'];
        }

        $enlace = 'generos.php';

        if (!empty($parametros)) {
            $enlace .= '?' . http_build_query($parametros);
        }

        return $enlace;
    }

    public static function claseFiltroExplorar($actual, $valor)
    {
        if ((string)$actual === (string)$valor) {
            return 'activo';
        }

        return '';
    }

    public static function claseGeneroSeleccionado($actual, $idGenero)
    {
        if ((int)$actual === (int)$idGenero) {
            return 'activo';
        }

        return '';
    }

    private static function normalizarContenido($fila)
    {
        $imagenPortada = self::leerCampo($fila, 'imagenPortada', 'imagen_portada');
        $imagenBanner = self::leerCampo($fila, 'imagenBanner', 'imagen_banner');
        $trailerUrl = self::leerCampo($fila, 'trailerUrl', 'trailer_url');
        $descripcion = self::leerCampo($fila, 'descripcion', 'descripcion');
        $favorito = (int)self::leerCampo($fila, 'favorito', 'favorito');
        $padreImagenPortada = self::leerCampo($fila, 'padreImagenPortada', 'padre_imagen_portada');
        $estadoEmision = self::leerCampo($fila, 'estadoEmision', 'estado_emision');

        if ($estadoEmision === '') {
            $estadoEmision = 'Finalizado';
        }

        return [
            'id' => (int)self::leerCampo($fila, 'idPeliculaSerie', 'id_pelicula_serie'),
            'titulo' => self::leerCampo($fila, 'titulo', 'titulo'),
            'tituloOriginal' => self::leerCampo($fila, 'tituloOriginal', 'titulo_original'),
            'descripcion' => $descripcion,
            'descripcionCorta' => self::descripcionCorta($descripcion, 170),
            'tipo' => self::leerCampo($fila, 'tipo', 'tipo'),
            'generos' => self::leerCampo($fila, 'generos', 'generos'),
            'imagenPortada' => $imagenPortada,
            'imagenPortadaUrl' => self::rutaImagenContenido($imagenPortada),
            'imagenBanner' => $imagenBanner,
            'imagenBannerUrl' => self::rutaImagenContenido($imagenBanner),
            'trailerUrl' => $trailerUrl,
            'trailerEmbedUrl' => self::youtubeEmbedUrl($trailerUrl),
            'trailerControlUrl' => self::youtubeEmbedUrlControles($trailerUrl),
            'anio' => (int)self::leerCampo($fila, 'anioLanzamiento', 'anio_lanzamiento'),
            'duracionMinutos' => (int)self::leerCampo($fila, 'duracionMinutos', 'duracion_minutos'),
            'temporadas' => (int)self::leerCampo($fila, 'temporadas', 'temporadas'),
            'episodios' => (int)self::leerCampo($fila, 'episodios', 'episodios'),
            'estado' => self::leerCampo($fila, 'estado', 'estado'),
            'estadoEmision' => $estadoEmision,
            'destacado' => (int)self::leerCampo($fila, 'destacado', 'destacado'),
            'favorito' => $favorito,
            'favoritoClase' => self::claseFavorito($favorito),
            'favoritoTexto' => self::textoFavorito($favorito),
            'seriePadreId' => (int)self::leerCampo($fila, 'seriePadreId', 'serie_padre_id'),
            'numeroTemporada' => (int)self::leerCampo($fila, 'numeroTemporada', 'numero_temporada'),
            'tipoRelacion' => self::leerCampo($fila, 'tipoRelacion', 'tipo_relacion'),
            'padreTitulo' => self::leerCampo($fila, 'padreTitulo', 'padre_titulo'),
            'padreImagenPortada' => $padreImagenPortada,
            'padreImagenPortadaUrl' => self::rutaImagenContenido($padreImagenPortada),
            'padreAnio' => (int)self::leerCampo($fila, 'padreAnioLanzamiento', 'padre_anio_lanzamiento')
        ];
    }

    public static function valorSeguro($valor)
    {
        return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
    }

    public static function claseFavorito($favorito)
    {
        if ((int)$favorito === 1) {
            return 'activo';
        }

        return '';
    }

    public static function textoFavorito($favorito)
    {
        if ((int)$favorito === 1) {
            return 'Quitar de favoritos';
        }

        return 'Agregar a favoritos';
    }

    public static function textoMeta($contenido)
    {
        $partes = [];

        if (isset($contenido['tipo']) && $contenido['tipo'] !== '') {
            $partes[] = $contenido['tipo'];
        }

        if (isset($contenido['anio']) && (int)$contenido['anio'] > 0) {
            $partes[] = (string)(int)$contenido['anio'];
        }

        if (isset($contenido['duracionMinutos']) && (int)$contenido['duracionMinutos'] > 0) {
            $partes[] = (int)$contenido['duracionMinutos'] . ' min';
        }

        return implode(' · ', $partes);
    }

    public static function textoMetaDetalle($contenido)
    {
        $partes = [];
        $tipo = '';

        if (isset($contenido['tipo']) && $contenido['tipo'] !== '') {
            $tipo = (string)$contenido['tipo'];
            $partes[] = $tipo;
        }

        if (isset($contenido['anio']) && (int)$contenido['anio'] > 0) {
            $partes[] = (string)(int)$contenido['anio'];
        }

        if (isset($contenido['duracionMinutos']) && (int)$contenido['duracionMinutos'] > 0) {
            $textoDuracion = (int)$contenido['duracionMinutos'] . ' minutos';

            if (strtolower($tipo) === 'serie') {
                $textoDuracion .= ' por capítulo';
            }

            $partes[] = $textoDuracion;
        }

        return implode(' · ', $partes);
    }

    public static function listaGenerosTexto($generos)
    {
        $resultado = [];
        $partes = explode(',', (string)$generos);

        foreach ($partes as $genero) {
            $genero = trim($genero);

            if ($genero !== '') {
                $resultado[] = $genero;
            }
        }

        return $resultado;
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

    private static function leerCampoFlexible($origen, $campo)
    {
        if (is_object($origen) && isset($origen->$campo)) {
            return $origen->$campo;
        }

        if (is_array($origen) && isset($origen[$campo])) {
            return $origen[$campo];
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

    private static function descripcionCorta($descripcion, $limite)
    {
        $texto = trim(strip_tags((string)$descripcion));

        if (strlen($texto) <= $limite) {
            return $texto;
        }

        return substr($texto, 0, $limite) . '...';
    }

    private static function youtubeEmbedUrl($url)
    {
        $idVideo = self::youtubeVideoId($url);

        if ($idVideo === '') {
            return '';
        }

        return 'https://www.youtube.com/embed/' . $idVideo . '?autoplay=1&mute=0&controls=0&loop=1&playlist=' . $idVideo . '&modestbranding=1&rel=0&vq=hd1080&hd=1&enablejsapi=1';
    }

    private static function youtubeEmbedUrlControles($url)
    {
        $idVideo = self::youtubeVideoId($url);

        if ($idVideo === '') {
            return '';
        }

        return 'https://www.youtube.com/embed/' . $idVideo . '?controls=1&rel=0&modestbranding=1&vq=hd1080&hd=1&enablejsapi=1';
    }

    private static function youtubeVideoId($url)
    {
        $url = trim($url);

        if ($url === '') {
            return '';
        }

        $partes = parse_url($url);

        if (!$partes) {
            return '';
        }

        if (isset($partes['host']) && strpos($partes['host'], 'youtu.be') !== false && isset($partes['path'])) {
            return trim($partes['path'], '/');
        }

        if (isset($partes['query'])) {
            parse_str($partes['query'], $parametros);

            if (isset($parametros['v']) && trim($parametros['v']) !== '') {
                return trim($parametros['v']);
            }
        }

        if (isset($partes['path']) && strpos($partes['path'], '/embed/') === 0) {
            return trim(str_replace('/embed/', '', $partes['path']), '/');
        }

        return '';
    }

    public static function iconoGenero($nombreGenero)
    {
        $genero = strtolower(trim($nombreGenero));

        if ($genero === 'acción' || $genero === 'accion') {
            return 'fa-solid fa-bolt';
        }

        if ($genero === 'romance') {
            return 'fa-solid fa-heart';
        }

        if ($genero === 'comedia') {
            return 'fa-solid fa-face-laugh';
        }

        if ($genero === 'drama') {
            return 'fa-solid fa-masks-theater';
        }

        if ($genero === 'terror') {
            return 'fa-solid fa-ghost';
        }

        if ($genero === 'fantasía' || $genero === 'fantasia') {
            return 'fa-solid fa-wand-magic-sparkles';
        }

        if ($genero === 'aventura') {
            return 'fa-solid fa-compass';
        }

        return 'fa-solid fa-tag';
    }
}
?>
