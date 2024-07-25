<?php
session_start();
include('dbconnection.php');
include('parts/head.php');
?>
</head>

<body>
    <?php
    include('parts/sidebar.php')
    ?>
    <main>
        <h1>Administrador de Proveedores</h1>
        <br>
        <p>
            <button class="button-modal" data-modal="form-cliente">
                <span><i class='fa fa-plus'></i></span>
                Registrar Nuevo Proveedor
            </button>
        </p>
        <div class="resultados" style="margin: 0px;padding: 0px"></div>
        <div class="resultadosImg" style="margin: 0px;padding: 0px"></div>
        <div class="nice-form-group">
            <div class="nice-form-group" width="30%">
                <label for="campo">Buscar: </label>
                <input type="text" class="campo" name="campo" id="campo" placeholder="Puedes buscar por Nombre, Direccion, Telefono, Email" />
            </div>
        </div>
        <!-- Mostrar Clientes -->
        <div class="recent-orders">
            <table>
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Nombre</th>
                        <th>Direccion</th>
                        <th>Telefono</th>
                        <th>Email</th>
                        <!-- <th class="status">Estado</th> -->
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody class="content">
                    <?php
                    //Paginador
                    $sql_register = mysqli_query($con, "SELECT COUNT(*) as total_registro FROM proveedores");
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
                    $ret = mysqli_query($con, "SELECT * FROM proveedores LIMIT $desde,$por_pagina");
                    $cnt = 1;
                    $row = mysqli_num_rows($ret);
                    $items = array();
                    if ($row > 0) {
                        while ($row = mysqli_fetch_array($ret)) {
                            // if ($row['Estado_Cliente'] == 1) {
                            if (1) {
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
                            <tr class="id_row" <?php echo $color_row; ?> id="<?php echo $row['Id_Proveedor'] ?>">
                                <td>
                                    <div class="nice-form-group"><?php echo $cnt; ?></div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan Nombre_Proveedor"><?php echo $row['Nombre_Proveedor']; ?></span>
                                        <input class="editInput Nombre_Proveedor" type="text" name="Nombre_Proveedor" value="<?php echo $row['Nombre_Proveedor']; ?>" style="display: none;">
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan Direccion_Proveedor"><?php echo $row['Direccion_Proveedor']; ?></span>
                                        <input class="editInput Direccion_Proveedor" type="text" name="Direccion_Proveedor" value="<?php echo $row['Direccion_Proveedor']; ?>" style="display: none;">
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan Telefono_Proveedor"><?php echo $row['Telefono_Proveedor']; ?></span>
                                        <input class="editInput Telefono_Proveedor" type="text" name="Telefono_Proveedor" value="<?php echo $row['Telefono_Proveedor']; ?>" style="display: none;">
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan Email_Proveedor"><?php echo $row['Email_Proveedor']; ?></span>
                                        <input class="editInput Email_Proveedor" type="text" name="Email_Proveedor" value="<?php echo $row['Email_Proveedor']; ?>" style="display: none;">
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
                    <form action="proveedor_registro.php" method="post" enctype="multipart/form-data">
                        <div class="nice-form-group">
                            <label for="nombre_proveedor">Nombre del Proveedor: </label>
                            <input type="text" name="nombre_proveedor" id="nombre_cliente" onkeyup="validate_nombre_cliente()" placeholder="Ej:La favorita" required />
                            <br><br><span id="nombre_cliente_error"></span>
                        </div>
                        <div class="nice-form-group">
                            <label for="direccion_proveedor">Direccion: </label>
                            <input type="text" name="direccion_proveedor" id="direccion_cliente" onkeyup="validate_direccion_cliente()" placeholder="Ej:AV. Amazonas" required />
                            <br><br><span id="direccion_cliente_error"></span>
                        </div>
                        <div class="nice-form-group">
                            <label for="telefono_proveedor">Numero de Telefono: </label>
                            <input type="text" name="telefono_proveedor" id="telefono_cliente" onkeyup="validate_telefono_cliente()" placeholder="Ej:0978805846" required />
                            <br><br><span id="telefono_cliente_error"></span>
                        </div>
                        <div class="nice-form-group">
                            <label for="email_proveedor">Email: </label>
                            <input type="text" name="email_proveedor" id="email_proveedor" placeholder="Ej:proveedor@gmail.com" required />

                        </div>
                        <div class="nice-form-group">
                            <button type="submit" value="Registrar Proveedor" class="button-save">Registrar Proveedor</button>
                            <!-- <br><br><span id="submit_cliente_error"></span> -->
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
    <script src="../js/proveedor_action.js"></script>
</body>