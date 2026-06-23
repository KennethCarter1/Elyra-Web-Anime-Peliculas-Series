<?php
// Cargar variables del .env
$archivo_env = __DIR__ . '/../.env';
if (file_exists($archivo_env)) {
    $lineas = file($archivo_env, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lineas as $linea) {
        if (strpos(trim($linea), '#') === 0) continue;
        list($nombre, $valor) = explode('=', $linea, 2);
        $_ENV[trim($nombre)] = trim($valor);
    }
}

// Configuración base de datos
function obtenerVariableEnv($nombre, $valorPorDefecto)
{
    if (isset($_ENV[$nombre]) && $_ENV[$nombre] !== '') {
        return $_ENV[$nombre];
    }

    return $valorPorDefecto;
}

function obtenerBaseUrlProyecto()
{
    $scriptName = '';
    if (isset($_SERVER['SCRIPT_NAME'])) {
        $scriptName = $_SERVER['SCRIPT_NAME'];
    }

    $partes = explode('/', trim($scriptName, '/'));

    if (isset($partes[0]) && $partes[0] !== '') {
        return '/' . $partes[0];
    }

    return '';
}

function obtenerPaginaIntentada()
{
    $paginaIntentada = '';

    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_REFERER'])) {
        $paginaIntentada = $_SERVER['HTTP_REFERER'];
    }

    if ($paginaIntentada === '' && isset($_SERVER['REQUEST_URI'])) {
        $paginaIntentada = $_SERVER['REQUEST_URI'];
    }

    if ($paginaIntentada === '' || strpos($paginaIntentada, 'Errorbd.php') !== false) {
        $paginaIntentada = obtenerBaseUrlProyecto() . '/Index.php';
    }

    return $paginaIntentada;
}

function redirigirErrorBaseDatos()
{
    $baseUrl = obtenerBaseUrlProyecto();
    $paginaIntentada = obtenerPaginaIntentada();
    $urlError = $baseUrl . '/Views/Errores/Errorbd.php?retorno=' . urlencode($paginaIntentada);

    header('Location: ' . $urlError);
    exit;
}

$host_db = obtenerVariableEnv('DB_HOST', 'localhost');
$nombre_db = obtenerVariableEnv('DB_NAME', 'elyra');
$usuario_db = obtenerVariableEnv('DB_USER', 'root');
$contrasena_db = obtenerVariableEnv('DB_PASS', '');

// Conexión a la base de datos 
try {
    $conexion = new PDO("mysql:host=$host_db;dbname=$nombre_db;charset=utf8mb4", $usuario_db, $contrasena_db);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->exec("SET NAMES utf8mb4");
} catch (PDOException $error) {
    redirigirErrorBaseDatos();
}

// Configuración de la app
$tema_por_defecto = obtenerVariableEnv('DEFAULT_THEME', 'claro');
$url_app = obtenerVariableEnv('APP_URL', '/');
