<?php
require_once __DIR__ . '/../../Models/Seguridad.php';
session_start();
require_once '../../Models/PeliculasSeries.php';
require_once '../../Models/Sesion.php';
require_once '../../Models/Navegacion.php';
require_once '../../Api/soap/ClienteSOAP.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: /elyra/login');
    exit;
}

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    header('Location: /elyra/inicio');
    exit;
}

$idPeliculaSerie = 0;
if (isset($_GET['id'])) {
    $idPeliculaSerie = (int)$_GET['id'];
}

$formularioContenido = PeliculasSeries::formularioInicial();
$generosGestion = PeliculasSeries::generosIniciales();
$seriesPadre = PeliculasSeries::generosIniciales();
$editando = false;

$mensajeFormulario = Sesion::tomarMensaje('mensaje_formulario_contenido');
$tipoMensajeFormulario = Sesion::tomarMensaje('tipo_mensaje_formulario_contenido');

try {
    $clienteSOAP = new ClienteSOAP();
    $generosGestion = PeliculasSeries::normalizarGenerosGestion($clienteSOAP->listarGenerosGestion());
    $seriesPadre = $clienteSOAP->listarSeriesPadre($idPeliculaSerie);

    if ($idPeliculaSerie > 0) {
        $detalleContenido = $clienteSOAP->obtenerDetallePeliculaSerie($idPeliculaSerie);
        $formularioContenido = PeliculasSeries::formularioDesdeDetalle($detalleContenido);

        if ((int)$formularioContenido['id'] > 0) {
            $editando = true;
        }
    }
} catch (Exception $e) {
    Navegacion::redirigirErrorBaseDatosVista('/elyra/admin/contenido/formulario', $_SERVER);
}

$tipoSidebar = 'administracion';
$tituloPagina = 'Agregar nueva película / serie';
$textoAyuda = 'Completa la información para agregar un nuevo contenido audiovisual a la plataforma.';

