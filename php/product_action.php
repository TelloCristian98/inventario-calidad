<?php
session_start();
include('dbconnection.php');
if (!empty($_POST)) {
    $msg = "";
    if (($_POST['action']) == 'edit' && !empty($_POST['id'])) {
        $eid = $_POST['id'];
        $nombre_producto = $_POST['nombre_producto'];
        $Id_Usuario = $_SESSION['idUser'];

        $query = mysqli_query($con, "SELECT * FROM producto WHERE ( Desc_Producto = '$nombre_producto' AND  Id_Producto != '$eid')");

        $result = mysqli_fetch_array($query);
        if ($result > 0) {
            $msg = 'El material ya esta registrado!';
            $response = array(
                'status' => 0,
                'msg' => $msg,
            );
        } else {
            // $sql = "update cliente set CI_Cliente = '$ci_cliente', Nombre_Cliente = '$nombre_cliente', Apellido_Cliente = '$apellido_cliente', Telefono_Cliente = '$telefono_cliente', Direccion_Cliente = '$direccion_cliente', Id_Usuario = '$id_usuario' where Id_Cliente = '$eid'";
            $sql = "UPDATE producto SET Desc_Producto = '$nombre_producto' WHERE Id_Producto = '$eid'";
            $result = mysqli_query($con, $sql);

            $userData = array(
                'nombre_producto' => $nombre_producto,
            );
            if ($result) {
                $response = array(
                    'status' => 1,
                    'msg' => 'Datos actualizados correctamente',
                    'data' => $userData,
                );
                echo json_encode($response);
                exit();
                // echo "<script>alert('Record updated successfully')</script>;";
            } else {
                $msg = 'Error actualizando usuario:' . mysqli_error($con);
                $response = array(
                    'status' => 0,
                    'msg' => $msg,
                );
                echo json_encode($response);
                exit();
            }
        }
    } elseif (($_POST['action'] == 'delete') && !empty($_POST['id'])) {
        $rid = $_POST['id'];
        $sql = mysqli_query($con, "UPDATE producto SET Estado_Prod=0 WHERE Id_Producto=$rid");
        // $result = mysqli_fetch_array($sql);
        if ($sql) {
            $response = array(
                'status' => 1,
                'msg' => 'Producto desactivado correctamente',
                'data' => 'Inactivo',
            );
        } else {
            $msg = 'Error desactivando producto:' . mysqli_error($con);
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
        $sql = mysqli_query($con, "UPDATE producto SET Estado_Prod=1 WHERE Id_Producto=$rid");

        if ($sql) {
            $response = array(
                'status' => 1,
                'msg' => 'producto activado correctamente',
                'data' => 'Activo',
            );
        } else {
            $msg = 'Error activando producto:' . mysqli_error($con);
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

        $sql = "SELECT * FROM material WHERE id_material = '$campo' AND Estado_Material = 1";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $result_unidad = mysqli_num_rows($result);
            if ($result_unidad > 0) {
                while ($unidad = mysqli_fetch_array($result)) {
                    $userData = array(
                        'nombre_material' => $unidad['Nombre_Material'],
                        'costo_material' => $unidad['CostoPorUnidad_Material'],
                        'cantidad_material' => $unidad['Existencia_Material'],
                    );
                }
                $response = array(
                    'status' => 1,
                    'msg' => 'Datos encontrados correctamente',
                    'data' => $userData,
                );

                echo json_encode($response);
                exit();
            }
            $msg = 'No data:';
            $response = array(
                'status' => 0,
                'msg' => $msg,
            );
            echo json_encode($response);
            exit();
        } else {
            $msg = 'No data:';
            $response = array(
                'status' => 0,
                'msg' => $msg,
            );
            echo json_encode($response);
            exit();
        }
    } elseif (($_POST['action']) == 'addMaterialTemp' && !empty($_POST['id'])) {
        if (!empty($_POST['cantidad'])) {
            $codmaterial = $_POST['id'];
            $cantidad = $_POST['cantidad'];
            $token = $_SESSION['idUser'];
            $query_producto_temp = mysqli_query($con, "CALL add_producto_temp($codmaterial,$cantidad,'$token')");
            $result = mysqli_num_rows($query_producto_temp);
            $detalleTabla = '';
            $total = 0;
            $cont = 1;
            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query_producto_temp)) {
                    $precio_total = round($data['Existencia_Prod_temp'] * $data['PrecioUnit_Prod_temp'], 2);
                    $total = round($total + $precio_total, 2);
                    $detalleTabla .= '     
                                <tr>
                                    <th scope="row">' . $cont . '</th>
                                    <td>' . $data['Id_Material_Prod_temp'] . '</td>
                                    <td colspan="2">' . $data['Nombre_Material'] . '</td>
                                    <td>' . $data['Existencia_Prod_temp'] . '</td>
                                    <td>' . $data['PrecioUnit_Prod_temp'] . '</td>
                                    <td>' . $precio_total . '</td>
                                    <td><button type="button" id="btn_delete_material" style="cursor: pointer;font-size: 18px" onclick="event.preventDefault();del_material_detalle(' . $data['Id_Producto_temp'] . ');"><span><i style="color: #ff0060;background-color:none" class="fa fa-trash"></i></span></button></td>                                    
                                </tr>';
                    $cont++;
                }
                $detalleTotal = '
                                <tr class="total-table">
                                    <th colspan="6" align="rigth">Total</th>
                                    <th>' . $total . '</th>
                                </tr>';
                $userData = array(
                    'detalle' => $detalleTabla,
                    'totales' => $detalleTotal,
                );

                $response = array(
                    'status' => 1,
                    'msg' => 'Material agregado correctamente',
                    'data' => $userData,
                );
                echo json_encode($response);
                exit();
            } else {
                $response = array(
                    'status' => 0,
                    'msg' => 'Error agregando material',
                    'data' => ' ',
                );
                echo json_encode($response);
                exit();
            }
        } else {
            $response = array(
                'status' => 0,
                'msg' => 'Error agregando material',
                'data' => ' ',
            );
            echo json_encode($response);
            exit();
        }
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
    } elseif (($_POST['action'] == 'delMaterialTemp') && !empty($_POST['id'])) {
        $id = $_POST['id'];
        $token = $_SESSION['idUser'];

        $query_producto_temp = mysqli_query($con, "CALL del_producto_temp($id,'$token')");
        $result = mysqli_num_rows($query_producto_temp);

        $detalleTabla = '';
        $total = 0;
        $cont = 1;

        if ($result >= 0) {
            while ($data = mysqli_fetch_assoc($query_producto_temp)) {
                $precio_total = round($data['Existencia_Prod_temp'] * $data['PrecioUnit_Prod_temp'], 2);
                $total = round($total + $precio_total, 2);
                $detalleTabla .= '     
                            <tr>
                                <th scope="row">' . $cont . '</th>
                                <td>' . $data['Id_Material_Prod_temp'] . '</td>
                                <td colspan="2">' . $data['Nombre_Material'] . '</td>
                                <td>' . $data['Existencia_Prod_temp'] . '</td>
                                <td>' . $data['PrecioUnit_Prod_temp'] . '</td>
                                <td>' . $precio_total . '</td>
                                <td><button type="button" style="cursor: pointer;font-size: 18px" id="btn_delete_material" onclick="event.preventDefault();del_material_detalle(' . $data['Id_Producto_temp'] . ');"><span><i style="color: #ff0060;background-color:none" class="fa fa-trash"></i></span></button></td>                                    
                            </tr>';
                $cont++;
            }
            $detalleTotal = '
                            <tr class="total-table">
                                <th colspan="6">Total</th>
                                <th>' . $total . '</th>
                            </tr>';
            $userData = array(
                'detalle' => $detalleTabla,
                'totales' => $detalleTotal,
            );
            $response = array(
                'status' => 1,
                'msg' => 'Material eliminado correctamente',
                'data' => $userData,
            );
            echo json_encode($response);
            exit();
        } else {
            $response = array(
                'status' => 0,
                'msg' => 'Error eliminando material',
                'data' => ' ',
            );
            echo json_encode($response);
            exit();
        }
    } elseif (($_POST['action'] == 'createProduct') && !empty($_POST['product_name'])) {
        $token = $_SESSION['idUser'];
        $product_name = $_POST['product_name'];
        $query = mysqli_query($con, "SELECT * FROM producto_temp WHERE Id_Usuario_Prod_temp = $token");
        $result = mysqli_num_rows($query);

        if ($result > 0) {
            $query_procesar = mysqli_query($con, "CALL procesar_producto('$token', '$product_name', 'foto.png')");
            $result_procesar = mysqli_num_rows($query_procesar);

            if ($result_procesar > 0) {
                $data = mysqli_fetch_assoc($query_procesar);
                $response = array(
                    'status' => 1,
                    'msg' => 'Producto creado correctamente',
                    'data' => $data,
                );
                echo json_encode($response);
                exit();
            } else {
                $response = array(
                    'status' => 0,
                    'msg' => 'Error creando producto',
                    'data' => ' ',
                );
                echo json_encode($response);
                exit();
            }
        } else {
            $response = array(
                'status' => 0,
                'msg' => 'Error creando producto',
                'data' => ' ',
            );
            echo json_encode($response);
            exit();
        }
    }
}
