<?php

include('php\dbconnection.php');
include('php\alert_msg.php');
$alert = '';
session_start();
include('php\user_session.php');
if ($alert != '') {
  alertMsg($alert);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Iniciar Sesion</title>
  <link rel="stylesheet" href="css/index_style.css" />
  <link rel="stylesheet" href="assets/fontawesome/css/all.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>

<body>

  <div class="container">
    <div class="screen">
      <div class="screen__content">
        <form class="login" id="login_form" method="post" action="">
          <div class="login__field">
            <i class="login__icon fas fa-user"></i>
            <input type="text" name="user" id="user" onkeyup="validateUser()" class="login__input" placeholder="Usuario" />
            <span id="userError"></span>
          </div>
          <div class="login__field">
            <i class="login__icon fas fa-lock"></i>
            <input type="password" name="password" id="password" onkeyup="validatePassword()" class="login__input" placeholder="Contraseña" />
            <span id="paswordError"></span>
          </div>
          <button class="button login__submit" id="submit" onclick="return validateForm()">
            <span class="button__text">Ingresar</span>
            <i class="button__icon fas fa-chevron-right"></i>
          </button>
          <span id="submit-error"></span>
        </form>
      </div>
      <div class="screen__background">
        <img src="img/logo.png" alt="logo" height="100px" width="100px" />
        <span class="screen__background__shape screen__background__shape4">
        </span>
        <span class="screen__background__shape screen__background__shape3"></span>
        <span class="screen__background__shape screen__background__shape2"></span>
        <span class="screen__background__shape screen__background__shape1"></span>
      </div>
    </div>
  </div>
  <script src="js/validate_indexForm.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
</body>

</html>