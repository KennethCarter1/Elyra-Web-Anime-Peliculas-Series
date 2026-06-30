<?php
session_start();
require_once '../Models/Seguridad.php';
require_once '../Api/soap/ClienteSOAP.php';

function redirigirPanelesInicio($mensaje, $tipo, $idPanelInicio = 0)
{
    $_SESSION['mensaje_panel_inicio'] = $mensaje;
    $_SESSION['tipo_mensaje_panel_inicio'] = $tipo;

    $url = "/elyra/admin/paneles-inicio";

    if ((int)$idPanelInicio > 0) {
        $url .= "?panel=" . (int)$idPanelInicio;
    }

    header("Location: " . $url);
    exit;
}

function redirigirErrorBaseDatosPanelesInicio()
{
    header("Location: /elyra/error-bd?retorno=" . urlencode('/elyra/admin/paneles-inicio'));
    exit;
}

function valorPostPanelInicio($campo)
{
    if (isset($_POST[$campo])) {
        return trim((string)$_POST[$campo]);
    }

    return '';
}

function idPanelInicioPost()
{
    if (isset($_POST['id_panel_inicio'])) {
        return (int)$_POST['id_panel_inicio'];
    }

    return 0;
}

function idPeliculaSeriePanelPost()
{
    if (isset($_POST['id_pelicula_serie'])) {
        return (int)$_POST['id_pelicula_serie'];
    }

    return 0;
}

function enteroPostPanelInicio($campo)
{
    if (isset($_POST[$campo])) {
        return (int)$_POST[$campo];
    }

    return 0;
}

function activoPostPanelInicio()
{
    if (isset($_POST['activo'])) {
        return 1;
    }

    return 0;
}

if (!isset($_SESSION['usuario'])) {
    header("Location: /elyra/login");
    exit;
}

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: /elyra/inicio");
    exit;
}

Seguridad::requerirPost('/elyra/admin/paneles-inicio');

if (!Seguridad::csrfValido($_POST)) {
    redirigirPanelesInicio('Solicitud no válida. Recarga la página e inténtalo de nuevo.', 'error', idPanelInicioPost());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $clienteSOAP = new ClienteSOAP();

        if (isset($_POST['CrearPanelInicio'])) {
            $datos = [
                'titulo' => valorPostPanelInicio('titulo'),
                'descripcion' => valorPostPanelInicio('descripcion'),
                'orden' => enteroPostPanelInicio('orden'),
                'activo' => activoPostPanelInicio()
            ];

            $resultado = $clienteSOAP->crearPanelInicio($datos);

            if (!$resultado['exito']) {
                redirigirPanelesInicio($resultado['mensaje'], 'error');
            }

            redirigirPanelesInicio($resultado['mensaje'], 'exito', $resultado['idPanelInicio']);
        }

        if (isset($_POST['ActualizarPanelInicio'])) {
            $idPanelInicio = idPanelInicioPost();
            $datos = [
                'idPanelInicio' => $idPanelInicio,
                'titulo' => valorPostPanelInicio('titulo'),
                'descripcion' => valorPostPanelInicio('descripcion'),
                'orden' => enteroPostPanelInicio('orden'),
                'activo' => activoPostPanelInicio()
            ];

            if ($idPanelInicio <= 0) {
                redirigirPanelesInicio('Selecciona un panel válido', 'error');
            }

            $resultado = $clienteSOAP->actualizarPanelInicio($datos);

            if (!$resultado['exito']) {
                redirigirPanelesInicio($resultado['mensaje'], 'error', $idPanelInicio);
            }

            redirigirPanelesInicio($resultado['mensaje'], 'exito', $idPanelInicio);
        }

        if (isset($_POST['EliminarPanelInicio'])) {
            $idPanelInicio = idPanelInicioPost();

            if ($idPanelInicio <= 0) {
                redirigirPanelesInicio('Selecciona un panel válido', 'error');
            }

            $resultado = $clienteSOAP->eliminarPanelInicio($idPanelInicio);

            if (!$resultado['exito']) {
                redirigirPanelesInicio($resultado['mensaje'], 'error', $idPanelInicio);
            }

            redirigirPanelesInicio($resultado['mensaje'], 'exito');
        }

        if (isset($_POST['AgregarContenidoPanelInicio'])) {
            $idPanelInicio = idPanelInicioPost();
            $idPeliculaSerie = idPeliculaSeriePanelPost();
            $orden = enteroPostPanelInicio('orden_contenido');

            if ($idPanelInicio <= 0) {
                redirigirPanelesInicio('Selecciona un panel válido', 'error');
            }

            if ($idPeliculaSerie <= 0) {
                redirigirPanelesInicio('Selecciona un anime válido', 'error', $idPanelInicio);
            }

            $resultado = $clienteSOAP->agregarContenidoPanelInicio($idPanelInicio, $idPeliculaSerie, $orden);

            if (!$resultado['exito']) {
                redirigirPanelesInicio($resultado['mensaje'], 'error', $idPanelInicio);
            }

            redirigirPanelesInicio($resultado['mensaje'], 'exito', $idPanelInicio);
        }

        if (isset($_POST['QuitarContenidoPanelInicio'])) {
            $idPanelInicio = idPanelInicioPost();
            $idPeliculaSerie = idPeliculaSeriePanelPost();

            if ($idPanelInicio <= 0 || $idPeliculaSerie <= 0) {
                redirigirPanelesInicio('Selecciona un anime válido', 'error', $idPanelInicio);
            }

            $resultado = $clienteSOAP->quitarContenidoPanelInicio($idPanelInicio, $idPeliculaSerie);

            if (!$resultado['exito']) {
                redirigirPanelesInicio($resultado['mensaje'], 'error', $idPanelInicio);
            }

            redirigirPanelesInicio($resultado['mensaje'], 'exito', $idPanelInicio);
        }
    } catch (Throwable $e) {
        redirigirErrorBaseDatosPanelesInicio();
    }
}

header("Location: /elyra/admin/paneles-inicio");
exit;
?>
