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
        <h1>Administrador de Materiales</h1>
        <br>
        <p>
            <button class="button-modal" data-modal="form-material">
                <span><i class='fa fa-plus'></i></span>
                Registrar Nuevo Material
            </button>
        </p>
        <div class="resultados" style="margin: 0px;padding: 0px"></div>
        <div class="resultadosImg" style="margin: 0px;padding: 0px"></div>
        <div class="nice-form-group">
            <div class="nice-form-group" width="30%">
                <label for="campo">Buscar: </label>
                <input type="text" class="campo" name="campo" id="campo" placeholder="Puedes buscar por Nombre, Codigo, Costo, Unidad de medida, Estado" />
            </div>
        </div>
        <!-- Mostrar Materiales -->
        <div class="recent-orders">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Material</th>
                        <th>Codigo</th>
                        <th>c/unidad de medida</th>
                        <th>Costo &#36 c/unidad</th>
                        <th>Cantidad</th>
                        <th>Unidad de Medida</th>
                        <th>Costo &#36 unidad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody class="content">
                    <?php
                    //Paginador
                    $sql_register = mysqli_query($con, "SELECT COUNT(*) as total_registro FROM material");
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
                    $ret = mysqli_query($con, "SELECT `Nombre_Material`,`Id_Material`,`CostoPorUnidad_Material`,`Existencia_Material`,`Estado_Material`,`Nombre_Unidad`,`Nombre_Unidades`,material.`Id_Unidad` FROM material JOIN unidad ON material.`Id_Unidad` = unidad.`Id_Unidad` LIMIT $desde,$por_pagina");
                    $cnt = 1;
                    $row = mysqli_num_rows($ret);
                    $items = array();
                    if ($row > 0) {
                        while ($row = mysqli_fetch_array($ret)) {
                            if ($row['Estado_Material'] == 1) {
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
                            <tr class="id_row" <?php echo $color_row; ?> id="<?php echo $row['Id_Material'] ?>">
                                <td>
                                    <div class="nice-form-group"><?php echo $cnt; ?></div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan nombre_material"><?php echo $row['Nombre_Material']; ?></span>
                                        <input class="editInput nombre_material" type="text" name="nombre_material" value="<?php echo $row['Nombre_Material']; ?>" style="display: none;">
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan id_material status"><?php echo $row['Id_Material']; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan nombre_unidad"><?php echo $row['Nombre_Unidad']; ?></span>
                                        <select class="editInput nombre_unidad" name="nombre_unidad" id="nombre_unidad" style="display: none;">
                                            <?php
                                            $query_unidad = mysqli_query($con, "SELECT * FROM unidad");
                                            $result_unidad = mysqli_num_rows($query_unidad);
                                            echo "<option value='" . $row['Id_Unidad'] . "'>" . $row['Nombre_Unidad'] . "</option>";
                                            if ($result_unidad > 0) {
                                                while ($unidad = mysqli_fetch_array($query_unidad)) {
                                                    if ($unidad['Id_Unidad'] != $row['Id_Unidad'])
                                                        echo "<option value='" . ($unidad['Id_Unidad']) . "'>" . $unidad['Nombre_Unidad'] . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan costo_unidad">&#36 <?php echo $row['CostoPorUnidad_Material']; ?></span>
                                        <!-- <input class="editInput costo_unidad" type="text" name="costo_unidad" value="<?php echo $row['CostoPorUnidad_Material']; ?>" style="display: none;"> -->
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan cantidad_material"><?php echo $row['Existencia_Material']; ?></span>
                                        <!-- <input class="editInput cantidad_material" type="text" name="cantidad_material" value="<?php echo $row['Existencia_Material']; ?>" style="display: none;"> -->
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan nombre_unidades"><?php echo $row['Nombre_Unidades']; ?></span>
                                        <!-- <input class="editInput nombre_unidades" type="text" name="nombre_unidades" value="<?php echo $row['Nombre_Unidades']; ?>" style="display: none;"> -->
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    $costo_unitario = floatval($row['CostoPorUnidad_Material']);
                                    $cantidad = floatval($row['Existencia_Material']);
                                    $costo_total = number_format(($costo_unitario * $cantidad), 2);
                                    ?>
                                    <div class="nice-form-group">
                                        <span class="editSpan costo_total">&#36 <?php echo $costo_total; ?></span>
                                        <!-- <input class="editInput costo_total" type="text" name="costo_total" value="<?php echo $costo_total; ?>" style="display: none;"> -->
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
                                        <button class="addBtn button-modal" data-modal="form-add-material" <?php echo $display; ?> style="cursor: pointer; color: #ff0060">
                                            <span></span><i class='fa fa-plus'></i></span>
                                        </button>
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
        <!-- Registrar Material -->
        <div id="form-material" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <section>
                    <form action="material_registro.php" method="post" enctype="multipart/form-data">
                        <div class="nice-form-group">
                            <label for="nombre_material">Nombre del Material: </label>
                            <input type="text" name="nombre_material" id="nombre_material" onkeyup="validate_nombre_material()" placeholder="Ej:Rodamiento" required />
                            <br><br><span id="nombre_material_error"></span>
                        </div>
                        <div class="nice-form-group">
                            <label for="unidad_material">Seleccione la unidad del material: </label>
                            <?php
                            $query_unidad = mysqli_query($con, "SELECT * FROM unidad");
                            $result_unidad = mysqli_num_rows($query_unidad);
                            ?>
                            <select name="unidad_material" id="unidad_material" required>
                                <?php
                                echo "<option value='" . $unidad['Id_Unidad'] . "'>" . $unidad['Nombre_Unidad'] . "</option>";
                                if ($result_unidad > 0) {
                                    while ($unidad = mysqli_fetch_array($query_unidad)) {
                                        echo "<option value='" . ($unidad['Id_Unidad']) . "'>" . $unidad['Nombre_Unidad'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="nice-form-group">
                            <label for="costo_unidad">Ingrese el costo por unidad: </label>
                            <input type="number" name="costo_unidad" id="costo_unidad" onkeyup="validate_costo_unidad()" placeholder="&#36 14.50" step=".01" required />
                            <br><br><span id="costo_unidad_error"></span>
                        </div>
                        <div class="nice-form-group">
                            <label for="existencia_material">Cantidad del material a ingresar: </label>
                            <input type="number" name="existencia_material" id="existencia_material" onkeyup="validate_existencia_material()" placeholder="Ej:4.5" step=".01" required />
                            <br><br><span id="existencia_material_error"></span>
                        </div>
                        <div class="nice-form-group">
                            <button type="submit" value="Registrar Material" class="button-save" onclick="return validate_submit_material()">Registrar Material</button>
                            <br><br><span id="submit_material_error"></span>
                        </div>
                    </form>
                </section>
            </div>
        </div>
        <!-- Modal agregar material -->
        <div id="form-add-material" class="modal">
            <div class="modal-content">
                <a href="#" onclick="closeModal()"><span class="close">&times;</span></a>
                <section>
                    <form action="" method="post" enctype="" name="form_add_material" id="form_add_material" onsubmit="sendDataMaterial()">
                        <div class="nice-form-group">
                            <h1 class="addnombre_material">Agregar</h1>
                            <label for="addCantidad_material">Cantidad del material a ingresar: </label>
                            <input type="number" name="addCantidad_material" id="addCantidad_material" placeholder="Ej:4.5" step=".01" required>
                            <input type="hidden" name="addId_material" id="addId_material" required>
                        </div>
                        <div class="nice-form-group">
                            <label for="addcostounidad_material">Ingrese el costo por unidad: </label>
                            <input type="number" name="addcostounidad_material" id="addcostounidad_material" placeholder="&#36 14.50" step=".01" required />
                        </div>
                        <div class="nice-form-group">
                            <button type="submit" value="Agregar Material" class="button-save">Agregar Material</button>
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
    <script src="../js/validate_materialForm.js"></script>
    <script src="../js/material_action.js"></script>
</body>