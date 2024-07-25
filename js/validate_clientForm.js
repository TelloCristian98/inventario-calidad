var nombre_cliente_error = document.getElementById("nombre_cliente_error");
var apellido_cliente_error = document.getElementById("apellido_cliente_error");
var ci_cliente_error = document.getElementById("ci_cliente_error");
var telefono_cliente_error = document.getElementById("telefono_cliente_error");
var direccion_cliente_error = document.getElementById(
  "direccion_cliente_error"
);
var submit_cliente_error = document.getElementById("submit_cliente_error");

function validate_nombre_cliente(params) {
  var nombre_cliente = document.getElementById("nombre_cliente").value;
  if (nombre_cliente.length == 0) {
    nombre_cliente_error.innerHTML = "Nombre de cliente es requerido";
    return false;
  }
  if (nombre_cliente.length <= 3) {
    nombre_cliente_error.innerHTML =
      "Nombre del cliente debe tener mas de tres caracteres";
    return false;
  }
  if (!nombre_cliente.match(/^[a-zA-Z]+$/)) {
    nombre_cliente_error.innerHTML = "Solo se permiten letras";
    return false;
  }
  nombre_cliente_error.innerHTML = "<i class='fa fa-check-circle'></i>";
  return true;
}
function validate_apellido_cliente(params) {
  var apellido_cliente = document.getElementById("apellido_cliente").value;
  if (apellido_cliente.length == 0) {
    apellido_cliente_error.innerHTML = "Apellido de cliente es requerido";
    return false;
  }
  if (apellido_cliente.length <= 3) {
    apellido_cliente_error.innerHTML =
      "Apellido del cliente debe tener mas de tres caracteres";
    return false;
  }
  if (!apellido_cliente.match(/^[a-zA-Z]+$/)) {
    apellido_cliente_error.innerHTML = "Solo se permiten letras";
    return false;
  }
  apellido_cliente_error.innerHTML = "<i class='fa fa-check-circle'></i>";
  return true;
}
function validate_ci_cliente(params) {
  var ci_cliente = document.getElementById("ci_cliente").value;
  if (ci_cliente.length == 0) {
    ci_cliente_error.innerHTML = "Cedula de identidad es requerida";
    return false;
  }
  if (!ci_cliente.match(/^[0-9]{10}$/)) {
    ci_cliente_error.innerHTML = "Formato invalido";
    return false;
  }
  ci_cliente_error.innerHTML = "<i class='fa fa-check-circle'></i>";
  return true;
}
function validate_telefono_cliente(params) {
  var telefono_cliente = document.getElementById("telefono_cliente").value;
  if (telefono_cliente.length == 0) {
    telefono_cliente_error.innerHTML = "Numero de telefono es requerido";
    return false;
  }
  if (telefono_cliente.length < 10) {
    telefono_cliente_error.innerHTML =
      "El numero de telefono debe tener diez caracteres";
    return false;
  }
  if (!telefono_cliente.match(/^[0-9]+$/)) {
    telefono_cliente_error.innerHTML = "Solo se permiten numeros";
    return false;
  }
  telefono_cliente_error.innerHTML = "<i class='fa fa-check-circle'></i>";
  return true;
}
function validate_direccion_cliente(params) {
  var direccion_cliente = document.getElementById("direccion_cliente").value;
  if (direccion_cliente.length == 0) {
    direccion_cliente_error.innerHTML = "La direccion es requerida";
    return false;
  }
  if (direccion_cliente.length <= 5) {
    direccion_cliente_error.innerHTML =
      "La direccion debe tener mas de cinco caracteres";
    return false;
  }
  direccion_cliente_error.innerHTML = "<i class='fa fa-check-circle'></i>";
  return true;
}
function validate_submit_cliente(params) {
  if (
    !validate_nombre_cliente() &&
    !validate_apellido_cliente() &&
    !validate_ci_cliente() &&
    !validate_cliente() &&
    !validate_direccion_cliente()
  ) {
    submit_cliente_error.innerHTML =
      "Por favor corrija los errores para continuar";
    setTimeout(function () {
      submit_cliente_error.style.display = "none";
    }, 3000);
    // document.getElementById("submit_cliente").disabled = true;
    return false;
  }
  // else {
  //   document.getElementById("submit_cliente").disabled = false;
  //   return true;
  // }
}
