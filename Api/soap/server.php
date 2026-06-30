<?php
// Incluir configuración y modelos necesarios
require_once __DIR__ . '/../../Config/config.php';
require_once __DIR__ . '/../../Models/Genero.php';
require_once __DIR__ . '/../../Models/Usuario.php';
require_once __DIR__ . '/../../Models/Preferencias.php';
require_once __DIR__ . '/../../Models/Panel.php';
require_once __DIR__ . '/../../Models/PanelInicio.php';
require_once __DIR__ . '/../../Models/PeliculasSeries.php';
require_once __DIR__ . '/../../Models/ReportesEstadisticas.php';
require_once __DIR__ . '/../../Models/Inicio.php';
require_once __DIR__ . '/../../Models/Seguridad.php';
// Más modelos se podrán incluir luego (Usuario, Pelicula, Serie, etc.)

/*
    Clase general del servicio SOAP
    Expone las funciones de la app (por ahora listarGeneros)
    Más funciones se agregarán según avance la app
*/
class ElyraService {
    private $modeloGenero;
    private $modeloUsuario;
    private $modeloPreferencia;
    private $modeloPanel;
    private $modeloPanelInicio;
    private $modeloPeliculasSeries;
    private $modeloReportesEstadisticas;
    private $modeloInicio;

    public function __construct($conexion) {
        $this->modeloGenero = new Genero($conexion);
        $this->modeloUsuario = new Usuario($conexion);
        $this->modeloPreferencia = new Preferencia($conexion);
        $this->modeloPanel = new Panel($conexion);
        $this->modeloPanelInicio = new PanelInicio($conexion);
        $this->modeloPeliculasSeries = new PeliculasSeries($conexion);
        $this->modeloReportesEstadisticas = new ReportesEstadisticas($conexion);
        $this->modeloInicio = new Inicio($conexion);
        // Otros modelos se inicializarán aquí
    }

    // Función que devuelve todos los nombres de géneros
    public function listarGeneros() {
        $lista = $this->modeloGenero->listar();
        $resultado = [];
        foreach ($lista as $genero) {
            $resultado[] = $genero['nombre_genero'];
        }
        return ['genero' => $resultado];
    }

    public function listarGenerosGestion() {
        $lista = $this->modeloGenero->listarGestion();
        $resultado = [];

        foreach ($lista as $genero) {
            $idGenero = 0;
            if (isset($genero['id_genero'])) {
                $idGenero = (int)$genero['id_genero'];
            }

            $nombreGenero = '';
            if (isset($genero['nombre_genero'])) {
                $nombreGenero = $genero['nombre_genero'];
            }

            $activo = 1;
            if (isset($genero['activo'])) {
                $activo = (int)$genero['activo'];
            }

            $totalContenido = 0;
            if (isset($genero['total_contenido'])) {
                $totalContenido = (int)$genero['total_contenido'];
            }

            $totalPreferencias = 0;
            if (isset($genero['total_preferencias'])) {
                $totalPreferencias = (int)$genero['total_preferencias'];
            }

            $resultado[] = [
                'idGenero' => $idGenero,
                'nombreGenero' => $nombreGenero,
                'activo' => $activo,
                'totalContenido' => $totalContenido,
                'totalPreferencias' => $totalPreferencias
            ];
        }

        return ['genero' => $resultado];
    }

