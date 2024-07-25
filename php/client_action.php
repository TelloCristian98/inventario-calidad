<?php
session_start();
include('dbconnection.php');
if (!empty($_POST)) {
    $msg = "";
    if (($_POST['action'] == 'edit') && !empty($_POST['id'])) {
        // echo $_POST['first_name'];
        $eid = $_POST['id'];
        $ci_cliente = $_POST['ci'];
        $nombre_cliente = $_POST['first_name'];
        $apellido_cliente = $_POST['last_name'];
        $telefono_cliente = $_POST['phone'];
        $direccion_cliente = $_POST['adress'];
        $id_usuario = $_SESSION['idUser'];



        $userData = array(
            'ci' => $_POST['ci'],
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'phone' => $_POST['phone'],
            'adress' => $_POST['adress'],
        );

        $query = mysqli_query($con, "SELECT * FROM cliente WHERE (CI_Cliente = '$ci_cliente' AND Id_Cliente != '$eid')        
        OR (Telefono_Cliente = '$telefono_cliente' AND Id_Cliente != '$eid')");

        $result = mysqli_fetch_array($query);
        if ($result > 0) {
            $msg = 'La cedula de identidad o el numero de telefono ya estan registrados!';
            $response = array(
                'status' => 0,
                'msg' => $msg,
            );
        } else {
            $sql = "update cliente set CI_Cliente = '$ci_cliente', Nombre_Cliente = '$nombre_cliente', Apellido_Cliente = '$apellido_cliente', Telefono_Cliente = '$telefono_cliente', Direccion_Cliente = '$direccion_cliente', Id_Usuario = '$id_usuario' where Id_Cliente = '$eid'";

            $result = mysqli_query($con, $sql);
            if ($result) {
                $response = array(
                    'status' => 1,
                    'msg' => 'Datos actualizados correctamente',
                    'data' => $userData,
                );
                // echo "<script>alert('Record updated successfully')</script>;";
            } else {
                $msg = 'Error actualizando usuario:' . mysqli_error($conn);
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
        $sql = mysqli_query($con, "UPDATE cliente SET Estado_Cliente=0 WHERE Id_Cliente=$rid");
        // $result = mysqli_fetch_array($sql);
        if ($sql) {
            $response = array(
                'status' => 1,
                'msg' => 'Cliente desactivado correctamente',
                'data' => 'Inactivo',
            );
        } else {
            $msg = 'Error desactivando cliente:' . mysqli_error($con);
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
        $sql = mysqli_query($con, "UPDATE cliente SET Estado_Cliente=1 WHERE Id_Cliente=$rid");

        if ($sql) {
            $response = array(
                'status' => 1,
                'msg' => 'Cliente activado correctamente',
                'data' => 'Activo',
            );
        } else {
            $msg = 'Error activando cliente:' . mysqli_error($con);
            $response = array(
                'status' => 0,
                'msg' => $msg,
                'data' => 'Inactivo',
            );
        }
        echo json_encode($response);
        exit();
    } elseif (($_POST['action'] == 'search') && ($_POST['campo'] != "")) {
        // $columns = ['Foto_Usuario', 'Nombre_Usuario', 'Apellido_Usuario', 'Correo_Usuario', 'Usuario', 'Id_Rol_Us', 'Estado_Usuario'];
        // $table = "usuario";
        $campo = $con->real_escape_string($_POST["campo"]) ?? null;
        $sql = "SELECT * FROM cliente WHERE CI_Cliente LIKE '%$campo%' OR Nombre_Cliente LIKE '%$campo%' OR Apellido_Cliente LIKE '%$campo%' OR Telefono_Cliente LIKE '%$campo%' OR Direccion_Cliente LIKE '%$campo%' OR Estado_Cliente LIKE '%$campo%'";
        // $sql = "SELECT " . implode(",", $columns) . " FROM " . $table;
        // " WHERE Nombre_Usuario LIKE '%$campo%' OR Apellido_Usuario LIKE '%$campo%' OR Correo_Usuario LIKE '%$campo%' OR Usuario LIKE '%$campo%' OR Rol LIKE '%$campo%' OR Estado_Usuario LIKE '%$campo%'";
        // $sql = "SELECT usuario.Foto_Usuario, usuario.Nombre_Usuario, usuario.Apellido_Usuario, usuario.Id_Rol_Us, usuario.Correo_Usuario, usuario.Usuario,rolusuario.Rol, usuario.Estado_Usuario FROM usuario INNER JOIN rolusuario ON usuario.Id_Rol_Us = rolusuario.Id_Rol WHERE Nombre_Usuario LIKE '%$campo%' OR Apellido_Usuario LIKE '%$campo%' OR Correo_Usuario LIKE '%$campo%' OR Usuario LIKE '%$campo%' OR Estado_Usuario LIKE '%$campo%' OR Rol LIKE '%$campo%'";
        // if (!empty($campo)) {
        // $sql = "SELECT usuario.Foto_Usuario, usuario.Nombre_Usuario, usuario.Apellido_Usuario, usuario.Correo_Usuario, usuario.Usuario,rolusuario.Rol, usuario.Estado_Usuario FROM usuario INNER JOIN rolusuario ON usuario.Id_Rol_Us = rolusuario.Id_Rol WHERE Nombre_Usuario LIKE '%$campo%' OR Apellido_Usuario LIKE '%$campo%' OR Correo_Usuario LIKE '%$campo%' OR Usuario LIKE '%$campo%' OR Estado_Usuario LIKE '%$campo%' OR Rol LIKE '%$campo%'";
        // $where = "WHERE Nombre_Usuario LIKE '%$campo%' OR Apellido_Usuario LIKE '%$campo%' OR Correo_Usuario LIKE '%$campo%' OR Usuario LIKE '%$campo%' OR Estado_Usuario LIKE '%$campo%' OR Rol LIKE '%$campo%'";
        // }

        $resultado = $con->query($sql);
        $num_rows = $resultado->num_rows;
        $hmlt = '';
        $cont = 1;
        // echo $resultado->fetch_assoc();
        // exit;

        if ($num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $hmlt .= '<tr>';
                $hmlt .= '<td>' . $cont . '</td>';
                // $hmlt .= '<td>' . $row['Foto_Usuario'] . '</td>';
                $hmlt .= '<td>' . $row['CI_Cliente'] . '</td>';
                $hmlt .= '<td>' . $row['Nombre_Cliente'] . '</td>';
                $hmlt .= '<td>' . $row['Apellido_Cliente'] . '</td>';
                $hmlt .= '<td>' . $row['Telefono_Cliente'] . '</td>';
                $hmlt .= '<td>' . $row['Direccion_Cliente'] . '</td>';
                if ($row['Estado_Cliente'] == 1) {
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
