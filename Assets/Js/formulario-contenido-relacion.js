document.addEventListener('DOMContentLoaded', function() {
    var selectPadre = document.getElementById('serie_padre_id');
    var campoTipoRelacion = document.getElementById('campo-tipo-relacion');
    var campoNumeroTemp = document.getElementById('campo-numero-temporada');
    var selectTipoRelacion = document.getElementById('tipo_relacion');

    if (!selectPadre || !campoTipoRelacion || !campoNumeroTemp || !selectTipoRelacion) {
        return;
    }

    function actualizarCampoNumeroTemp() {
        if (selectTipoRelacion.value === 'temporada' && selectPadre.value !== '0') {
            campoNumeroTemp.classList.remove('oculto');
        } else {
            campoNumeroTemp.classList.add('oculto');
        }
    }

    function actualizarCamposRelacion() {
        if (selectPadre.value !== '0') {
            campoTipoRelacion.classList.remove('oculto');
        } else {
            campoTipoRelacion.classList.add('oculto');
            campoNumeroTemp.classList.add('oculto');
        }

        actualizarCampoNumeroTemp();
    }

    selectPadre.addEventListener('change', actualizarCamposRelacion);
    selectTipoRelacion.addEventListener('change', actualizarCampoNumeroTemp);
    actualizarCamposRelacion();
});
