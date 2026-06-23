<?php
class ClienteSOAP {
    private $cliente;

    public function __construct($wsdl = null) {
        if ($wsdl === null) {
            $wsdl = __DIR__ . '/servicioElyra.wsdl';
        }
        $this->cliente = new SoapClient($wsdl, [
            'cache_wsdl' => WSDL_CACHE_NONE,
            'exceptions' => true
        ]);
    }

    // Función general para llamar cualquier método del WSDL
    public function llamar($metodo, $parametros = []) {
        try {
            $argumentos = [];
            if (!empty($parametros)) {
                $argumentos = [$parametros];
            }

            return $this->cliente->__soapCall($metodo, $argumentos);
        } catch (SoapFault $e) {
            throw new Exception("Error llamando a $metodo: " . $e->getMessage());
        }
    }

    // Función para listar géneros (ya existente)
    public function listarGeneros() {
        $respuesta = $this->llamar('listarGeneros');
        if (!isset($respuesta->genero)) {
            return [];
        }

        if (is_array($respuesta->genero)) {
            return $respuesta->genero;
        }

        return [$respuesta->genero];
    }

    public function listarGenerosGestion() {
        $respuesta = $this->llamar('listarGenerosGestion');
        if (!isset($respuesta->genero)) {
            return [];
        }

        if (is_array($respuesta->genero)) {
            return $respuesta->genero;
        }

        return [$respuesta->genero];
    }

    public function crearGenero($nombreGenero) {
        $respuesta = $this->llamar('crearGenero', [
            'nombreGenero' => $nombreGenero
        ]);

        return $this->normalizarRespuestaGenero($respuesta, 'No se pudo agregar el género');
    }

    public function actualizarGenero($idGenero, $nombreGenero) {
        $respuesta = $this->llamar('actualizarGenero', [
            'idGenero' => $idGenero,
            'nombreGenero' => $nombreGenero
        ]);

        return $this->normalizarRespuestaGenero($respuesta, 'No se pudo actualizar el género');
    }

    public function desactivarGenero($idGenero) {
        $respuesta = $this->llamar('desactivarGenero', [
            'idGenero' => $idGenero
        ]);

        return $this->normalizarRespuestaGenero($respuesta, 'No se pudo desactivar el género');
    }

    public function activarGenero($idGenero) {
        $respuesta = $this->llamar('activarGenero', [
            'idGenero' => $idGenero
        ]);

        return $this->normalizarRespuestaGenero($respuesta, 'No se pudo activar el género');
    }

    // Nueva función para registrar usuario
    public function registrarUsuario($usuario, $correo, $contrasena, $fechaNacimiento, $generos = []) {
        // Si los géneros vienen como array, convertimos a CSV
        if (is_array($generos)) {
            $generos = implode(",", $generos);
        }

        $respuesta = $this->llamar('registrarUsuario', [
            'usuario' => $usuario,
            'correo' => $correo,
            'contrasena' => $contrasena,
            'fechaNacimiento' => $fechaNacimiento,
            'generos' => $generos
        ]);

        if (is_bool($respuesta)) {
            return $respuesta;
        }

        if (isset($respuesta->exito)) {
            return (bool)$respuesta->exito;
        }

        return false;
    }

    public function loginUsuario($usuario, $contrasena) {
        $respuesta = $this->llamar('loginUsuario', [
            'usuario' => $usuario,
            'contrasena' => $contrasena
        ]);

        $resultado = [
            'exito' => false,
            'idUsuario' => 0,
            'usuario' => '',
            'rol' => '',
            'mensaje' => 'Usuario o contraseña incorrectos'
        ];

        if (isset($respuesta->exito)) {
            $resultado['exito'] = (bool)$respuesta->exito;
        }

        if (isset($respuesta->idUsuario)) {
            $resultado['idUsuario'] = (int)$respuesta->idUsuario;
        }

        if (isset($respuesta->usuario)) {
            $resultado['usuario'] = $respuesta->usuario;
        }

        if (isset($respuesta->rol)) {
            $resultado['rol'] = $respuesta->rol;
        }

        if (isset($respuesta->mensaje)) {
            $resultado['mensaje'] = $respuesta->mensaje;
        }

        return $resultado;
    }

