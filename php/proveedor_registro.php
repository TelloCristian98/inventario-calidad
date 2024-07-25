<?php
session_start();
include('dbconnection.php');
if (!empty($_POST)) {
    $nombre_proveedor = $_POST['nombre_proveedor'];
    $direccion_proveedor = $_POST['direccion_proveedor'];
    $telefono_proveedor = $_POST['telefono_proveedor'];
    $email_proveedor = $_POST['email_proveedor'];

    $query_insert = mysqli_query($con, "INSERT INTO proveedores (Nombre_Proveedor, Direccion_Proveedor, Telefono_Proveedor, Email_Proveedor) 
                VALUES ('$nombre_proveedor', '$direccion_proveedor', '$telefono_proveedor', '$email_proveedor')");
    if ($query_insert) {
        header('Location: proveedor_panel.php');
    } else {
        $error = mysqli_error($con);
        echo ("Error description: " . $error);
        echo "<script>alert('Algo salio mal. Intentalo de nuevo');</script>";
    }
}
