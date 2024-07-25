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
        <h1>Panel de Inventario</h1>        
        <div class="resultados" style="margin: 0px;padding: 0px"></div>
        <div class="resultadosImg" style="margin: 0px;padding: 0px"></div>
        <div class="nice-form-group">
            <div class="nice-form-group" width="30%">
                <label for="campo">Buscar: </label>
                <input type="text" class="campo" name="campo" id="campo" placeholder="Puedes buscar Inventario inicial, Entrada de material, Salida de Material" />
            </div>
        </div>
        <!-- Mostrar Kardex -->
        <div class="recent-orders">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Codigo Material</th>
                        <th>Material</th>
                        <th>Actividad</th>
                        <th>Valor Unitario</th>
                        <th>Cantidad Entrada</th>
                        <th>Valor Entrada</th>
                        <th>Cantidad Salida</th>
                        <th>Valor Salida</th>
                        <th>Cantidad Saldo</th>
                        <th>Valor Saldo</th>
                    </tr>
                </thead>
                <tbody class="content">
                    <?php
                    //Paginador
                    $sql_register = mysqli_query($con, "SELECT COUNT(*) as total_registro FROM kardex");
                    $result_register = mysqli_fetch_array($sql_register);
                    $total_registro = $result_register['total_registro'];
                    $por_pagina = 10;
                    if (empty($_GET['pagina'])) {
                        $pagina = 1;
                    } else {
                        $pagina = $_GET['pagina'];
                    }
                    $desde = ($pagina - 1) * $por_pagina;
                    $total_paginas = ceil($total_registro / $por_pagina);
                    $ret = mysqli_query($con, "SELECT * FROM kardex LIMIT $desde,$por_pagina");
                    $cnt = 1;
                    $row = mysqli_num_rows($ret);
                    $items = array();
                    if ($row > 0) {
                        while ($row = mysqli_fetch_array($ret)) {
                            // if ($row['Estado_Prod'] == 1) {
                            $estado_text = "Activo";
                            $color_row = "";
                            $display = "";
                            $displayActive = "style='display: none; '";
                            // } else {
                            //     $estado_text = "Inactivo";
                            //     $color_row = "style='background-color: #ffb0b0; '";
                            //     $display = "style='display: none; '";
                            //     $displayActive = "style='display: '";
                            // }

                    ?>
                            <tr class="id_row" <?php echo $color_row; ?> id="<?php echo $row['Id_Kardex'] ?>">
                                <td>
                                    <div class="nice-form-group"><?php echo $cnt; ?></div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan dd_material status"><?php echo $row['Id_Material']; ?></span>
                                    </div>
                                </td>
                                <?php
                                $ret_material = mysqli_query($con, "SELECT Nombre_Material FROM material WHERE Id_Material = '" . $row['Id_Material'] . "' ");
                                $row_material = mysqli_fetch_array($ret_material);
                                if ($row_material > 0) {
                                ?>
                                    <td>
                                        <div class="nice-form-group">
                                            <span class="editSpan fecha_kardex"><?php echo $row_material['Nombre_Material']; ?></span>
                                        </div>
                                    </td>
                                <?php
                                }
                                ?>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan desc_kardex"><?php echo $row['Desc_K']; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan valor_unit_K"><?php echo $row['Valor_Unit_K']; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan cantidad_ent_k"><?php echo $row['Cantidad_Ent_K']; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan valor_ent_k"><?php echo $row['Valor_Ent_K']; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan cantidad_sal_k"><?php echo $row['Cantidad_Sal_K']; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan valor_sal_k"><?php echo $row['Valor_Sal_K']; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan cantidad_saldo_k"><?php echo $row['Cantidad_Saldo_k']; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan cantidad_saldo_k"><?php echo $row['Valor_Saldo_K']; ?></span>
                                    </div>
                                </td>
                                <!-- <td>
                                    <div class="nice-form-group">
                                        <button class="addBtn button-modal" data-modal="form-add-material" <?php echo $display; ?> style="cursor: pointer; color: #ff0060">
                                            <span></span><i class='fa fa-eye'></i></span>
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
                                </td> -->
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
    <script src="../js/inventario_action.js"></script>
</body>