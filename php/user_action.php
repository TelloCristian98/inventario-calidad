<?php
include('dbconnection.php');
if (!empty($_POST)) {
    $msg = "";
    if (($_POST['action'] == 'edit') && !empty($_POST['id'])) {
        // echo $_POST['first_name'];
        $eid = $_POST['id'];
        $nombre_usuario = $_POST['first_name'];
        $apellido_usuario = $_POST['last_name'];
        $correo_usuario = $_POST['correo'];
        $usuario = $_POST['user'];
        $Id_Rol_Us = $_POST['rol'];
        $uploadImgOk = 1;
        $rol_usuario = "";
        $contrasena_usuario = md5($_POST['password']);


        $query_rol = mysqli_query($con, "SELECT * FROM rolusuario");
        $result_rol = mysqli_num_rows($query_rol);
        if ($result_rol > 0) {
            while ($rol = mysqli_fetch_array($query_rol)) {
                if ($Id_Rol_Us == $rol['Id_Rol']) {
                    $rol_usuario = $rol['Rol'];
                }
            }
        }
        // echo "Hola";
        // if (isset($_FILES["image"])) {
        $query_foto = mysqli_query($con, "SELECT Foto_Usuario FROM usuario WHERE Id_Usuario = '$eid'");
        $data_foto = mysqli_fetch_array($query_foto);
        if ($data_foto > 0) {
            $foto_usuario = $data_foto['Foto_Usuario'];
        }
        // $foto_usuario = $data_foto['Foto_Usuario'];
        if (!empty($_POST['photo'])) {
            $target_dir = "../img/perfil/";
            $target_file = $target_dir . $_POST['photo'];
            $msg = $target_file;
            if (file_exists($target_file)) {
                $msg = "La imagen ya existe. Intentalo de nuevo con otra imagen";
                $uploadImgOk = 0;
            } else {
                $foto_usuario = $_POST['photo'];
            }
        }

        $userData = array(
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'correo' => $_POST['correo'],
            'user' => $_POST['user'],
            'rol' => $rol_usuario,
            'photo' => $foto_usuario,
        );

        $query = mysqli_query($con, "SELECT * FROM usuario WHERE (Usuario = '$usuario' AND 	Id_Usuario != '$eid') 
        OR (Correo_Usuario = '$correo_usuario' AND Id_Usuario != '$eid')");
        $result = mysqli_fetch_array($query);
        if ($result > 0) {
            $msg = 'El usuario o el correo ya estan registrados!';
        } else {
            if ($uploadImgOk == 1) {
                if (!empty($_POST['password'])) {
                    $sql = "update usuario set Nombre_Usuario = '$nombre_usuario', Apellido_Usuario = '$apellido_usuario', Correo_Usuario = '$correo_usuario', Usuario = '$usuario', Id_Rol_Us = '$Id_Rol_Us', Foto_Usuario = '$foto_usuario', Clave_Usuario = '$contrasena_usuario' where Id_Usuario = '$eid'";
                } else {
                    $sql = "update usuario set Nombre_Usuario = '$nombre_usuario', Apellido_Usuario = '$apellido_usuario', Correo_Usuario = '$correo_usuario', Usuario = '$usuario', Id_Rol_Us = '$Id_Rol_Us', Foto_Usuario = '$foto_usuario' where Id_Usuario = '$eid'";
                }


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
            } else {
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
        $sql = mysqli_query($con, "UPDATE usuario SET Estado_Usuario=0 WHERE Id_Usuario=$rid");
        // $result = mysqli_fetch_array($sql);
        if ($sql) {
            $response = array(
                'status' => 1,
                'msg' => 'Usuario desactivado correctamente',
                'data' => 'Inactivo',
            );
        } else {
            $msg = 'Error desactivando usuario:' . mysqli_error($con);
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
        $sql = mysqli_query($con, "UPDATE usuario SET Estado_Usuario=1 WHERE Id_Usuario=$rid");

        if ($sql) {
            $response = array(
                'status' => 1,
                'msg' => 'Usuario activado correctamente',
                'data' => 'Activo',
            );
        } else {
            $msg = 'Error activando usuario:' . mysqli_error($con);
            $response = array(
                'status' => 0,
                'msg' => $msg,
                'data' => 'Inactivo',
            );
        }
        echo json_encode($response);
        exit();
    } elseif (($_POST['action'] == 'search') && ($_POST['campo'] != "")) {
        $columns = ['Foto_Usuario', 'Nombre_Usuario', 'Apellido_Usuario', 'Correo_Usuario', 'Usuario', 'Id_Rol_Us', 'Estado_Usuario'];
        $table = "usuario";
        $campo = $con->real_escape_string($_POST["campo"]) ?? null;
        // $sql = "SELECT " . implode(",", $columns) . " FROM " . $table;
        // " WHERE Nombre_Usuario LIKE '%$campo%' OR Apellido_Usuario LIKE '%$campo%' OR Correo_Usuario LIKE '%$campo%' OR Usuario LIKE '%$campo%' OR Rol LIKE '%$campo%' OR Estado_Usuario LIKE '%$campo%'";
        $sql = "SELECT usuario.Foto_Usuario, usuario.Nombre_Usuario, usuario.Apellido_Usuario, usuario.Id_Rol_Us, usuario.Correo_Usuario, usuario.Usuario,rolusuario.Rol, usuario.Estado_Usuario FROM usuario INNER JOIN rolusuario ON usuario.Id_Rol_Us = rolusuario.Id_Rol WHERE Nombre_Usuario LIKE '%$campo%' OR Apellido_Usuario LIKE '%$campo%' OR Correo_Usuario LIKE '%$campo%' OR Usuario LIKE '%$campo%' OR Estado_Usuario LIKE '%$campo%' OR Rol LIKE '%$campo%'";
        // if (!empty($campo)) {
        // $sql = "SELECT usuario.Foto_Usuario, usuario.Nombre_Usuario, usuario.Apellido_Usuario, usuario.Correo_Usuario, usuario.Usuario,rolusuario.Rol, usuario.Estado_Usuario FROM usuario INNER JOIN rolusuario ON usuario.Id_Rol_Us = rolusuario.Id_Rol WHERE Nombre_Usuario LIKE '%$campo%' OR Apellido_Usuario LIKE '%$campo%' OR Correo_Usuario LIKE '%$campo%' OR Usuario LIKE '%$campo%' OR Estado_Usuario LIKE '%$campo%' OR Rol LIKE '%$campo%'";
        // $where = "WHERE Nombre_Usuario LIKE '%$campo%' OR Apellido_Usuario LIKE '%$campo%' OR Correo_Usuario LIKE '%$campo%' OR Usuario LIKE '%$campo%' OR Estado_Usuario LIKE '%$campo%' OR Rol LIKE '%$campo%'";
        // }

        $resultado = $con->query($sql);
        $num_rows = $resultado->num_rows;
        $hmlt = '';
        $cont = 1;
        $rol_usuario = "";
        // echo $resultado->fetch_assoc();
        // exit;

        if ($num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $hmlt .= '<tr>';
                $hmlt .= '<td>' . $cont . '</td>';
                // foreach ($columns as $column) {
                $hmlt .= '<td><img class="editSpan" src="../img/perfil/' . $row['Foto_Usuario'] . '" width="60" height="60" /></td>';
                // $hmlt .= '<td>' . $row['Foto_Usuario'] . '</td>';
                $hmlt .= '<td>' . $row['Nombre_Usuario'] . '</td>';
                $hmlt .= '<td>' . $row['Apellido_Usuario'] . '</td>';
                $hmlt .= '<td>' . $row['Correo_Usuario'] . '</td>';
                $hmlt .= '<td>' . $row['Usuario'] . '</td>';
                $query_rol = mysqli_query($con, "SELECT * FROM rolusuario");
                $result_rol = mysqli_num_rows($query_rol);
                if ($result_rol > 0) {
                    while ($rol = mysqli_fetch_array($query_rol)) {
                        if ($rol['Id_Rol'] == $row['Id_Rol_Us']) {
                            $hmlt .= '<td>' . $rol['Rol'] . '</td>';
                        }
                    }
                }
                if ($row['Estado_Usuario'] == 1) {
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
