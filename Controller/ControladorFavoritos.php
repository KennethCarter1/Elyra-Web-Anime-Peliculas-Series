<?php
session_start();
require_once __DIR__ . '/../Models/Seguridad.php';
require_once __DIR__ . '/../Api/soap/ClienteSOAP.php';

function esSolicitudAjaxFavorito()
{
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        return true;
    }

    if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
        return true;
    }

    return false;
}

function responderJsonFavorito($datos, $codigo = 200)
{
    http_response_code($codigo);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($datos);
    exit;
}

function retornoFavorito()
{
    $retorno = '/elyra/inicio';

    if (isset($_POST['retorno']) && trim($_POST['retorno']) !== '') {
        $retornoPost = trim($_POST['retorno']);

        if (strpos($retornoPost, '://') === false && substr($retornoPost, 0, 2) !== '//') {
            $retorno = $retornoPost;
        }
    }

    return $retorno;
}

function redirigirFavorito($mensaje, $tipo)
{
    if (esSolicitudAjaxFavorito()) {
        responderJsonFavorito([
            'exito' => $tipo === 'exito',
            'mensaje' => $mensaje,
            'favorito' => 0
        ]);
    }

    $_SESSION['mensaje_favorito'] = $mensaje;
    $_SESSION['tipo_mensaje_favorito'] = $tipo;
    header('Location: ' . retornoFavorito());
    exit;
}

function redirigirErrorBaseDatosFavorito()
{
    if (esSolicitudAjaxFavorito()) {
        responderJsonFavorito([
            'exito' => false,
            'mensaje' => 'No se pudo conectar con la base de datos',
            'favorito' => 0
        ], 500);
    }

    header('Location: /elyra/error-bd?retorno=' . urlencode(retornoFavorito()));
    exit;
}

function redirigirCsrfFavorito()
{
    if (esSolicitudAjaxFavorito()) {
        responderJsonFavorito([
            'exito' => false,
            'mensaje' => 'Solicitud no válida. Recarga la página e inténtalo de nuevo.',
            'favorito' => 0
        ], 403);
    }

    $_SESSION['mensaje_favorito'] = 'Solicitud no válida. Recarga la página e inténtalo de nuevo.';
    $_SESSION['tipo_mensaje_favorito'] = 'error';
    header('Location: ' . retornoFavorito());
    exit;
}

if (!isset($_SESSION['usuario'])) {
    if (esSolicitudAjaxFavorito()) {
        responderJsonFavorito([
            'exito' => false,
            'mensaje' => 'Debes iniciar sesión',
            'favorito' => 0,
            'redirigir' => '/elyra/login'
        ], 401);
    }

    header('Location: /elyra/login');
    exit;
}

Seguridad::requerirPost('/elyra/inicio');

if (!Seguridad::csrfValido($_POST)) {
    redirigirCsrfFavorito();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idPeliculaSerie = 0;

    if (isset($_POST['id_pelicula_serie'])) {
        $idPeliculaSerie = (int)$_POST['id_pelicula_serie'];
    }

    if ($idPeliculaSerie <= 0) {
        redirigirFavorito('Selecciona un contenido válido', 'error');
    }

    try {
        $clienteSOAP = new ClienteSOAP();
        $resultado = $clienteSOAP->alternarFavoritoUsuario($_SESSION['usuario'], $idPeliculaSerie);

        if (!$resultado['exito']) {
            if (esSolicitudAjaxFavorito()) {
                responderJsonFavorito($resultado, 400);
            }

            redirigirFavorito($resultado['mensaje'], 'error');
        }

        if (esSolicitudAjaxFavorito()) {
            responderJsonFavorito($resultado);
        }

        redirigirFavorito($resultado['mensaje'], 'exito');

    } catch (Throwable $e) {
        redirigirErrorBaseDatosFavorito();
    }
}

header('Location: /elyra/inicio');
exit;
?>
