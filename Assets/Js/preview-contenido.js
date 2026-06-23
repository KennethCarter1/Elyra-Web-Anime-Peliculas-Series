document.addEventListener('DOMContentLoaded', function () {
    const entradasImagen = document.querySelectorAll('input[type="file"][data-preview]');
    const urlsTemporales = {};

    entradasImagen.forEach(function (entrada) {
        entrada.addEventListener('change', function () {
            const idPreview = entrada.getAttribute('data-preview');
            const preview = document.getElementById(idPreview);

            if (!preview) {
                return;
            }

            if (!entrada.files || entrada.files.length === 0) {
                return;
            }

            const archivo = entrada.files[0];

            if (!archivo.type || archivo.type.indexOf('image/') !== 0) {
                preview.classList.add('oculto');
                preview.removeAttribute('src');
                return;
            }

            if (urlsTemporales[idPreview]) {
                URL.revokeObjectURL(urlsTemporales[idPreview]);
            }

            urlsTemporales[idPreview] = URL.createObjectURL(archivo);
            preview.src = urlsTemporales[idPreview];
            preview.classList.remove('oculto');
        });
    });
});
