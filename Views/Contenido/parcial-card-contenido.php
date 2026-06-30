<?php
require_once __DIR__ . '/../../Models/Seguridad.php';
$urlDetalleCard = '/elyra/detalle';

if (isset($urlDetalleContenido) && trim($urlDetalleContenido) !== '') {
    $urlDetalleCard = $urlDetalleContenido;
}
?>
<article class="card-contenido-inicio" data-favorito-card-id="<?php echo (int)$contenido['id']; ?>" data-favorito-card-tipo="<?php echo Inicio::valorSeguro($contenido['tipo']); ?>">
    <a href="<?php echo Inicio::valorSeguro($urlDetalleCard); ?>?id=<?php echo (int)$contenido['id']; ?>" class="enlace-card-contenido">
        <span class="poster-card-contenido">
            <?php if ($contenido['imagenPortadaUrl'] !== '') { ?>
                <img src="<?php echo Inicio::valorSeguro($contenido['imagenPortadaUrl']); ?>" alt="<?php echo Inicio::valorSeguro($contenido['titulo']); ?>" loading="lazy" decoding="async">
            <?php } else { ?>
                <span class="poster-card-vacio">
                    <i class="fa-solid fa-film"></i>
                </span>
            <?php } ?>
        </span>

        <strong><?php echo Inicio::valorSeguro($contenido['titulo']); ?></strong>
        <small><?php echo Inicio::valorSeguro($contenido['tipo']); ?> · <?php echo Inicio::valorSeguro($contenido['generos']); ?></small>
    </a>

    <form method="POST" action="../../Controller/ControladorFavoritos.php" class="form-favorito-card">
                <?php echo Seguridad::campoCsrf(); ?>
        <input type="hidden" name="id_pelicula_serie" value="<?php echo (int)$contenido['id']; ?>">
        <input type="hidden" name="retorno" value="<?php echo Inicio::valorSeguro($retornoFavorito); ?>">
        <button type="submit" name="AlternarFavorito" value="1" class="btn-favorito-card <?php echo Inicio::valorSeguro($contenido['favoritoClase']); ?>" aria-label="<?php echo Inicio::valorSeguro($contenido['favoritoTexto']); ?>">
            <i class="fa-solid fa-heart"></i>
        </button>
    </form>
</article>
