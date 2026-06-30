const toggle = document.getElementById('toggle-theme');
const toggleVidrio = document.getElementById('toggle-glass-effect');
const opcionesTema = document.querySelectorAll('[data-theme-option]');
const opcionesAcento = document.querySelectorAll('[data-accent]');
const logosPorAcento = {
    morado: {
        imagen: 'morado.webp',
        icono: 'morado.ico'
    },
    azul: {
        imagen: 'azul.webp',
        icono: 'azul.ico'
    },
    verde: {
        imagen: 'verde.webp',
        icono: 'verde.ico'
    },
    rosa: {
        imagen: 'rosado.webp',
        icono: 'rosado.ico'
    },
    naranja: {
        imagen: 'naranja.webp',
        icono: 'naranja.ico'
    },
    cian: {
        imagen: 'cian.webp',
        icono: 'cian.ico'
    }
};

const aliasAcento = {
    rosado: 'rosa',
    amarillo: 'naranja',
    amarilla: 'naranja'
};

const versionAssets = 'vidrio-global-20260630';

const paletasAcento = {
    morado: {
        '--color-acento': '#6A5ACD',
        '--color-acento-hover': '#4b3bb3',
        '--color-acento-claro': '#d0d0ff',
        '--color-acento-claro-hover': '#b8b8ff',
        '--color-acento-borde': '#b8a7ff',
        '--color-acento-suave': 'rgba(106, 90, 205, 0.12)',
        '--color-acento-suave-2': 'rgba(106, 90, 205, 0.14)',
        '--color-acento-suave-3': 'rgba(106, 90, 205, 0.08)',
        '--color-acento-sombra': 'rgba(106, 90, 205, 0.12)',
        '--color-acento-sombra-2': 'rgba(106, 90, 205, 0.36)',
        '--color-acento-icono': 'rgba(106, 90, 205, 0.82)',
        '--color-acento-oscuro-suave': 'rgba(208, 208, 255, 0.14)',
        '--color-acento-oscuro-suave-2': 'rgba(208, 208, 255, 0.12)',
        '--color-acento-oscuro-suave-3': 'rgba(208, 208, 255, 0.2)',
        '--color-acento-oscuro-sombra': 'rgba(208, 208, 255, 0.32)',
        '--color-acento-oscuro-outline': 'rgba(208, 208, 255, 0.44)'
    },
    azul: {
        '--color-acento': '#2F80ED',
        '--color-acento-hover': '#1f5fbd',
        '--color-acento-claro': '#bad8ff',
        '--color-acento-claro-hover': '#9fc6ff',
        '--color-acento-borde': '#9cc5ff',
        '--color-acento-suave': 'rgba(47, 128, 237, 0.12)',
        '--color-acento-suave-2': 'rgba(47, 128, 237, 0.14)',
        '--color-acento-suave-3': 'rgba(47, 128, 237, 0.08)',
        '--color-acento-sombra': 'rgba(47, 128, 237, 0.12)',
        '--color-acento-sombra-2': 'rgba(47, 128, 237, 0.36)',
        '--color-acento-icono': 'rgba(47, 128, 237, 0.82)',
        '--color-acento-oscuro-suave': 'rgba(186, 216, 255, 0.14)',
        '--color-acento-oscuro-suave-2': 'rgba(186, 216, 255, 0.12)',
        '--color-acento-oscuro-suave-3': 'rgba(186, 216, 255, 0.2)',
        '--color-acento-oscuro-sombra': 'rgba(186, 216, 255, 0.32)',
        '--color-acento-oscuro-outline': 'rgba(186, 216, 255, 0.44)'
    },
    verde: {
        '--color-acento': '#22A06B',
        '--color-acento-hover': '#167a50',
        '--color-acento-claro': '#bff4dc',
        '--color-acento-claro-hover': '#9fe9c7',
        '--color-acento-borde': '#96ddb9',
        '--color-acento-suave': 'rgba(34, 160, 107, 0.12)',
        '--color-acento-suave-2': 'rgba(34, 160, 107, 0.14)',
        '--color-acento-suave-3': 'rgba(34, 160, 107, 0.08)',
        '--color-acento-sombra': 'rgba(34, 160, 107, 0.12)',
        '--color-acento-sombra-2': 'rgba(34, 160, 107, 0.36)',
        '--color-acento-icono': 'rgba(34, 160, 107, 0.82)',
        '--color-acento-oscuro-suave': 'rgba(191, 244, 220, 0.14)',
        '--color-acento-oscuro-suave-2': 'rgba(191, 244, 220, 0.12)',
        '--color-acento-oscuro-suave-3': 'rgba(191, 244, 220, 0.2)',
        '--color-acento-oscuro-sombra': 'rgba(191, 244, 220, 0.32)',
        '--color-acento-oscuro-outline': 'rgba(191, 244, 220, 0.44)'
    },
    rosa: {
        '--color-acento': '#E84393',
        '--color-acento-hover': '#b92c72',
        '--color-acento-claro': '#ffd0e7',
        '--color-acento-claro-hover': '#ffb5d7',
        '--color-acento-borde': '#f6a2cb',
        '--color-acento-suave': 'rgba(232, 67, 147, 0.12)',
        '--color-acento-suave-2': 'rgba(232, 67, 147, 0.14)',
        '--color-acento-suave-3': 'rgba(232, 67, 147, 0.08)',
        '--color-acento-sombra': 'rgba(232, 67, 147, 0.12)',
        '--color-acento-sombra-2': 'rgba(232, 67, 147, 0.36)',
        '--color-acento-icono': 'rgba(232, 67, 147, 0.82)',
        '--color-acento-oscuro-suave': 'rgba(255, 208, 231, 0.14)',
        '--color-acento-oscuro-suave-2': 'rgba(255, 208, 231, 0.12)',
        '--color-acento-oscuro-suave-3': 'rgba(255, 208, 231, 0.2)',
        '--color-acento-oscuro-sombra': 'rgba(255, 208, 231, 0.32)',
        '--color-acento-oscuro-outline': 'rgba(255, 208, 231, 0.44)'
    },
    naranja: {
        '--color-acento': '#F59E0B',
        '--color-acento-hover': '#c47a05',
        '--color-acento-claro': '#ffe0a8',
        '--color-acento-claro-hover': '#ffd184',
        '--color-acento-borde': '#ffc96f',
        '--color-acento-suave': 'rgba(245, 158, 11, 0.12)',
        '--color-acento-suave-2': 'rgba(245, 158, 11, 0.14)',
        '--color-acento-suave-3': 'rgba(245, 158, 11, 0.08)',
        '--color-acento-sombra': 'rgba(245, 158, 11, 0.12)',
        '--color-acento-sombra-2': 'rgba(245, 158, 11, 0.36)',
        '--color-acento-icono': 'rgba(245, 158, 11, 0.82)',
        '--color-acento-oscuro-suave': 'rgba(255, 224, 168, 0.14)',
        '--color-acento-oscuro-suave-2': 'rgba(255, 224, 168, 0.12)',
        '--color-acento-oscuro-suave-3': 'rgba(255, 224, 168, 0.2)',
        '--color-acento-oscuro-sombra': 'rgba(255, 224, 168, 0.32)',
        '--color-acento-oscuro-outline': 'rgba(255, 224, 168, 0.44)'
    },
    cian: {
        '--color-acento': '#00A6A6',
        '--color-acento-hover': '#007c7c',
        '--color-acento-claro': '#b8f7f7',
        '--color-acento-claro-hover': '#93eeee',
        '--color-acento-borde': '#86dddd',
        '--color-acento-suave': 'rgba(0, 166, 166, 0.12)',
        '--color-acento-suave-2': 'rgba(0, 166, 166, 0.14)',
        '--color-acento-suave-3': 'rgba(0, 166, 166, 0.08)',
        '--color-acento-sombra': 'rgba(0, 166, 166, 0.12)',
        '--color-acento-sombra-2': 'rgba(0, 166, 166, 0.36)',
        '--color-acento-icono': 'rgba(0, 166, 166, 0.82)',
        '--color-acento-oscuro-suave': 'rgba(184, 247, 247, 0.14)',
        '--color-acento-oscuro-suave-2': 'rgba(184, 247, 247, 0.12)',
        '--color-acento-oscuro-suave-3': 'rgba(184, 247, 247, 0.2)',
        '--color-acento-oscuro-sombra': 'rgba(184, 247, 247, 0.32)',
        '--color-acento-oscuro-outline': 'rgba(184, 247, 247, 0.44)'
    }
};

