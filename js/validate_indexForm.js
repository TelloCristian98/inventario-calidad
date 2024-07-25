var userError = document.getElementById("userError");
var paswordError = document.getElementById("paswordError");
var submitError = document.getElementById("submit-error");

function validateUser(params) {
  var user = document.getElementById("user").value;
  if (user.length == 0) {
    userError.innerHTML = "Usuario es requerido";
    return false;
  }
  if (user.length <= 3) {
    userError.innerHTML = "Usuario debe tener mas de tres caracteres";
    return false;
  }
  if (!user.match(/^[a-zA-Z0-9]+$/)) {
    userError.innerHTML = "Solo se permite letras y/o numeros";
    return false;
  }
  userError.innerHTML = "<i class='fa fa-check-circle'></i>";
  return true;
}

function validatePassword(params) {
  var password = document.getElementById("password").value;
  if (password.length == 0) {
    paswordError.innerHTML = "Contraseña es requerida";
    return false;
  }
  if (password.length <= 3) {
    paswordError.innerHTML = "Contraseña debe tener mas de tres caracteres";
    return false;
  }
  if (!password.match(/^[a-zA-Z0-9]+$/)) {
    paswordError.innerHTML = "Solo se permite letras y/o numeros";
    return false;
  }
  paswordError.innerHTML = "<i class='fa fa-check-circle'></i>";
  return true;
}

function validateForm(params) {
  if (!validateUser() || !validatePassword()) {
    submitError.style.display = "block";
    submitError.innerHTML = "Por favor corrija los errores para continuar";
    setTimeout(function () {
      submitError.style.display = "none";
      submitError.style.backgroundColor = "white";
      submitError.style.borderRadius = "5px";
    }, 3000);
    return false;
  }
}
