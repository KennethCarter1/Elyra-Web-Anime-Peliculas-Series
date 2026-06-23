document.addEventListener('submit', function(evento) {
    const formulario = evento.target;

    if (!formulario.matches('form[action$="ControladorFavoritos.php"]')) {
        return;
    }

    evento.preventDefault();

    const boton = formulario.querySelector('button[type="submit"]');
    const datos = new FormData(formulario);
    const idContenido = datos.get('id_pelicula_serie');

    if (!datos.has('AlternarFavorito')) {
        datos.append('AlternarFavorito', '1');
    }

    if (boton) {
        boton.disabled = true;
    }

    fetch(formulario.getAttribute('action'), {
        method: 'POST',
        body: datos,
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
        .then(function(respuesta) {
            return respuesta.json();
        })
        .then(function(respuesta) {
            if (respuesta.redirigir) {
                window.location.href = respuesta.redirigir;
                return;
            }

            if (!respuesta.exito) {
                return;
            }

            actualizarBotonesFavorito(idContenido, parseInt(respuesta.favorito, 10));
            actualizarPantallaFavoritos(idContenido, parseInt(respuesta.favorito, 10));
        })
        .catch(function() {
            asegurarCampoAccionFavorito(formulario);
            formulario.submit();
        })
        .finally(function() {
            if (boton) {
                boton.disabled = false;
            }
        });
});

function actualizarBotonesFavorito(idContenido, favorito) {
    const formularios = document.querySelectorAll('form[action$="ControladorFavoritos.php"]');
    const texto = favorito === 1 ? 'Quitar de favoritos' : 'Agregar a favoritos';
    const estaEnFavoritos = favorito === 1;

    formularios.forEach(function(formulario) {
        const inputId = formulario.querySelector('input[name="id_pelicula_serie"]');

        if (!inputId || inputId.value !== String(idContenido)) {
            return;
        }

        const boton = formulario.querySelector('button[type="submit"]');

        if (!boton) {
            return;
        }

        boton.classList.toggle('activo', estaEnFavoritos);
        boton.dataset.favorito = estaEnFavoritos ? '1' : '0';
        boton.setAttribute('aria-label', texto);
        boton.setAttribute('title', texto);
    });
}

function actualizarPantallaFavoritos(idContenido, favorito) {
    const paginaFavoritos = document.querySelector('.favoritos-usuario');

    if (!paginaFavoritos || favorito !== 0) {
        return;
    }

    const tarjeta = paginaFavoritos.querySelector('[data-favorito-card-id="' + idContenido + '"]');

    if (!tarjeta) {
        return;
    }

    const tipo = tarjeta.dataset.favoritoCardTipo || '';
    tarjeta.classList.add('removiendo');

    setTimeout(function() {
        tarjeta.remove();
        descontarResumenFavoritos(tipo);
        mostrarVacioFavoritosSiAplica(paginaFavoritos);
    }, 180);
}

function descontarResumenFavoritos(tipo) {
    descontarValorResumen('total');

    if (tipo === 'Pelicula') {
        descontarValorResumen('peliculas');
    }

    if (tipo === 'Serie') {
        descontarValorResumen('series');
    }
}

function descontarValorResumen(nombre) {
    const elemento = document.querySelector('[data-resumen-favoritos="' + nombre + '"]');

    if (!elemento) {
        return;
    }

    const valorActual = parseInt(elemento.textContent, 10);

    if (Number.isNaN(valorActual) || valorActual <= 0) {
        elemento.textContent = '0';
        return;
    }

    elemento.textContent = String(valorActual - 1);
}

function mostrarVacioFavoritosSiAplica(paginaFavoritos) {
    const lista = paginaFavoritos.querySelector('[data-lista-favoritos]');

    if (!lista || lista.querySelector('.card-contenido-inicio')) {
        return;
    }

    const catalogo = paginaFavoritos.querySelector('.catalogo-favoritos');

    if (!catalogo) {
        return;
    }

    lista.remove();

    const vacio = document.createElement('div');
    vacio.className = 'favoritos-vacio';
    vacio.dataset.favoritosVacio = '';

    const icono = document.createElement('i');
    icono.className = 'fa-solid fa-heart-crack';

    const titulo = document.createElement('h2');
    titulo.textContent = 'Aún no tienes favoritos';

    const texto = document.createElement('p');
    texto.textContent = 'Guarda películas y series para encontrarlas rápido después.';

    const enlace = document.createElement('a');
    enlace.href = 'explorar.php';
    enlace.textContent = 'Explorar contenido';

    vacio.appendChild(icono);
    vacio.appendChild(titulo);
    vacio.appendChild(texto);
    vacio.appendChild(enlace);
    catalogo.appendChild(vacio);
}

function asegurarCampoAccionFavorito(formulario) {
    let accion = formulario.querySelector('input[name="AlternarFavorito"]');

    if (!accion) {
        accion = document.createElement('input');
        accion.type = 'hidden';
        accion.name = 'AlternarFavorito';
        formulario.appendChild(accion);
    }

    accion.value = '1';
}
