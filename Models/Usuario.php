<?php
class Usuario{
    private $conexion;

    public function __construct($conexion){
        $this->conexion = $conexion;
    }

    public function registrar($usuario, $correo, $contrasena, $fechaNacimiento) {
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);

        $stmt = $this->conexion->prepare(
            "CALL sp_registrar_usuario(:usuario, :correo, :contrasena, :fechaNacimiento)"
        );
        $stmt->execute([
            ':usuario' => $usuario,
            ':correo' => $correo,
            ':contrasena' => $hash,
            ':fechaNacimiento' => $fechaNacimiento
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if (isset($resultado['id_usuario'])) {
            return (int)$resultado['id_usuario'];
        }

        return 0;
    }

    public function agregarPreferencia($idUsuario, $idGenero) {
        $stmt = $this->conexion->prepare(
            "CALL sp_agregar_preferencia_usuario(:idUsuario, :idGenero)"
        );
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':idGenero', $idGenero, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    }

    public function obtenerPorUsuario($usuario) {
        $stmt = $this->conexion->prepare(
            "CALL sp_login_usuario(:usuario)"
        );
        $stmt->execute([
            ':usuario' => $usuario
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return false;
    }

    public function obtenerDatos($usuario){
        $stmt = $this->conexion->prepare(
            "CALL  sp_obtener_usuario_por_usuario(:usuario)"
        );
        $stmt->execute([
            ':usuario' => $usuario
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return false;
    }

    public function actualizar($usuarioActual, $nombre, $usuario, $correo, $fechaNacimiento, $genero) {
        $stmt = $this->conexion->prepare(
            "CALL sp_actualizar_usuario(:usuarioActual, :nombre, :usuario, :correo, :fechaNacimiento, :genero)"
        );
        $stmt->execute([
            ':usuarioActual' => $usuarioActual,
            ':nombre' => $nombre,
            ':usuario' => $usuario,
            ':correo' => $correo,
            ':fechaNacimiento' => $fechaNacimiento,
            ':genero' => $genero
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [
            'exito' => 0,
            'mensaje' => 'No se pudo actualizar el usuario',
            'usuario' => $usuarioActual
        ];
    }

    public function cambiarContrasena($usuario, $contrasenaActual, $nuevaContrasena) {
        $datosUsuario = $this->obtenerPorUsuario($usuario);

        if (!$datosUsuario) {
            return [
                'exito' => 0,
                'mensaje' => 'Usuario no encontrado'
            ];
        }

        if (!password_verify($contrasenaActual, $datosUsuario['contrasena_hash'])) {
            return [
                'exito' => 0,
                'mensaje' => 'La contraseña actual no es correcta'
            ];
        }

        $hash = password_hash($nuevaContrasena, PASSWORD_DEFAULT);

        $stmt = $this->conexion->prepare(
            "CALL sp_cambiar_contrasena_usuario(:usuario, :contrasenaHash)"
        );
        $stmt->execute([
            ':usuario' => $usuario,
            ':contrasenaHash' => $hash
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [
            'exito' => 0,
            'mensaje' => 'No se pudo cambiar la contraseña'
        ];
    }

    public function obtenerResumenGestion() {
        $stmt = $this->conexion->prepare(
            "CALL sp_resumen_gestion_usuarios()"
        );
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [
            'usuarios_totales' => 0,
            'administradores' => 0,
            'usuarios_normales' => 0,
            'nuevos_usuarios' => 0,
            'preferencias_guardadas' => 0,
            'usuarios_desactivados' => 0
        ];
    }

    public function listarGestion($busqueda, $rol, $genero) {
        $stmt = $this->conexion->prepare(
            "CALL sp_listar_usuarios_gestion(:busqueda, :rol, :genero)"
        );
        $stmt->execute([
            ':busqueda' => $busqueda,
            ':rol' => $rol,
            ':genero' => $genero
        ]);

        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [];
    }

    public function obtenerDetalleGestion($idUsuario) {
        $stmt = $this->conexion->prepare(
            "CALL sp_obtener_detalle_usuario_gestion(:idUsuario)"
        );
        $stmt->execute([
            ':idUsuario' => $idUsuario
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return false;
    }

    public function actualizarRolGestion($idUsuario, $rol) {
        $stmt = $this->conexion->prepare(
            "CALL sp_actualizar_rol_usuario_gestion(:idUsuario, :rol)"
        );
        $stmt->execute([
            ':idUsuario' => $idUsuario,
            ':rol' => $rol
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [
            'exito' => 0,
            'mensaje' => 'No se pudo actualizar el rol',
            'id_usuario' => $idUsuario
        ];
    }

    public function desactivarGestion($idUsuario) {
        $stmt = $this->conexion->prepare(
            "CALL sp_desactivar_usuario_gestion(:idUsuario)"
        );
        $stmt->execute([
            ':idUsuario' => $idUsuario
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [
            'exito' => 0,
            'mensaje' => 'No se pudo desactivar el usuario',
            'id_usuario' => $idUsuario
        ];
    }

    public function activarGestion($idUsuario) {
        $stmt = $this->conexion->prepare(
            "CALL sp_activar_usuario_gestion(:idUsuario)"
        );
        $stmt->execute([
            ':idUsuario' => $idUsuario
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($resultado) {
            return $resultado;
        }

        return [
            'exito' => 0,
            'mensaje' => 'No se pudo activar el usuario',
            'id_usuario' => $idUsuario
        ];
    }

    public static function resumenGestionInicial()
    {
        return [
            'usuariosTotales' => 0,
            'administradores' => 0,
            'usuariosNormales' => 0,
            'nuevosUsuarios' => 0,
            'preferenciasGuardadas' => 0,
            'usuariosDesactivados' => 0
        ];
    }

    public static function filtrosGestionVacios()
    {
        return [
            'busqueda' => '',
            'rol' => 'Todos',
            'genero' => 'Todos',
            'id' => 0
        ];
    }

    public static function listaGestionInicial()
    {
        return [];
    }

    public static function detalleGestionInicial()
    {
        return [];
    }

    public static function filtrosGestionDesdeSolicitud($get)
    {
        $filtros = self::filtrosGestionVacios();

        if (isset($get['busqueda'])) {
            $filtros['busqueda'] = trim($get['busqueda']);
        }

        if (isset($get['rol']) && trim($get['rol']) !== '') {
            $filtros['rol'] = trim($get['rol']);
        }

        if (isset($get['genero']) && trim($get['genero']) !== '') {
            $filtros['genero'] = trim($get['genero']);
        }

        if (isset($get['id'])) {
            $filtros['id'] = (int)$get['id'];
        }

        return $filtros;
    }

    public static function normalizarResumenGestion($resumen)
    {
        $resultado = self::resumenGestionInicial();

        $resultado['usuariosTotales'] = (int)self::leerCampoGestion($resumen, 'usuariosTotales', 'usuarios_totales');
        $resultado['administradores'] = (int)self::leerCampoGestion($resumen, 'administradores', 'administradores');
        $resultado['usuariosNormales'] = (int)self::leerCampoGestion($resumen, 'usuariosNormales', 'usuarios_normales');
        $resultado['nuevosUsuarios'] = (int)self::leerCampoGestion($resumen, 'nuevosUsuarios', 'nuevos_usuarios');
        $resultado['preferenciasGuardadas'] = (int)self::leerCampoGestion($resumen, 'preferenciasGuardadas', 'preferencias_guardadas');
        $resultado['usuariosDesactivados'] = (int)self::leerCampoGestion($resumen, 'usuariosDesactivados', 'usuarios_desactivados');

        return $resultado;
    }

    public static function tarjetasResumenGestion($resumen)
    {
        return [
            [
                'titulo' => 'Usuarios totales',
                'valor' => $resumen['usuariosTotales'],
                'icono' => 'fa-solid fa-users',
                'color' => 'morado'
            ],
            [
                'titulo' => 'Administradores',
                'valor' => $resumen['administradores'],
                'icono' => 'fa-solid fa-shield-halved',
                'color' => 'azul'
            ],
            [
                'titulo' => 'Usuarios normales',
                'valor' => $resumen['usuariosNormales'],
                'icono' => 'fa-solid fa-user',
                'color' => 'verde'
            ],
            [
                'titulo' => 'Nuevos usuarios',
                'valor' => $resumen['nuevosUsuarios'],
                'icono' => 'fa-solid fa-user-plus',
                'color' => 'verde'
            ],
            [
                'titulo' => 'Preferencias guardadas',
                'valor' => $resumen['preferenciasGuardadas'],
                'icono' => 'fa-solid fa-heart',
                'color' => 'rosa'
            ]
        ];
    }

    public static function normalizarListaGestion($usuarios)
    {
        if (!is_array($usuarios)) {
            return [];
        }

        $usuariosNormalizados = [];

        foreach ($usuarios as $usuario) {
            $idUsuario = self::leerCampoGestion($usuario, 'idUsuario', 'id_usuario');
            $nombre = self::leerCampoGestion($usuario, 'nombre', 'nombre');
            $usuarioCuenta = self::leerCampoGestion($usuario, 'usuario', 'usuario');
            $correo = self::leerCampoGestion($usuario, 'correo', 'correo');
            $genero = self::leerCampoGestion($usuario, 'genero', 'genero');
            $rol = self::leerCampoGestion($usuario, 'rol', 'rol');
            $fechaCreacion = self::leerCampoGestion($usuario, 'fechaCreacion', 'fecha_creacion_formateada');
            $activo = self::leerCampoGestion($usuario, 'activo', 'activo');
            $totalPreferencias = self::leerCampoGestion($usuario, 'totalPreferencias', 'total_preferencias');

            $usuariosNormalizados[] = [
                'id' => (int)$idUsuario,
                'nombre' => $nombre,
                'usuario' => $usuarioCuenta,
                'correo' => $correo,
                'genero' => self::textoGeneroGestion($genero),
                'generoValor' => strtolower(trim($genero)),
                'rol' => self::textoRolGestion($rol),
                'rolValor' => strtolower(trim($rol)),
                'fechaCreacion' => $fechaCreacion,
                'activo' => (int)$activo,
                'estado' => self::textoEstadoUsuarioGestion((int)$activo),
                'estadoClase' => self::claseEstadoUsuarioGestion((int)$activo),
                'totalPreferencias' => (int)$totalPreferencias,
                'iniciales' => self::inicialesUsuarioGestion($nombre, $usuarioCuenta)
            ];
        }

        return $usuariosNormalizados;
    }

    public static function normalizarDetalleGestion($detalle)
    {
        if (!$detalle) {
            return [];
        }

        $idUsuario = self::leerCampoGestion($detalle, 'idUsuario', 'id_usuario');
        $nombre = self::leerCampoGestion($detalle, 'nombre', 'nombre');
        $usuarioCuenta = self::leerCampoGestion($detalle, 'usuario', 'usuario');
        $correo = self::leerCampoGestion($detalle, 'correo', 'correo');
        $fechaNacimiento = self::leerCampoGestion($detalle, 'fechaNacimiento', 'fecha_nacimiento');
        $genero = self::leerCampoGestion($detalle, 'genero', 'genero');
        $rol = self::leerCampoGestion($detalle, 'rol', 'rol');
        $activo = self::leerCampoGestion($detalle, 'activo', 'activo');
        $fechaCreacion = self::leerCampoGestion($detalle, 'fechaCreacion', 'fecha_creacion_formateada');
        $preferencias = self::leerCampoGestion($detalle, 'preferencias', 'preferencias');
        $totalPreferencias = self::leerCampoGestion($detalle, 'totalPreferencias', 'total_preferencias');

        return [
            'id' => (int)$idUsuario,
            'nombre' => $nombre,
            'usuario' => $usuarioCuenta,
            'correo' => $correo,
            'fechaNacimiento' => $fechaNacimiento,
            'genero' => self::textoGeneroGestion($genero),
            'generoValor' => strtolower(trim($genero)),
            'rol' => self::textoRolGestion($rol),
            'rolValor' => strtolower(trim($rol)),
            'activo' => (int)$activo,
            'estado' => self::textoEstadoUsuarioGestion((int)$activo),
            'estadoClase' => self::claseEstadoUsuarioGestion((int)$activo),
            'fechaCreacion' => $fechaCreacion,
            'preferencias' => $preferencias,
            'totalPreferencias' => (int)$totalPreferencias,
            'iniciales' => self::inicialesUsuarioGestion($nombre, $usuarioCuenta)
        ];
    }

    public static function opcionesRolGestion()
    {
        return [
            ['valor' => 'Todos', 'texto' => 'Todos'],
            ['valor' => 'usuario', 'texto' => 'Usuario'],
            ['valor' => 'administrador', 'texto' => 'Administrador']
        ];
    }

    public static function opcionesRolAccionGestion()
    {
        return [
            ['valor' => 'usuario', 'texto' => 'Usuario'],
            ['valor' => 'administrador', 'texto' => 'Administrador']
        ];
    }

    public static function opcionesGeneroGestion()
    {
        return [
            ['valor' => 'Todos', 'texto' => 'Todos'],
            ['valor' => 'masculino', 'texto' => 'Masculino'],
            ['valor' => 'femenino', 'texto' => 'Femenino'],
            ['valor' => 'otaku', 'texto' => 'Otaku']
        ];
    }

    public static function formatearNumeroGestion($numero)
    {
        return number_format((int)$numero, 0, '.', ',');
    }

    public static function seleccionarGestion($valorActual, $valorOpcion)
    {
        if ((string)$valorActual === (string)$valorOpcion) {
            return ' selected';
        }

        return '';
    }

    public static function valorFiltroGestion($filtros, $campo)
    {
        if (isset($filtros[$campo])) {
            return htmlspecialchars((string)$filtros[$campo], ENT_QUOTES, 'UTF-8');
        }

        return '';
    }

    public static function enlaceInformacionGestion($filtros, $idUsuario)
    {
        $parametros = [
            'busqueda' => $filtros['busqueda'],
            'rol' => $filtros['rol'],
            'genero' => $filtros['genero'],
            'id' => $idUsuario
        ];

        return 'gestion-usuarios.php?' . http_build_query($parametros);
    }

    public static function enlaceSinInformacionGestion($filtros)
    {
        $parametros = [
            'busqueda' => $filtros['busqueda'],
            'rol' => $filtros['rol'],
            'genero' => $filtros['genero']
        ];

        return 'gestion-usuarios.php?' . http_build_query($parametros);
    }

    public static function valorSeguroGestion($valor)
    {
        return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
    }

    private static function leerCampoGestion($origen, $campoObjeto, $campoArreglo)
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

    private static function textoGeneroGestion($genero)
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

    private static function textoRolGestion($rol)
    {
        $rolNormalizado = strtolower(trim($rol));

        if ($rolNormalizado === 'administrador') {
            return 'Administrador';
        }

        if ($rolNormalizado === 'usuario') {
            return 'Usuario';
        }

        return 'Usuario';
    }

    private static function textoEstadoUsuarioGestion($activo)
    {
        if ((int)$activo === 1) {
            return 'Activo';
        }

        return 'Desactivado';
    }

    private static function claseEstadoUsuarioGestion($activo)
    {
        if ((int)$activo === 1) {
            return 'activo';
        }

        return 'desactivado';
    }

    private static function inicialesUsuarioGestion($nombre, $usuario)
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

    public function valorEditar($datosUsuario, $campo)
    {
        if ($datosUsuario && isset($datosUsuario[$campo]) && $datosUsuario[$campo] !== null && $datosUsuario[$campo] !== '') {
            return htmlspecialchars($datosUsuario[$campo], ENT_QUOTES, 'UTF-8');
        }

        return '';
    }

    public function mostrarDato($datosUsuario, $campo)
    {
        if ($datosUsuario && isset($datosUsuario[$campo]) && $datosUsuario[$campo] !== null && $datosUsuario[$campo] !== '') {
            return htmlspecialchars($datosUsuario[$campo], ENT_QUOTES, 'UTF-8');
        }

        return 'No registrado';
    }

    public function seleccionarOpcion($datosUsuario, $campo, $valor)
    {
        if ($datosUsuario && isset($datosUsuario[$campo]) && $datosUsuario[$campo] !== null && $datosUsuario[$campo] !== '') {
            $valorGuardado = strtolower(trim($datosUsuario[$campo]));
            if ($valorGuardado === $valor) {
                return ' selected';
            }
        }

        return '';
    }

    public function seleccionarOpcionVacia($datosUsuario, $campo)
    {
        if (!$datosUsuario || !isset($datosUsuario[$campo]) || $datosUsuario[$campo] === null || $datosUsuario[$campo] === '') {
            return ' selected';
        }

        return '';
    }

    public function obtenerFechaNacimientoEditar($datosUsuario)
    {
        $fechaNacimiento = [
            'dia' => '',
            'mes' => '',
            'anio' => ''
        ];

        if (!$datosUsuario || !isset($datosUsuario['fecha_nacimiento']) || $datosUsuario['fecha_nacimiento'] === null || $datosUsuario['fecha_nacimiento'] === '') {
            return $fechaNacimiento;
        }

        $valorFecha = strtolower(trim($datosUsuario['fecha_nacimiento']));

        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $valorFecha, $coincidencias)) {
            $fechaNacimiento['anio'] = $coincidencias[1];
            $fechaNacimiento['mes'] = (string)((int)$coincidencias[2] - 1);
            $fechaNacimiento['dia'] = (string)((int)$coincidencias[3]);
            return $fechaNacimiento;
        }

        $meses = [
            'enero' => '0',
            'febrero' => '1',
            'marzo' => '2',
            'abril' => '3',
            'mayo' => '4',
            'junio' => '5',
            'julio' => '6',
            'agosto' => '7',
            'septiembre' => '8',
            'octubre' => '9',
            'noviembre' => '10',
            'diciembre' => '11'
        ];

        foreach ($meses as $nombreMes => $numeroMes) {
            if (strpos($valorFecha, $nombreMes) !== false) {
                $fechaNacimiento['mes'] = $numeroMes;
            }
        }

        if (preg_match('/^(\d{1,2})\s+de\s+/', $valorFecha, $coincidencias)) {
            $fechaNacimiento['dia'] = $coincidencias[1];
        }

        if (preg_match('/(\d{4})$/', $valorFecha, $coincidencias)) {
            $fechaNacimiento['anio'] = $coincidencias[1];
        }

        return $fechaNacimiento;
    }

    public function mostrarGenero($datosUsuario)
    {
        if ($datosUsuario && isset($datosUsuario['genero']) && $datosUsuario['genero'] !== null && $datosUsuario['genero'] !== '') {
            $genero = strtolower(trim($datosUsuario['genero']));

            if ($genero === 'masculino') {
                return 'Masculino';
            }

            if ($genero === 'femenino') {
                return 'Femenino';
            }

            if ($genero === 'otaku') {
                return 'Otaku';
            }

            return htmlspecialchars($datosUsuario['genero'], ENT_QUOTES, 'UTF-8');
        }

        return 'No registrado';
    }

    public function mostrarIniciales($datosUsuario)
    {
        $nombre = '';
        if ($datosUsuario && isset($datosUsuario['nombre'])) {
            $nombre = $datosUsuario['nombre'];
        }

        $usuario = '';
        if ($datosUsuario && isset($datosUsuario['usuario'])) {
            $usuario = $datosUsuario['usuario'];
        }

        return htmlspecialchars(self::inicialesUsuarioGestion($nombre, $usuario), ENT_QUOTES, 'UTF-8');
    }

    public function mostrarFechaCreacion($datosUsuario)
    {
        if (!$datosUsuario || !isset($datosUsuario['fecha_creacion']) || $datosUsuario['fecha_creacion'] === null || $datosUsuario['fecha_creacion'] === '') {
            return 'No registrado';
        }

        try {
            $fecha = new DateTime($datosUsuario['fecha_creacion']);
        } catch (Exception $error) {
            return htmlspecialchars($datosUsuario['fecha_creacion'], ENT_QUOTES, 'UTF-8');
        }

        $meses = [
            1 => 'enero',
            2 => 'febrero',
            3 => 'marzo',
            4 => 'abril',
            5 => 'mayo',
            6 => 'junio',
            7 => 'julio',
            8 => 'agosto',
            9 => 'septiembre',
            10 => 'octubre',
            11 => 'noviembre',
            12 => 'diciembre'
        ];

        $numeroMes = (int)$fecha->format('n');
        $mes = $meses[$numeroMes];
        $fechaFormateada = $fecha->format('j') . ' de ' . $mes . ' de ' . $fecha->format('Y') . ', ' . $fecha->format('H:i');

        return htmlspecialchars($fechaFormateada, ENT_QUOTES, 'UTF-8');
    }


}

?>
