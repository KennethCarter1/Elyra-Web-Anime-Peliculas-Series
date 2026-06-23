const carruselDestacado = document.querySelector('[data-carrusel-destacado]');

if (carruselDestacado) {
    const slidesDestacados = carruselDestacado.querySelectorAll('[data-slide-destacado]');
    const puntosDestacados = carruselDestacado.querySelectorAll('[data-punto-destacado]');
    const botonAnterior = carruselDestacado.querySelector('[data-slide-anterior]');
    const botonSiguiente = carruselDestacado.querySelector('[data-slide-siguiente]');
    const botonSonido = carruselDestacado.querySelector('[data-sonido-destacado]');
    let indiceActual = 0;
    let sonidoActivo = true;
    let carruselVisible = true;

    function urlVideoDestacado(url) {
        if (sonidoActivo) {
            return url.replace('mute=1', 'mute=0').replace('controls=0', 'controls=1');
        }

        return url.replace('mute=0', 'mute=1').replace('controls=1', 'controls=0');
    }

    function aplicarCalidadVideoDestacado(iframe) {
        if (!iframe || !iframe.contentWindow) {
            return;
        }

        const mensajeCalidad = JSON.stringify({
            event: 'command',
            func: 'setPlaybackQuality',
            args: ['hd1080']
        });

        iframe.contentWindow.postMessage(mensajeCalidad, 'https://www.youtube.com');
    }

    function cargarVideoDestacado(indice) {
        if (!carruselVisible) {
            detenerVideosDestacados();
            return;
        }

        slidesDestacados.forEach(function(slide, posicion) {
            const iframe = slide.querySelector('iframe');

            if (!iframe) {
                return;
            }

            if (posicion === indice) {
                const videoSrc = iframe.getAttribute('data-video-src');

                if (videoSrc) {
                    const nuevaUrl = urlVideoDestacado(videoSrc);

                    if (iframe.getAttribute('src') !== nuevaUrl) {
                        iframe.addEventListener('load', function() {
                            aplicarCalidadVideoDestacado(iframe);
                        }, { once: true });
                        iframe.setAttribute('src', nuevaUrl);
                    }

                    setTimeout(function() {
                        aplicarCalidadVideoDestacado(iframe);
                    }, 1200);
                }
            } else {
                iframe.removeAttribute('src');
            }
        });
    }

    function detenerVideosDestacados() {
        slidesDestacados.forEach(function(slide) {
            const iframe = slide.querySelector('iframe');

            if (iframe) {
                iframe.removeAttribute('src');
            }
        });
    }

    function actualizarVideoPorVisibilidad(visible) {
        carruselVisible = visible;

        if (carruselVisible) {
            cargarVideoDestacado(indiceActual);
        } else {
            detenerVideosDestacados();
        }
    }

    function observarCarruselDestacado() {
        if ('IntersectionObserver' in window) {
            const observador = new IntersectionObserver(function(entradas) {
                entradas.forEach(function(entrada) {
                    actualizarVideoPorVisibilidad(entrada.isIntersecting && entrada.intersectionRatio > 0.15);
                });
            }, {
                threshold: [0, 0.15]
            });

            observador.observe(carruselDestacado);
            return;
        }

        function revisarScroll() {
            const rectangulo = carruselDestacado.getBoundingClientRect();
            const visible = rectangulo.bottom > 80 && rectangulo.top < window.innerHeight - 80;
            actualizarVideoPorVisibilidad(visible);
        }

        window.addEventListener('scroll', revisarScroll);
        window.addEventListener('resize', revisarScroll);
        revisarScroll();
    }

    function mostrarSlideDestacado(indice) {
        if (slidesDestacados.length === 0) {
            return;
        }

        if (indice < 0) {
            indice = slidesDestacados.length - 1;
        }

        if (indice >= slidesDestacados.length) {
            indice = 0;
        }

        slidesDestacados.forEach(function(slide) {
            slide.classList.remove('activo');
        });

        puntosDestacados.forEach(function(punto) {
            punto.classList.remove('activo');
        });

        slidesDestacados[indice].classList.add('activo');

        if (puntosDestacados[indice]) {
            puntosDestacados[indice].classList.add('activo');
        }

        indiceActual = indice;
        cargarVideoDestacado(indiceActual);
    }

    if (botonAnterior) {
        botonAnterior.addEventListener('click', function() {
            mostrarSlideDestacado(indiceActual - 1);
        });
    }

    if (botonSiguiente) {
        botonSiguiente.addEventListener('click', function() {
            mostrarSlideDestacado(indiceActual + 1);
        });
    }

    puntosDestacados.forEach(function(punto) {
        punto.addEventListener('click', function() {
            const indicePunto = parseInt(punto.getAttribute('data-punto-destacado'), 10);

            if (!Number.isNaN(indicePunto)) {
                mostrarSlideDestacado(indicePunto);
            }
        });
    });

    if (botonSonido) {
        botonSonido.addEventListener('click', function() {
            const iconoSonido = botonSonido.querySelector('i');
            sonidoActivo = !sonidoActivo;

            botonSonido.classList.toggle('activo', sonidoActivo);
            botonSonido.setAttribute('aria-label', sonidoActivo ? 'Silenciar tráiler' : 'Activar sonido');

            if (iconoSonido) {
                iconoSonido.className = sonidoActivo ? 'fa-solid fa-volume-high' : 'fa-solid fa-volume-xmark';
            }

            cargarVideoDestacado(indiceActual);
        });
    }

    observarCarruselDestacado();
    mostrarSlideDestacado(0);
}