if ($editando) {
    $tituloPagina = 'Actualizar película / serie';
    $textoAyuda = 'Actualiza la información del contenido seleccionado.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/elyra/Views/Administracion/">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="../../Assets/Images/logos/iconos/morado.ico">
    <link rel="stylesheet" href="../../Assets/Css/Variables.css?v=vidrio-global-20260630">
    <link rel="stylesheet" href="../../Assets/Css/Parciales.css?v=vidrio-global-20260630">
    <link rel="stylesheet" href="../../Assets/Css/AgregarPeliculaSerie.css">
    <link rel="stylesheet" href="../../Assets/Css/switch.css">
    <title><?php echo htmlspecialchars($tituloPagina, ENT_QUOTES, 'UTF-8'); ?></title>
</head>
<body>
    <?php include '../Parciales/header.php'; ?>
    <?php include '../Parciales/sidebar.php'; ?>

    <main class="contenido-principal pagina-formulario-contenido">
        <section class="encabezado-formulario-contenido">
            <div>
                <h1><?php echo htmlspecialchars($tituloPagina, ENT_QUOTES, 'UTF-8'); ?></h1>
                <p><?php echo htmlspecialchars($textoAyuda, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        </section>

        <?php if ($mensajeFormulario !== '') { ?>
            <div class="mensaje-formulario-contenido mensaje-<?php echo htmlspecialchars($tipoMensajeFormulario, ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($mensajeFormulario, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php } ?>

        <form method="POST" action="../../Controller/ControladorContenido.php" enctype="multipart/form-data" class="formulario-contenido-admin">
                <?php echo Seguridad::campoCsrf(); ?>
            <input type="hidden" name="GuardarPeliculaSerie" value="1">
            <input type="hidden" name="id_pelicula_serie" value="<?php echo (int)$formularioContenido['id']; ?>">
            <input type="hidden" name="imagen_portada_actual" value="<?php echo PeliculasSeries::valorFormulario($formularioContenido, 'imagenPortada'); ?>">
            <input type="hidden" name="imagen_banner_actual" value="<?php echo PeliculasSeries::valorFormulario($formularioContenido, 'imagenBanner'); ?>">

            <section class="panel-formulario-contenido">
                <div class="campo-formulario-contenido campo-completo">
                    <label for="titulo">Título <span>*</span></label>
                    <input id="titulo" type="text" name="titulo" placeholder="Ingresa el título de la película o serie" value="<?php echo PeliculasSeries::valorFormulario($formularioContenido, 'titulo'); ?>">
                </div>

                <div class="campo-formulario-contenido campo-completo">
                    <label for="titulo_original">Título original</label>
                    <input id="titulo_original" type="text" name="titulo_original" placeholder="Ingresa el título original" value="<?php echo PeliculasSeries::valorFormulario($formularioContenido, 'tituloOriginal'); ?>">
                </div>

                <div class="campo-formulario-contenido campo-completo">
                    <label for="descripcion">Descripción <span>*</span></label>
                    <textarea id="descripcion" name="descripcion" rows="6" placeholder="Escribe una descripción del contenido..."><?php echo PeliculasSeries::valorFormulario($formularioContenido, 'descripcion'); ?></textarea>
                </div>

                <div class="campo-formulario-contenido">
                    <label for="tipo">Tipo <span>*</span></label>
                    <select id="tipo" name="tipo">
                        <option value="">Selecciona el tipo</option>
                        <option value="Película"<?php echo PeliculasSeries::seleccionar($formularioContenido['tipo'], 'Película'); ?>>Película</option>
                        <option value="Serie"<?php echo PeliculasSeries::seleccionar($formularioContenido['tipo'], 'Serie'); ?>>Serie</option>
                    </select>
                </div>

                <div class="campo-formulario-contenido">
                    <label>Género(s) <span>*</span></label>
                    <div class="selector-generos-contenido">
                        <?php foreach ($generosGestion as $genero) { ?>
                            <label>
                                <input type="checkbox" name="generos[]" value="<?php echo (int)$genero['id']; ?>"<?php echo PeliculasSeries::estaSeleccionadoGenero($formularioContenido['idsGeneros'], $genero['id']); ?>>
                                <span><?php echo htmlspecialchars($genero['nombre'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </label>
                        <?php } ?>
                    </div>
                </div>

                <div class="campo-formulario-contenido">
                    <label for="anio_lanzamiento">Año de lanzamiento <span>*</span></label>
                    <input id="anio_lanzamiento" type="number" name="anio_lanzamiento" min="1900" max="2100" placeholder="Selecciona el año" value="<?php echo PeliculasSeries::valorFormulario($formularioContenido, 'anio'); ?>">
                </div>

                <div class="campo-formulario-contenido">
                    <label for="fecha_estreno">Fecha de estreno <span>*</span></label>
                    <input id="fecha_estreno" type="date" name="fecha_estreno" value="<?php echo PeliculasSeries::valorFormulario($formularioContenido, 'fechaEstreno'); ?>">
                </div>

                <div class="campo-formulario-contenido campo-duracion">
                    <label for="duracion_minutos">Duración por episodio <span>*</span></label>
                    <div>
                        <input id="duracion_minutos" type="number" name="duracion_minutos" min="1" placeholder="Ej: 45" value="<?php echo PeliculasSeries::valorFormulario($formularioContenido, 'duracionMinutos'); ?>">
                        <span>minutos</span>
                    </div>
                </div>

                <div class="campo-formulario-contenido">
                    <label for="temporadas">Temporadas <span>*</span></label>
                    <input id="temporadas" type="number" name="temporadas" min="0" placeholder="Número de temporadas" value="<?php echo PeliculasSeries::valorFormulario($formularioContenido, 'temporadas'); ?>">
                </div>

                <div class="campo-formulario-contenido">
                    <label for="episodios">Episodios <span>*</span></label>
                    <input id="episodios" type="number" name="episodios" min="1" placeholder="Número total de episodios" value="<?php echo PeliculasSeries::valorFormulario($formularioContenido, 'episodios'); ?>">
                </div>

                <div class="campo-formulario-contenido">
                    <label for="serie_padre_id">Serie padre (precuela/secuela/temporada)</label>
                    <select id="serie_padre_id" name="serie_padre_id">
                        <option value="0">Ninguna</option>
                        <?php foreach ($seriesPadre as $serie) { ?>
                            <option value="<?php echo (int)$serie->idPeliculaSerie; ?>"<?php if ((int)$formularioContenido['seriePadreId'] === (int)$serie->idPeliculaSerie) { echo ' selected'; } ?>>
                                <?php echo htmlspecialchars($serie->titulo, ENT_QUOTES, 'UTF-8'); ?> (<?php echo (int)$serie->anioLanzamiento; ?>)
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="campo-formulario-contenido" id="campo-tipo-relacion">
                    <label for="tipo_relacion">Tipo de relación</label>
                    <select id="tipo_relacion" name="tipo_relacion">
                        <option value="">Selecciona el tipo</option>
                        <option value="temporada"<?php echo PeliculasSeries::seleccionar($formularioContenido['tipoRelacion'], 'temporada'); ?>>Temporada</option>
                        <option value="precuela"<?php echo PeliculasSeries::seleccionar($formularioContenido['tipoRelacion'], 'precuela'); ?>>Precuela</option>
                        <option value="secuela"<?php echo PeliculasSeries::seleccionar($formularioContenido['tipoRelacion'], 'secuela'); ?>>Secuela</option>
                    </select>
                </div>

                <div class="campo-formulario-contenido" id="campo-numero-temporada">
                    <label for="numero_temporada">Número de temporada</label>
                    <input id="numero_temporada" type="number" name="numero_temporada" min="0" placeholder="Ej: 2" value="<?php echo PeliculasSeries::valorFormulario($formularioContenido, 'numeroTemporada'); ?>">
                </div>

                <div class="campo-formulario-contenido campo-completo">
                    <label for="trailer_url">URL del tráiler (YouTube)</label>
                    <input id="trailer_url" type="url" name="trailer_url" placeholder="https://www.youtube.com/watch?v=..." value="<?php echo PeliculasSeries::valorFormulario($formularioContenido, 'trailerUrl'); ?>">
                    <small>Ingresa el enlace del tráiler de YouTube</small>
                </div>

                <fieldset class="estado-formulario-contenido campo-completo">
                    <legend>Estado</legend>
                    <label>
                        <input type="radio" name="estado" value="Publicado"<?php echo PeliculasSeries::marcar($formularioContenido['estado'], 'Publicado'); ?>>
                        <span>
                            <strong>Publicado</strong>
                            <small>El contenido estará visible para los usuarios.</small>
                        </span>
                    </label>

                    <label>
                        <input type="radio" name="estado" value="Desactivado"<?php echo PeliculasSeries::marcar($formularioContenido['estado'], 'Desactivado'); ?>>
                        <span>
                            <strong>Desactivado</strong>
                            <small>El contenido no será visible para los usuarios.</small>
                        </span>
                    </label>
                </fieldset>

                <fieldset class="estado-formulario-contenido campo-completo">
                    <legend>Estado de emisión</legend>
                    <label>
                        <input type="radio" name="estado_emision" value="Finalizado"<?php echo PeliculasSeries::marcar($formularioContenido['estadoEmision'], 'Finalizado'); ?>>
                        <span>
                            <strong>Finalizado</strong>
                            <small>El anime o contenido ya terminó.</small>
                        </span>
                    </label>

                    <label>
                        <input type="radio" name="estado_emision" value="En emisión"<?php echo PeliculasSeries::marcar($formularioContenido['estadoEmision'], 'En emisión'); ?>>
                        <span>
                            <strong>En emisión</strong>
                            <small>El anime sigue publicando episodios.</small>
                        </span>
                    </label>

                    <label>
                        <input type="radio" name="estado_emision" value="Próximamente"<?php echo PeliculasSeries::marcar($formularioContenido['estadoEmision'], 'Próximamente'); ?>>
                        <span>
                            <strong>Próximamente</strong>
                            <small>El anime o temporada aún no se estrena.</small>
                        </span>
                    </label>
                </fieldset>

                <div class="destacado-formulario-contenido campo-completo">
                    <label>
                        <input type="checkbox" name="destacado" value="1"<?php if ((int)$formularioContenido['destacado'] === 1) { echo ' checked'; } ?>>
                        <span>
                            <strong>Mostrar en destacado</strong>
                            <small>El contenido aparecerá en el carrusel principal del inicio.</small>
                        </span>
                    </label>
                </div>

                <div class="acciones-formulario-contenido campo-completo">
                    <a href="gestion-peliculas-series.php" class="btn-cancelar-contenido">Cancelar</a>
                    <button type="submit" class="btn-guardar-contenido">
                        <?php echo PeliculasSeries::textoBotonFormulario($editando); ?>
                    </button>
                </div>
            </section>

            <aside class="panel-imagenes-contenido">
                <div class="tarjeta-imagen-contenido">
                    <div class="titulo-imagen-contenido">
                        <h2>Imagen de portada <span>*</span></h2>
                        <i class="fa-regular fa-circle-question"></i>
                    </div>

                    <img id="preview-portada" class="preview-imagen-contenido<?php if ($formularioContenido['imagenPortadaUrl'] === '') { ?> oculto<?php } ?>" src="<?php echo htmlspecialchars($formularioContenido['imagenPortadaUrl'], ENT_QUOTES, 'UTF-8'); ?>" alt="Vista previa de portada" loading="lazy" decoding="async">

                    <label for="imagen_portada" class="zona-subida-contenido">
                        <i class="fa-regular fa-image"></i>
                    </label>
                    <input id="imagen_portada" type="file" name="imagen_portada" accept=".jpg,.jpeg,.png,.webp" data-preview="preview-portada">
                </div>

                <div class="tarjeta-imagen-contenido">
                    <div class="titulo-imagen-contenido">
                        <h2>Imagen / banner (opcional)</h2>
                        <i class="fa-regular fa-circle-question"></i>
                    </div>

                    <img id="preview-banner" class="preview-imagen-contenido<?php if ($formularioContenido['imagenBannerUrl'] === '') { ?> oculto<?php } ?>" src="<?php echo htmlspecialchars($formularioContenido['imagenBannerUrl'], ENT_QUOTES, 'UTF-8'); ?>" alt="Vista previa de banner" loading="lazy" decoding="async">

                    <label for="imagen_banner" class="zona-subida-contenido">
                        <i class="fa-regular fa-image"></i>
                    </label>
                    <input id="imagen_banner" type="file" name="imagen_banner" accept=".jpg,.jpeg,.png,.webp" data-preview="preview-banner">
                </div>
            </aside>
        </form>
    </main>

    <script src="../../Assets/Js/dark-mode.js?v=vidrio-global-20260630"></script>
    <script src="../../Assets/Js/preview-contenido.js"></script>
    <script src="../../Assets/Js/formulario-contenido-relacion.js"></script>
</body>
</html>