function normalizarAcento(acentoActual) {
    if (aliasAcento[acentoActual]) {
        return aliasAcento[acentoActual];
    }

    return acentoActual;
}

function actualizarOpcionesAcento(acentoActual) {
    opcionesAcento.forEach(function(opcion) {
        opcion.classList.remove('activo');

        if (opcion.getAttribute('data-accent') === acentoActual) {
            opcion.classList.add('activo');
        }
    });
}

function aplicarAcento(acentoActual) {
    acentoActual = normalizarAcento(acentoActual);

    if (!paletasAcento[acentoActual]) {
        acentoActual = 'morado';
    }

    Object.keys(paletasAcento[acentoActual]).forEach(function(variable) {
        document.documentElement.style.setProperty(variable, paletasAcento[acentoActual][variable]);
    });

    localStorage.setItem('colorAcento', acentoActual);
    actualizarOpcionesAcento(acentoActual);
    actualizarLogoAcento(acentoActual);
}

function agregarVersion(rutaArchivo) {
    return rutaArchivo + '?v=' + versionAssets;
}

function rutaLogo(nombreArchivo) {
    return agregarVersion('../../Assets/Images/logos/logos/' + nombreArchivo);
}

function rutaIcono(nombreArchivo) {
    return agregarVersion('../../Assets/Images/logos/iconos/' + nombreArchivo);
}

