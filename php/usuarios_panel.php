<?php
session_start();
include('dbconnection.php');
include('alert_msg.php');
include('parts/head.php');

?>
</head>

<body>
    <?php
    include('parts/sidebar.php')
    ?>
    <main>
        <h1>Administrador de Usuarios</h1>
        <br>
        <p>
            <button class="button-modal" data-modal="form-usuario">
                <span><i class='fa fa-plus'></i></span>
                Registrar Nuevo Usuario
            </button>
        </p>
        <div class="resultados" style="margin: 0px;padding: 0px"></div>
        <div class="resultadosImg" style="margin: 0px;padding: 0px"></div>
        <div class="nice-form-group">
            <div class="nice-form-group" width="30%">
                <label for="campo">Buscar: </label>
                <input type="text" class="campo" name="campo" id="campo" placeholder="Puedes buscar por Nombre, Apellido, Correo, Usuario, Rol, Estado" />
            </div>
        </div>
        <!-- Mostrar Usuarios -->
        <div class="recent-orders">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Foto</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Correo</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th class="status">Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody class="content">
                    <?php
                    //Paginador
                    $sql_register = mysqli_query($con, "SELECT COUNT(*) as total_registro FROM usuario");
                    $result_register = mysqli_fetch_array($sql_register);
                    $total_registro = $result_register['total_registro'];
                    $por_pagina = 5;
                    if (empty($_GET['pagina'])) {
                        $pagina = 1;
                    } else {
                        $pagina = $_GET['pagina'];
                    }
                    $desde = ($pagina - 1) * $por_pagina;
                    $total_paginas = ceil($total_registro / $por_pagina);
                    $ret = mysqli_query($con, "SELECT usuario.Id_Usuario,usuario.Foto_Usuario,usuario.Nombre_Usuario, usuario.Apellido_Usuario, usuario.Correo_Usuario, usuario.Usuario, rolusuario.Rol,usuario.Id_Rol_Us,usuario.Id_Usuario,usuario.Clave_Usuario, usuario.Estado_Usuario FROM usuario INNER JOIN rolusuario ON usuario.Id_Rol_Us = rolusuario.Id_Rol LIMIT $desde,$por_pagina");
                    $cnt = 1;
                    $row = mysqli_num_rows($ret);
                    $items = array();
                    if ($row > 0) {
                        while ($row = mysqli_fetch_array($ret)) {
                            if ($row['Estado_Usuario'] == 1) {
                                $estado_text = "Activo";
                                $color_row = "";
                                $display = "";
                                $displayActive = "style='display: none; '";
                            } else {
                                $estado_text = "Inactivo";
                                $color_row = "style='background-color: #ffb0b0; '";
                                $display = "style='display: none; '";
                                $displayActive = "style='display: '";
                            }

                    ?>
                            <tr class="id_row" <?php echo $color_row; ?> id="<?php echo $row['Id_Usuario'] ?>">
                                <td><?php echo $cnt; ?></td>
                                <td>
                                    <div class="nice-form-group">
                                        <img class="editSpan" src="../img/perfil/<?php echo htmlentities($row['Foto_Usuario']); ?>" width="60" height="60" />
                                        <input class="editInput photo" id="photo" accept="image/png,image/jpeg,image/jpg" type="file" name="photo" style="display: none;" width="60" height="60">
                                    </div>


                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan first_name"><?php echo $row['Nombre_Usuario']; ?></span>
                                        <input class="editInput first_name" type="text" name="first_name" value="<?php echo $row['Nombre_Usuario']; ?>" style="display: none;">
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan last_name"><?php echo $row['Apellido_Usuario']; ?></span>
                                        <input class="editInput last_name" type="text" name="last_name" value="<?php echo $row['Apellido_Usuario']; ?>" style="display: none;">
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan correo"><?php echo $row['Correo_Usuario']; ?></span>
                                        <input class="editInput correo" type="text" name="correo" value="<?php echo $row['Correo_Usuario']; ?>" style="display: none;">
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan user"><?php echo $row['Usuario']; ?></span>
                                        <input class="editInput user" type="text" name="user" value="<?php echo $row['Usuario']; ?>" style="display: none;">
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan rol"><?php echo htmlentities($row['Rol']); ?></span>
                                        <select class="editInput rol" name="rol" style="display: none;">
                                            <option value="<?php echo $row['Id_Rol_Us'] ?>"><?php echo $row['Rol'] ?></option>
                                            <?php
                                            $query_rol = mysqli_query($con, "SELECT * FROM rolusuario");
                                            $result_rol = mysqli_num_rows($query_rol);
                                            if ($result_rol > 0) {
                                                while ($rol = mysqli_fetch_array($query_rol)) {
                                                    if ($rol['Id_Rol'] != $row['Id_Rol_Us']) {
                                                        echo "<option value='" . $rol['Id_Rol'] . "'>" . $rol['Rol'] . "</option>";
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan password"><?php echo $estado_text; ?></span>
                                        <input class="editInput password" type="text" name="password" value="" style="display: none;">
                                    </div>

                                </td>
                                <td>
                                    <button class="editBtn" <?php echo $display; ?> style="cursor: pointer; color: #ff0060">
                                        <span></span><i class='fa fa-edit'></i></span>
                                    </button>
                                    <button class="saveBtn" style="display: none;" style="cursor: pointer; color: #ff0060">
                                        <span></span><i class='fa fa-save'></i></span>
                                    </button>
                                    <button class="cancelBtn" style="display: none;" style="cursor: pointer; color: #ff0060">
                                        <span></span><i class='fa fa-ban'></i></span>
                                    </button>

                                    <?php if ($row['Rol'] != 'Administrador') { ?>

                                        <button class="deleteBtn" <?php echo $display; ?> style="cursor: pointer; color: #ff0060">
                                            <span><i class='fa fa-trash'></i></span>

                                        </button>

                                    <?php } ?>

                                    <button class="confirmBtn" <?php echo $displayActive; ?> style="cursor: pointer; color: #ff0060;">
                                        <span><i class='fa fa-check'></i></span>

                                    </button>
                                </td>
                            </tr>
                        <?php
                            $cnt = $cnt + 1;
                        }
                    } else { ?>
                        <tr>
                            <th style="text-align:center; color:red;" colspan="8">No hay datos</th>
                        </tr>
                    <?php }

                    ?>
                </tbody>
            </table>
        </div>
        <!-- Registrar Usuario -->
        <div id="form-usuario" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <section>
                    <form action="usuario_registro.php" method="post" enctype="multipart/form-data">
                        <div class="nice-form-group">
                            <label for="nombre_usuario">Nombre: </label>
                            <input type="text" name="nombre_usuario" id="nombre_usuario" onkeyup="validate_nombre_usuario()" placeholder="Ej:Juan" required />
                            <br><br><span id="nombre_usuario_error"></span>
                        </div>
                        <div class="nice-form-group">
                            <label for="apellido_usuario">Apellido: </label>
                            <input type="text" name="apellido_usuario" id="apellido_usuario" onkeyup="validate_apellido_usuario()" placeholder="Ej:Perez" required />
                            <br><br><span id="apellido_usuario_error"></span>
                        </div>
                        <div class="nice-form-group">
                            <label for="correo_usuario">Correo Electronico: </label>
                            <input type="email" name="correo_usuario" id="correo_usuario" onkeyup="validate_correo_usuario()" placeholder="Ej:email@email.com" class="icon-left" required />
                            <br><br><span id="correo_usuario_error"></span>
                        </div>
                        <div class="nice-form-group">
                            <label for="usuario">Usuario: </label>
                            <input type="text" name="usuario" id="usuario" onkeyup="validate_usuario()" placeholder="Ej:nombreApellido1" required />
                            <br><br><span id="usuario_error"></span>
                        </div>
                        <div class="nice-form-group">
                            <label for="clave_usuario">Contraseña: </label>
                            <input type="password" name="clave_usuario" id="clave_usuario" onkeyup="validate_clave_usuario()" class="icon-left" required />
                            <br><br><span id="clave_usuario_error"></span>
                        </div>
                        <div class="nice-form-group">
                            <label for="rol_usuario">Seleccione el Rol del Usuario</label>
                            <?php
                            $query_rol = mysqli_query($con, "SELECT * FROM rolusuario");
                            $result_rol = mysqli_num_rows($query_rol);
                            ?>
                            <select name="rol_usuario" id="rol_usuario" required>
                                <?php
                                echo "<option value='" . $rol['Id_Rol'] . "'>" . $rol['Rol'] . "</option>";
                                if ($result_rol > 0) {
                                    while ($rol = mysqli_fetch_array($query_rol)) {
                                        echo "<option value='" . $rol['Id_Rol'] . "'>" . $rol['Rol'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="nice-form-group">
                            <label for="foto_usuario">Suba foto de perfil</label>
                            <input type="file" accept="image/png,image/jpeg,image/jpg" name="foto_usuario" id="foto_usuario" required />
                        </div>
                        <div class="nice-form-group">
                            <button type="submit" value="Registrar Usuario" class="button-save" onclick="return validate_submit_usuario()">Registrar Usuario</button>
                            <br><br><span id="submit_usuario_error"></span>
                        </div>
                    </form>
                </section>
            </div>
        </div>
        <br>
        <div class="pagination">
            <?php
            if ($pagina != 1) {
            ?>
                <a href="?pagina=<?php echo $pagina - 1 ?>">&laquo;</a>
            <?php
            }
            for ($i = 1; $i <= $total_paginas; $i++) {
                if ($i == $pagina) {
                    echo '<a class="active">' . $i . '</a>';
                } else {
                    echo '<a href="?pagina=' . $i . '">' . $i . '</a>';
                }
            }
            if ($pagina != $total_paginas) {
            ?>
                <a href="?pagina=<?php echo $pagina + 1 ?>">&raquo;</a>
            <?php
            }
            ?>
        </div>

    </main>
    <?php
    include('parts/right_section.php');
    ?>
    </div>
    <?php
    include('parts/footer.php');    ?>


    <script src="../js/validate_userForm.js"></script>
    <script src="../js/user_action.js"></script>

</body>