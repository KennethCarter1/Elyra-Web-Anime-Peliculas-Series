<?php
session_start();
require_once '../Api/soap/ClienteSOAP.php';

function redirigirGestionGeneros($mensaje, $tipo)
{
    $_SESSION['mensaje_genero'] = $mensaje;
    $_SESSION['tipo_mensaje_genero'] = $tipo;
    header("Location: ../Views/Administracion/gestion-generos.php");
    exit;
}

function redirigirErrorBaseDatosGenero()
{
    header("Location: ../Views/Errores/Errorbd.php?retorno=" . urlencode('../Views/Administracion/gestion-generos.php'));
    exit;
}

function valorPostGenero($campo)
{
    if (isset($_POST[$campo])) {
        return trim((string)$_POST[$campo]);
    }

    return '';
}

function idGeneroPost()
{
    if (isset($_POST['id_genero'])) {
        return (int)$_POST['id_genero'];
    }

    return 0;
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

        if (isset($_POST['CrearGenero'])) {
            $nombreGenero = valorPostGenero('nombre_genero');
            $resultado = $clienteSOAP->crearGenero($nombreGenero);

            if (!$resultado['exito']) {
                redirigirGestionGeneros($resultado['mensaje'], 'error');
            }

            redirigirGestionGeneros($resultado['mensaje'], 'exito');
        }

        if (isset($_POST['ActualizarGenero'])) {
            $idGenero = idGeneroPost();
            $nombreGenero = valorPostGenero('nombre_genero');

            if ($idGenero <= 0) {
                redirigirGestionGeneros('Selecciona un género válido', 'error');
            }

            $resultado = $clienteSOAP->actualizarGenero($idGenero, $nombreGenero);

            if (!$resultado['exito']) {
                redirigirGestionGeneros($resultado['mensaje'], 'error');
            }

            redirigirGestionGeneros($resultado['mensaje'], 'exito');
        }

        if (isset($_POST['DesactivarGenero'])) {
            $idGenero = idGeneroPost();

            if ($idGenero <= 0) {
                redirigirGestionGeneros('Selecciona un género válido', 'error');
            }

            $resultado = $clienteSOAP->desactivarGenero($idGenero);

            if (!$resultado['exito']) {
                redirigirGestionGeneros($resultado['mensaje'], 'error');
            }

            redirigirGestionGeneros($resultado['mensaje'], 'exito');
        }

        if (isset($_POST['ActivarGenero'])) {
            $idGenero = idGeneroPost();

            if ($idGenero <= 0) {
                redirigirGestionGeneros('Selecciona un género válido', 'error');
            }

            $resultado = $clienteSOAP->activarGenero($idGenero);

            if (!$resultado['exito']) {
                redirigirGestionGeneros($resultado['mensaje'], 'error');
            }

            redirigirGestionGeneros($resultado['mensaje'], 'exito');
        }

    } catch (Throwable $e) {
        redirigirErrorBaseDatosGenero();
    }
}

header("Location: ../Views/Administracion/gestion-generos.php");
exit;
?>
