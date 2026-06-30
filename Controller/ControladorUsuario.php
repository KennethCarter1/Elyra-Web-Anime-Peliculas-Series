<?php
session_start();
require_once '../Models/Seguridad.php';
require_once '../Api/soap/ClienteSOAP.php';

function redirigirRegistro($mensaje)
{
    $_SESSION['mensaje_error'] = $mensaje;
    header("Location: /elyra/registro");
    exit;
}

function redirigirLogin($mensaje)
{
    $_SESSION['mensaje_error_login'] = $mensaje;
    header("Location: /elyra/login");
    exit;
}

function redirigirPreferencias($mensaje, $tipo)
{
    $_SESSION['mensaje_preferencias'] = $mensaje;
    $_SESSION['tipo_mensaje_preferencias'] = $tipo;
    header("Location: /elyra/preferencias");
    exit;
}

function redirigirConfiguracion($mensaje, $tipo)
{
    $_SESSION['mensaje_configuracion'] = $mensaje;
    $_SESSION['tipo_mensaje_configuracion'] = $tipo;
    header("Location: /elyra/configuracion");
    exit;
}

function redirigirCambiarContrasena($mensaje, $tipo)
{
    $_SESSION['mensaje_cambiar_contrasena'] = $mensaje;
    $_SESSION['tipo_mensaje_cambiar_contrasena'] = $tipo;
    header("Location: /elyra/cambiar-contrasena");
    exit;
}

function obtenerRetornoErrorBaseDatos()
{
    $retorno = '/elyra/';

    if (isset($_SERVER['HTTP_REFERER']) && trim($_SERVER['HTTP_REFERER']) !== '') {
        $retorno = $_SERVER['HTTP_REFERER'];
    }

    return $retorno;
}

function redirigirErrorBaseDatosControlador()
{
    $retorno = obtenerRetornoErrorBaseDatos();
    header("Location: /elyra/error-bd?retorno=" . urlencode($retorno));
    exit;
}

function contrasenaSegura($contrasena)
{
    if (strlen($contrasena) < 15) {
        return false;
    }

    if (!preg_match('/[A-ZÁÉÍÓÚÑ]/u', $contrasena)) {
        return false;
    }

    if (!preg_match('/[a-záéíóúñ]/u', $contrasena)) {
        return false;
    }

    if (!preg_match('/[0-9]/', $contrasena)) {
        return false;
    }

    if (!preg_match('/[^A-Za-z0-9ÁÉÍÓÚáéíóúÑñ]/u', $contrasena)) {
        return false;
    }

    return true;
}

function usuarioValido($usuario)
{
    if ($usuario === '') {
        return false;
    }

    if (preg_match('/\s/u', $usuario)) {
        return false;
    }

    return true;
}

function redirigirCsrfUsuario()
{
    if (isset($_POST['Registrarse'])) {
        redirigirRegistro('Solicitud no válida. Recarga la página e inténtalo de nuevo.');
    }

    if (isset($_POST['ActualizarPreferencias'])) {
        redirigirPreferencias('Solicitud no válida. Recarga la página e inténtalo de nuevo.', 'error');
    }

    if (isset($_POST['ActualizarUsuario'])) {
        redirigirConfiguracion('Solicitud no válida. Recarga la página e inténtalo de nuevo.', 'error');
    }

    if (isset($_POST['CambiarContrasena'])) {
        redirigirCambiarContrasena('Solicitud no válida. Recarga la página e inténtalo de nuevo.', 'error');
    }

    redirigirLogin('Solicitud no válida. Recarga la página e inténtalo de nuevo.');
}

Seguridad::requerirPost('/elyra/login');

if (!Seguridad::csrfValido($_POST)) {
    redirigirCsrfUsuario();
}

