<?php
include('dbconnection.php');

if (isset($_POST['action'])) {

    if ($_POST['action'] == 'create') {
        $nombre_proveedor = $_POST['Nombre_Proveedor'];
        $direccion_proveedor = $_POST['Direccion_Proveedor'];
        $telefono_proveedor = $_POST['Telefono_Proveedor'];
        $email_proveedor = $_POST['Email_Proveedor'];

        $sql = "INSERT INTO proveedores (Nombre_Proveedor, Direccion_Proveedor, Telefono_Proveedor, Email_Proveedor) 
                VALUES ('$nombre_proveedor', '$direccion_proveedor', '$telefono_proveedor', '$email_proveedor')";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $response = array(
                'status' => 1,
                'msg' => 'Proveedor registrado correctamente',
                'data' => $_POST,
            );
        } else {
            $msg = 'Error registrando proveedor: ' . mysqli_error($con);
            $response = array(
                'status' => 0,
                'msg' => $msg,
            );
        }
        echo json_encode($response);
        exit();
    } elseif ($_POST['action'] == 'update') {
        $nombre_proveedor = $_POST['Nombre_Proveedor'];
        $direccion_proveedor = $_POST['Direccion_Proveedor'];
        $telefono_proveedor = $_POST['Telefono_Proveedor'];
        $email_proveedor = $_POST['Email_Proveedor'];

        $id_proveedor = $_POST['id_proveedor'];
        $sql = "UPDATE proveedores 
                SET Nombre_Proveedor = '$nombre_proveedor', Direccion_Proveedor = '$direccion_proveedor', 
                    Telefono_Proveedor = '$telefono_proveedor', Email_Proveedor = '$email_proveedor' 
                WHERE Id_Proveedor = $id_proveedor";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $response = array(
                'status' => 1,
                'msg' => 'Proveedor actualizado correctamente',
                'data' => $_POST,
            );
        } else {
            $msg = 'Error actualizando proveedor: ' . mysqli_error($con);
            $response = array(
                'status' => 0,
                'msg' => $msg,
            );
        }
        echo json_encode($response);
        exit();
    } elseif ($_POST['action'] == 'delete') {
        $id_proveedor = $_POST['id'];
        $sql = "DELETE FROM proveedores WHERE Id_Proveedor = $id_proveedor";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $response = array(
                'status' => 1,
                'msg' => 'Proveedor eliminado correctamente',
            );
        } else {
            $msg = 'Error eliminando proveedor: ' . mysqli_error($con);
            $response = array(
                'status' => 0,
                'msg' => $msg,
            );
        }
        echo json_encode($response);
        exit();
    } elseif ($_POST['action'] == 'search') {
        $campo = $con->real_escape_string($_POST["campo"]) ?? null;
        $sql = "SELECT * FROM proveedores WHERE Nombre_Proveedor LIKE '%$campo%' OR Direccion_Proveedor LIKE '%$campo%' OR Telefono_Proveedor LIKE '%$campo%' OR Email_Proveedor LIKE '%$campo%'";
        $resultado = $con->query($sql);
        $num_rows = $resultado->num_rows;
        $hmlt = '';
        $cont = 1;

        if ($num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $hmlt .= '<tr>';
                $hmlt .= '<td>' . $cont . '</td>';
                // $hmlt .= '<td>' . $row['Foto_Usuario'] . '</td>';
                $hmlt .= '<td>' . $row['Nombre_Proveedor'] . '</td>';
                $hmlt .= '<td>' . $row['Direccion_Proveedor'] . '</td>';
                $hmlt .= '<td>' . $row['Telefono_Proveedor'] . '</td>';
                $hmlt .= '<td>' . $row['Email_Proveedor'] . '</td>';
                if (1) {
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
    }
}
