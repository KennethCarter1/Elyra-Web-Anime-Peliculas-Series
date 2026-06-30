<?php

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Este script solo se puede ejecutar por consola.');
}

require_once __DIR__ . '/../Models/OptimizadorImagen.php';

$raizProyecto = realpath(__DIR__ . '/..');
$fecha = date('Ymd-His');
$directorioBackup = 'C:\\xampp\\codex_backups\\Elyra-Web-Anime-Peliculas-Series\\imagenes-webp-' . $fecha;
$rutas = [
    $raizProyecto . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'contenido',
    $raizProyecto . DIRECTORY_SEPARATOR . 'Assets' . DIRECTORY_SEPARATOR . 'Images'
];

function tamanoImagenesWebp($rutas)
{
    $total = 0;
    $cantidad = 0;

    foreach ($rutas as $ruta) {
        if (!is_dir($ruta)) {
            continue;
        }

        $iterador = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($ruta, FilesystemIterator::SKIP_DOTS));
        foreach ($iterador as $archivo) {
            if (!$archivo->isFile()) {
                continue;
            }

            if (strtolower($archivo->getExtension()) !== 'webp') {
                continue;
            }

            $total += $archivo->getSize();
            $cantidad++;
        }
    }

    return [
        'cantidad' => $cantidad,
        'bytes' => $total
    ];
}

function copiarBackupImagen($rutaArchivo, $raizProyecto, $directorioBackup)
{
    $rutaRelativa = substr($rutaArchivo, strlen($raizProyecto) + 1);
    $rutaBackup = $directorioBackup . DIRECTORY_SEPARATOR . $rutaRelativa;
    $carpetaBackup = dirname($rutaBackup);

    if (!is_dir($carpetaBackup)) {
        mkdir($carpetaBackup, 0777, true);
    }

    if (!copy($rutaArchivo, $rutaBackup)) {
        throw new Exception('No se pudo crear backup de ' . $rutaRelativa);
    }
}

function buscarImagenesWebp($rutas)
{
    $imagenes = [];

    foreach ($rutas as $ruta) {
        if (!is_dir($ruta)) {
            continue;
        }

        $iterador = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($ruta, FilesystemIterator::SKIP_DOTS));
        foreach ($iterador as $archivo) {
            if (!$archivo->isFile()) {
                continue;
            }

            if (strtolower($archivo->getExtension()) !== 'webp') {
                continue;
            }

            $imagenes[] = $archivo->getPathname();
        }
    }

    return $imagenes;
}

$antes = tamanoImagenesWebp($rutas);
$imagenes = buscarImagenesWebp($rutas);
$procesadas = 0;
$cambiadas = 0;
$sinCambio = 0;
$errores = 0;

foreach ($imagenes as $rutaImagen) {
    try {
        copiarBackupImagen($rutaImagen, $raizProyecto, $directorioBackup);
        $resultado = OptimizadorImagen::optimizarArchivoExistente($rutaImagen);

        if (isset($resultado['procesado']) && $resultado['procesado']) {
            $procesadas++;
        }

        if (isset($resultado['cambiado']) && $resultado['cambiado']) {
            $cambiadas++;
        } else {
            $sinCambio++;
        }
    } catch (Exception $e) {
        $errores++;
        fwrite(STDERR, $rutaImagen . ' - ' . $e->getMessage() . PHP_EOL);
    }
}

$despues = tamanoImagenesWebp($rutas);
$ahorro = $antes['bytes'] - $despues['bytes'];
$porcentaje = 0;

if ($antes['bytes'] > 0) {
    $porcentaje = ($ahorro / $antes['bytes']) * 100;
}

echo 'Backup: ' . $directorioBackup . PHP_EOL;
echo 'Imagenes: ' . $antes['cantidad'] . PHP_EOL;
echo 'Procesadas: ' . $procesadas . PHP_EOL;
echo 'Cambiadas: ' . $cambiadas . PHP_EOL;
echo 'Sin cambio: ' . $sinCambio . PHP_EOL;
echo 'Errores: ' . $errores . PHP_EOL;
echo 'Antes bytes: ' . $antes['bytes'] . PHP_EOL;
echo 'Despues bytes: ' . $despues['bytes'] . PHP_EOL;
echo 'Ahorro bytes: ' . $ahorro . PHP_EOL;
echo 'Ahorro porcentaje: ' . round($porcentaje, 2) . PHP_EOL;