$clienteSOAP = new ClienteSOAP();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(isset($_POST['login'])){
        $usuario = '';
        if (isset($_POST['usuario'])) {
            $usuario = trim($_POST['usuario']);
        }

        $contrasena = '';
        if (isset($_POST['password'])) {
            $contrasena = $_POST['password'];
        }

        $recordar = false;
        if (isset($_POST['recordar'])) {
            $recordar = true;
        }

        if ($usuario === '' || $contrasena === '') {
            redirigirLogin("Completa todos los campos");
        }

        try {
            $resultadoLogin = $clienteSOAP->loginUsuario($usuario, $contrasena);

            if (!$resultadoLogin['exito']) {
                redirigirLogin($resultadoLogin['mensaje']);
            }

            $_SESSION['id_usuario'] = $resultadoLogin['idUsuario'];
            $_SESSION['usuario'] = $resultadoLogin['usuario'];
            $_SESSION['rol'] = $resultadoLogin['rol'];

            if ($recordar) {
                setcookie('usuario_login', $resultadoLogin['idUsuario'], time() + (86400 * 30), "/");
            }

            header("Location: /elyra/");
            exit;

        } catch(Throwable $e){
            redirigirErrorBaseDatosControlador();
        }
    }

    if(isset($_POST['ActualizarPreferencias'])){
        if (!isset($_SESSION['usuario'])) {
            redirigirLogin("Debes iniciar sesión para actualizar tus preferencias");
        }

        $usuario = $_SESSION['usuario'];

        $generos = [];
        if (isset($_POST['generos']) && is_array($_POST['generos'])) {
            $generos = $_POST['generos'];
        }

        if (count($generos) < 3) {
            redirigirPreferencias("Selecciona mínimo 3 géneros favoritos", "error");
        }

        try {
            $resultadoPreferencias = $clienteSOAP->actualizarPreferenciasUsuario($usuario, $generos);

            if (!$resultadoPreferencias['exito']) {
                redirigirPreferencias($resultadoPreferencias['mensaje'], "error");
            }

            redirigirPreferencias("Preferencias actualizadas correctamente", "exito");

        } catch(Throwable $e){
            redirigirErrorBaseDatosControlador();
        }
    }

    if(isset($_POST['ActualizarUsuario'])){
        if (!isset($_SESSION['usuario'])) {
            redirigirLogin("Debes iniciar sesión para actualizar tu perfil");
        }

        $usuarioActual = $_SESSION['usuario'];

        $nombre = '';
        if (isset($_POST['nombre'])) {
            $nombre = trim($_POST['nombre']);
        }

        $usuario = '';
        if (isset($_POST['usuario'])) {
            $usuario = trim($_POST['usuario']);
        }

        $correo = '';
        if (isset($_POST['correo'])) {
            $correo = trim($_POST['correo']);
        }

        $genero = '';
        if (isset($_POST['genero'])) {
            $genero = strtolower(trim($_POST['genero']));
        }

        $dia = 0;
        if (isset($_POST['dia'])) {
            $dia = (int)$_POST['dia'];
        }

        $numeroMes = -1;
        if (isset($_POST['mes'])) {
            $numeroMes = (int)$_POST['mes'];
        }

        $anio = 0;
        if (isset($_POST['anio'])) {
            $anio = (int)$_POST['anio'];
        }

        if ($nombre === '' || $usuario === '' || $correo === '' || $dia === 0 || $numeroMes < 0 || $anio === 0) {
            redirigirConfiguracion("Completa todos los campos del perfil", "error");
        }

        if (!usuarioValido($usuario)) {
            redirigirConfiguracion("El usuario no es válido. Escríbelo sin espacios, ejemplo: kennethcarter", "error");
        }

        if ($genero === '') {
            redirigirConfiguracion("Selecciona tu género", "error");
        }

        $generosPermitidos = [
            "masculino",
            "femenino",
            "otaku"
        ];

        if (!in_array($genero, $generosPermitidos)) {
            redirigirConfiguracion("El género no es válido", "error");
        }

        $mes = $numeroMes + 1;

        if (!checkdate($mes, $dia, $anio)) {
            redirigirConfiguracion("La fecha de nacimiento no es válida", "error");
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            redirigirConfiguracion("El correo electrónico no es válido", "error");
        }

        $meses = [
            "enero",
            "febrero",
            "marzo",
            "abril",
            "mayo",
            "junio",
            "julio",
            "agosto",
            "septiembre",
            "octubre",
            "noviembre",
            "diciembre"
        ];

        $fechaDeNacimiento = $dia . " de " . $meses[$numeroMes] . " del " . $anio;

        try {
            $resultadoUsuario = $clienteSOAP->actualizarUsuario(
                $usuarioActual,
                $nombre,
                $usuario,
                $correo,
                $fechaDeNacimiento,
                $genero
            );

            if (!$resultadoUsuario['exito']) {
                redirigirConfiguracion($resultadoUsuario['mensaje'], "error");
            }

            $_SESSION['usuario'] = $resultadoUsuario['usuario'];
            $_SESSION['nombre'] = $nombre;
            $_SESSION['genero'] = $genero;

            header("Location: /elyra/perfil");
            exit;

        } catch(Throwable $e){
            redirigirErrorBaseDatosControlador();
        }
    }

    if(isset($_POST['CambiarContrasena'])){
        if (!isset($_SESSION['usuario'])) {
            redirigirLogin("Debes iniciar sesión para cambiar tu contraseña");
        }

        $usuario = $_SESSION['usuario'];

        $contrasenaActual = '';
        if (isset($_POST['contrasena_actual'])) {
            $contrasenaActual = $_POST['contrasena_actual'];
        }

        $nuevaContrasena = '';
        if (isset($_POST['nueva_contrasena'])) {
            $nuevaContrasena = $_POST['nueva_contrasena'];
        }

        $confirmarContrasena = '';
        if (isset($_POST['confirmar_contrasena'])) {
            $confirmarContrasena = $_POST['confirmar_contrasena'];
        }

        if ($contrasenaActual === '' || $nuevaContrasena === '' || $confirmarContrasena === '') {
            redirigirCambiarContrasena("Completa todos los campos", "error");
        }

        if ($nuevaContrasena !== $confirmarContrasena) {
            redirigirCambiarContrasena("Las contraseñas nuevas no coinciden", "error");
        }

        if (!contrasenaSegura($nuevaContrasena)) {
            redirigirCambiarContrasena("La contraseña debe tener mínimo 15 caracteres e incluir mayúsculas, minúsculas, números y caracteres especiales", "error");
        }

        if ($contrasenaActual === $nuevaContrasena) {
            redirigirCambiarContrasena("La nueva contraseña debe ser diferente a la actual", "error");
        }

        try {
            $resultadoContrasena = $clienteSOAP->cambiarContrasenaUsuario(
                $usuario,
                $contrasenaActual,
                $nuevaContrasena
            );

            if (!$resultadoContrasena['exito']) {
                redirigirCambiarContrasena($resultadoContrasena['mensaje'], "error");
            }

            redirigirCambiarContrasena("Contraseña actualizada correctamente", "exito");

        } catch(Throwable $e){
            redirigirErrorBaseDatosControlador();
        }
    }

    if(isset($_POST['Registrarse'])){
        $dia = 0;
        if (isset($_POST['dia'])) {
            $dia = (int)$_POST['dia'];
        }

        $numeroMes = -1;
        if (isset($_POST['mes'])) {
            $numeroMes = (int)$_POST['mes'];
        }

        $anio = 0;
        if (isset($_POST['anio'])) {
            $anio = (int)$_POST['anio'];
        }

        $mes = $numeroMes + 1;

        if (!checkdate($mes, $dia, $anio)) {
            redirigirRegistro("La fecha de nacimiento no es válida");
        }

        $meses = [
            "enero",
            "febrero",
            "marzo",
            "abril",
            "mayo",
            "junio",
            "julio",
            "agosto",
            "septiembre",
            "octubre",
            "noviembre",
            "diciembre"
        ];

        $fechaDeNacimiento = $dia . " de " . $meses[$numeroMes] . " del " . $anio;

        $usuario = '';
        if (isset($_POST['usuario'])) {
            $usuario = trim($_POST['usuario']);
        }

        $correo = '';
        if (isset($_POST['email'])) {
            $correo = trim($_POST['email']);
        }

        $contrasena = '';
        if (isset($_POST['password'])) {
            $contrasena = $_POST['password'];
        }

        $verificarContrasena = '';
        if (isset($_POST['confirm_password'])) {
            $verificarContrasena = $_POST['confirm_password'];
        }

        $generos = [];
        if (isset($_POST['generos'])) {
            $generos = $_POST['generos'];
        }

        // Validaciones
        if ($usuario === '' || $correo === '' || $contrasena === '' || $verificarContrasena === '') {
            redirigirRegistro("Completa todos los campos");
        } elseif (!usuarioValido($usuario)) {
            redirigirRegistro("El usuario no es válido. Escríbelo sin espacios, ejemplo: kennethcarter");
        } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            redirigirRegistro("El correo electrónico no es válido");
        } elseif($contrasena !== $verificarContrasena){
            redirigirRegistro("Las contraseñas no coinciden");
        } elseif(!contrasenaSegura($contrasena)){
            redirigirRegistro("La contraseña debe tener mínimo 15 caracteres e incluir mayúsculas, minúsculas, números y caracteres especiales");
        } elseif(count($generos) < 3){
            redirigirRegistro("Selecciona mínimo 3 géneros favoritos");
        } else {
            try {
                // Llamada al servicio SOAP para registrar usuario
                $registroExitoso = $clienteSOAP->registrarUsuario(
                    $usuario,
                    $correo,
                    $contrasena,
                    $fechaDeNacimiento,
                    $generos
                );

                if (!$registroExitoso) {
                    redirigirRegistro("No se pudo registrar el usuario. Verifica si el usuario o correo ya existe");
                }

                // Redirigir a login
                header("Location: /elyra/login?registro=exito");
                exit;

            } catch(Throwable $e){
                redirigirErrorBaseDatosControlador();
            }
        }
    }
}

header("Location: /elyra/login");
exit;