    public function actualizarPreferenciasUsuario($usuario, $generos = []) {
        if (is_array($generos)) {
            $generos = implode(',', $generos);
        }

        $respuesta = $this->llamar('actualizarPreferenciasUsuario', [
            'usuario' => $usuario,
            'generos' => $generos
        ]);

        $resultado = [
            'exito' => false,
            'mensaje' => 'No se pudieron actualizar las preferencias',
            'total' => 0
        ];

        if (isset($respuesta->exito)) {
            $resultado['exito'] = (bool)$respuesta->exito;
        }

        if (isset($respuesta->mensaje)) {
            $resultado['mensaje'] = $respuesta->mensaje;
        }

        if (isset($respuesta->total)) {
            $resultado['total'] = (int)$respuesta->total;
        }

        return $resultado;
    }

    public function actualizarUsuario($usuarioActual, $nombre, $usuario, $correo, $fechaNacimiento, $genero) {
        $respuesta = $this->llamar('actualizarUsuario', [
            'usuarioActual' => $usuarioActual,
            'nombre' => $nombre,
            'usuario' => $usuario,
            'correo' => $correo,
            'fechaNacimiento' => $fechaNacimiento,
            'genero' => $genero
        ]);

        $resultado = [
            'exito' => false,
            'mensaje' => 'No se pudo actualizar el usuario',
            'usuario' => $usuarioActual
        ];

        if (isset($respuesta->exito)) {
            $resultado['exito'] = (bool)$respuesta->exito;
        }

        if (isset($respuesta->mensaje)) {
            $resultado['mensaje'] = $respuesta->mensaje;
        }

        if (isset($respuesta->usuario)) {
            $resultado['usuario'] = $respuesta->usuario;
        }

        return $resultado;
    }

    public function cambiarContrasenaUsuario($usuario, $contrasenaActual, $nuevaContrasena) {
        $respuesta = $this->llamar('cambiarContrasenaUsuario', [
            'usuario' => $usuario,
            'contrasenaActual' => $contrasenaActual,
            'nuevaContrasena' => $nuevaContrasena
        ]);

        $resultado = [
            'exito' => false,
            'mensaje' => 'No se pudo cambiar la contraseña'
        ];

        if (isset($respuesta->exito)) {
            $resultado['exito'] = (bool)$respuesta->exito;
        }

        if (isset($respuesta->mensaje)) {
            $resultado['mensaje'] = $respuesta->mensaje;
        }

        return $resultado;
    }

    public function resumenGestionUsuarios() {
        $respuesta = $this->llamar('resumenGestionUsuarios');

        $resultado = [
            'usuariosTotales' => 0,
            'administradores' => 0,
            'usuariosNormales' => 0,
            'nuevosUsuarios' => 0,
            'preferenciasGuardadas' => 0,
            'usuariosDesactivados' => 0
        ];

        if (isset($respuesta->usuariosTotales)) {
            $resultado['usuariosTotales'] = (int)$respuesta->usuariosTotales;
        }

        if (isset($respuesta->administradores)) {
            $resultado['administradores'] = (int)$respuesta->administradores;
        }

        if (isset($respuesta->usuariosNormales)) {
            $resultado['usuariosNormales'] = (int)$respuesta->usuariosNormales;
        }

        if (isset($respuesta->nuevosUsuarios)) {
            $resultado['nuevosUsuarios'] = (int)$respuesta->nuevosUsuarios;
        }

        if (isset($respuesta->preferenciasGuardadas)) {
            $resultado['preferenciasGuardadas'] = (int)$respuesta->preferenciasGuardadas;
        }

        if (isset($respuesta->usuariosDesactivados)) {
            $resultado['usuariosDesactivados'] = (int)$respuesta->usuariosDesactivados;
        }

        return $resultado;
    }

