<?php
session_start();
require_once '../Api/soap/ClienteSOAP.php';

function redirigirGestionContenido($mensaje, $tipo, $idPeliculaSerie = 0)
{
    $_SESSION['mensaje_contenido'] = $mensaje;
    $_SESSION['tipo_mensaje_contenido'] = $tipo;

    $url = "../Views/Administracion/gestion-peliculas-series.php";
    if ((int)$idPeliculaSerie > 0) {
        $url .= "?id=" . (int)$idPeliculaSerie;
    }

    header("Location: " . $url);
    exit;
}

function redirigirErrorBaseDatosContenido()
{
    header("Location: ../Views/Errores/Errorbd.php?retorno=" . urlencode('../Views/Administracion/gestion-peliculas-series.php'));
    exit;
}

function redirigirFormularioContenido($mensaje, $tipo, $idPeliculaSerie = 0)
{
    $_SESSION['mensaje_formulario_contenido'] = $mensaje;
    $_SESSION['tipo_mensaje_formulario_contenido'] = $tipo;

    $url = "../Views/Administracion/agregar-pelicula-serie.php";
    if ((int)$idPeliculaSerie > 0) {
        $url .= "?id=" . (int)$idPeliculaSerie;
    }

    header("Location: " . $url);
    exit;
}

function valorPostContenido($campo)
{
    if (isset($_POST[$campo])) {
        return trim((string)$_POST[$campo]);
    }

    return '';
}

function generosPostContenido()
{
    $generos = [];

    if (isset($_POST['generos']) && is_array($_POST['generos'])) {
        foreach ($_POST['generos'] as $genero) {
            $idGenero = (int)$genero;
            if ($idGenero > 0 && !in_array($idGenero, $generos)) {
                $generos[] = $idGenero;
            }
        }
    }

    return implode(',', $generos);
}

function normalizarTipoContenido($tipo)
{
    if ($tipo === 'Pelicula') {
        return 'Película';
    }

    if ($tipo === 'pelicula') {
        return 'Película';
    }

    if ($tipo === 'Pel?cula') {
        return 'Película';
    }

    if ($tipo === 'pel?cula') {
        return 'Película';
    }

    return $tipo;
}

function datosPostContenido()
{
    $tipo = normalizarTipoContenido(valorPostContenido('tipo'));
    $temporadas = (int)valorPostContenido('temporadas');
    $episodios = (int)valorPostContenido('episodios');
    $destacado = 0;

    if (isset($_POST['destacado'])) {
        $destacado = 1;
    }

    if ($tipo === 'Película') {
        $temporadas = 0;

        if ($episodios <= 0) {
            $episodios = 1;
        }
    }

    $seriePadreId = (int)valorPostContenido('serie_padre_id');

    if ($seriePadreId <= 0) {
        $seriePadreId = 0;
    }

    $numeroTemporada = (int)valorPostContenido('numero_temporada');

    if ($numeroTemporada <= 0) {
        $numeroTemporada = 0;
    }

    $tipoRelacion = valorPostContenido('tipo_relacion');

    return [
        'titulo' => valorPostContenido('titulo'),
        'tituloOriginal' => valorPostContenido('titulo_original'),
        'descripcion' => valorPostContenido('descripcion'),
        'tipo' => $tipo,
        'estado' => valorPostContenido('estado'),
        'estadoEmision' => valorPostContenido('estado_emision'),
        'anioLanzamiento' => (int)valorPostContenido('anio_lanzamiento'),
        'fechaEstreno' => valorPostContenido('fecha_estreno'),
        'duracionMinutos' => (int)valorPostContenido('duracion_minutos'),
        'temporadas' => $temporadas,
        'episodios' => $episodios,
        'imagenPortada' => valorPostContenido('imagen_portada_actual'),
        'imagenBanner' => valorPostContenido('imagen_banner_actual'),
        'trailerUrl' => valorPostContenido('trailer_url'),
        'generos' => generosPostContenido(),
        'destacado' => $destacado,
        'seriePadreId' => $seriePadreId,
        'numeroTemporada' => $numeroTemporada,
        'tipoRelacion' => $tipoRelacion
    ];
}

function validarDatosContenido($datos, $idPeliculaSerie)
{
    if ($datos['titulo'] === '') {
        return 'El título es obligatorio';
    }

    if ($datos['descripcion'] === '') {
        return 'La descripción es obligatoria';
    }

    if ($datos['tipo'] !== 'Película' && $datos['tipo'] !== 'Serie') {
        return 'Selecciona un tipo válido';
    }

    if ($datos['generos'] === '') {
        return 'Selecciona al menos un género';
    }

    if ($datos['anioLanzamiento'] <= 0) {
        return 'Selecciona un año de lanzamiento válido';
    }

    if ($datos['fechaEstreno'] === '') {
        return 'Selecciona la fecha de estreno';
    }

    if ($datos['duracionMinutos'] <= 0) {
        return 'Ingresa una duración válida';
    }

    if ($datos['tipo'] === 'Serie' && $datos['temporadas'] <= 0) {
        return 'Ingresa el número de temporadas';
    }

    if ($datos['episodios'] <= 0) {
        return 'Ingresa el número de episodios';
    }

    if ($datos['estado'] !== 'Publicado' && $datos['estado'] !== 'Desactivado') {
        return 'Selecciona un estado válido';
    }

    if ($datos['estadoEmision'] !== 'Finalizado'
        && $datos['estadoEmision'] !== 'En emisión'
        && $datos['estadoEmision'] !== 'Próximamente') {
        return 'Selecciona un estado de emisión válido';
    }

    if ((int)$idPeliculaSerie <= 0 && !archivoSubidoContenido('imagen_portada')) {
        return 'Selecciona una imagen de portada';
    }

    return '';
}

