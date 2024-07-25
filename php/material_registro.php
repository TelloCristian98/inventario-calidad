<?php
session_start();
include('dbconnection.php');
if (!empty($_POST)) {
    $nombre_material = $_POST['nombre_material'];
    $costo_unidad = $_POST['costo_unidad'];
    $existencia_material = $_POST['existencia_material'];
    $Id_Unidad = $_POST['unidad_material'];
    $Id_Usuario = $_SESSION['idUser'];

    $query = mysqli_query($con, "SELECT * FROM material WHERE Nombre_Material = '$nombre_material'");
    $result = mysqli_num_rows($query);
    if ($result > 0) {
        echo "<script>alert('El material ya existe. Intentalo de nuevo con otro material');</script>";
    } else {
        $query_insert = mysqli_query($con, "INSERT INTO material (Nombre_Material,  CostoPorUnidad_Material, Existencia_Material, Id_Usuario, Id_Unidad)
        VALUES ('$nombre_material', '$costo_unidad', '$existencia_material', '$Id_Usuario', '$Id_Unidad')");
        if ($query_insert) {
            header('Location: materiales_panel.php');
        } else {
            $error = mysqli_error($con);
            echo ("Error description: " . $error);
            echo "<script>alert('Algo salio mal. Intentalo de nuevo');</script>";
        }
    }
}