    public function listarUsuariosGestion($filtros) {
        $respuesta = $this->llamar('listarUsuariosGestion', [
            'busqueda' => $filtros['busqueda'],
            'rol' => $filtros['rol'],
            'genero' => $filtros['genero']
        ]);

        if (!isset($respuesta->usuarioGestion)) {
            return [];
        }

        if (is_array($respuesta->usuarioGestion)) {
            return $respuesta->usuarioGestion;
        }

        return [$respuesta->usuarioGestion];
    }

    public function obtenerDetalleUsuarioGestion($idUsuario) {
        return $this->llamar('obtenerDetalleUsuarioGestion', [
            'idUsuario' => $idUsuario
        ]);
    }

    public function actualizarRolUsuarioGestion($idUsuario, $rol) {
        $respuesta = $this->llamar('actualizarRolUsuarioGestion', [
            'idUsuario' => $idUsuario,
            'rol' => $rol
        ]);

        return $this->normalizarRespuestaUsuarioGestion($respuesta, 'No se pudo actualizar el rol');
    }

    public function desactivarUsuarioGestion($idUsuario) {
        $respuesta = $this->llamar('desactivarUsuarioGestion', [
            'idUsuario' => $idUsuario
        ]);

        return $this->normalizarRespuestaUsuarioGestion($respuesta, 'No se pudo desactivar el usuario');
    }

    public function activarUsuarioGestion($idUsuario) {
        $respuesta = $this->llamar('activarUsuarioGestion', [
            'idUsuario' => $idUsuario
        ]);

        return $this->normalizarRespuestaUsuarioGestion($respuesta, 'No se pudo activar el usuario');
    }

    public function resumenReportesEstadisticas() {
        $respuesta = $this->llamar('resumenReportesEstadisticas');

        $resultado = [
            'usuariosTotales' => 0,
            'usuariosActivos' => 0,
            'cuentasBloqueadas' => 0,
            'peliculasTotales' => 0,
            'seriesTotales' => 0,
            'generosTotales' => 0,
            'contenidosPublicados' => 0,
            'contenidosDesactivados' => 0
        ];

        foreach ($resultado as $campo => $valor) {
            if (isset($respuesta->$campo)) {
                $resultado[$campo] = (int)$respuesta->$campo;
            }
        }

        return $resultado;
    }

    public function ultimosUsuariosReportes() {
        $respuesta = $this->llamar('ultimosUsuariosReportes');

        if (!isset($respuesta->usuarioReporte)) {
            return [];
        }

        if (is_array($respuesta->usuarioReporte)) {
            return $respuesta->usuarioReporte;
        }

        return [$respuesta->usuarioReporte];
    }

    public function ultimoContenidoReportes() {
        $respuesta = $this->llamar('ultimoContenidoReportes');

        if (!isset($respuesta->contenidoReporte)) {
            return [];
        }

        if (is_array($respuesta->contenidoReporte)) {
            return $respuesta->contenidoReporte;
        }

        return [$respuesta->contenidoReporte];
    }

    public function generosMasElegidosReportes() {
        return $this->normalizarListaGeneroReporte(
            $this->llamar('generosMasElegidosReportes')
        );
    }

    public function contenidoPorGeneroReportes() {
        return $this->normalizarListaGeneroReporte(
            $this->llamar('contenidoPorGeneroReportes')
        );
    }

    public function distribucionContenidoReportes() {
        return $this->normalizarListaDatoReporte(
            $this->llamar('distribucionContenidoReportes')
        );
    }

    public function estadoContenidoReportes() {
        return $this->normalizarListaDatoReporte(
            $this->llamar('estadoContenidoReportes')
        );
    }

    public function resumenPanelAdministrador() {
        $respuesta = $this->llamar('resumenPanelAdministrador');

        $resultado = [
            'usuariosTotales' => 0,
            'peliculasSeriesTotales' => 0,
            'generosTotales' => 0,
            'favoritosTotales' => 0
        ];

        if (isset($respuesta->usuariosTotales)) {
            $resultado['usuariosTotales'] = (int)$respuesta->usuariosTotales;
        }

        if (isset($respuesta->peliculasSeriesTotales)) {
            $resultado['peliculasSeriesTotales'] = (int)$respuesta->peliculasSeriesTotales;
        }

        if (isset($respuesta->generosTotales)) {
            $resultado['generosTotales'] = (int)$respuesta->generosTotales;
        }

        if (isset($respuesta->favoritosTotales)) {
            $resultado['favoritosTotales'] = (int)$respuesta->favoritosTotales;
        }

        return $resultado;
    }

