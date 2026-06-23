<?php
session_start();
require_once '../Api/soap/ClienteSOAP.php';

function redirigirGestionUsuarios($mensaje, $tipo, $idUsuario = 0)
{
    $_SESSION['mensaje_gestion_usuario'] = $mensaje;
    $_SESSION['tipo_mensaje_gestion_usuario'] = $tipo;

    $url = "../Views/Administracion/gestion-usuarios.php";

    if ((int)$idUsuario > 0) {
        $url .= "?id=" . (int)$idUsuario;
    }

    header("Location: " . $url);
    exit;
}

function redirigirErrorBaseDatosGestionUsuarios()
{
    header("Location: ../Views/Errores/Errorbd.php?retorno=" . urlencode('../Views/Administracion/gestion-usuarios.php'));
    exit;
}

function idUsuarioGestionPost()
{
    if (isset($_POST['id_usuario'])) {
        return (int)$_POST['id_usuario'];
    }

    return 0;
}

function rolUsuarioGestionPost()
{
    if (isset($_POST['rol'])) {
        return trim((string)$_POST['rol']);
    }

    return '';
}

function usuarioGestionEsElActual($idUsuario)
{
    if (!isset($_SESSION['id_usuario'])) {
        return false;
    }

    if ((int)$_SESSION['id_usuario'] === (int)$idUsuario) {
        return true;
    }

    return false;
}

if (!isset($_SESSION['usuario'])) {
    header("Location: ../Views/Usuario/IniciarSesion.php");
    exit;
}

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../Views/Usuario/inicio.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $clienteSOAP = new ClienteSOAP();

        if (isset($_POST['ActualizarRolUsuarioGestion'])) {
            $idUsuario = idUsuarioGestionPost();
            $rol = rolUsuarioGestionPost();

            if ($idUsuario <= 0) {
                redirigirGestionUsuarios('Selecciona un usuario válido', 'error');
            }

            if (usuarioGestionEsElActual($idUsuario)) {
                redirigirGestionUsuarios('No puedes cambiar tu propio rol desde este panel', 'error', $idUsuario);
            }

            if ($rol !== 'usuario' && $rol !== 'administrador') {
                redirigirGestionUsuarios('Selecciona un rol válido', 'error', $idUsuario);
            }

            $resultado = $clienteSOAP->actualizarRolUsuarioGestion($idUsuario, $rol);

            if (!$resultado['exito']) {
                redirigirGestionUsuarios($resultado['mensaje'], 'error', $idUsuario);
            }

            redirigirGestionUsuarios($resultado['mensaje'], 'exito', $idUsuario);
        }

        if (isset($_POST['DesactivarUsuarioGestion'])) {
            $idUsuario = idUsuarioGestionPost();

            if ($idUsuario <= 0) {
                redirigirGestionUsuarios('Selecciona un usuario válido', 'error');
            }

            if (usuarioGestionEsElActual($idUsuario)) {
                redirigirGestionUsuarios('No puedes desactivar tu propia cuenta desde este panel', 'error', $idUsuario);
            }

            $resultado = $clienteSOAP->desactivarUsuarioGestion($idUsuario);

            if (!$resultado['exito']) {
                redirigirGestionUsuarios($resultado['mensaje'], 'error', $idUsuario);
            }

            redirigirGestionUsuarios($resultado['mensaje'], 'exito', $idUsuario);
        }

        if (isset($_POST['ActivarUsuarioGestion'])) {
            $idUsuario = idUsuarioGestionPost();

            if ($idUsuario <= 0) {
                redirigirGestionUsuarios('Selecciona un usuario válido', 'error');
            }

            $resultado = $clienteSOAP->activarUsuarioGestion($idUsuario);

            if (!$resultado['exito']) {
                redirigirGestionUsuarios($resultado['mensaje'], 'error', $idUsuario);
            }

            redirigirGestionUsuarios($resultado['mensaje'], 'exito', $idUsuario);
        }

    } catch (Throwable $e) {
        redirigirErrorBaseDatosGestionUsuarios();
    }
}

header("Location: ../Views/Administracion/gestion-usuarios.php");
exit;
?>
