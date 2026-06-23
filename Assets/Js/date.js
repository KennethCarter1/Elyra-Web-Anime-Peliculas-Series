document.addEventListener("DOMContentLoaded", function () {

const dia = document.getElementById("dia");
const mes = document.getElementById("mes");
const anio = document.getElementById("anio");

if (!dia || !mes || !anio) {
  return;
}


for (let i = 2008; i >= 1926; i--) {
  let opcion = document.createElement("option");
  opcion.value = i;
  opcion.text = i;
  anio.appendChild(opcion);
}

function generarDias(diaSeleccionado = "") {

  if (mes.value === "") {
    dia.innerHTML = `<option value="" disabled selected>Día</option>`;
    return;
  }

  let mesSeleccionado = parseInt(mes.value);
  let añoSeleccionado = parseInt(anio.value) || 2000;

  let diasMes = [
    31,
    (añoSeleccionado % 4 === 0 && añoSeleccionado % 100 !== 0) || (añoSeleccionado % 400 === 0) ? 29 : 28,
    31,30,31,30,31,31,30,31,30,31
  ];

  let totalDias = diasMes[mesSeleccionado];

  let diaActual = parseInt(diaSeleccionado);
  if (isNaN(diaActual)) {
    diaActual = parseInt(dia.value);
  }

  if (isNaN(diaActual) && typeof dia.dataset.valor !== "undefined" && dia.dataset.valor !== "") {
    diaActual = parseInt(dia.dataset.valor);
  }

  dia.innerHTML = `<option value="" disabled selected>Día</option>`;

  for (let i = 1; i <= totalDias; i++) {
    let opcion = document.createElement("option");
    opcion.value = i;
    opcion.text = i;


    if (i === diaActual) {
      opcion.selected = true;
    }

    dia.appendChild(opcion);
  }

  if (diaActual > totalDias) {
    dia.value = "";
  }
}

function colocarFechaInicial() {
  const diaInicial = dia.dataset.valor || "";
  const mesInicial = mes.dataset.valor || "";
  const anioInicial = anio.dataset.valor || "";

  if (anioInicial !== "") {
    anio.value = anioInicial;
  } else {
    anio.value = "";
  }

  if (mesInicial !== "") {
    mes.value = mesInicial;
  } else {
    mes.value = "";
  }

  generarDias(diaInicial);

  if (diaInicial !== "") {
    dia.value = diaInicial;
  } else {
    dia.value = "";
  }
}


mes.addEventListener("change", generarDias);
anio.addEventListener("change", generarDias);

const formularioFecha = dia.closest("form");
if (formularioFecha) {
  formularioFecha.addEventListener("reset", function () {
    setTimeout(function () {
      colocarFechaInicial();
    }, 0);
  });
}

const botonRestaurar = document.querySelector("[data-restaurar-formulario='editar-perfil']");
if (botonRestaurar && formularioFecha) {
  botonRestaurar.addEventListener("click", function () {
    formularioFecha.reset();
    colocarFechaInicial();
  });
}

colocarFechaInicial();

});