    public function crearGenero($nombreGenero = null)
    {
        try {
            if (is_object($nombreGenero) || is_array($nombreGenero)) {
                $datos = (array)$nombreGenero;
                $nombreGenero = '';

                if (isset($datos['nombreGenero'])) {
                    $nombreGenero = $datos['nombreGenero'];
                }
            }

            $resultado = $this->modeloGenero->crear($nombreGenero);

            return $this->respuestaGenero($resultado, 'No se pudo agregar el género');

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'exito' => false,
                'mensaje' => 'No se pudo agregar el género',
                'idGenero' => 0
            ];
        }
    }

    public function actualizarGenero($idGenero = null, $nombreGenero = null)
    {
        try {
            if (is_object($idGenero) || is_array($idGenero)) {
                $datos = (array)$idGenero;

                $nombreGenero = '';
                if (isset($datos['nombreGenero'])) {
                    $nombreGenero = $datos['nombreGenero'];
                }

                $idGenero = 0;
                if (isset($datos['idGenero'])) {
                    $idGenero = (int)$datos['idGenero'];
                }
            }

            $resultado = $this->modeloGenero->actualizar((int)$idGenero, $nombreGenero);

            return $this->respuestaGenero($resultado, 'No se pudo actualizar el género');

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'exito' => false,
                'mensaje' => 'No se pudo actualizar el género',
                'idGenero' => 0
            ];
        }
    }

    public function desactivarGenero($idGenero = null)
    {
        try {
            if (is_object($idGenero) || is_array($idGenero)) {
                $datos = (array)$idGenero;
                $idGenero = 0;

                if (isset($datos['idGenero'])) {
                    $idGenero = (int)$datos['idGenero'];
                }
            }

            $resultado = $this->modeloGenero->desactivar((int)$idGenero);

            return $this->respuestaGenero($resultado, 'No se pudo desactivar el género');

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'exito' => false,
                'mensaje' => 'No se pudo desactivar el género',
                'idGenero' => 0
            ];
        }
    }

    public function activarGenero($idGenero = null)
    {
        try {
            if (is_object($idGenero) || is_array($idGenero)) {
                $datos = (array)$idGenero;
                $idGenero = 0;

                if (isset($datos['idGenero'])) {
                    $idGenero = (int)$datos['idGenero'];
                }
            }

            $resultado = $this->modeloGenero->activar((int)$idGenero);

            return $this->respuestaGenero($resultado, 'No se pudo activar el género');

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'exito' => false,
                'mensaje' => 'No se pudo activar el género',
                'idGenero' => 0
            ];
        }
    }

    public function registrarUsuario($usuario = null, $correo = null, $contrasena = null, $fechaNacimiento = null, $generos = '')
    {
        try {
            if (is_object($usuario) || is_array($usuario)) {
                $datos = (array)$usuario;

                $correo = '';
                if (isset($datos['correo'])) {
                    $correo = $datos['correo'];
                }

                $contrasena = '';
                if (isset($datos['contrasena'])) {
                    $contrasena = $datos['contrasena'];
                }

                $fechaNacimiento = '';
                if (isset($datos['fechaNacimiento'])) {
                    $fechaNacimiento = $datos['fechaNacimiento'];
                }

                $generos = '';
                if (isset($datos['generos'])) {
                    $generos = $datos['generos'];
                }

                $usuario = '';
                if (isset($datos['usuario'])) {
                    $usuario = $datos['usuario'];
                }
            }

            // Registrar usuario
            $idUsuario = $this->modeloUsuario->registrar(
                $usuario,
                $correo,
                $contrasena,
                $fechaNacimiento
            );

            if (!$idUsuario) {
                return ['exito' => false];
            }

            // Convertir $generos a array si llega como string
            if (!is_array($generos)) {
                $generos = explode(",", $generos);
            }

            // Guardar preferencias del usuario
            foreach ($generos as $genero) {
                $genero = trim((string)$genero);
                if ($genero === '') {
                    continue;
                }

                if (is_numeric($genero)) {
                    $idGenero = (int)$genero;
                } else {
                    $idGenero = $this->modeloGenero->obtenerIdPorNombre($genero);
                }

                if ($idGenero) {
                    $this->modeloUsuario->agregarPreferencia($idUsuario, $idGenero);
                }
            }

            return ['exito' => true]; // Devuelve verdadero si todo salió bien

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return ['exito' => false]; // Devuelve falso si hubo error
        }
    }

    public function loginUsuario($usuario = null, $contrasena = null)
    {
        try {
            if (is_object($usuario) || is_array($usuario)) {
                $datos = (array)$usuario;

                $contrasena = '';
                if (isset($datos['contrasena'])) {
                    $contrasena = $datos['contrasena'];
                }

                $usuario = '';
                if (isset($datos['usuario'])) {
                    $usuario = $datos['usuario'];
                }
            }

            $usuarioDB = $this->modeloUsuario->obtenerPorUsuario($usuario);

            if (!$usuarioDB) {
                return [
                    'exito' => false,
                    'idUsuario' => 0,
                    'usuario' => '',
                    'rol' => '',
                    'mensaje' => 'Usuario o contraseña incorrectos'
                ];
            }

            if (isset($usuarioDB['activo']) && (int)$usuarioDB['activo'] === 0) {
                return [
                    'exito' => false,
                    'idUsuario' => 0,
                    'usuario' => '',
                    'rol' => '',
                    'mensaje' => 'Tu usuario ha sido bloqueado. Contacta al administrador'
                ];
            }

            if (!password_verify($contrasena, $usuarioDB['contrasena_hash'])) {
                return [
                    'exito' => false,
                    'idUsuario' => 0,
                    'usuario' => '',
                    'rol' => '',
                    'mensaje' => 'Usuario o contraseña incorrectos'
                ];
            }

            return [
                'exito' => true,
                'idUsuario' => (int)$usuarioDB['id_usuario'],
                'usuario' => $usuarioDB['usuario'],
                'rol' => $usuarioDB['rol'],
                'mensaje' => ''
            ];

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'exito' => false,
                'idUsuario' => 0,
                'usuario' => '',
                'rol' => '',
                'mensaje' => 'No se pudo iniciar sesión'
            ];
        }
    }

    public function actualizarPreferenciasUsuario($usuario = null, $generos = '')
    {
        try {
            if (is_object($usuario) || is_array($usuario)) {
                $datos = (array)$usuario;

                $generos = '';
                if (isset($datos['generos'])) {
                    $generos = $datos['generos'];
                }

                $usuario = '';
                if (isset($datos['usuario'])) {
                    $usuario = $datos['usuario'];
                }
            }

            if (is_array($generos)) {
                $generos = implode(',', $generos);
            }

            $resultado = $this->modeloPreferencia->actualizarPorUsuario($usuario, $generos);

            $exito = false;
            if (isset($resultado['exito'])) {
                $exito = (bool)$resultado['exito'];
            }

            $mensaje = 'No se pudieron actualizar las preferencias';
            if (isset($resultado['mensaje'])) {
                $mensaje = $resultado['mensaje'];
            }

            $total = 0;
            if (isset($resultado['total'])) {
                $total = (int)$resultado['total'];
            }

            return [
                'exito' => $exito,
                'mensaje' => $mensaje,
                'total' => $total
            ];

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'exito' => false,
                'mensaje' => 'No se pudieron actualizar las preferencias',
                'total' => 0
            ];
        }
    }

    public function preferenciasUsuario($usuario = null)
    {
        try {
            if (is_object($usuario) || is_array($usuario)) {
                $datos = (array)$usuario;

                $usuario = '';
                if (isset($datos['usuario'])) {
                    $usuario = $datos['usuario'];
                }
            }

            $lista = $this->modeloPreferencia->listarPorUsuario($usuario);
            $resultado = [];

            foreach ($lista as $preferencia) {
                $idGenero = 0;
                if (isset($preferencia['id_genero'])) {
                    $idGenero = (int)$preferencia['id_genero'];
                }

                $nombreGenero = '';
                if (isset($preferencia['nombre_genero'])) {
                    $nombreGenero = $preferencia['nombre_genero'];
                }

                $resultado[] = [
                    'idGenero' => $idGenero,
                    'nombreGenero' => $nombreGenero
                ];
            }

            return [
                'genero' => $resultado
            ];

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'genero' => []
            ];
        }
    }

    public function actualizarUsuario($usuarioActual = null, $nombre = null, $usuario = null, $correo = null, $fechaNacimiento = null, $genero = null)
    {
        try {
            if (is_object($usuarioActual) || is_array($usuarioActual)) {
                $datos = (array)$usuarioActual;

                $usuarioActual = '';
                if (isset($datos['usuarioActual'])) {
                    $usuarioActual = $datos['usuarioActual'];
                }

                $nombre = '';
                if (isset($datos['nombre'])) {
                    $nombre = $datos['nombre'];
                }

                $usuario = '';
                if (isset($datos['usuario'])) {
                    $usuario = $datos['usuario'];
                }

                $correo = '';
                if (isset($datos['correo'])) {
                    $correo = $datos['correo'];
                }

                $fechaNacimiento = '';
                if (isset($datos['fechaNacimiento'])) {
                    $fechaNacimiento = $datos['fechaNacimiento'];
                }

                $genero = '';
                if (isset($datos['genero'])) {
                    $genero = $datos['genero'];
                }
            }

            $resultado = $this->modeloUsuario->actualizar(
                $usuarioActual,
                $nombre,
                $usuario,
                $correo,
                $fechaNacimiento,
                $genero
            );

            $exito = false;
            if (isset($resultado['exito'])) {
                $exito = (bool)$resultado['exito'];
            }

            $mensaje = 'No se pudo actualizar el usuario';
            if (isset($resultado['mensaje'])) {
                $mensaje = $resultado['mensaje'];
            }

            $usuarioActualizado = $usuarioActual;
            if (isset($resultado['usuario'])) {
                $usuarioActualizado = $resultado['usuario'];
            }

            return [
                'exito' => $exito,
                'mensaje' => $mensaje,
                'usuario' => $usuarioActualizado
            ];

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'exito' => false,
                'mensaje' => 'No se pudo actualizar el usuario',
                'usuario' => ''
            ];
        }
    }

    public function cambiarContrasenaUsuario($usuario = null, $contrasenaActual = null, $nuevaContrasena = null)
    {
        try {
            if (is_object($usuario) || is_array($usuario)) {
                $datos = (array)$usuario;

                $usuario = '';
                if (isset($datos['usuario'])) {
                    $usuario = $datos['usuario'];
                }

                $contrasenaActual = '';
                if (isset($datos['contrasenaActual'])) {
                    $contrasenaActual = $datos['contrasenaActual'];
                }

                $nuevaContrasena = '';
                if (isset($datos['nuevaContrasena'])) {
                    $nuevaContrasena = $datos['nuevaContrasena'];
                }
            }

            $resultado = $this->modeloUsuario->cambiarContrasena(
                $usuario,
                $contrasenaActual,
                $nuevaContrasena
            );

            $exito = false;
            if (isset($resultado['exito'])) {
                $exito = (bool)$resultado['exito'];
            }

            $mensaje = 'No se pudo cambiar la contraseña';
            if (isset($resultado['mensaje'])) {
                $mensaje = $resultado['mensaje'];
            }

            return [
                'exito' => $exito,
                'mensaje' => $mensaje
            ];

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'exito' => false,
                'mensaje' => 'No se pudo cambiar la contraseña'
            ];
        }
    }

    public function resumenGestionUsuarios()
    {
        try {
            $resultado = $this->modeloUsuario->obtenerResumenGestion();

            return [
                'usuariosTotales' => (int)$resultado['usuarios_totales'],
                'administradores' => (int)$resultado['administradores'],
                'usuariosNormales' => (int)$resultado['usuarios_normales'],
                'nuevosUsuarios' => (int)$resultado['nuevos_usuarios'],
                'preferenciasGuardadas' => (int)$resultado['preferencias_guardadas'],
                'usuariosDesactivados' => (int)$resultado['usuarios_desactivados']
            ];

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'usuariosTotales' => 0,
                'administradores' => 0,
                'usuariosNormales' => 0,
                'nuevosUsuarios' => 0,
                'preferenciasGuardadas' => 0,
                'usuariosDesactivados' => 0
            ];
        }
    }

    public function listarUsuariosGestion($busqueda = null, $rol = null, $genero = null)
    {
        try {
            if (is_object($busqueda) || is_array($busqueda)) {
                $datos = (array)$busqueda;

                $rol = 'Todos';
                if (isset($datos['rol'])) {
                    $rol = $datos['rol'];
                }

                $genero = 'Todos';
                if (isset($datos['genero'])) {
                    $genero = $datos['genero'];
                }

                $busqueda = '';
                if (isset($datos['busqueda'])) {
                    $busqueda = $datos['busqueda'];
                }
            }

            if ($busqueda === null) {
                $busqueda = '';
            }

            if ($rol === null) {
                $rol = 'Todos';
            }

            if ($genero === null) {
                $genero = 'Todos';
            }

            $usuarios = $this->modeloUsuario->listarGestion($busqueda, $rol, $genero);
            $resultado = [];

            foreach ($usuarios as $fila) {
                $resultado[] = [
                    'idUsuario' => (int)$fila['id_usuario'],
                    'nombre' => $fila['nombre'],
                    'usuario' => $fila['usuario'],
                    'correo' => $fila['correo'],
                    'genero' => $fila['genero'],
                    'rol' => $fila['rol'],
                    'fechaCreacion' => $fila['fecha_creacion_formateada'],
                    'activo' => (int)$fila['activo'],
                    'totalPreferencias' => (int)$fila['total_preferencias']
                ];
            }

            return [
                'usuarioGestion' => $resultado
            ];

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'usuarioGestion' => []
            ];
        }
    }

    public function obtenerDetalleUsuarioGestion($idUsuario = null)
    {
        try {
            if (is_object($idUsuario) || is_array($idUsuario)) {
                $datos = (array)$idUsuario;
                $idUsuario = 0;

                if (isset($datos['idUsuario'])) {
                    $idUsuario = (int)$datos['idUsuario'];
                }
            }

            $detalle = $this->modeloUsuario->obtenerDetalleGestion((int)$idUsuario);

            if (!$detalle) {
                return $this->detalleUsuarioGestionVacio();
            }

            return [
                'idUsuario' => (int)$detalle['id_usuario'],
                'nombre' => $detalle['nombre'],
                'usuario' => $detalle['usuario'],
                'correo' => $detalle['correo'],
                'fechaNacimiento' => $detalle['fecha_nacimiento'],
                'genero' => $detalle['genero'],
                'rol' => $detalle['rol'],
                'activo' => (int)$detalle['activo'],
                'fechaCreacion' => $detalle['fecha_creacion_formateada'],
                'preferencias' => $detalle['preferencias'],
                'totalPreferencias' => (int)$detalle['total_preferencias']
            ];

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return $this->detalleUsuarioGestionVacio();
        }
    }

    public function actualizarRolUsuarioGestion($idUsuario = null, $rol = null)
    {
        try {
            if (is_object($idUsuario) || is_array($idUsuario)) {
                $datos = (array)$idUsuario;

                $rol = '';
                if (isset($datos['rol'])) {
                    $rol = $datos['rol'];
                }

                $idUsuario = 0;
                if (isset($datos['idUsuario'])) {
                    $idUsuario = (int)$datos['idUsuario'];
                }
            }

            $resultado = $this->modeloUsuario->actualizarRolGestion((int)$idUsuario, $rol);

            return $this->respuestaUsuarioGestion($resultado, 'No se pudo actualizar el rol');

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'exito' => false,
                'mensaje' => 'No se pudo actualizar el rol',
                'idUsuario' => 0
            ];
        }
    }

    public function desactivarUsuarioGestion($idUsuario = null)
    {
        try {
            if (is_object($idUsuario) || is_array($idUsuario)) {
                $datos = (array)$idUsuario;
                $idUsuario = 0;

                if (isset($datos['idUsuario'])) {
                    $idUsuario = (int)$datos['idUsuario'];
                }
            }

            $resultado = $this->modeloUsuario->desactivarGestion((int)$idUsuario);

            return $this->respuestaUsuarioGestion($resultado, 'No se pudo desactivar el usuario');

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'exito' => false,
                'mensaje' => 'No se pudo desactivar el usuario',
                'idUsuario' => 0
            ];
        }
    }

    public function activarUsuarioGestion($idUsuario = null)
    {
        try {
            if (is_object($idUsuario) || is_array($idUsuario)) {
                $datos = (array)$idUsuario;
                $idUsuario = 0;

                if (isset($datos['idUsuario'])) {
                    $idUsuario = (int)$datos['idUsuario'];
                }
            }

            $resultado = $this->modeloUsuario->activarGestion((int)$idUsuario);

            return $this->respuestaUsuarioGestion($resultado, 'No se pudo activar el usuario');

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'exito' => false,
                'mensaje' => 'No se pudo activar el usuario',
                'idUsuario' => 0
            ];
        }
    }

    public function resumenReportesEstadisticas()
    {
        try {
            $resultado = $this->modeloReportesEstadisticas->obtenerResumen();

            return [
                'usuariosTotales' => (int)$resultado['usuarios_totales'],
                'usuariosActivos' => (int)$resultado['usuarios_activos'],
                'cuentasBloqueadas' => (int)$resultado['cuentas_bloqueadas'],
                'peliculasTotales' => (int)$resultado['peliculas_totales'],
                'seriesTotales' => (int)$resultado['series_totales'],
                'generosTotales' => (int)$resultado['generos_totales'],
                'contenidosPublicados' => (int)$resultado['contenidos_publicados'],
                'contenidosDesactivados' => (int)$resultado['contenidos_desactivados']
            ];

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
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
    }

    public function ultimosUsuariosReportes()
    {
        try {
            $usuarios = $this->modeloReportesEstadisticas->obtenerUltimosUsuarios();
            $resultado = [];

            foreach ($usuarios as $usuario) {
                $resultado[] = [
                    'idUsuario' => (int)$usuario['id_usuario'],
                    'nombre' => $usuario['nombre'],
                    'usuario' => $usuario['usuario'],
                    'correo' => $usuario['correo'],
                    'genero' => $usuario['genero'],
                    'rol' => $usuario['rol'],
                    'activo' => (int)$usuario['activo'],
                    'fechaCreacion' => $usuario['fecha_creacion_formateada']
                ];
            }

            return [
                'usuarioReporte' => $resultado
            ];

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'usuarioReporte' => []
            ];
        }
    }

    public function ultimoContenidoReportes()
    {
        try {
            $contenidos = $this->modeloReportesEstadisticas->obtenerUltimoContenido();
            $resultado = [];

            foreach ($contenidos as $contenido) {
                $resultado[] = [
                    'idPeliculaSerie' => (int)$contenido['id_pelicula_serie'],
                    'titulo' => $contenido['titulo'],
                    'tipo' => $contenido['tipo'],
                    'imagen' => $contenido['imagen'],
                    'generos' => $contenido['generos'],
                    'fecha' => $contenido['fecha'],
                    'estado' => $contenido['estado'],
                    'activo' => (int)$contenido['activo']
                ];
            }

            return [
                'contenidoReporte' => $resultado
            ];

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'contenidoReporte' => []
            ];
        }
    }

    public function generosMasElegidosReportes()
    {
        try {
            return $this->respuestaGeneroReporte(
                $this->modeloReportesEstadisticas->obtenerGenerosMasElegidos(),
                'total_usuarios',
                'totalUsuarios'
            );

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'generoReporte' => []
            ];
        }
    }

    public function contenidoPorGeneroReportes()
    {
        try {
            return $this->respuestaGeneroReporte(
                $this->modeloReportesEstadisticas->obtenerContenidoPorGenero(),
                'total_contenido',
                'totalContenido'
            );

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'generoReporte' => []
            ];
        }
    }

    public function distribucionContenidoReportes()
    {
        try {
            return $this->respuestaDatoReporte(
                $this->modeloReportesEstadisticas->obtenerDistribucionContenido()
            );

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'datoReporte' => []
            ];
        }
    }

    public function estadoContenidoReportes()
    {
        try {
            return $this->respuestaDatoReporte(
                $this->modeloReportesEstadisticas->obtenerEstadoContenido()
            );

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'datoReporte' => []
            ];
        }
    }

    public function resumenPanelAdministrador()
    {
        try {
            $resultado = $this->modeloPanel->obtenerResumenAdministrador();

            $usuariosTotales = 0;
            if (isset($resultado['usuarios_totales'])) {
                $usuariosTotales = (int)$resultado['usuarios_totales'];
            }

            $peliculasSeriesTotales = 0;
            if (isset($resultado['peliculas_series_totales'])) {
                $peliculasSeriesTotales = (int)$resultado['peliculas_series_totales'];
            }

            $generosTotales = 0;
            if (isset($resultado['generos_totales'])) {
                $generosTotales = (int)$resultado['generos_totales'];
            }

            $favoritosTotales = 0;
            if (isset($resultado['favoritos_totales'])) {
                $favoritosTotales = (int)$resultado['favoritos_totales'];
            } elseif (isset($resultado['me_gustas_totales'])) {
                $favoritosTotales = (int)$resultado['me_gustas_totales'];
            }

            return [
                'usuariosTotales' => $usuariosTotales,
                'peliculasSeriesTotales' => $peliculasSeriesTotales,
                'generosTotales' => $generosTotales,
                'favoritosTotales' => $favoritosTotales
            ];

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'usuariosTotales' => 0,
                'peliculasSeriesTotales' => 0,
                'generosTotales' => 0,
                'favoritosTotales' => 0
            ];
        }
    }

    public function actividadRecientePanel()
    {
        try {
            $actividad = $this->modeloPanel->obtenerActividadReciente();
            $resultado = [];

            foreach ($actividad as $fila) {
                $tipoActividad = '';
                if (isset($fila['tipo_actividad'])) {
                    $tipoActividad = $fila['tipo_actividad'];
                }

                $accion = '';
                if (isset($fila['accion'])) {
                    $accion = $fila['accion'];
                }

                $referencia = '';
                if (isset($fila['referencia'])) {
                    $referencia = $fila['referencia'];
                }

                $imagen = '';
                if (isset($fila['imagen'])) {
                    $imagen = $fila['imagen'];
                }

                $fechaActividad = '';
                if (isset($fila['fecha_actividad'])) {
                    $fechaActividad = $fila['fecha_actividad'];
                }

                $resultado[] = [
                    'tipoActividad' => $tipoActividad,
                    'accion' => $accion,
                    'referencia' => $referencia,
                    'imagen' => $imagen,
                    'fechaActividad' => $fechaActividad
                ];
            }

            return [
                'actividad' => $resultado
            ];

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'actividad' => []
            ];
        }
    }

    public function ultimoContenidoAgregadoPanel()
    {
        try {
            $contenido = $this->modeloPanel->obtenerUltimoContenidoAgregado();
            $resultado = [];

            foreach ($contenido as $fila) {
                $idPeliculaSerie = 0;
                if (isset($fila['id_pelicula_serie'])) {
                    $idPeliculaSerie = (int)$fila['id_pelicula_serie'];
                }

                $titulo = '';
                if (isset($fila['titulo'])) {
                    $titulo = $fila['titulo'];
                }

                $tipo = '';
                if (isset($fila['tipo'])) {
                    $tipo = $fila['tipo'];
                }

                $imagen = '';
                if (isset($fila['imagen'])) {
                    $imagen = $fila['imagen'];
                }

                $generos = '';
                if (isset($fila['generos'])) {
                    $generos = $fila['generos'];
                }

                $fecha = '';
                if (isset($fila['fecha'])) {
                    $fecha = $fila['fecha'];
                }

                $estado = '';
                if (isset($fila['estado'])) {
                    $estado = $fila['estado'];
                }

                $activo = 0;
                if (isset($fila['activo'])) {
                    $activo = (int)$fila['activo'];
                }

                $resultado[] = [
                    'idPeliculaSerie' => $idPeliculaSerie,
                    'titulo' => $titulo,
                    'tipo' => $tipo,
                    'imagen' => $imagen,
                    'generos' => $generos,
                    'fecha' => $fecha,
                    'estado' => $estado,
                    'activo' => $activo
                ];
            }

            return [
                'contenido' => $resultado
            ];

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'contenido' => []
            ];
        }
    }

    public function resumenGestionPeliculasSeries()
    {
        try {
            $resultado = $this->modeloPeliculasSeries->obtenerResumenGestion();

            return [
                'totalContenido' => (int)$resultado['total_contenido'],
                'totalPeliculas' => (int)$resultado['total_peliculas'],
                'totalSeries' => (int)$resultado['total_series'],
                'totalPublicados' => (int)$resultado['total_publicados'],
                'totalDesactivados' => (int)$resultado['total_desactivados']
            ];

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'totalContenido' => 0,
                'totalPeliculas' => 0,
                'totalSeries' => 0,
                'totalPublicados' => 0,
                'totalDesactivados' => 0
            ];
        }
    }

    public function listarPeliculasSeriesGestion($busqueda = null, $tipo = null, $idGenero = null, $estado = null, $anio = null, $estadoEmision = null, $destacado = null)
    {
        try {
            if (is_object($busqueda) || is_array($busqueda)) {
                $datos = (array)$busqueda;

                $tipo = 'Todos';
                if (isset($datos['tipo'])) {
                    $tipo = $datos['tipo'];
                }

                $idGenero = 0;
                if (isset($datos['idGenero'])) {
                    $idGenero = (int)$datos['idGenero'];
                }

                $estado = 'Todos';
                if (isset($datos['estado'])) {
                    $estado = $datos['estado'];
                }

                $anio = 0;
                if (isset($datos['anio'])) {
                    $anio = (int)$datos['anio'];
                }

                $estadoEmision = 'Todos';
                if (isset($datos['estadoEmision'])) {
                    $estadoEmision = $datos['estadoEmision'];
                }

                $destacado = 0;
                if (isset($datos['destacado'])) {
                    $destacado = (int)$datos['destacado'];
                }

                $busqueda = '';
                if (isset($datos['busqueda'])) {
                    $busqueda = $datos['busqueda'];
                }
            }

            if ($busqueda === null) {
                $busqueda = '';
            }

            if ($tipo === null) {
                $tipo = 'Todos';
            }

            if ($idGenero === null) {
                $idGenero = 0;
            }

            if ($estado === null) {
                $estado = 'Todos';
            }

            if ($anio === null) {
                $anio = 0;
            }

            if ($estadoEmision === null) {
                $estadoEmision = 'Todos';
            }

            if ($destacado === null) {
                $destacado = 0;
            }

            $contenido = $this->modeloPeliculasSeries->listarGestion(
                $busqueda,
                $tipo,
                (int)$idGenero,
                $estado,
                (int)$anio,
                $estadoEmision,
                (int)$destacado
            );

            $resultado = [];

            foreach ($contenido as $fila) {
                $resultado[] = [
                    'idPeliculaSerie' => (int)$fila['id_pelicula_serie'],
                    'titulo' => $fila['titulo'],
                    'tituloOriginal' => $fila['titulo_original'],
                    'imagen' => $fila['imagen'],
                    'tipo' => $fila['tipo'],
                    'generos' => $fila['generos'],
                    'anio' => (int)$fila['anio'],
                    'estado' => $fila['estado'],
                    'estadoEmision' => $fila['estado_emision'],
                    'trailerUrl' => $fila['trailer_url'],
                    'activo' => (int)$fila['activo'],
                    'destacado' => (int)$fila['destacado']
                ];
            }

            return [
                'contenido' => $resultado
            ];

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'contenido' => []
            ];
        }
    }

    public function obtenerDetallePeliculaSerie($idPeliculaSerie = null)
    {
        try {
            if (is_object($idPeliculaSerie) || is_array($idPeliculaSerie)) {
                $datos = (array)$idPeliculaSerie;
                $idPeliculaSerie = 0;

                if (isset($datos['idPeliculaSerie'])) {
                    $idPeliculaSerie = (int)$datos['idPeliculaSerie'];
                }
            }

            $detalle = $this->modeloPeliculasSeries->obtenerDetalle((int)$idPeliculaSerie);

            if (!$detalle) {
                return $this->detallePeliculaSerieVacio();
            }

            return [
                'idPeliculaSerie' => (int)$detalle['id_pelicula_serie'],
                'titulo' => $detalle['titulo'],
                'tituloOriginal' => $detalle['titulo_original'],
                'descripcion' => $detalle['descripcion'],
                'tipo' => $detalle['tipo'],
                'estado' => $detalle['estado'],
                'estadoEmision' => $detalle['estado_emision'],
                'anioLanzamiento' => (int)$detalle['anio_lanzamiento'],
                'fechaEstreno' => (string)$detalle['fecha_estreno'],
                'duracionMinutos' => (int)$detalle['duracion_minutos'],
                'temporadas' => (int)$detalle['temporadas'],
                'episodios' => (int)$detalle['episodios'],
                'imagenPortada' => $detalle['imagen_portada'],
                'imagenBanner' => $detalle['imagen_banner'],
                'trailerUrl' => $detalle['trailer_url'],
                'destacado' => (int)$detalle['destacado'],
                'activo' => (int)$detalle['activo'],
                'fechaCreacion' => (string)$detalle['fecha_creacion'],
                'fechaActualizacion' => (string)$detalle['fecha_actualizacion'],
                'generos' => $detalle['generos'],
                'idsGeneros' => $detalle['ids_generos'],
                'seriePadreId' => (int)$detalle['serie_padre_id'],
                'numeroTemporada' => (int)$detalle['numero_temporada'],
                'tipoRelacion' => (string)$detalle['tipo_relacion'],
                'padreTitulo' => $detalle['padre_titulo'],
                'padreImagenPortada' => $detalle['padre_imagen_portada'],
                'padreAnioLanzamiento' => (int)$detalle['padre_anio_lanzamiento']
            ];

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return $this->detallePeliculaSerieVacio();
        }
    }

    public function crearPeliculaSerie($datosContenido = null)
    {
        try {
            $datos = $this->datosContenidoDesdeSolicitud($datosContenido);
            $resultado = $this->modeloPeliculasSeries->crear($datos);

            return $this->respuestaContenido($resultado, 'No se pudo crear el contenido');

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'exito' => false,
                'mensaje' => 'No se pudo crear el contenido',
                'idPeliculaSerie' => 0
            ];
        }
    }

    public function actualizarPeliculaSerie($datosContenido = null)
    {
        try {
            $datosSolicitud = [];

            if (is_object($datosContenido) || is_array($datosContenido)) {
                $datosSolicitud = (array)$datosContenido;
            }

            $idPeliculaSerie = 0;
            if (isset($datosSolicitud['idPeliculaSerie'])) {
                $idPeliculaSerie = (int)$datosSolicitud['idPeliculaSerie'];
            }

            $datos = $this->datosContenidoDesdeSolicitud($datosContenido);
            $resultado = $this->modeloPeliculasSeries->actualizar($idPeliculaSerie, $datos);

            return $this->respuestaContenido($resultado, 'No se pudo actualizar el contenido');

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'exito' => false,
                'mensaje' => 'No se pudo actualizar el contenido',
                'idPeliculaSerie' => 0
            ];
        }
    }

    public function desactivarPeliculaSerie($idPeliculaSerie = null)
    {
        try {
            if (is_object($idPeliculaSerie) || is_array($idPeliculaSerie)) {
                $datos = (array)$idPeliculaSerie;
                $idPeliculaSerie = 0;

                if (isset($datos['idPeliculaSerie'])) {
                    $idPeliculaSerie = (int)$datos['idPeliculaSerie'];
                }
            }

            $resultado = $this->modeloPeliculasSeries->desactivar((int)$idPeliculaSerie);

            $exito = false;
            if (isset($resultado['exito'])) {
                $exito = (bool)$resultado['exito'];
            }

            $mensaje = 'No se pudo desactivar el contenido';
            if (isset($resultado['mensaje'])) {
                $mensaje = $resultado['mensaje'];
            }

            return [
                'exito' => $exito,
                'mensaje' => $mensaje
            ];

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'exito' => false,
                'mensaje' => 'No se pudo desactivar el contenido'
            ];
        }
    }

    public function activarPeliculaSerie($idPeliculaSerie = null)
    {
        try {
            if (is_object($idPeliculaSerie) || is_array($idPeliculaSerie)) {
                $datos = (array)$idPeliculaSerie;
                $idPeliculaSerie = 0;

                if (isset($datos['idPeliculaSerie'])) {
                    $idPeliculaSerie = (int)$datos['idPeliculaSerie'];
                }
            }

            $resultado = $this->modeloPeliculasSeries->activar((int)$idPeliculaSerie);

            $exito = false;
            if (isset($resultado['exito'])) {
                $exito = (bool)$resultado['exito'];
            }

            $mensaje = 'No se pudo activar el contenido';
            if (isset($resultado['mensaje'])) {
                $mensaje = $resultado['mensaje'];
            }

            return [
                'exito' => $exito,
                'mensaje' => $mensaje
            ];

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'exito' => false,
                'mensaje' => 'No se pudo activar el contenido'
            ];
        }
    }

    public function listarPanelesInicioGestion()
    {
        try {
            $paneles = $this->modeloPanelInicio->listarGestion();

            return [
                'panelInicio' => $this->respuestaPanelesInicioGestion($paneles)
            ];
        } catch (Throwable $e) {
            return [
                'panelInicio' => []
            ];
        }
    }

    public function buscarContenidoPanelInicio($busqueda = null)
    {
        try {
            if (is_object($busqueda) || is_array($busqueda)) {
                $datos = (array)$busqueda;
                $busqueda = '';

                if (isset($datos['busqueda'])) {
                    $busqueda = $datos['busqueda'];
                }
            }

            if ($busqueda === null) {
                $busqueda = '';
            }

            $contenido = $this->modeloPanelInicio->buscarContenidoDisponible(trim((string)$busqueda));

            return [
                'contenidoPanel' => $this->respuestaContenidoPanelInicioGestion($contenido)
            ];
        } catch (Throwable $e) {
            return [
                'contenidoPanel' => []
            ];
        }
    }

    public function listarContenidoPanelInicioGestion($idPanelInicio = null)
    {
        try {
            if (is_object($idPanelInicio) || is_array($idPanelInicio)) {
                $datos = (array)$idPanelInicio;
                $idPanelInicio = 0;

                if (isset($datos['idPanelInicio'])) {
                    $idPanelInicio = (int)$datos['idPanelInicio'];
                }
            }

            $contenido = $this->modeloPanelInicio->listarContenidoGestion((int)$idPanelInicio);

            return [
                'contenidoPanel' => $this->respuestaContenidoPanelInicioGestion($contenido)
            ];
        } catch (Throwable $e) {
            return [
                'contenidoPanel' => []
            ];
        }
    }

    public function crearPanelInicio($datosPanel = null)
    {
        try {
            $datos = $this->datosPanelInicioDesdeSolicitud($datosPanel);
            $resultado = $this->modeloPanelInicio->crear(
                $datos['titulo'],
                $datos['descripcion'],
                $datos['orden'],
                $datos['activo']
            );

            return $this->respuestaPanelInicio($resultado, 'No se pudo crear el panel');
        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'exito' => false,
                'mensaje' => 'No se pudo crear el panel',
                'idPanelInicio' => 0
            ];
        }
    }

    public function actualizarPanelInicio($datosPanel = null)
    {
        try {
            $datos = $this->datosPanelInicioDesdeSolicitud($datosPanel);
            $resultado = $this->modeloPanelInicio->actualizar(
                $datos['idPanelInicio'],
                $datos['titulo'],
                $datos['descripcion'],
                $datos['orden'],
                $datos['activo']
            );

            return $this->respuestaPanelInicio($resultado, 'No se pudo actualizar el panel');
        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'exito' => false,
                'mensaje' => 'No se pudo actualizar el panel',
                'idPanelInicio' => 0
            ];
        }
    }

    public function eliminarPanelInicio($idPanelInicio = null)
    {
        try {
            if (is_object($idPanelInicio) || is_array($idPanelInicio)) {
                $datos = (array)$idPanelInicio;
                $idPanelInicio = 0;

                if (isset($datos['idPanelInicio'])) {
                    $idPanelInicio = (int)$datos['idPanelInicio'];
                }
            }

            $resultado = $this->modeloPanelInicio->eliminar((int)$idPanelInicio);

            return $this->respuestaPanelInicio($resultado, 'No se pudo eliminar el panel');
        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'exito' => false,
                'mensaje' => 'No se pudo eliminar el panel',
                'idPanelInicio' => 0
            ];
        }
    }

    public function agregarContenidoPanelInicio($datosContenido = null)
    {
        try {
            $datos = $this->datosContenidoPanelInicioDesdeSolicitud($datosContenido);
            $resultado = $this->modeloPanelInicio->agregarContenido(
                $datos['idPanelInicio'],
                $datos['idPeliculaSerie'],
                $datos['orden']
            );

            return $this->respuestaPanelInicio($resultado, 'No se pudo agregar el anime al panel');
        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'exito' => false,
                'mensaje' => 'No se pudo agregar el anime al panel',
                'idPanelInicio' => 0
            ];
        }
    }

    public function quitarContenidoPanelInicio($datosContenido = null)
    {
        try {
            $datos = $this->datosContenidoPanelInicioDesdeSolicitud($datosContenido);
            $resultado = $this->modeloPanelInicio->quitarContenido(
                $datos['idPanelInicio'],
                $datos['idPeliculaSerie']
            );

            return $this->respuestaPanelInicio($resultado, 'No se pudo quitar el anime del panel');
        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'exito' => false,
                'mensaje' => 'No se pudo quitar el anime del panel',
                'idPanelInicio' => 0
            ];
        }
    }

    public function datosInicioUsuario($usuario = null)
    {
        try {
            if (is_object($usuario) || is_array($usuario)) {
                $datos = (array)$usuario;
                $usuario = '';

                if (isset($datos['usuario'])) {
                    $usuario = $datos['usuario'];
                }
            }

            if ($usuario === null) {
                $usuario = '';
            }

            $destacados = $this->modeloInicio->obtenerDestacados($usuario);
            $recomendaciones = $this->modeloInicio->obtenerRecomendaciones($usuario);
            $ultimosAgregados = $this->modeloInicio->obtenerUltimosAgregados($usuario);
            $generos = $this->modeloInicio->obtenerGenerosInicio();
            $panelesInicio = $this->modeloPanelInicio->obtenerPanelesInicio();

            if (empty($recomendaciones)) {
                $recomendaciones = $ultimosAgregados;
            }

            return [
                'destacado' => $this->respuestaContenidoInicio($destacados),
                'recomendado' => $this->respuestaContenidoInicio($recomendaciones),
                'ultimo' => $this->respuestaContenidoInicio($ultimosAgregados),
                'genero' => $this->respuestaGeneroInicio($generos),
                'panelInicio' => $this->respuestaPanelesInicioUsuario($panelesInicio, $usuario)
            ];

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'destacado' => [],
                'recomendado' => [],
                'ultimo' => [],
                'genero' => [],
                'panelInicio' => []
            ];
        }
    }

    public function detalleContenidoUsuario($idPeliculaSerie = null, $usuario = null)
    {
        try {
            if (is_object($idPeliculaSerie) || is_array($idPeliculaSerie)) {
                $datos = (array)$idPeliculaSerie;

                $usuario = '';
                if (isset($datos['usuario'])) {
                    $usuario = $datos['usuario'];
                }

                $idPeliculaSerie = 0;
                if (isset($datos['idPeliculaSerie'])) {
                    $idPeliculaSerie = (int)$datos['idPeliculaSerie'];
                }
            }

            if ($usuario === null) {
                $usuario = '';
            }

            $detalle = $this->modeloInicio->obtenerDetalle((int)$idPeliculaSerie, $usuario);

            if (!$detalle) {
                return $this->contenidoInicioVacio();
            }

            return $this->respuestaFilaContenidoInicio($detalle);

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return $this->contenidoInicioVacio();
        }
    }

    public function buscarContenidoUsuario($busqueda = null)
    {
        try {
            if (is_object($busqueda) || is_array($busqueda)) {
                $datos = (array)$busqueda;
                $busqueda = '';

                if (isset($datos['busqueda'])) {
                    $busqueda = $datos['busqueda'];
                }
            }

            if ($busqueda === null) {
                $busqueda = '';
            }

            $busqueda = trim((string)$busqueda);

            if ($busqueda === '') {
                return [
                    'contenido' => []
                ];
            }

            $contenido = $this->modeloInicio->buscarContenido($busqueda);

            return [
                'contenido' => $this->respuestaContenidoInicio($contenido)
            ];

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'contenido' => []
            ];
        }
    }

    public function explorarContenidoUsuario($usuario = null, $busqueda = null, $tipo = null, $genero = null, $anio = null, $orden = null)
    {
        try {
            if (is_object($usuario) || is_array($usuario)) {
                $datos = (array)$usuario;

                $usuario = '';
                if (isset($datos['usuario'])) {
                    $usuario = $datos['usuario'];
                }

                $busqueda = '';
                if (isset($datos['busqueda'])) {
                    $busqueda = $datos['busqueda'];
                }

                $tipo = 'Todos';
                if (isset($datos['tipo'])) {
                    $tipo = $datos['tipo'];
                }

                $genero = 'Todos';
                if (isset($datos['genero'])) {
                    $genero = $datos['genero'];
                }

                $anio = 0;
                if (isset($datos['anio'])) {
                    $anio = (int)$datos['anio'];
                }

                $orden = 'ultimos';
                if (isset($datos['orden'])) {
                    $orden = $datos['orden'];
                }
            }

            if ($usuario === null) {
                $usuario = '';
            }

            if ($busqueda === null) {
                $busqueda = '';
            }

            if ($tipo === null || trim((string)$tipo) === '') {
                $tipo = 'Todos';
            }

            if ($genero === null || trim((string)$genero) === '') {
                $genero = 'Todos';
            }

            if ($anio === null) {
                $anio = 0;
            }

            if ($orden === null || trim((string)$orden) === '') {
                $orden = 'ultimos';
            }

            $contenido = $this->modeloInicio->explorarContenido(
                trim((string)$usuario),
                trim((string)$busqueda),
                trim((string)$tipo),
                trim((string)$genero),
                (int)$anio,
                trim((string)$orden)
            );

            return [
                'contenido' => $this->respuestaContenidoInicio($contenido)
            ];

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'contenido' => []
            ];
        }
    }

    public function contenidoPorGeneroUsuario($usuario = null, $idGenero = null, $tipo = null, $anio = null, $orden = null)
    {
        try {
            if (is_object($usuario) || is_array($usuario)) {
                $datos = (array)$usuario;

                $usuario = '';
                if (isset($datos['usuario'])) {
                    $usuario = $datos['usuario'];
                }

                $idGenero = 0;
                if (isset($datos['idGenero'])) {
                    $idGenero = (int)$datos['idGenero'];
                }

                $tipo = 'Todos';
                if (isset($datos['tipo'])) {
                    $tipo = $datos['tipo'];
                }

                $anio = 0;
                if (isset($datos['anio'])) {
                    $anio = (int)$datos['anio'];
                }

                $orden = 'ultimos';
                if (isset($datos['orden'])) {
                    $orden = $datos['orden'];
                }
            }

            if ($usuario === null) {
                $usuario = '';
            }

            if ($idGenero === null) {
                $idGenero = 0;
            }

            if ($tipo === null || trim((string)$tipo) === '') {
                $tipo = 'Todos';
            }

            if ($anio === null) {
                $anio = 0;
            }

            if ($orden === null || trim((string)$orden) === '') {
                $orden = 'ultimos';
            }

            $contenido = $this->modeloInicio->contenidoPorGenero(
                trim((string)$usuario),
                (int)$idGenero,
                trim((string)$tipo),
                (int)$anio,
                trim((string)$orden)
            );

            return [
                'contenido' => $this->respuestaContenidoInicio($contenido)
            ];

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'contenido' => []
            ];
        }
    }

    public function favoritosUsuario($usuario = null, $tipo = null, $anio = null, $orden = null)
    {
        try {
            if (is_object($usuario) || is_array($usuario)) {
                $datos = (array)$usuario;

                $usuario = '';
                if (isset($datos['usuario'])) {
                    $usuario = $datos['usuario'];
                }

                $tipo = 'Todos';
                if (isset($datos['tipo'])) {
                    $tipo = $datos['tipo'];
                }

                $anio = 0;
                if (isset($datos['anio'])) {
                    $anio = (int)$datos['anio'];
                }

                $orden = 'ultimos';
                if (isset($datos['orden'])) {
                    $orden = $datos['orden'];
                }
            }

            if ($usuario === null) {
                $usuario = '';
            }

            if ($tipo === null || trim((string)$tipo) === '') {
                $tipo = 'Todos';
            }

            if ($anio === null) {
                $anio = 0;
            }

            if ($orden === null || trim((string)$orden) === '') {
                $orden = 'ultimos';
            }

            $contenido = $this->modeloInicio->favoritosUsuario(
                trim((string)$usuario),
                trim((string)$tipo),
                (int)$anio,
                trim((string)$orden)
            );

            return [
                'contenido' => $this->respuestaContenidoInicio($contenido)
            ];

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'contenido' => []
            ];
        }
    }

    public function alternarFavoritoUsuario($usuario = null, $idPeliculaSerie = null)
    {
        try {
            if (is_object($usuario) || is_array($usuario)) {
                $datos = (array)$usuario;

                $idPeliculaSerie = 0;
                if (isset($datos['idPeliculaSerie'])) {
                    $idPeliculaSerie = (int)$datos['idPeliculaSerie'];
                }

                $usuario = '';
                if (isset($datos['usuario'])) {
                    $usuario = $datos['usuario'];
                }
            }

            if ($usuario === null) {
                $usuario = '';
            }

            if ($idPeliculaSerie === null) {
                $idPeliculaSerie = 0;
            }

            $resultado = $this->modeloInicio->alternarFavorito($usuario, (int)$idPeliculaSerie);

            $exito = false;
            if (isset($resultado['exito'])) {
                $exito = (bool)$resultado['exito'];
            }

            $mensaje = 'No se pudo actualizar favoritos';
            if (isset($resultado['mensaje'])) {
                $mensaje = $resultado['mensaje'];
            }

            $favorito = 0;
            if (isset($resultado['favorito'])) {
                $favorito = (int)$resultado['favorito'];
            }

            return [
                'exito' => $exito,
                'mensaje' => $mensaje,
                'favorito' => $favorito
            ];

        } catch (PDOException $e) {
            throw $e;
        } catch (Throwable $e) {
            return [
                'exito' => false,
                'mensaje' => 'No se pudo actualizar favoritos',
                'favorito' => 0
            ];
        }
    }

    public function listarSeriesPadre($excluirId = null)
    {
        try {
            if (is_object($excluirId) || is_array($excluirId)) {
                $datos = (array)$excluirId;
                $excluirId = 0;

                if (isset($datos['excluirId'])) {
                    $excluirId = (int)$datos['excluirId'];
                }
            }

            $series = $this->modeloPeliculasSeries->listarSeriesPadre((int)$excluirId);
            $resultado = [];

            foreach ($series as $fila) {
                $resultado[] = [
                    'idPeliculaSerie' => (int)$fila['id_pelicula_serie'],
                    'titulo' => $fila['titulo'],
                    'anioLanzamiento' => (int)$fila['anio_lanzamiento']
                ];
            }

            return ['seriePadre' => $resultado];

        } catch (Throwable $e) {
            return ['seriePadre' => []];
        }
    }

    public function obtenerHijosPeliculaSerie($idPeliculaSerie = null)
    {
        try {
            if (is_object($idPeliculaSerie) || is_array($idPeliculaSerie)) {
                $datos = (array)$idPeliculaSerie;
                $idPeliculaSerie = 0;

                if (isset($datos['idPeliculaSerie'])) {
                    $idPeliculaSerie = (int)$datos['idPeliculaSerie'];
                }
            }

            $hijos = $this->modeloPeliculasSeries->obtenerHijos((int)$idPeliculaSerie);
            $resultado = [];

            foreach ($hijos as $fila) {
                $resultado[] = [
                    'idPeliculaSerie' => (int)$fila['id_pelicula_serie'],
                    'titulo' => $fila['titulo'],
                    'imagenPortada' => $fila['imagen_portada'],
                    'anioLanzamiento' => (int)$fila['anio_lanzamiento'],
                    'numeroTemporada' => (int)$fila['numero_temporada'],
                    'tipoRelacion' => (string)$fila['tipo_relacion'],
                    'tipo' => $fila['tipo']
                ];
            }

            return ['hijo' => $resultado];

        } catch (Throwable $e) {
            return ['hijo' => []];
        }
    }

    private function datosPanelInicioDesdeSolicitud($datosPanel)
    {
        $datosSolicitud = [];

        if (is_object($datosPanel) || is_array($datosPanel)) {
            $datosSolicitud = (array)$datosPanel;
        }

        $idPanelInicio = 0;
        if (isset($datosSolicitud['idPanelInicio'])) {
            $idPanelInicio = (int)$datosSolicitud['idPanelInicio'];
        }

        $titulo = '';
        if (isset($datosSolicitud['titulo'])) {
            $titulo = trim((string)$datosSolicitud['titulo']);
        }

        $descripcion = '';
        if (isset($datosSolicitud['descripcion'])) {
            $descripcion = trim((string)$datosSolicitud['descripcion']);
        }

        $orden = 0;
        if (isset($datosSolicitud['orden'])) {
            $orden = (int)$datosSolicitud['orden'];
        }

        $activo = 0;
        if (isset($datosSolicitud['activo'])) {
            $activo = (int)$datosSolicitud['activo'];
        }

        return [
            'idPanelInicio' => $idPanelInicio,
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'orden' => $orden,
            'activo' => $activo
        ];
    }

    private function datosContenidoPanelInicioDesdeSolicitud($datosContenido)
    {
        $datosSolicitud = [];

        if (is_object($datosContenido) || is_array($datosContenido)) {
            $datosSolicitud = (array)$datosContenido;
        }

        $idPanelInicio = 0;
        if (isset($datosSolicitud['idPanelInicio'])) {
            $idPanelInicio = (int)$datosSolicitud['idPanelInicio'];
        }

        $idPeliculaSerie = 0;
        if (isset($datosSolicitud['idPeliculaSerie'])) {
            $idPeliculaSerie = (int)$datosSolicitud['idPeliculaSerie'];
        }

        $orden = 0;
        if (isset($datosSolicitud['orden'])) {
            $orden = (int)$datosSolicitud['orden'];
        }

        return [
            'idPanelInicio' => $idPanelInicio,
            'idPeliculaSerie' => $idPeliculaSerie,
            'orden' => $orden
        ];
    }

    private function respuestaPanelInicio($resultado, $mensajeDefecto)
    {
        $exito = false;
        if (isset($resultado['exito'])) {
            $exito = (bool)$resultado['exito'];
        }

        $mensaje = $mensajeDefecto;
        if (isset($resultado['mensaje'])) {
            $mensaje = $resultado['mensaje'];
        }

        $idPanelInicio = 0;
        if (isset($resultado['id_panel_inicio'])) {
            $idPanelInicio = (int)$resultado['id_panel_inicio'];
        }

        if (isset($resultado['idPanelInicio'])) {
            $idPanelInicio = (int)$resultado['idPanelInicio'];
        }

        return [
            'exito' => $exito,
            'mensaje' => $mensaje,
            'idPanelInicio' => $idPanelInicio
        ];
    }

    private function respuestaPanelesInicioGestion($paneles)
    {
        $resultado = [];

        foreach ($paneles as $panel) {
            $resultado[] = [
                'idPanelInicio' => (int)$panel['id_panel_inicio'],
                'titulo' => $panel['titulo'],
                'descripcion' => $panel['descripcion'],
                'orden' => (int)$panel['orden'],
                'activo' => (int)$panel['activo'],
                'totalContenido' => (int)$panel['total_contenido']
            ];
        }

        return $resultado;
    }

    private function respuestaContenidoPanelInicioGestion($contenido)
    {
        $resultado = [];

        foreach ($contenido as $fila) {
            $orden = 0;
            if (isset($fila['orden'])) {
                $orden = (int)$fila['orden'];
            }

            $resultado[] = [
                'idPanelInicio' => (int)$this->campoArreglo($fila, 'id_panel_inicio'),
                'idPeliculaSerie' => (int)$fila['id_pelicula_serie'],
                'titulo' => $fila['titulo'],
                'tituloOriginal' => $fila['titulo_original'],
                'tipo' => $fila['tipo'],
                'anioLanzamiento' => (int)$fila['anio_lanzamiento'],
                'imagenPortada' => $fila['imagen_portada'],
                'estado' => $fila['estado'],
                'estadoEmision' => $fila['estado_emision'],
                'generos' => $fila['generos'],
                'orden' => $orden
            ];
        }

        return $resultado;
    }

    private function respuestaPanelesInicioUsuario($paneles, $usuario)
    {
        $resultado = [];

        foreach ($paneles as $panel) {
            $idPanelInicio = (int)$panel['id_panel_inicio'];
            $contenido = $this->modeloPanelInicio->obtenerContenidoInicio($idPanelInicio, $usuario);
            $contenidoInicio = $this->respuestaContenidoInicio($contenido);

            if (!empty($contenidoInicio)) {
                $resultado[] = [
                    'idPanelInicio' => $idPanelInicio,
                    'titulo' => $panel['titulo'],
                    'descripcion' => $panel['descripcion'],
                    'orden' => (int)$panel['orden'],
                    'totalContenido' => (int)$panel['total_contenido'],
                    'contenidosJson' => json_encode($contenidoInicio, JSON_UNESCAPED_UNICODE)
                ];
            }
        }

        return $resultado;
    }

    private function campoArreglo($fila, $campo)
    {
        if (isset($fila[$campo])) {
            return $fila[$campo];
        }

        return '';
    }

    private function detalleUsuarioGestionVacio()
    {
        return [
            'idUsuario' => 0,
            'nombre' => '',
            'usuario' => '',
            'correo' => '',
            'fechaNacimiento' => '',
            'genero' => '',
            'rol' => '',
            'activo' => 0,
            'fechaCreacion' => '',
            'preferencias' => '',
            'totalPreferencias' => 0
        ];
    }

    private function respuestaContenidoInicio($filas)
    {
        $resultado = [];

        foreach ($filas as $fila) {
            $resultado[] = $this->respuestaFilaContenidoInicio($fila);
        }

        return $resultado;
    }

    private function respuestaFilaContenidoInicio($fila)
    {
        $idPeliculaSerie = 0;
        if (isset($fila['id_pelicula_serie'])) {
            $idPeliculaSerie = (int)$fila['id_pelicula_serie'];
        }

        $titulo = '';
        if (isset($fila['titulo'])) {
            $titulo = $fila['titulo'];
        }

        $tituloOriginal = '';
        if (isset($fila['titulo_original'])) {
            $tituloOriginal = $fila['titulo_original'];
        }

        $descripcion = '';
        if (isset($fila['descripcion'])) {
            $descripcion = $fila['descripcion'];
        }

        $tipo = '';
        if (isset($fila['tipo'])) {
            $tipo = $fila['tipo'];
        }

        $generos = '';
        if (isset($fila['generos'])) {
            $generos = $fila['generos'];
        }

        $imagenPortada = '';
        if (isset($fila['imagen_portada'])) {
            $imagenPortada = $fila['imagen_portada'];
        }

        $imagenBanner = '';
        if (isset($fila['imagen_banner'])) {
            $imagenBanner = $fila['imagen_banner'];
        }

        $trailerUrl = '';
        if (isset($fila['trailer_url'])) {
            $trailerUrl = $fila['trailer_url'];
        }

        $anioLanzamiento = 0;
        if (isset($fila['anio_lanzamiento'])) {
            $anioLanzamiento = (int)$fila['anio_lanzamiento'];
        }

        $duracionMinutos = 0;
        if (isset($fila['duracion_minutos'])) {
            $duracionMinutos = (int)$fila['duracion_minutos'];
        }

        $temporadas = 0;
        if (isset($fila['temporadas'])) {
            $temporadas = (int)$fila['temporadas'];
        }

        $episodios = 0;
        if (isset($fila['episodios'])) {
            $episodios = (int)$fila['episodios'];
        }

        $estado = '';
        if (isset($fila['estado'])) {
            $estado = $fila['estado'];
        }

        $estadoEmision = 'Finalizado';
        if (isset($fila['estado_emision']) && trim((string)$fila['estado_emision']) !== '') {
            $estadoEmision = $fila['estado_emision'];
        }

        $activo = 1;
        if (isset($fila['activo'])) {
            $activo = (int)$fila['activo'];
        }

        $destacado = 0;
        if (isset($fila['destacado'])) {
            $destacado = (int)$fila['destacado'];
        }

        $favorito = 0;
        if (isset($fila['favorito'])) {
            $favorito = (int)$fila['favorito'];
        }

        $seriePadreId = 0;
        if (isset($fila['serie_padre_id'])) {
            $seriePadreId = (int)$fila['serie_padre_id'];
        }

        $numeroTemporada = 0;
        if (isset($fila['numero_temporada'])) {
            $numeroTemporada = (int)$fila['numero_temporada'];
        }

        $tipoRelacion = '';
        if (isset($fila['tipo_relacion'])) {
            $tipoRelacion = $fila['tipo_relacion'];
        }

        $padreTitulo = '';
        if (isset($fila['padre_titulo'])) {
            $padreTitulo = $fila['padre_titulo'];
        }

        $padreImagenPortada = '';
        if (isset($fila['padre_imagen_portada'])) {
            $padreImagenPortada = $fila['padre_imagen_portada'];
        }

        $padreAnioLanzamiento = 0;
        if (isset($fila['padre_anio_lanzamiento'])) {
            $padreAnioLanzamiento = (int)$fila['padre_anio_lanzamiento'];
        }

        return [
            'idPeliculaSerie' => $idPeliculaSerie,
            'titulo' => $titulo,
            'tituloOriginal' => $tituloOriginal,
            'descripcion' => $descripcion,
            'tipo' => $tipo,
            'generos' => $generos,
            'imagenPortada' => $imagenPortada,
            'imagenBanner' => $imagenBanner,
            'trailerUrl' => $trailerUrl,
            'anioLanzamiento' => $anioLanzamiento,
            'duracionMinutos' => $duracionMinutos,
            'temporadas' => $temporadas,
            'episodios' => $episodios,
            'estado' => $estado,
            'estadoEmision' => $estadoEmision,
            'activo' => $activo,
            'destacado' => $destacado,
            'favorito' => $favorito,
            'seriePadreId' => $seriePadreId,
            'numeroTemporada' => $numeroTemporada,
            'tipoRelacion' => $tipoRelacion,
            'padreTitulo' => $padreTitulo,
            'padreImagenPortada' => $padreImagenPortada,
            'padreAnioLanzamiento' => $padreAnioLanzamiento
        ];
    }

    private function respuestaGeneroInicio($filas)
    {
        $resultado = [];

        foreach ($filas as $fila) {
            $idGenero = 0;
            if (isset($fila['id_genero'])) {
                $idGenero = (int)$fila['id_genero'];
            }

            $nombreGenero = '';
            if (isset($fila['nombre_genero'])) {
                $nombreGenero = $fila['nombre_genero'];
            }

            $resultado[] = [
                'idGenero' => $idGenero,
                'nombreGenero' => $nombreGenero
            ];
        }

        return $resultado;
    }

    private function contenidoInicioVacio()
    {
        return [
            'idPeliculaSerie' => 0,
            'titulo' => '',
            'tituloOriginal' => '',
            'descripcion' => '',
            'tipo' => '',
            'generos' => '',
            'imagenPortada' => '',
            'imagenBanner' => '',
            'trailerUrl' => '',
            'anioLanzamiento' => 0,
            'duracionMinutos' => 0,
            'temporadas' => 0,
            'episodios' => 0,
            'estado' => '',
            'estadoEmision' => 'Finalizado',
            'activo' => 0,
            'destacado' => 0,
            'favorito' => 0,
            'seriePadreId' => 0,
            'numeroTemporada' => 0,
            'tipoRelacion' => '',
            'padreTitulo' => '',
            'padreImagenPortada' => '',
            'padreAnioLanzamiento' => 0
        ];
    }

    private function respuestaGeneroReporte($filas, $campoTotalArreglo, $campoTotalRespuesta)
    {
        $resultado = [];

        foreach ($filas as $fila) {
            $idGenero = 0;
            if (isset($fila['id_genero'])) {
                $idGenero = (int)$fila['id_genero'];
            }

            $nombreGenero = '';
            if (isset($fila['nombre_genero'])) {
                $nombreGenero = $fila['nombre_genero'];
            }

            $total = 0;
            if (isset($fila[$campoTotalArreglo])) {
                $total = (int)$fila[$campoTotalArreglo];
            }

            $resultado[] = [
                'idGenero' => $idGenero,
                'nombreGenero' => $nombreGenero,
                $campoTotalRespuesta => $total
            ];
        }

        return [
            'generoReporte' => $resultado
        ];
    }

    private function respuestaDatoReporte($filas)
    {
        $resultado = [];

        foreach ($filas as $fila) {
            $etiqueta = '';
            if (isset($fila['etiqueta'])) {
                $etiqueta = $fila['etiqueta'];
            }

            $total = 0;
            if (isset($fila['total'])) {
                $total = (int)$fila['total'];
            }

            $resultado[] = [
                'etiqueta' => $etiqueta,
                'total' => $total
            ];
        }

        return [
            'datoReporte' => $resultado
        ];
    }

    private function respuestaUsuarioGestion($resultado, $mensajeDefecto)
    {
        $exito = false;
        if (isset($resultado['exito'])) {
            $exito = (bool)$resultado['exito'];
        }

        $mensaje = $mensajeDefecto;
        if (isset($resultado['mensaje'])) {
            $mensaje = $resultado['mensaje'];
        }

        $idUsuario = 0;
        if (isset($resultado['id_usuario'])) {
            $idUsuario = (int)$resultado['id_usuario'];
        }

        if (isset($resultado['idUsuario'])) {
            $idUsuario = (int)$resultado['idUsuario'];
        }

        return [
            'exito' => $exito,
            'mensaje' => $mensaje,
            'idUsuario' => $idUsuario
        ];
    }

    private function detallePeliculaSerieVacio()
    {
        return [
            'idPeliculaSerie' => 0,
            'titulo' => '',
            'tituloOriginal' => '',
            'descripcion' => '',
            'tipo' => '',
            'estado' => '',
            'estadoEmision' => 'Finalizado',
            'anioLanzamiento' => 0,
            'fechaEstreno' => '',
            'duracionMinutos' => 0,
            'temporadas' => 0,
            'episodios' => 0,
            'imagenPortada' => '',
            'imagenBanner' => '',
            'trailerUrl' => '',
            'destacado' => 0,
            'activo' => 0,
            'fechaCreacion' => '',
            'fechaActualizacion' => '',
            'generos' => '',
            'idsGeneros' => '',
            'seriePadreId' => 0,
            'numeroTemporada' => 0,
            'tipoRelacion' => '',
            'padreTitulo' => '',
            'padreImagenPortada' => '',
            'padreAnioLanzamiento' => 0
        ];
    }

    private function datosContenidoDesdeSolicitud($datosContenido)
    {
        $datosSolicitud = [];

        if (is_object($datosContenido) || is_array($datosContenido)) {
            $datosSolicitud = (array)$datosContenido;
        }

        $datos = [
            'titulo' => '',
            'tituloOriginal' => '',
            'descripcion' => '',
            'tipo' => '',
            'estado' => 'Publicado',
            'estadoEmision' => 'Finalizado',
            'anioLanzamiento' => 0,
            'fechaEstreno' => '',
            'duracionMinutos' => 0,
            'temporadas' => 0,
            'episodios' => 0,
            'imagenPortada' => '',
            'imagenBanner' => '',
            'trailerUrl' => '',
            'generos' => '',
            'destacado' => 0,
            'seriePadreId' => 0,
            'numeroTemporada' => 0,
            'tipoRelacion' => ''
        ];

        foreach ($datos as $campo => $valor) {
            if (isset($datosSolicitud[$campo])) {
                $datos[$campo] = $datosSolicitud[$campo];
            }
        }

        $datos['anioLanzamiento'] = (int)$datos['anioLanzamiento'];
        $datos['duracionMinutos'] = (int)$datos['duracionMinutos'];
        $datos['temporadas'] = (int)$datos['temporadas'];
        $datos['episodios'] = (int)$datos['episodios'];
        $datos['destacado'] = (int)$datos['destacado'];
        $datos['seriePadreId'] = (int)$datos['seriePadreId'];
        $datos['numeroTemporada'] = (int)$datos['numeroTemporada'];

        if ($datos['destacado'] !== 1) {
            $datos['destacado'] = 0;
        }

        if ($datos['seriePadreId'] <= 0) {
            $datos['seriePadreId'] = 0;
            $datos['numeroTemporada'] = 0;
            $datos['tipoRelacion'] = '';
        }

        if ($datos['numeroTemporada'] <= 0) {
            $datos['numeroTemporada'] = 0;
        }

        return $datos;
    }

    private function respuestaContenido($resultado, $mensajeDefecto)
    {
        $exito = false;
        if (isset($resultado['exito'])) {
            $exito = (bool)$resultado['exito'];
        }

        $mensaje = $mensajeDefecto;
        if (isset($resultado['mensaje'])) {
            $mensaje = $resultado['mensaje'];
        }

        $idPeliculaSerie = 0;
        if (isset($resultado['id_pelicula_serie'])) {
            $idPeliculaSerie = (int)$resultado['id_pelicula_serie'];
        }

        if (isset($resultado['idPeliculaSerie'])) {
            $idPeliculaSerie = (int)$resultado['idPeliculaSerie'];
        }

        return [
            'exito' => $exito,
            'mensaje' => $mensaje,
            'idPeliculaSerie' => $idPeliculaSerie
        ];
    }

    private function respuestaGenero($resultado, $mensajeDefecto)
    {
        $exito = false;
        if (isset($resultado['exito'])) {
            $exito = (bool)$resultado['exito'];
        }

        $mensaje = $mensajeDefecto;
        if (isset($resultado['mensaje'])) {
            $mensaje = $resultado['mensaje'];
        }

        $idGenero = 0;
        if (isset($resultado['id_genero'])) {
            $idGenero = (int)$resultado['id_genero'];
        }

        if (isset($resultado['idGenero'])) {
            $idGenero = (int)$resultado['idGenero'];
        }

        return [
            'exito' => $exito,
            'mensaje' => $mensaje,
            'idGenero' => $idGenero
        ];
    }


    // Aquí más funciones: login, registro, listarPeliculas, listarSeries, etc.
}

// Configuración del servidor SOAP
if (!Seguridad::soapInternoValido($_SERVER)) {
    http_response_code(403);
    exit;
}

$options = [
    'uri' => 'http://localhost/elyra/Api/soap'
];

try {
    $server = new SoapServer(__DIR__ . '/servicioElyra.wsdl', $options);
    $server->setObject(new ElyraService($conexion));
    $server->handle();
} catch (Throwable $e) {
    echo "Error en el servidor SOAP: " . $e->getMessage();
}
