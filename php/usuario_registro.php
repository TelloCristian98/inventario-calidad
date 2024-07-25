<?php
include('dbconnection.php');
if (!empty($_POST)) {
    $nombre_usuario = $_POST['nombre_usuario'];
    $apellido_usuario = $_POST['apellido_usuario'];
    $correo_usuario = $_POST['correo_usuario'];
    $usuario = $_POST['usuario'];
    $clave_usuario = md5($_POST['clave_usuario']);
    $rol_usuario = $_POST['rol_usuario'];
    $target_dir = "../img/perfil/";
    $target_file = $target_dir . basename($_FILES["foto_usuario"]["name"]);
    $uploadOk = 1;
    $imageFileType = "." . $_FILES['foto_usuario']['type'];

    if (!(move_uploaded_file($_FILES["foto_usuario"]["tmp_name"], $target_file))) {
        echo "<script>alert('Algo salio mal al guardar la imagen. Intentalo de nuevo.');</script>";
    }
    $foto_usuario = basename($_FILES["foto_usuario"]["name"], $imageFileType);

    $query = mysqli_query($con, "SELECT * FROM usuario WHERE Usuario = '$usuario' OR Correo_Usuario = '$correo_usuario'");
    $result = mysqli_fetch_array($query);
    if ($result > 0) {
        echo "<script>alert('El usuario o el correo ya estan registrados!')</script>";
    } else {
        $query_insert = mysqli_query($con, "insert into usuario(Nombre_Usuario, Apellido_Usuario, Correo_Usuario, Usuario, Clave_Usuario, Id_Rol_Us, Foto_Usuario) value('$nombre_usuario','$apellido_usuario','$correo_usuario','$usuario','$clave_usuario','$rol_usuario','$foto_usuario')");
        if ($query_insert) {
            header('location: usuarios_panel.php');
        } else {
            echo "<script>alert('Error al registrar usuario!')</script>";
            $error = mysqli_error($con);
            echo ("Error description: " . $error);
        }
    }
}
