var nombre_usuario_error = document.getElementById("nombre_usuario_error");
var apellido_usuario_error = document.getElementById("apellido_usuario_error");
var correo_usuario_error = document.getElementById("correo_usuario_error");
var usuario_error = document.getElementById("usuario_error");
var clave_usuario_error = document.getElementById("clave_usuario_error");
var submit_usuario_error = document.getElementById("submit_usuario_error");

function validate_nombre_usuario(params) {
  var nombre_usuario = document.getElementById("nombre_usuario").value;
  if (nombre_usuario.length == 0) {
    nombre_usuario_error.innerHTML = "Nombre de usuario es requerido";
    return false;
  }
  if (nombre_usuario.length <= 3) {
    nombre_usuario_error.innerHTML =
      "Nombre del usuario debe tener mas de tres caracteres";
    return false;
  }
  if (!nombre_usuario.match(/^[a-zA-Z]+$/)) {
    nombre_usuario_error.innerHTML = "Solo se permiten letras";
    return false;
  }
  nombre_usuario_error.innerHTML = "<i class='fa fa-check-circle'></i>";
  return true;
}
function validate_apellido_usuario(params) {
  var apellido_usuario = document.getElementById("apellido_usuario").value;
  if (apellido_usuario.length == 0) {
    apellido_usuario_error.innerHTML = "Apellido de usuario es requerido";
    return false;
  }
  if (apellido_usuario.length <= 3) {
    apellido_usuario_error.innerHTML =
      "Apellido del usuario debe tener mas de tres caracteres";
    return false;
  }
  if (!apellido_usuario.match(/^[a-zA-Z]+$/)) {
    apellido_usuario_error.innerHTML = "Solo se permiten letras";
    return false;
  }
  apellido_usuario_error.innerHTML = "<i class='fa fa-check-circle'></i>";
  return true;
}
function validate_correo_usuario(params) {
  var correo_usuario = document.getElementById("correo_usuario").value;
  if (correo_usuario.length == 0) {
    correo_usuario_error.innerHTML = "Correo es requerido";
    return false;
  }
  if (
    !correo_usuario.match(
      /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/
    )
  ) {
    correo_usuario_error.innerHTML = "Formato invalido";
    return false;
  }
  correo_usuario_error.innerHTML = "<i class='fa fa-check-circle'></i>";
  return true;
}
function validate_usuario(params) {
  var usuario = document.getElementById("usuario").value;
  if (usuario.length == 0) {
    usuario_error.innerHTML = "Usuario es requerido";
    return false;
  }
  if (usuario.length <= 3) {
    usuario_error.innerHTML = "Usuario debe tener mas de tres caracteres";
    return false;
  }
  if (!usuario.match(/^[a-zA-Z0-9]+$/)) {
    usuario_error.innerHTML = "Solo se permite letras y/o numeros";
    return false;
  }
  usuario_error.innerHTML = "<i class='fa fa-check-circle'></i>";
  return true;
}
function validate_clave_usuario(params) {
  var clave_usuario = document.getElementById("clave_usuario").value;
  if (clave_usuario.length == 0) {
    clave_usuario_error.innerHTML = "La clave es requerida";
    return false;
  }
  if (clave_usuario.length <= 3) {
    clave_usuario_error.innerHTML =
      "La clave debe tener mas de tres caracteres";
    return false;
  }
  if (!clave_usuario.match(/^[a-zA-Z0-9]+$/)) {
    clave_usuario_error.innerHTML = "Solo se permite letras y/o numeros";
    return false;
  }
  clave_usuario_error.innerHTML = "<i class='fa fa-check-circle'></i>";
  return true;
}

function validate_submit_usuario(params) {
  if (
    !validate_nombre_usuario() &&
    !validate_apellido_usuario() &&
    !validate_correo_usuario() &&
    !validate_usuario() &&
    !validate_clave_usuario()
  ) {
    submit_usuario_error.innerHTML =
      "Por favor corrija los errores para continuar";
    setTimeout(function () {
      submit_usuario_error.style.display = "none";
    }, 3000);
    // document.getElementById("submit_usuario").disabled = true;
    return false;
  }
  // else {
  //   document.getElementById("submit_usuario").disabled = false;
  //   return true;
  // }
}
