<?php
include('dbconnection.php');
if (isset($_FILES["image"])) {
    // $eid = $_POST['id'];
    // $sql = mysqli_query($con, "SELECT * FROM usuario WHERE Id_Usuario = '$eid'");
    // echo $_POST['id'];
    $msg = "";
    // $foto_usuario = basename($_FILES["image"]["name"]);
    $target_dir = "../img/perfil/";
    $target_file = $target_dir . $_FILES['image']['name'];
    $imageFileType = "." . $_FILES['image']['type'];

    // $msg = $target_file;
    if (!empty($_FILES["image"]["name"])) {
        if (file_exists($target_file)) {
            $msg = "La imagen ya existe. Intentalo de nuevo con otra imagen";
        }
        if (!(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file))) {
            $msg = "Algo salio mal al guardar la imagen. Intentalo de nuevo";
        }
        // $foto_usuario = basename($_FILES["foto_usuario"]["name"], $imageFileType);
    }
    echo $msg;
}
