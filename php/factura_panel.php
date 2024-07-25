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
        <h1>Administrador de Facturas</h1>
        <br>
        <p>
            <a href="factura_registro.php" style="cursor: pointer; color: #ff0060;font-size:medium">
                <span><i class='fa fa-plus'></i></span>&nbsp;Registrar Nueva Factura
            </a>
        </p>
        <div class="resultados" style="margin: 0px;padding: 0px"></div>
        <div class="resultadosImg" style="margin: 0px;padding: 0px"></div>
        <div class="nice-form-group">
            <div class="nice-form-group" width="30%">
                <label for="campo">Buscar: </label>
                <input type="text" class="campo" name="campo" id="campo" placeholder="Puedes buscar Inventario inicial, Entrada de material, Salida de Material" />
            </div>
        </div>
    </main>
    <?php
    include('parts/right_section.php');
    ?>
    </div>
    <?php
    include('parts/footer.php');
    ?>

</body>