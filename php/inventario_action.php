<?php
session_start();
include('dbconnection.php');
if (!empty($_POST)) {
    if (($_POST['action'] == 'search') && ($_POST['campo'] != "")) {
        $campo = $con->real_escape_string($_POST["campo"]) ?? null;
        $sql = "SELECT * FROM kardex 
        WHERE Desc_K LIKE '%$campo%'";

        $resultado = $con->query($sql);
        $num_rows = $resultado->num_rows;
        $hmlt = '';
        $cont = 1;

        if ($num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $hmlt .= '<tr>';
                $hmlt .= '<td>' . $cont . '</td>';
                // $hmlt .= '<td>' . $row['Foto_Usuario'] . '</td>';
                $hmlt .= '<td>' . $row['Id_Kardex'] . '</td>';
                $hmlt .= '<td>' . $row['Fecha_K'] . '</td>';
                $hmlt .= '<td>' . $row['Desc_K'] . '</td>';
                $hmlt .= '<td>' . $row['Valor_Unit_K'] . '</td>';
                $hmlt .= '<td>' . $row['Cantidad_Ent_K'] . '</td>';
                $hmlt .= '<td>' . $row['Valor_Ent_K'] . '</td>';
                $hmlt .= '<td>' . $row['Cantidad_Sal_K'] . '</td>';
                $hmlt .= '<td>' . $row['Valor_Sal_K'] . '</td>';
                $hmlt .= '<td>' . $row['Cantidad_Saldo_k'] . '</td>';
                $hmlt .= '<td>' . $row['Valor_Saldo_K'] . '</td>';
                $cont++;
                $hmlt .= ' ';
                // $hmlt .= '<td>
                // <button class="editBtn" style="cursor: pointer; color: #ff0060">
                // <span></span><i class="fa fa-edit"></i></span>
                // </button>
                // <button class="deleteBtn" style="cursor: pointer; color: #ff0060">
                // <span><i class="fa fa-trash"></i></span>
                // </button>
                // </td>';
                $hmlt .= '</tr>';
            }
        } else {
            $hmlt .= '<tr><td colspan="6">No se encontraron resultados</td></tr>';
        }

        echo json_encode($hmlt, JSON_UNESCAPED_UNICODE);
        exit();
    }
}
