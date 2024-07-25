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
        <h1>Panel de Control Administrador</h1>
    </main>
    <?php
    include('parts/right_section.php');
    ?>
    </div>
    <?php
    include('parts/footer.php');
    ?>

</body>