    public function actividadRecientePanel() {
        $respuesta = $this->llamar('actividadRecientePanel');

        if (!isset($respuesta->actividad)) {
            return [];
        }

        if (is_array($respuesta->actividad)) {
            return $respuesta->actividad;
        }

        return [$respuesta->actividad];
    }

    public function ultimoContenidoAgregadoPanel() {
        $respuesta = $this->llamar('ultimoContenidoAgregadoPanel');

        if (!isset($respuesta->contenido)) {
            return [];
        }

        if (is_array($respuesta->contenido)) {
            return $respuesta->contenido;
        }

        return [$respuesta->contenido];
    }

    public function resumenGestionPeliculasSeries() {
        $respuesta = $this->llamar('resumenGestionPeliculasSeries');

        $resultado = [
            'totalContenido' => 0,
            'totalPeliculas' => 0,
            'totalSeries' => 0,
            'totalPublicados' => 0,
            'totalDesactivados' => 0
        ];

        if (isset($respuesta->totalContenido)) {
            $resultado['totalContenido'] = (int)$respuesta->totalContenido;
        }

        if (isset($respuesta->totalPeliculas)) {
            $resultado['totalPeliculas'] = (int)$respuesta->totalPeliculas;
        }

        if (isset($respuesta->totalSeries)) {
            $resultado['totalSeries'] = (int)$respuesta->totalSeries;
        }

        if (isset($respuesta->totalPublicados)) {
            $resultado['totalPublicados'] = (int)$respuesta->totalPublicados;
        }

        if (isset($respuesta->totalDesactivados)) {
            $resultado['totalDesactivados'] = (int)$respuesta->totalDesactivados;
        }

        return $resultado;
    }

    public function listarPeliculasSeriesGestion($filtros) {
        $respuesta = $this->llamar('listarPeliculasSeriesGestion', [
            'busqueda' => $filtros['busqueda'],
            'tipo' => $filtros['tipo'],
            'idGenero' => $filtros['idGenero'],
            'estado' => $filtros['estado'],
            'anio' => $filtros['anio']
        ]);

        if (!isset($respuesta->contenido)) {
            return [];
        }

        if (is_array($respuesta->contenido)) {
            return $respuesta->contenido;
        }

        return [$respuesta->contenido];
    }

    public function obtenerDetallePeliculaSerie($idPeliculaSerie) {
        return $this->llamar('obtenerDetallePeliculaSerie', [
            'idPeliculaSerie' => $idPeliculaSerie
        ]);
    }

    public function desactivarPeliculaSerie($idPeliculaSerie) {
        $respuesta = $this->llamar('desactivarPeliculaSerie', [
            'idPeliculaSerie' => $idPeliculaSerie
        ]);

        $resultado = [
            'exito' => false,
            'mensaje' => 'No se pudo desactivar el contenido'
        ];

        if (isset($respuesta->exito)) {
            $resultado['exito'] = (bool)$respuesta->exito;
        }

        if (isset($respuesta->mensaje)) {
            $resultado['mensaje'] = $respuesta->mensaje;
        }

        return $resultado;
    }

    public function activarPeliculaSerie($idPeliculaSerie) {
        $respuesta = $this->llamar('activarPeliculaSerie', [
            'idPeliculaSerie' => $idPeliculaSerie
        ]);

        $resultado = [
            'exito' => false,
            'mensaje' => 'No se pudo activar el contenido'
        ];

        if (isset($respuesta->exito)) {
            $resultado['exito'] = (bool)$respuesta->exito;
        }

        if (isset($respuesta->mensaje)) {
            $resultado['mensaje'] = $respuesta->mensaje;
        }

        return $resultado;
    }