function archivoSubidoContenido($campo)
{
    if (!isset($_FILES[$campo])) {
        return false;
    }

    if (!isset($_FILES[$campo]['error'])) {
        return false;
    }

    if ((int)$_FILES[$campo]['error'] === UPLOAD_ERR_NO_FILE) {
        return false;
    }

    return true;
}

function guardarImagenContenido($campo, $subcarpeta, $rutaActual)
{
    if (!archivoSubidoContenido($campo)) {
        return $rutaActual;
    }

    if ((int)$_FILES[$campo]['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('No se pudo subir la imagen');
    }

    $tamanoMaximo = 5 * 1024 * 1024;
    if ((int)$_FILES[$campo]['size'] > $tamanoMaximo) {
        throw new Exception('La imagen no puede superar los 5MB');
    }

    $extension = strtolower(pathinfo($_FILES[$campo]['name'], PATHINFO_EXTENSION));
    $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($extension, $extensionesPermitidas)) {
        throw new Exception('Solo se permiten imágenes JPG, PNG o WEBP');
    }

    $nombreBase = pathinfo($_FILES[$campo]['name'], PATHINFO_FILENAME);
    $nombreBase = strtolower($nombreBase);
    $nombreBase = preg_replace('/[^a-z0-9_-]+/', '-', $nombreBase);
    $nombreBase = trim($nombreBase, '-');

    if ($nombreBase === '') {
        $nombreBase = 'contenido';
    }

    $nombreArchivo = $nombreBase . '-' . date('YmdHis') . '-' . mt_rand(1000, 9999) . '.' . $extension;
    $directorio = __DIR__ . '/../library/contenido/' . $subcarpeta;

    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true);
    }

    $rutaDestino = $directorio . '/' . $nombreArchivo;

    if (!move_uploaded_file($_FILES[$campo]['tmp_name'], $rutaDestino)) {
        throw new Exception('No se pudo guardar la imagen');
    }

    return 'library/contenido/' . $subcarpeta . '/' . $nombreArchivo;
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
    if (isset($_POST['GuardarPeliculaSerie'])) {
        $idPeliculaSerie = 0;

        if (isset($_POST['id_pelicula_serie'])) {
            $idPeliculaSerie = (int)$_POST['id_pelicula_serie'];
        }

        $datos = datosPostContenido();
        $mensajeValidacion = validarDatosContenido($datos, $idPeliculaSerie);

        if ($mensajeValidacion !== '') {
            redirigirFormularioContenido($mensajeValidacion, 'error', $idPeliculaSerie);
        }

        try {
            $datos['imagenPortada'] = guardarImagenContenido('imagen_portada', 'portadas', $datos['imagenPortada']);
            $datos['imagenBanner'] = guardarImagenContenido('imagen_banner', 'banners', $datos['imagenBanner']);

            $clienteSOAP = new ClienteSOAP();

            if ($idPeliculaSerie > 0) {
                $resultado = $clienteSOAP->actualizarPeliculaSerie($idPeliculaSerie, $datos);
            } else {
                $resultado = $clienteSOAP->crearPeliculaSerie($datos);
            }

            if (!$resultado['exito']) {
                redirigirFormularioContenido($resultado['mensaje'], 'error', $idPeliculaSerie);
            }

            $idResultado = $idPeliculaSerie;
            if (isset($resultado['idPeliculaSerie']) && (int)$resultado['idPeliculaSerie'] > 0) {
                $idResultado = (int)$resultado['idPeliculaSerie'];
            }

            redirigirGestionContenido($resultado['mensaje'], 'exito', $idResultado);

        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Error llamando') === 0) {
                redirigirErrorBaseDatosContenido();
            }

            redirigirFormularioContenido($e->getMessage(), 'error', $idPeliculaSerie);
        } catch (Throwable $e) {
            redirigirErrorBaseDatosContenido();
        }
    }

    if (isset($_POST['DesactivarPeliculaSerie'])) {
        $idPeliculaSerie = 0;

        if (isset($_POST['id_pelicula_serie'])) {
            $idPeliculaSerie = (int)$_POST['id_pelicula_serie'];
        }

        if ($idPeliculaSerie <= 0) {
            redirigirGestionContenido('Selecciona un contenido válido', 'error');
        }

        try {
            $clienteSOAP = new ClienteSOAP();
            $resultado = $clienteSOAP->desactivarPeliculaSerie($idPeliculaSerie);

            if (!$resultado['exito']) {
                redirigirGestionContenido($resultado['mensaje'], 'error', $idPeliculaSerie);
            }

            redirigirGestionContenido('Contenido desactivado correctamente', 'exito', $idPeliculaSerie);

        } catch (Throwable $e) {
            redirigirErrorBaseDatosContenido();
        }
    }

    if (isset($_POST['ActivarPeliculaSerie'])) {
        $idPeliculaSerie = 0;

        if (isset($_POST['id_pelicula_serie'])) {
            $idPeliculaSerie = (int)$_POST['id_pelicula_serie'];
        }

        if ($idPeliculaSerie <= 0) {
            redirigirGestionContenido('Selecciona un contenido válido', 'error');
        }

        try {
            $clienteSOAP = new ClienteSOAP();
            $resultado = $clienteSOAP->activarPeliculaSerie($idPeliculaSerie);

            if (!$resultado['exito']) {
                redirigirGestionContenido($resultado['mensaje'], 'error', $idPeliculaSerie);
            }

            redirigirGestionContenido('Contenido activado correctamente', 'exito', $idPeliculaSerie);

        } catch (Throwable $e) {
            redirigirErrorBaseDatosContenido();
        }
    }
}

header("Location: ../Views/Administracion/gestion-peliculas-series.php");
exit;
?>
