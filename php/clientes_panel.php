<?php
// session_start();
include('dbconnection.php');
include('parts/head.php');
?>
</head>

<body>
    <?php
    include('parts/sidebar.php')
    ?>
    <main>
        <h1>Administrador de Clientes</h1>
        <br>
        <p>
            <button class="button-modal" data-modal="form-cliente">
                <span><i class='fa fa-plus'></i></span>
                Registrar Nuevo Cliente
            </button>
        </p>
        <div class="resultados" style="margin: 0px;padding: 0px"></div>
        <div class="resultadosImg" style="margin: 0px;padding: 0px"></div>
        <div class="nice-form-group">
            <div class="nice-form-group" width="30%">
                <label for="campo">Buscar: </label>
                <input type="text" class="campo" name="campo" id="campo" placeholder="Puedes buscar por Nombre, Apellido, CI, Estado" />
            </div>
        </div>
        <!-- Mostrar Clientes -->
        <div class="recent-orders">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Cedula de I.</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Telefono</th>
                        <th>Direccion</th>
                        <th class="status">Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody class="content">
                    <?php
                    //Paginador
                    $sql_register = mysqli_query($con, "SELECT COUNT(*) as total_registro FROM cliente");
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
                    $ret = mysqli_query($con, "SELECT * FROM cliente LIMIT $desde,$por_pagina");
                    $cnt = 1;
                    $row = mysqli_num_rows($ret);
                    $items = array();
                    if ($row > 0) {
                        while ($row = mysqli_fetch_array($ret)) {
                            if ($row['Estado_Cliente'] == 1) {
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
                            <tr class="id_row" <?php echo $color_row; ?> id="<?php echo $row['Id_Cliente'] ?>">
                                <td>
                                    <div class="nice-form-group"><?php echo $cnt; ?></div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan ci"><?php echo $row['CI_Cliente']; ?></span>
                                        <input class="editInput ci" type="text" name="ci" value="<?php echo $row['CI_Cliente']; ?>" style="display: none;">
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan first_name"><?php echo $row['Nombre_Cliente']; ?></span>
                                        <input class="editInput first_name" type="text" name="first_name" value="<?php echo $row['Nombre_Cliente']; ?>" style="display: none;">
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan last_name"><?php echo $row['Apellido_Cliente']; ?></span>
                                        <input class="editInput last_name" type="text" name="last_name" value="<?php echo $row['Apellido_Cliente']; ?>" style="display: none;">
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan phone"><?php echo $row['Telefono_Cliente']; ?></span>
                                        <input class="editInput phone" type="text" name="phone" value="<?php echo $row['Telefono_Cliente']; ?>" style="display: none;">
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan adress"><?php echo $row['Direccion_Cliente']; ?></span>
                                        <input class="editInput adress" type="text" name="adress" value="<?php echo $row['Direccion_Cliente']; ?>" style="display: none;">
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan password"><?php echo $estado_text; ?></span>
                                        <input class="password" type="text" name="password" value="" style="display: none;">
                                    </div>

                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <button class="editBtn" <?php echo $display; ?> style="cursor: pointer; color: #ff0060">
                                            <span></span><i class='fa fa-edit'></i></span>
                                        </button>
                                        <button class="deleteBtn" <?php echo $display; ?> style="cursor: pointer; color: #ff0060">
                                            <span><i class='fa fa-trash'></i></span>
                                        </button>
                                        <button class="saveBtn" style="display: none;" style="cursor: pointer; color: #ff0060">
                                            <span></span><i class='fa fa-save'></i></span>
                                        </button>
                                        <button class="cancelBtn" style="display: none;" style="cursor: pointer; color: #ff0060">
                                            <span></span><i class='fa fa-ban'></i></span>
                                        </button>
                                        <button class="confirmBtn" <?php echo $displayActive; ?> style="cursor: pointer; color: #ff0060;">
                                            <span><i class='fa fa-check'></i></span>

                                        </button>
                                    </div>
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

        <!-- Registrar Cliente -->
        <div id="form-cliente" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <section>
                    <form action="cliente_registro.php" method="post" enctype="multipart/form-data">
                        <div class="nice-form-group">
                            <label for="ci_cliente">Cedula de Identidad: </label>
                            <input type="text" name="ci_cliente" id="ci_cliente" onkeyup="validate_ci_cliente()" placeholder="Ej:1721286395" required />
                            <br><br><span id="ci_cliente_error"></span>
                        </div>
                        <div class="nice-form-group">
                            <label for="nombre_cliente">Nombre: </label>
                            <input type="text" name="nombre_cliente" id="nombre_cliente" onkeyup="validate_nombre_cliente()" placeholder="Ej:Juan" required />
                            <br><br><span id="nombre_cliente_error"></span>
                        </div>
                        <div class="nice-form-group">
                            <label for="apellido_cliente">Apellido: </label>
                            <input type="text" name="apellido_cliente" id="apellido_cliente" onkeyup="validate_apellido_cliente()" placeholder="Ej:Perez" required />
                            <br><br><span id="apellido_cliente_error"></span>
                        </div>
                        <div class="nice-form-group">
                            <label for="telefono_cliente">Numero de Telefono: </label>
                            <input type="text" name="telefono_cliente" id="telefono_cliente" onkeyup="validate_telefono_cliente()" placeholder="Ej:0978805846" required />
                            <br><br><span id="telefono_cliente_error"></span>
                        </div>
                        <div class="nice-form-group">
                            <label for="direccion_cliente">Direccion de domicilio: </label>
                            <input type="text" name="direccion_cliente" id="direccion_cliente" onkeyup="validate_direccion_cliente()" placeholder="Ej:25 de noviembre y maldonado" required />
                            <br><br><span id="direccion_cliente_error"></span>
                        </div>
                        <div class="nice-form-group">
                            <button type="submit" value="Registrar Cliente" class="button-save" onclick="return validate_submit_cliente()">Registrar Cliente</button>
                            <br><br><span id="submit_cliente_error"></span>
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
    include('parts/footer.php');
    ?>
    <script src="../js/modal_form.js"></script>
    <script src="../js/validate_clientForm.js"></script>
    <script src="../js/client_action.js"></script>
</body>