    public function crearPeliculaSerie($datos) {
        $respuesta = $this->llamar('crearPeliculaSerie', $datos);

        return $this->normalizarRespuestaContenido($respuesta, 'No se pudo crear el contenido');
    }

    public function actualizarPeliculaSerie($idPeliculaSerie, $datos) {
        $parametros = [
            'idPeliculaSerie' => $idPeliculaSerie,
            'titulo' => $datos['titulo'],
            'tituloOriginal' => $datos['tituloOriginal'],
            'descripcion' => $datos['descripcion'],
            'tipo' => $datos['tipo'],
            'estado' => $datos['estado'],
            'estadoEmision' => $datos['estadoEmision'],
            'anioLanzamiento' => $datos['anioLanzamiento'],
            'fechaEstreno' => $datos['fechaEstreno'],
            'duracionMinutos' => $datos['duracionMinutos'],
            'temporadas' => $datos['temporadas'],
            'episodios' => $datos['episodios'],
            'imagenPortada' => $datos['imagenPortada'],
            'imagenBanner' => $datos['imagenBanner'],
            'trailerUrl' => $datos['trailerUrl'],
            'generos' => $datos['generos'],
            'destacado' => $datos['destacado'],
            'seriePadreId' => $datos['seriePadreId'],
            'numeroTemporada' => $datos['numeroTemporada'],
            'tipoRelacion' => $datos['tipoRelacion']
        ];

        $respuesta = $this->llamar('actualizarPeliculaSerie', $parametros);

        return $this->normalizarRespuestaContenido($respuesta, 'No se pudo actualizar el contenido');
    }

    public function listarSeriesPadre($excluirId = 0) {
        $respuesta = $this->llamar('listarSeriesPadre', [
            'excluirId' => $excluirId
        ]);

        if (!isset($respuesta->seriePadre)) {
            return [];
        }

        if (is_array($respuesta->seriePadre)) {
            return $respuesta->seriePadre;
        }

        return [$respuesta->seriePadre];
    }

    public function obtenerHijosPeliculaSerie($idPeliculaSerie) {
        $respuesta = $this->llamar('obtenerHijosPeliculaSerie', [
            'idPeliculaSerie' => $idPeliculaSerie
        ]);

        if (!isset($respuesta->hijo)) {
            return [];
        }

        if (is_array($respuesta->hijo)) {
            return $respuesta->hijo;
        }

        return [$respuesta->hijo];
    }

    public function datosInicioUsuario($usuario) {
        $respuesta = $this->llamar('datosInicioUsuario', [
            'usuario' => $usuario
        ]);

        return [
            'destacados' => $this->normalizarListaInicio($respuesta, 'destacado'),
            'recomendaciones' => $this->normalizarListaInicio($respuesta, 'recomendado'),
            'ultimos' => $this->normalizarListaInicio($respuesta, 'ultimo'),
            'generos' => $this->normalizarGenerosInicio($respuesta)
        ];
    }

    public function detalleContenidoUsuario($idPeliculaSerie, $usuario) {
        return $this->llamar('detalleContenidoUsuario', [
            'idPeliculaSerie' => $idPeliculaSerie,
            'usuario' => $usuario
        ]);
    }

    public function buscarContenidoUsuario($busqueda) {
        $respuesta = $this->llamar('buscarContenidoUsuario', [
            'busqueda' => $busqueda
        ]);

        return $this->normalizarListaInicio($respuesta, 'contenido');
    }

    public function explorarContenidoUsuario($usuario, $filtros) {
        $respuesta = $this->llamar('explorarContenidoUsuario', [
            'usuario' => $usuario,
            'busqueda' => $filtros['busqueda'],
            'tipo' => $filtros['tipo'],
            'genero' => $filtros['genero'],
            'anio' => $filtros['anio'],
            'orden' => $filtros['orden']
        ]);

        return $this->normalizarListaInicio($respuesta, 'contenido');
    }

