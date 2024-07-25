var nombre_material_error = document.getElementById("nombre_material_error");
var costo_unidad_error = document.getElementById("costo_unidad_error");
var existencia_material_error = document.getElementById(
  "existencia_material_error"
);
var submit_material_error = document.getElementById("submit_material_error");

function validate_nombre_material(params) {
  var nombre_material = document.getElementById("nombre_material").value;
  if (nombre_material.length == 0) {
    nombre_material_error.innerHTML = "Nombre del material es requerido";
    return false;
  }
  if (nombre_material.length <= 3) {
    nombre_material_error.innerHTML =
      "Nombre del material debe tener mas de tres caracteres";
    return false;
  }
  if (nombre_material.match(/[\'";\\;%<>&\(\)\[\]{}]/)) {
    nombre_material_error.innerHTML = "Caracter no permitido";
    return false;
  }
  nombre_material_error.innerHTML = "<i class='fa fa-check-circle'></i>";
  return true;
}
function validate_costo_unidad() {
  var costo_unidad = document.getElementById("costo_unidad").value;
  if (costo_unidad.length == 0) {
    costo_unidad_error.innerHTML = "Costo del material es requerido";
    return false;
  }
  if (
    !costo_unidad.match(
      /^(?!0\.00$)(?!0$)(?!1\.$)(?!2\.$)[0-9]\d*(?:\.\d{1,2})?$/
    )
  ) {
    costo_unidad_error.innerHTML =
      "Solo se permiten numeros positivos con dos decimales max";
    return false;
  }
  costo_unidad_error.innerHTML = "<i class='fa fa-check-circle'></i>";
  return true;
}
function validate_existencia_material(params) {
  var existencia_material = document.getElementById(
    "existencia_material"
  ).value;
  if (existencia_material.length == 0) {
    existencia_material_error.innerHTML = "Cantidad del material es requerida";
    return false;
  }
  if (
    !existencia_material.match(
      /^(?!0\.00$)(?!0$)(?!1\.$)(?!2\.$)[0-9]\d*(?:\.\d{1,2})?$/
    )
  ) {
    existencia_material_error.innerHTML =
      "Solo se permiten numeros positivos con dos decimales max";
    return false;
  }
  existencia_material_error.innerHTML = "<i class='fa fa-check-circle'></i>";
  return true;
}
function validate_submit_material(params) {
  if (
    !validate_nombre_material() &&
    !validate_costo_unidad() &&
    !validate_existencia_material()
  ) {
    submit_material_error.innerHTML =
      "Por favor corrija los errores para continuar";
    setTimeout(function () {
      submit_material_error.style.display = "none";
    }, 3000);
    // document.getElementById("submit_cliente").disabled = true;
    return false;
  }
  // else {
  //   document.getElementById("submit_cliente").disabled = false;
  //   return true;
  // }
}
