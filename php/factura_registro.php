<?php
session_start();
include('dbconnection.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/panel.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/fontawesome/css/all.min.css">
    <title>Registro Factura</title>
    <link rel="stylesheet" href="../css/modal_form_style.css">
    <link rel="stylesheet" href="../css/form_style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>

<body>
    <?php
    include('parts/sidebar.php')
    ?>
    <main>
        <h1>Registro de una Nueva Factura</h1>
        <div class="recent-orders">

            <div class="nice-form-group">
                <p>
                    <button class="button-modal btn btn-sm" data-modal="form-cliente">
                        <span><i class='fa fa-plus'></i></span>
                        Registrar Nuevo Cliente
                    </button>
                </p>
            </div>
        </div>
        <br>
        <section>
            <div class="recent-orders">
                <label for="">CI/RUC:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </label>
                <!-- <select name="ci_cliente" id="ci_cliente" required> -->
                <select class="editInput ci_cliente" name="ci_cliente" id="ci_cliente">
                    <?php
                    $query_cliente = mysqli_query($con, "SELECT * FROM cliente");
                    $result_cliente = mysqli_num_rows($query_cliente);

                    // echo "<option value='" . $row['Id_Cliente'] . "'>" . $row['Nombre_Cliente'] . "</option>";

                    if ($result_cliente > 0) {
                        while ($row = mysqli_fetch_assoc($query_cliente)) {
                            echo "<option value='" . $row['CI_Cliente'] . "'>" . $row['CI_Cliente'] . " - " . $row['Nombre_Cliente'] . "</option>";
                        }
                    }
                    ?>
                </select>
                &nbsp;&nbsp;&nbsp;&nbsp;

                <!-- <input type="text" name="ci_cliente" id="ci_cliente" required>&nbsp;&nbsp;&nbsp;&nbsp; -->
                <label for="">Nombre: </label>
                <input type="text" name="name_cliente" id="name_cliente" disabled required>&nbsp;&nbsp;&nbsp;&nbsp;
                <label for="">Apellido: </label>
                <input type="text" name="last_name_cliente" id="last_name_cliente" disabled required>&nbsp;&nbsp;&nbsp;&nbsp;
                <label for="">Telefono: </label>
                <input type="text" name="phone_cliente" id="phone_cliente" disabled required>&nbsp;&nbsp;&nbsp;&nbsp;
                <br>
                <label for="">Direccion: </label>
                <input type="text" name="address_cliente" id="address_cliente" disabled required>&nbsp;
            </div>
        </section>
        <section>
            <div class="recent-orders">
                <label for="" class="txt_venta">Vendedor: </label>
                <span class="txt_venta"><?php echo $_SESSION['nombre']; ?></span>
                <a href="#" class="btn_venta" id="btn_anular_venta"><i class="fa fa-ban"></i>&nbsp;Anular</a>
                <a href="#" class="btn_venta" id="btn_facturar_venta" style="display: none;"><i class="fa fa-check"></i>&nbsp;Procesar</a>
            </div>
        </section>

        <div class="recent-orders">
            <br>
            <table class="tbl_venta">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Producto</th>
                        <th>Descripcion</th>
                        <th>Existencia</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Precio Total</th>
                        <th>Accion</th>
                    </tr>

                </thead>
                <tr>
                    <th scope="row"></th>
                    <td>
                        <div class="nice-form-group">
                            <!-- <input type="text" name="txt_cod_producto" id="txt_cod_producto"> -->
                            <select name="txt_cod_producto" id="txt_cod_producto">
                                <!-- <select class="editInput ci_cliente" name="ci_cliente" id="ci_cliente"> -->
                                <?php
                                $query_producto = mysqli_query($con, "SELECT * FROM producto");
                                $result_producto = mysqli_num_rows($query_producto);

                                if ($result_producto > 0) {
                                    while ($row = mysqli_fetch_assoc($query_producto)) {
                                        echo "<option value='" . $row['Id_Producto'] . "'>" . $row['Id_Producto'] . " - " . $row['Desc_Producto'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </td>

                    <td id="txt_descripcion"></td>
                    <td id="txt_existencia"></td>
                    <td>
                        <div class="nice-form-group">
                            <input type="text" name="txt_cant_producto" id="txt_cant_producto" value="0" min="1" disabled>
                        </div>
                    </td>
                    <td id="txt_precio">0.00</td>
                    <td id="txt_precio_total">0.00</td>
                    <td><button type="button" style="display: none;cursor: pointer;font-size: 20px" id="btn_agregar_producto"><span><i style="color: #ff0060;background-color:none" class='fa fa-plus'></i></span></button></td>
                </tr>
                <thead>
                    <tr>
                        <th> </th>
                        <th>Codigo</th>
                        <th colspan="2">Descripcion</th>
                        <th></th>
                        <th>Precio</th>
                        <th>Precio Total</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody id="detalle_productos">
                </tbody>
                <tfoot id="detalle_totales">
                </tfoot>
            </table>
        </div>


        <!-- Registrar Nuevo Cliente -->
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
    </main>
    </div>
    <?php
    include('parts/footer.php');
    ?>
    <script src="../js/modal_form.js"></script>
    <script src="../js/validate_clientForm.js"></script>
    <script src="../js/factura_action.js"></script>
</body>

</html>