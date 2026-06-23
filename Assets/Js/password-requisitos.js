document.addEventListener("DOMContentLoaded", function () {
  const inputNuevaContrasena = document.getElementById("nueva_contrasena");

  if (!inputNuevaContrasena) {
    return;
  }

  const requisitos = {
    longitud: document.querySelector('[data-requisito="longitud"]'),
    mayuscula: document.querySelector('[data-requisito="mayuscula"]'),
    minuscula: document.querySelector('[data-requisito="minuscula"]'),
    numero: document.querySelector('[data-requisito="numero"]'),
    especial: document.querySelector('[data-requisito="especial"]')
  };

  function cambiarEstado(requisito, cumple) {
    if (!requisito) {
      return;
    }

    const icono = requisito.querySelector("i");

    if (cumple) {
      requisito.classList.add("cumplido");

      if (icono) {
        icono.classList.remove("fa-regular");
        icono.classList.remove("fa-circle");
        icono.classList.add("fa-solid");
        icono.classList.add("fa-circle-check");
      }
    } else {
      requisito.classList.remove("cumplido");

      if (icono) {
        icono.classList.remove("fa-solid");
        icono.classList.remove("fa-circle-check");
        icono.classList.add("fa-regular");
        icono.classList.add("fa-circle");
      }
    }
  }

  function revisarContrasena() {
    const contrasena = inputNuevaContrasena.value;

    cambiarEstado(requisitos.longitud, contrasena.length >= 15);
    cambiarEstado(requisitos.mayuscula, /[A-ZÁÉÍÓÚÑ]/u.test(contrasena));
    cambiarEstado(requisitos.minuscula, /[a-záéíóúñ]/u.test(contrasena));
    cambiarEstado(requisitos.numero, /[0-9]/.test(contrasena));
    cambiarEstado(requisitos.especial, /[^A-Za-z0-9ÁÉÍÓÚáéíóúÑñ]/u.test(contrasena));
  }

  inputNuevaContrasena.addEventListener("input", revisarContrasena);
  revisarContrasena();
});