    public function contenidoPorGeneroUsuario($usuario, $filtros) {
        $respuesta = $this->llamar('contenidoPorGeneroUsuario', [
            'usuario' => $usuario,
            'idGenero' => $filtros['genero'],
            'tipo' => $filtros['tipo'],
            'anio' => $filtros['anio'],
            'orden' => $filtros['orden']
        ]);

        return $this->normalizarListaInicio($respuesta, 'contenido');
    }

    public function favoritosUsuario($usuario, $filtros) {
        $respuesta = $this->llamar('favoritosUsuario', [
            'usuario' => $usuario,
            'tipo' => $filtros['tipo'],
            'anio' => $filtros['anio'],
            'orden' => $filtros['orden']
        ]);

        return $this->normalizarListaInicio($respuesta, 'contenido');
    }

    public function alternarFavoritoUsuario($usuario, $idPeliculaSerie) {
        $respuesta = $this->llamar('alternarFavoritoUsuario', [
            'usuario' => $usuario,
            'idPeliculaSerie' => $idPeliculaSerie
        ]);

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

    private function normalizarRespuestaContenido($respuesta, $mensajeDefecto) {
        $resultado = [
            'exito' => false,
            'mensaje' => $mensajeDefecto,
            'idPeliculaSerie' => 0
        ];

        if (isset($respuesta->exito)) {
            $resultado['exito'] = (bool)$respuesta->exito;
        }

        if (isset($respuesta->mensaje)) {
            $resultado['mensaje'] = $respuesta->mensaje;
        }

        if (isset($respuesta->idPeliculaSerie)) {
            $resultado['idPeliculaSerie'] = (int)$respuesta->idPeliculaSerie;
        }

        return $resultado;
    }

    private function normalizarRespuestaGenero($respuesta, $mensajeDefecto) {
        $resultado = [
            'exito' => false,
            'mensaje' => $mensajeDefecto,
            'idGenero' => 0
        ];

        if (isset($respuesta->exito)) {
            $resultado['exito'] = (bool)$respuesta->exito;
        }

        if (isset($respuesta->mensaje)) {
            $resultado['mensaje'] = $respuesta->mensaje;
        }

        if (isset($respuesta->idGenero)) {
            $resultado['idGenero'] = (int)$respuesta->idGenero;
        }

        return $resultado;
    }

    private function normalizarRespuestaUsuarioGestion($respuesta, $mensajeDefecto) {
        $resultado = [
            'exito' => false,
            'mensaje' => $mensajeDefecto,
            'idUsuario' => 0
        ];

        if (isset($respuesta->exito)) {
            $resultado['exito'] = (bool)$respuesta->exito;
        }

        if (isset($respuesta->mensaje)) {
            $resultado['mensaje'] = $respuesta->mensaje;
        }

        if (isset($respuesta->idUsuario)) {
            $resultado['idUsuario'] = (int)$respuesta->idUsuario;
        }

        return $resultado;
    }

    private function normalizarListaGeneroReporte($respuesta) {
        if (!isset($respuesta->generoReporte)) {
            return [];
        }

        if (is_array($respuesta->generoReporte)) {
            return $respuesta->generoReporte;
        }

        return [$respuesta->generoReporte];
    }

    private function normalizarListaDatoReporte($respuesta) {
        if (!isset($respuesta->datoReporte)) {
            return [];
        }

        if (is_array($respuesta->datoReporte)) {
            return $respuesta->datoReporte;
        }

        return [$respuesta->datoReporte];
    }

    private function normalizarListaInicio($respuesta, $campo) {
        if (!isset($respuesta->$campo)) {
            return [];
        }

        if (is_array($respuesta->$campo)) {
            return $respuesta->$campo;
        }

        return [$respuesta->$campo];
    }

    private function normalizarGenerosInicio($respuesta) {
        if (!isset($respuesta->genero)) {
            return [];
        }

        if (is_array($respuesta->genero)) {
            return $respuesta->genero;
        }

        return [$respuesta->genero];
    }

    // Futuras funciones: login, listarPeliculas, listarSeries, etc.
}
?>
