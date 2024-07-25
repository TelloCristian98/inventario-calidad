<?php
// session_start();
include('dbconnection.php');
if (!empty($_POST)) {
    $ci_cliente = $_POST['ci_cliente'];
    $nombre_cliente = $_POST['nombre_cliente'];
    $apellido_cliente = $_POST['apellido_cliente'];
    $telefono_cliente = $_POST['telefono_cliente'];
    $direccion_cliente = $_POST['direccion_cliente'];
    // $Id_Usuario = $_SESSION['idUser'];
    $Id_Usuario = 10000;

    $query = mysqli_query($con, "SELECT * FROM cliente WHERE CI_Cliente = '$ci_cliente'");
    $result = mysqli_num_rows($query);
    if ($result > 0) {
        echo "<script>alert('El cliente ya existe. Intentalo de nuevo con otra cedula de identidad');</script>";
    } else {
        $query_insert = mysqli_query($con, "INSERT INTO cliente (CI_Cliente, Nombre_Cliente, Apellido_Cliente, Telefono_Cliente, Direccion_Cliente, Id_Usuario) 
        VALUES ('$ci_cliente', '$nombre_cliente', '$apellido_cliente', '$telefono_cliente', '$direccion_cliente', '$Id_Usuario')");
        if ($query_insert) {
            header('Location: clientes_panel.php');
        } else {
            $error = mysqli_error($con);
            echo ("Error description: " . $error);
            echo "<script>alert('Algo salio mal. Intentalo de nuevo');</script>";
        }
    }
}
