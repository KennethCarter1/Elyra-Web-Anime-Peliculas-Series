<?php
session_start();
require_once __DIR__ . '/../Models/Inicio.php';
require_once __DIR__ . '/../Models/Navegacion.php';
require_once __DIR__ . '/../Api/soap/ClienteSOAP.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['usuario']) || Navegacion::esAdministrador($_SESSION)) {
    echo json_encode([
        'exito' => false,
        'resultados' => []
    ]);
    exit;
}

$busqueda = '';
if (isset($_GET['busqueda'])) {
    $busqueda = trim($_GET['busqueda']);
}

if ($busqueda === '' || strlen($busqueda) < 2) {
    echo json_encode([
        'exito' => true,
        'resultados' => []
    ]);
    exit;
}

try {
    $clienteSOAP = new ClienteSOAP();
    $contenido = Inicio::normalizarListaContenido(
        $clienteSOAP->buscarContenidoUsuario($busqueda)
    );

    $baseUrl = Navegacion::obtenerBaseUrlProyecto($_SERVER);
    $resultados = [];

    foreach ($contenido as $item) {
        $resultados[] = [
            'id' => (int)$item['id'],
            'titulo' => $item['titulo'],
            'tipo' => $item['tipo'],
            'generos' => $item['generos'],
            'imagen' => $item['imagenPortadaUrl'],
            'url' => $baseUrl . '/Views/Contenido/detalle.php?id=' . (int)$item['id']
        ];
    }

    echo json_encode([
        'exito' => true,
        'resultados' => $resultados
    ]);
    exit;

} catch (Throwable $e) {
    echo json_encode([
        'exito' => false,
        'resultados' => []
    ]);
    exit;
}
?>
