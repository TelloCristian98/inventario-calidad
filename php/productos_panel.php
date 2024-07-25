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
        <h1>Panel de Productos</h1>
        <br>
        <p>
            <a href="producto_registro.php" style="cursor: pointer; color: #ff0060;font-size:medium">
                <span><i class='fa fa-plus'></i></span>&nbsp;Registrar Nuevo Producto
            </a>
        </p>
        <div class="resultados" style="margin: 0px;padding: 0px"></div>
        <div class="resultadosImg" style="margin: 0px;padding: 0px"></div>
        <div class="nice-form-group">
            <div class="nice-form-group" width="30%">
                <label for="campoUno">Buscar: </label>
                <input type="text" class="campoUno" name="campoUno" id="campoUno" placeholder="Puedes buscar por Nombre, Codigo, Costo, Estado" />
            </div>
        </div>
        <!-- Mostrar Productos -->
        <div class="recent-orders">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Producto</th>
                        <th>Codigo</th>
                        <th>Cantidad</th>
                        <th>Costo &#36</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody class="content">
                    <?php
                    //Paginador
                    $sql_register = mysqli_query($con, "SELECT COUNT(*) as total_registro FROM producto");
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
                    $ret = mysqli_query($con, "SELECT * FROM producto LIMIT $desde,$por_pagina");
                    $cnt = 1;
                    $row = mysqli_num_rows($ret);
                    $items = array();
                    if ($row > 0) {
                        while ($row = mysqli_fetch_array($ret)) {
                            if ($row['Estado_Prod'] == 1) {
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
                            <tr class="id_row" <?php echo $color_row; ?> id="<?php echo $row['Id_Producto'] ?>">
                                <td>
                                    <div class="nice-form-group"><?php echo $cnt; ?></div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan nombre_producto"><?php echo $row['Desc_Producto']; ?></span>
                                        <input class="editInput nombre_producto" type="text" name="nombre_producto" value="<?php echo $row['Desc_Producto']; ?>" style="display: none;">
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan id_producto status"><?php echo $row['Id_Producto']; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan cantidad_producto"><?php echo $row['Existencia_Prod']; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="nice-form-group">
                                        <span class="editSpan costo_producto">&#36 <?php echo $row['PrecioUnit_Prod']; ?></span>
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
    <script src="../js/products_action.js"></script>
</body>