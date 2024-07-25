<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/panel.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />

    <link rel="stylesheet" href="../css/form_style.css">
    <link rel="stylesheet" href="../assets/fontawesome/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <title>Registro Producto</title>
</head>

<body>
    <?php
    include('parts/sidebar.php')
    ?>
    <main>
        <h1>Registro de un Nuevo Producto</h1>
        <div class="recent-orders">

            <div class="nice-form-group">
                <label>Nombre del producto<span class="danger"> *</span></label>
                <input type="text" id="product_name" name="product_name" placeholder="Ej:" onkeyup="validate_product()" required>
                <span id="productError" style="color: red;"></span>
            </div>
        </div>
        <div class="recent-orders">
            <h3 class="primary">Escoge los materiales que van en el producto</h3>
            <br>
            <table class="tbl_productos">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Codigo</th>
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
                            <input type="text" name="txt_cod_material" id="txt_cod_material">
                        </div>
                    </td>

                    <td id="txt_descripcion"></td>
                    <td id="txt_existencia"></td>
                    <td>
                        <div class="nice-form-group">
                            <input type="text" name="txt_cant_material" id="txt_cant_material" value="0" min="1" disabled>
                        </div>
                    </td>
                    <td id="txt_precio">0.00</td>
                    <td id="txt_precio_total">0.00</td>
                    <td><button type="button" style="display: none;cursor: pointer;font-size: 20px" id="btn_agregar_material"><span><i style="color: #ff0060;background-color:none" class='fa fa-plus'></i></span></button></td>
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
                <tbody id="detalle_material">
                </tbody>
                <tfoot id="detalle_totales">
                </tfoot>
            </table>
            <div class="nice-form-group">
                <button type="submit" class="button-save" id="btn_crear_producto" style="display: none;">Crear Producto</button>
                <span id="submitError" style="color: red;"></span>
            </div>
        </div>
    </main>
    </div>
    <?php
    include('parts/footer.php');
    ?>
    <script src="../js/products_action.js"></script>
</body>

</html>