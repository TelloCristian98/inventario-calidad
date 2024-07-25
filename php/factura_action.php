<?php
session_start();
include('dbconnection.php');
if (!empty($_POST)) {
    if (($_POST['action']) == 'searchCliente' && !empty($_POST['id'])) {
        $id = $_POST['id'];
        $query = "SELECT * FROM cliente WHERE CI_Cliente = '$id' AND Estado_Cliente = 1";
        $sql = mysqli_query($con, $query);
        $result = mysqli_num_rows($sql);
        if ($result > 0) {
            while ($data = mysqli_fetch_assoc($sql)) {
                $userData = array(
                    'nombre_cliente' => $data['Nombre_Cliente'],
                    'apellido_cliente' => $data['Apellido_Cliente'],
                    'telefono_cliente' => $data['Telefono_Cliente'],
                    'direccion_cliente' => $data['Direccion_Cliente'],
                );
            }
            $response = array(
                'status' => 1,
                'msg' => 'Cliente encontrados correctamente',
                'data' => $userData,
            );
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            $msg = 'No data:';
            $response = array(
                'status' => 0,
                'msg' => $msg,
            );
            echo json_encode($response);
            exit();
        }
    } elseif (($_POST['action']) == 'searchProducto' && !empty($_POST['cod'])) {
        $cod = $con->real_escape_string($_POST["cod"]) ?? null;
        $query = "SELECT * FROM producto WHERE Id_Producto = '$cod' AND Estado_Prod = 1";
        $result = mysqli_query($con, $query);
        if ($result) {
            $result_prod = mysqli_num_rows($result);
            if ($result_prod > 0) {
                while ($data = mysqli_fetch_assoc($result)) {
                    $userData = array(
                        'nombre_producto' => $data['Desc_Producto'],
                        'existencia_producto' => $data['Existencia_Prod'],
                        'costo_producto' => $data['PrecioUnit_Prod'],
                    );
                }
                $response = array(
                    'status' => 1,
                    'msg' => 'Producto encontrado correctamente',
                    'data' => $userData,
                );
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                $msg = 'No data:';
                $response = array(
                    'status' => 0,
                    'msg' => $msg,
                );
                echo json_encode($response);
                exit();
            }
        } else {
            $msg = 'No data:';
            $response = array(
                'status' => 0,
                'msg' => $msg,
            );
            echo json_encode($response);
            exit();
        }
    } elseif (($_POST['action']) == 'agregarProductoTemp' && !empty($_POST['cod'])) {
        if (!empty($_POST['cantidad'])) {


            $codproducto = $_POST['cod'];
            $cantidad = $_POST['cantidad'];
            $token = $_SESSION['idUser'];
            $query_factura_temp = mysqli_query($con, "CALL add_factura_temp($codproducto,$cantidad,'$token')");
            $result = mysqli_num_rows($query_factura_temp);
            $detalleTabla = '';
            $total = 0;
            $cont = 1;
            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query_factura_temp)) {
                    $precio_total = round($data['Cantidad_Factura_Temp'] * $data['Precio_Venta_Temp'], 2);
                    $total = round($total + $precio_total, 2);
                    $detalleTabla .= '
                <tr>
                <th scope="row">' . $cont . '</th>
                <td>' . $data['Id_Factura_Temp_Prod'] . '</td>
                <td colspan="2">' . $data['Desc_Prod_Fac_Temp'] . '</td>
                <td>' . $data['Cantidad_Factura_Temp'] . '</td>
                <td>' . $data['Precio_Venta_Temp'] . '</td>
                <td>' . $precio_total . '</td>
                <td>
                <button type="button" id="btn_delete_producto" style="cursor: pointer;font-size: 18px" onclick="event.preventDefault();del_producto_detalle(' . $data['Id_Factura_Temp'] . ');"><span><i style="color: #ff0060;background-color:none" class="fa fa-trash"></i></span></button>
                </td>
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
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                $msg = 'No data:';
                $response = array(
                    'status' => 0,
                    'msg' => $msg,
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
    } elseif (($_POST['action']) == 'delProductTemp' && !empty($_POST['id'])) {
        $id = $_POST['id'];
        $token = $_SESSION['idUser'];
        $query_factura_temp = mysqli_query($con, "CALL del_factura_temp($id,'$token')");
        $result = mysqli_num_rows($query_factura_temp);
        $detalleTabla = '';
        $total = 0;
        $cont = 1;
        if ($result >= 0) {
            while ($data = mysqli_fetch_assoc($query_factura_temp)) {
                $precio_total = round($data['Cantidad_Factura_Temp'] * $data['Precio_Venta_Temp'], 2);
                $total = round($total + $precio_total, 2);
                $detalleTabla .= '
                <tr>
                <th scope="row">' . $cont . '</th>
                <td>' . $data['Id_Factura_Temp_Prod'] . '</td>
                <td colspan="2">' . $data['Desc_Prod_Fac_Temp'] . '</td>
                <td>' . $data['Cantidad_Factura_Temp'] . '</td>
                <td>' . $data['Precio_Venta_Temp'] . '</td>
                <td>' . $precio_total . '</td>
                <td>
                <button type="button" id="btn_delete_producto" style="cursor: pointer;font-size: 18px" onclick="event.preventDefault();del_producto_detalle(' . $data['Id_Factura_Temp'] . ');"><span><i style="color: #ff0060;background-color:none" class="fa fa-trash"></i></span></button>
                </td>
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
                'msg' => 'Producto eliminado correctamente',
                'data' => $userData,
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
    }
}
