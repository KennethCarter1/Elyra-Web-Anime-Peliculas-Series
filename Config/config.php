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
$host_db       = $_ENV['DB_HOST'] ?? 'localhost';
$nombre_db     = $_ENV['DB_NAME'] ?? 'elyra';
$usuario_db    = $_ENV['DB_USER'] ?? 'root';
$contrasena_db = $_ENV['DB_PASS'] ?? '';

// Conexión a la base de datos 
try {
    $conexion = new PDO("mysql:host=$host_db;dbname=$nombre_db;charset=utf8", $usuario_db, $contrasena_db);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $error) {
   
    header("Location: ../Views/errors/ErrorDB.php");
    exit;
}

// Configuración de la app
$tema_por_defecto = $_ENV['DEFAULT_THEME'] ?? 'claro';
$url_app          = $_ENV['APP_URL'] ?? '/';