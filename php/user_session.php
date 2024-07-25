<?php
// if (!empty($_SESSION['active'])) {
//     header('location: php\dashboard_admin.php');
// } else {
if (!empty($_POST)) {
    $user = mysqli_real_escape_string($con, $_POST['user']);
    $password = md5(mysqli_real_escape_string($con, $_POST['password']));
    $query = mysqli_query($con, "SELECT * FROM `usuario` WHERE `Usuario` = '$user' AND `Clave_Usuario`= '$password'");
    $result = mysqli_num_rows($query);
    // $alert = $password;
    // alertMsg($alert);
    if ($result > 0) {
        $data = mysqli_fetch_array($query);
        session_start();
        $_SESSION['active'] = true;
        $_SESSION['idUser'] = $data['Id_Usuario'];
        $_SESSION['nombre'] = $data['Nombre_Usuario'];
        $_SESSION['apellido'] = $data['Apellido_Usuario'];
        $_SESSION['user'] = $data['Usuario'];
        $_SESSION['rol'] = $data['Rol_Usuario'];
        $_SESSION['foto'] = $data['Foto_Usuario'];
        header('location: php\dashboard_admin.php');
    } else {
        $alert = 'El usuario no registrado';
    }
}
// }
