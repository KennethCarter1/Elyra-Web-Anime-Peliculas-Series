<?php

class OptimizadorImagen
{
    public static function crearDesdeArchivo($rutaArchivo, $tipoImagen)
    {
        if ($tipoImagen === IMAGETYPE_JPEG) {
            return imagecreatefromjpeg($rutaArchivo);
        }

        if ($tipoImagen === IMAGETYPE_PNG) {
            $imagen = imagecreatefrompng($rutaArchivo);
            if ($imagen !== false) {
                imagepalettetotruecolor($imagen);
                imagealphablending($imagen, true);
                imagesavealpha($imagen, true);
            }

            return $imagen;
        }

        if (defined('IMAGETYPE_WEBP') && $tipoImagen === IMAGETYPE_WEBP) {
            return imagecreatefromwebp($rutaArchivo);
        }

        return false;
    }

    public static function configuracionPorUso($uso)
    {
        if ($uso === 'portada') {
            return [
                'ancho' => 520,
                'alto' => 780,
                'calidad' => 72
            ];
        }

        if ($uso === 'banner') {
            return [
                'ancho' => 1280,
                'alto' => 720,
                'calidad' => 70
            ];
        }

        if ($uso === 'background') {
            return [
                'ancho' => 1600,
                'alto' => 900,
                'calidad' => 70
            ];
        }

        if ($uso === 'logo') {
            return [
                'ancho' => 420,
                'alto' => 420,
                'calidad' => 78
            ];
        }

        return [
            'ancho' => 1200,
            'alto' => 1200,
            'calidad' => 72
        ];
    }

    public static function usoPorRuta($rutaArchivo)
    {
        $ruta = strtolower(str_replace('\\', '/', $rutaArchivo));

        if (strpos($ruta, '/library/contenido/portadas/') !== false) {
            return 'portada';
        }

        if (strpos($ruta, '/library/contenido/banners/') !== false) {
            return 'banner';
        }

        if (strpos($ruta, '/assets/images/background/') !== false) {
            return 'background';
        }

        if (strpos($ruta, '/assets/images/logos/') !== false) {
            return 'logo';
        }

        return 'general';
    }

    public static function guardarWebpOptimizado($rutaOrigen, $rutaDestino, $tipoImagen, $uso)
    {
        if (!function_exists('imagewebp')) {
            throw new Exception('GD con soporte WEBP no esta activo en PHP');
        }

        $configuracion = self::configuracionPorUso($uso);
        $imagen = self::crearDesdeArchivo($rutaOrigen, $tipoImagen);

        if ($imagen === false) {
            throw new Exception('No se pudo procesar la imagen');
        }

        $anchoOriginal = imagesx($imagen);
        $altoOriginal = imagesy($imagen);
        $anchoMaximo = (int)$configuracion['ancho'];
        $altoMaximo = (int)$configuracion['alto'];
        $escalaAncho = $anchoMaximo / $anchoOriginal;
        $escalaAlto = $altoMaximo / $altoOriginal;
        $escala = min($escalaAncho, $escalaAlto, 1);
        $anchoFinal = max(1, (int)floor($anchoOriginal * $escala));
        $altoFinal = max(1, (int)floor($altoOriginal * $escala));

        if ($anchoFinal !== $anchoOriginal || $altoFinal !== $altoOriginal) {
            $imagenFinal = imagecreatetruecolor($anchoFinal, $altoFinal);
            imagealphablending($imagenFinal, false);
            imagesavealpha($imagenFinal, true);
            $transparente = imagecolorallocatealpha($imagenFinal, 0, 0, 0, 127);
            imagefilledrectangle($imagenFinal, 0, 0, $anchoFinal, $altoFinal, $transparente);
            imagecopyresampled($imagenFinal, $imagen, 0, 0, 0, 0, $anchoFinal, $altoFinal, $anchoOriginal, $altoOriginal);
            imagedestroy($imagen);
            $imagen = $imagenFinal;
        }

        $guardado = imagewebp($imagen, $rutaDestino, (int)$configuracion['calidad']);
        imagedestroy($imagen);

        if (!$guardado) {
            if (file_exists($rutaDestino)) {
                unlink($rutaDestino);
            }

            throw new Exception('No se pudo guardar la imagen en WEBP');
        }

        return [
            'anchoOriginal' => $anchoOriginal,
            'altoOriginal' => $altoOriginal,
            'anchoFinal' => $anchoFinal,
            'altoFinal' => $altoFinal,
            'calidad' => (int)$configuracion['calidad']
        ];
    }

    public static function optimizarArchivoExistente($rutaArchivo)
    {
        $datosImagen = getimagesize($rutaArchivo);
        if ($datosImagen === false || !isset($datosImagen[2])) {
            return [
                'procesado' => false,
                'cambiado' => false,
                'motivo' => 'no es imagen valida'
            ];
        }

        $tipoImagen = (int)$datosImagen[2];
        if (!defined('IMAGETYPE_WEBP') || $tipoImagen !== IMAGETYPE_WEBP) {
            return [
                'procesado' => false,
                'cambiado' => false,
                'motivo' => 'no es webp'
            ];
        }

        $tamanoAnterior = filesize($rutaArchivo);
        $rutaTemporal = tempnam(dirname($rutaArchivo), 'opt-webp-');

        if ($rutaTemporal === false) {
            throw new Exception('No se pudo crear archivo temporal');
        }

        $uso = self::usoPorRuta($rutaArchivo);
        $resultado = self::guardarWebpOptimizado($rutaArchivo, $rutaTemporal, $tipoImagen, $uso);
        clearstatcache(true, $rutaTemporal);
        $tamanoNuevo = filesize($rutaTemporal);

        $redimensionada = false;
        if ((int)$resultado['anchoOriginal'] !== (int)$resultado['anchoFinal']
            || (int)$resultado['altoOriginal'] !== (int)$resultado['altoFinal']) {
            $redimensionada = true;
        }

        if ($tamanoNuevo > 0 && ($tamanoNuevo < $tamanoAnterior || $redimensionada)) {
            if (!copy($rutaTemporal, $rutaArchivo)) {
                unlink($rutaTemporal);
                throw new Exception('No se pudo reemplazar la imagen optimizada');
            }

            unlink($rutaTemporal);

            return [
                'procesado' => true,
                'cambiado' => true,
                'uso' => $uso,
                'tamanoAnterior' => $tamanoAnterior,
                'tamanoNuevo' => $tamanoNuevo,
                'anchoAnterior' => (int)$resultado['anchoOriginal'],
                'altoAnterior' => (int)$resultado['altoOriginal'],
                'anchoNuevo' => (int)$resultado['anchoFinal'],
                'altoNuevo' => (int)$resultado['altoFinal'],
                'calidad' => (int)$resultado['calidad']
            ];
        }

        unlink($rutaTemporal);

        return [
            'procesado' => true,
            'cambiado' => false,
            'uso' => $uso,
            'tamanoAnterior' => $tamanoAnterior,
            'tamanoNuevo' => $tamanoAnterior,
            'anchoAnterior' => (int)$resultado['anchoOriginal'],
            'altoAnterior' => (int)$resultado['altoOriginal'],
            'anchoNuevo' => (int)$resultado['anchoFinal'],
            'altoNuevo' => (int)$resultado['altoFinal'],
            'calidad' => (int)$resultado['calidad']
        ];
    }
}