function actualizarIconoPagina(logoActual, acentoActual) {
    document.querySelectorAll('[data-accent-favicon], link[rel~="icon"]').forEach(function(iconoActual) {
        iconoActual.remove();
    });

    const iconoPagina = document.createElement('link');
    iconoPagina.setAttribute('rel', 'icon');
    iconoPagina.setAttribute('type', 'image/x-icon');
    iconoPagina.setAttribute('href', rutaIcono(logoActual.icono));
    iconoPagina.setAttribute('data-accent-favicon', '');
    document.head.appendChild(iconoPagina);

    const iconoAccesoRapido = document.createElement('link');
    iconoAccesoRapido.setAttribute('rel', 'shortcut icon');
    iconoAccesoRapido.setAttribute('type', 'image/x-icon');
    iconoAccesoRapido.setAttribute('href', rutaIcono(logoActual.icono));
    iconoAccesoRapido.setAttribute('data-accent-favicon', '');
    document.head.appendChild(iconoAccesoRapido);
}

function actualizarLogoAcento(acentoActual) {
    let logoActual = logosPorAcento[acentoActual];

    if (!logoActual) {
        logoActual = logosPorAcento.morado;
    }

    document.querySelectorAll('[data-accent-logo], img.logo, img.logo-pagina').forEach(function(logo) {
        logo.setAttribute('src', rutaLogo(logoActual.imagen));
    });

    actualizarIconoPagina(logoActual, acentoActual);
}

function actualizarOpcionesTema(modoOscuro) {
    opcionesTema.forEach(function(opcion) {
        opcion.classList.remove('activo');

        if (modoOscuro && opcion.getAttribute('data-theme-option') === 'dark') {
            opcion.classList.add('activo');
        }

        if (!modoOscuro && opcion.getAttribute('data-theme-option') === 'light') {
            opcion.classList.add('activo');
        }
    });
}

function aplicarTema(modoOscuro) {
    if (modoOscuro) {
        document.body.classList.add('dark');
        localStorage.setItem('modoOscuro', 'true');
    } else {
        document.body.classList.remove('dark');
        localStorage.setItem('modoOscuro', 'false');
    }

    if (toggle) {
        toggle.checked = modoOscuro;
    }

    actualizarOpcionesTema(modoOscuro);
    aplicarEstadoVidrio();
}

function vidrioPreferidoActivo() {
    if (localStorage.getItem('efectoVidrio') === 'false') {
        return false;
    }

    return true;
}

function aplicarEstadoVidrio() {
    const vidrioActivo = vidrioPreferidoActivo();

    if (vidrioActivo) {
        document.body.classList.remove('sin-vidrio');
    } else {
        document.body.classList.add('sin-vidrio');
    }

    if (toggleVidrio) {
        toggleVidrio.checked = vidrioActivo;
        toggleVidrio.disabled = false;
    }
}

function aplicarVidrio(vidrioActivo) {
    if (vidrioActivo) {
        localStorage.setItem('efectoVidrio', 'true');
    } else {
        localStorage.setItem('efectoVidrio', 'false');
    }

    aplicarEstadoVidrio();
}

if (toggle) {
    toggle.addEventListener('change', function() {
        aplicarTema(toggle.checked);
    });
}

if (toggleVidrio) {
    toggleVidrio.addEventListener('change', function() {
        aplicarVidrio(toggleVidrio.checked);
    });
}

opcionesTema.forEach(function(opcion) {
    opcion.addEventListener('click', function() {
        if (opcion.getAttribute('data-theme-option') === 'dark') {
            aplicarTema(true);
        } else {
            aplicarTema(false);
        }
    });
});

opcionesAcento.forEach(function(opcion) {
    opcion.addEventListener('click', function() {
        aplicarAcento(opcion.getAttribute('data-accent'));
    });
});

aplicarAcento(localStorage.getItem('colorAcento'));

if (localStorage.getItem('modoOscuro') === 'true') {
    aplicarTema(true);
} else {
    aplicarTema(false);
}

aplicarEstadoVidrio();
