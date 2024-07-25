<?php
session_start();
include('dbconnection.php');
if (!empty($_POST)) {
    $msg = "";
    if (($_POST['action'] == 'edit') && !empty($_POST['id'])) {
        // echo $_POST['first_name'];
        $eid = $_POST['id'];
        $nombre_material = $_POST['nombre_material'];
        $Id_Unidad = $_POST['nombre_unidad'];
        $costo_unidad = $_POST['costo_unidad'];
        $cantidad_material = $_POST['cantidad_material'];
        $Id_Usuario = $_SESSION['idUser'];
        $nombre_unidad = "";

        $query = mysqli_query($con, "SELECT * FROM material WHERE ( Nombre_Material = '$nombre_material' AND  Id_Material != '$eid')");

        $result = mysqli_fetch_array($query);
        if ($result > 0) {
            $msg = 'El material ya esta registrado!';
            $response = array(
                'status' => 0,
                'msg' => $msg,
            );
        } else {
            // $sql = "update cliente set CI_Cliente = '$ci_cliente', Nombre_Cliente = '$nombre_cliente', Apellido_Cliente = '$apellido_cliente', Telefono_Cliente = '$telefono_cliente', Direccion_Cliente = '$direccion_cliente', Id_Usuario = '$id_usuario' where Id_Cliente = '$eid'";
            $sql = "UPDATE material SET Nombre_Material = '$nombre_material', Id_Unidad = '$Id_Unidad',  CostoPorUnidad_Material = '$costo_unidad',  Existencia_Material = '$cantidad_material', Id_Usuario = '$Id_Usuario' WHERE Id_Material = '$eid'";
            $result = mysqli_query($con, $sql);

            $query_unidad = mysqli_query($con, "SELECT * FROM unidad");
            $result_unidad = mysqli_num_rows($query_unidad);
            if ($result_unidad > 0) {
                while ($unidad = mysqli_fetch_array($query_unidad)) {
                    if ($Id_Unidad == $unidad['Id_Unidad']) {
                        $nombre_unidad = $unidad['Nombre_Unidad'];
                    }
                }
            }
            $userData = array(
                'nombre_material' => $_POST['nombre_material'],
                'nombre_unidad' => $nombre_unidad,
                'costo_unidad' => $_POST['costo_unidad'],
                'cantidad_material' => $_POST['cantidad_material'],
            );
            if ($result) {
                $response = array(
                    'status' => 1,
                    'msg' => 'Datos actualizados correctamente',
                    'data' => $userData,
                );
                // echo "<script>alert('Record updated successfully')</script>;";
            } else {
                $msg = 'Error actualizando usuario:' . mysqli_error($con);
                $response = array(
                    'status' => 0,
                    'msg' => $msg,
                );
            }
        }

        echo json_encode($response);
        exit();
    } elseif (($_POST['action'] == 'delete') && !empty($_POST['id'])) {
        $rid = $_POST['id'];
        $sql = mysqli_query($con, "UPDATE material SET Estado_Material=0 WHERE Id_Material=$rid");
        // $result = mysqli_fetch_array($sql);
        if ($sql) {
            $response = array(
                'status' => 1,
                'msg' => 'Material desactivado correctamente',
                'data' => 'Inactivo',
            );
        } else {
            $msg = 'Error desactivando material:' . mysqli_error($con);
            $response = array(
                'status' => 0,
                'msg' => $msg,
                'data' => 'Activo',
            );
        }
        echo json_encode($response);
        exit();
    } elseif (($_POST['action'] == 'active') && !empty($_POST['id'])) {
        $rid = $_POST['id'];
        $sql = mysqli_query($con, "UPDATE material SET Estado_Material=1 WHERE Id_Material=$rid");

        if ($sql) {
            $response = array(
                'status' => 1,
                'msg' => 'material activado correctamente',
                'data' => 'Activo',
            );
        } else {
            $msg = 'Error activando material:' . mysqli_error($con);
            $response = array(
                'status' => 0,
                'msg' => $msg,
                'data' => 'Inactivo',
            );
        }
        echo json_encode($response);
        exit();
    } elseif (($_POST['action'] == 'search') && ($_POST['campo'] != "")) {
        $campo = $con->real_escape_string($_POST["campo"]) ?? null;
        $sql = "SELECT * FROM material INNER JOIN unidad ON material.Id_Unidad = unidad.Id_Unidad 
        WHERE Nombre_Material LIKE '%$campo%' OR Existencia_Material LIKE '%$campo%' OR Nombre_Unidad LIKE '%$campo%' OR CostoPorUnidad_Material LIKE '%$campo%' OR  Id_Material LIKE '%$campo%' OR Estado_Material LIKE '%$campo%'";

        $resultado = $con->query($sql);
        $num_rows = $resultado->num_rows;
        $hmlt = '';
        $cont = 1;

        if ($num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $costo_unitario = floatval($row['CostoPorUnidad_Material']);
                $cantidad = floatval($row['Existencia_Material']);
                $costo_total = number_format(($costo_unitario * $cantidad), 2);
                $hmlt .= '<tr>';
                $hmlt .= '<td>' . $cont . '</td>';
                // $hmlt .= '<td>' . $row['Foto_Usuario'] . '</td>';
                $hmlt .= '<td>' . $row['Nombre_Material'] . '</td>';
                $hmlt .= '<td>' . $row['Id_Material'] . '</td>';
                $hmlt .= '<td>' . $row['Nombre_Unidad'] . '</td>';
                $hmlt .= '<td>' . $row['CostoPorUnidad_Material'] . '</td>';
                $hmlt .= '<td>' . $row['Existencia_Material'] . '</td>';
                $hmlt .= '<td>' . $row['Nombre_Unidades'] . '</td>';
                $hmlt .= '<td>' . $costo_total . '</td>';
                if ($row['Estado_Material'] == 1) {
                    $hmlt .= '<td>Activo</td>';
                } else {
                    $hmlt .= '<td>Inactivo</td>';
                }
                $cont++;
                $hmlt .= ' ';
                // $hmlt .= '<td>
                // <button class="editBtn" style="cursor: pointer; color: #ff0060">
                // <span></span><i class="fa fa-edit"></i></span>
                // </button>
                // <button class="deleteBtn" style="cursor: pointer; color: #ff0060">
                // <span><i class="fa fa-trash"></i></span>
                // </button>
                // </td>';
                $hmlt .= '</tr>';
            }
        } else {
            $hmlt .= '<tr><td colspan="6">No se encontraron resultados</td></tr>';
        }

        echo json_encode($hmlt, JSON_UNESCAPED_UNICODE);
        exit();
    } elseif (($_POST['action']) == 'add' && !empty($_POST['id'])) {
        $rid = $_POST['id'];
        $sql = mysqli_query($con, "SELECT `Id_Material`, `Nombre_Material` FROM material WHERE `Id_Material` = $rid AND `Estado_Material` = 1");
        // mysqli_close($con);
        $result = mysqli_num_rows($sql);

        if ($result > 0) {
            while ($row = mysqli_fetch_array($sql)) {
                $userData = array(
                    'id_material' => $row['Id_Material'],
                    'nombre_material' => $row['Nombre_Material'],
                );
            }
            // $data = mysqli_fetch_assoc($sql);
            $response = array(
                'status' => 1,
                'msg' => 'Material agregado correctamente',
                'data' => $userData,
            );
        } else {
            $msg = 'Error activando cliente:' . mysqli_error($con);
            $response = array(
                'status' => 0,
                'msg' => $msg,
                'data' => ' ',
            );
        }
        echo json_encode($response);
        exit();
    } elseif (($_POST['action'] == 'addMaterial') && !empty($_POST['addId_material'])) {
        $cantidad_material = $_POST['addCantidad_material'];
        $precio_material = $_POST['addcostounidad_material'];
        $id_material = $_POST['addId_material'];
        $Id_Usuario = $_SESSION['idUser'];
        $query_insert = mysqli_query($con, "INSERT INTO `kardex`(`Id_Material`, `Id_Usuario`, `Desc_K`, `Cantidad_Ent_K`,`Valor_Ent_K`) 
        VALUES ('$id_material','$Id_Usuario', 'Entrada de material', '$cantidad_material', '$precio_material')");
        // mysqli_close($con);
        // echo $query_insert;
        // exit();
        if ($query_insert) {
            $get_id_karex = mysqli_query($con, "SELECT `Id_Kardex` FROM kardex ORDER BY `Id_Kardex` DESC LIMIT 1");

            $result_id_kardex = mysqli_num_rows($get_id_karex);
            if ($result_id_kardex > 0) {
                while ($row = mysqli_fetch_array($get_id_karex)) {
                    $id_kardex = $row['Id_Kardex'];
                }
            }

            $query_update = mysqli_query($con, "CALL actualizar_precio_material($cantidad_material, $precio_material, $id_material)");
            $result = mysqli_num_rows($query_update);

            if ($result > 0) {
                while ($row = mysqli_fetch_array($query_update)) {
                    $userData = array(
                        'nueva_existencia' => $row['nueva_existencia'],
                        'nuevo_precio' => $row['nuevo_precio'],
                    );
                    $nueva_existencia = $row['nueva_existencia'];
                    $nuevo_precio = $row['nuevo_precio'];
                    $nuevo_total = $row['nuevo_total'];
                }
                mysqli_close($con);
                include('dbconnection.php');

                $query_update_kardex = "UPDATE kardex SET Cantidad_Saldo_k = '$nueva_existencia', Valor_Saldo_K = '$nuevo_total', Valor_Unit_K = '$nuevo_precio' 
                WHERE Id_Kardex = '$id_kardex'";
                $result_kardex = mysqli_multi_query($con, $query_update_kardex);

                $response = array(
                    'status' => 1,
                    'msg' => 'Material agregado correctamente',
                    'data' => $userData,
                );
            } else {
                $msg = 'Error agregando material:' . mysqli_error($con);
                $response = array(
                    'status' => 0,
                    'msg' => $msg,
                    'data' => ' ',
                );
            }
        } else {
            $msg = 'Error agregando material:' . mysqli_error($con);
            $response = array(
                'status' => 0,
                'msg' => $msg,
                'data' => ' ',
            );
        }
        echo json_encode($response);
        exit();
    }
}
