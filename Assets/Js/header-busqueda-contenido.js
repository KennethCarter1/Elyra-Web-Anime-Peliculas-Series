const formularioBusquedaContenido = document.querySelector('[data-busqueda-contenido]');

if (formularioBusquedaContenido) {
    const inputBusquedaContenido = formularioBusquedaContenido.querySelector('[data-input-busqueda-contenido]');
    const contenedorResultados = formularioBusquedaContenido.querySelector('[data-resultados-busqueda-contenido]');
    const botonBuscarContenido = formularioBusquedaContenido.querySelector('.btn-buscar');
    const endpointBusqueda = formularioBusquedaContenido.getAttribute('data-endpoint-busqueda');
    let temporizadorBusqueda = null;
    let controladorBusqueda = null;

    function limpiarResultadosBusqueda() {
        contenedorResultados.innerHTML = '';
        contenedorResultados.classList.remove('activo');
    }

    function crearPortadaResultado(resultado) {
        const portada = document.createElement('span');
        portada.className = 'portada-resultado-busqueda';

        if (resultado.imagen) {
            const imagen = document.createElement('img');
            imagen.src = resultado.imagen;
            imagen.alt = resultado.titulo;
            portada.appendChild(imagen);
        } else {
            const icono = document.createElement('i');
            icono.className = 'fa-solid fa-film';
            portada.appendChild(icono);
        }

        return portada;
    }

    function pintarResultadosBusqueda(resultados) {
        limpiarResultadosBusqueda();

        if (!resultados.length) {
            const vacio = document.createElement('div');
            vacio.className = 'resultado-busqueda-vacio';
            vacio.textContent = 'No se encontraron resultados';
            contenedorResultados.appendChild(vacio);
            contenedorResultados.classList.add('activo');
            return;
        }

        resultados.forEach(function(resultado) {
            const enlace = document.createElement('a');
            enlace.href = resultado.url;
            enlace.className = 'resultado-busqueda-header';

            const texto = document.createElement('span');
            texto.className = 'texto-resultado-busqueda';

            const titulo = document.createElement('strong');
            titulo.textContent = resultado.titulo;

            const detalle = document.createElement('small');
            detalle.textContent = resultado.tipo + ' · ' + resultado.generos;

            texto.appendChild(titulo);
            texto.appendChild(detalle);
            enlace.appendChild(crearPortadaResultado(resultado));
            enlace.appendChild(texto);
            contenedorResultados.appendChild(enlace);
        });

        contenedorResultados.classList.add('activo');
    }

    function buscarContenidoHeader() {
        const busqueda = inputBusquedaContenido.value.trim();

        if (busqueda.length < 2) {
            limpiarResultadosBusqueda();
            return;
        }

        if (controladorBusqueda) {
            controladorBusqueda.abort();
        }

        controladorBusqueda = new AbortController();
        const urlBusqueda = new URL(endpointBusqueda, window.location.href);
        urlBusqueda.searchParams.set('busqueda', busqueda);

        fetch(urlBusqueda.toString(), {
            headers: {
                'Accept': 'application/json'
            },
            signal: controladorBusqueda.signal
        })
            .then(function(respuesta) {
                return respuesta.json();
            })
            .then(function(respuesta) {
                if (!respuesta.exito) {
                    limpiarResultadosBusqueda();
                    return;
                }

                pintarResultadosBusqueda(respuesta.resultados);
            })
            .catch(function(error) {
                if (error.name !== 'AbortError') {
                    limpiarResultadosBusqueda();
                }
            });
    }

    inputBusquedaContenido.addEventListener('input', function() {
        clearTimeout(temporizadorBusqueda);
        temporizadorBusqueda = setTimeout(buscarContenidoHeader, 220);
    });

    inputBusquedaContenido.addEventListener('focus', function() {
        if (inputBusquedaContenido.value.trim().length >= 2) {
            buscarContenidoHeader();
        }
    });

    formularioBusquedaContenido.addEventListener('submit', function(evento) {
        evento.preventDefault();
        const primerResultado = contenedorResultados.querySelector('a');

        if (primerResultado) {
            window.location.href = primerResultado.href;
        }
    });

    if (botonBuscarContenido) {
        botonBuscarContenido.addEventListener('click', function() {
            const primerResultado = contenedorResultados.querySelector('a');

            if (primerResultado) {
                window.location.href = primerResultado.href;
                return;
            }

            buscarContenidoHeader();
        });
    }

    document.addEventListener('click', function(evento) {
        if (!formularioBusquedaContenido.contains(evento.target)) {
            limpiarResultadosBusqueda();
        }
    });
}
