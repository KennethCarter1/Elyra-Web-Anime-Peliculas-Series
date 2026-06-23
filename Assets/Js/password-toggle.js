document.addEventListener("DOMContentLoaded", function () {
  const botonesPassword = document.querySelectorAll("[data-password-toggle]");

  botonesPassword.forEach(function (boton) {
    boton.addEventListener("click", function () {
      const inputId = boton.getAttribute("data-password-toggle");
      const inputPassword = document.getElementById(inputId);
      const icono = boton.querySelector("i");

      if (!inputPassword) {
        return;
      }

      if (inputPassword.type === "password") {
        inputPassword.type = "text";
        boton.setAttribute("aria-label", "Ocultar contraseña");

        if (icono) {
          icono.classList.remove("fa-eye");
          icono.classList.add("fa-eye-slash");
        }
      } else {
        inputPassword.type = "password";
        boton.setAttribute("aria-label", "Mostrar contraseña");

        if (icono) {
          icono.classList.remove("fa-eye-slash");
          icono.classList.add("fa-eye");
        }
      }
    });
  });
});
