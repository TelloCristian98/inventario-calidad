$(document).ready(function () {
  $("#ci_cliente").change(function (e) {
    e.preventDefault();
    var ci = $("#ci_cliente").val();
    // console.log(ci);
    $.ajax({
      type: "POST",
      url: "factura_action.php",
      data: "action=searchCliente&id=" + ci,
      success: function (result) {
        var response = JSON.parse(result);
        if (response.status == 1) {
          $("#name_cliente").val(response.data.nombre_cliente);
          $("#last_name_cliente").val(response.data.apellido_cliente);
          $("#phone_cliente").val(response.data.telefono_cliente);
          $("#address_cliente").val(response.data.direccion_cliente);
        } else {
          $("#name_cliente").val("-");
          $("#last_name_cliente").val("-");
          $("#phone_cliente").val("-");
          $("#address_cliente").val("-");
        }
      },
    });
  });

  $("#txt_cod_producto").on("change", function (e) {
    e.preventDefault();
    var cod = $("#txt_cod_producto").val();
    // alert(cod);
    $.ajax({
      type: "POST",
      url: "factura_action.php",
      data: "action=searchProducto&cod=" + cod,
      success: function (result) {
        // alert(result);
        var response = JSON.parse(result);
        if (response.status == 1) {
          $("#txt_descripcion").text(response.data.nombre_producto);
          $("#txt_existencia").text(response.data.existencia_producto);
          $("#txt_cant_producto").val(1);
          $("#txt_precio").text(response.data.costo_producto);
          $("#txt_precio_total").text(response.data.costo_producto);
          $("#txt_cant_producto").removeAttr("disabled");
          $("#btn_agregar_producto").slideDown("disabled");
        } else {
          $("#txt_descripcion").text("-");
          $("#txt_existencia").text("-");
          $("#txt_cant_producto").text("-");
          $("#txt_precio").text("-");
          $("#txt_precio_total").text("-");
          $("#txt_cant_producto").attr("disabled", "disabled");
          $("#btn_agregar_producto").slideUp("disabled");
        }
      },
    });
  });

  $("#txt_cant_producto").on("keyup", function (e) {
    e.preventDefault();
    var precio_total = $(this).val() * $("#txt_precio").html();
    var existencia = parseFloat($("#txt_existencia").text());
    $("#txt_precio_total").text(precio_total.toFixed(2));
    if (
      $(this).val() < 1 ||
      $(this).val() == "" ||
      isNaN($(this).val()) ||
      $(this).val() > existencia
    ) {
      $("#btn_agregar_producto").slideUp("disabled");
    } else {
      $("#btn_agregar_producto").slideDown("disabled");
    }
  });

  $("#btn_agregar_producto").on("click", function (e) {
    e.preventDefault();
    var cod = $("#txt_cod_producto").val();
    var cantidad = $("#txt_cant_producto").val();
    if (cantidad > 0) {
      $.ajax({
        type: "POST",
        url: "factura_action.php",
        data: "action=agregarProductoTemp&cod=" + cod + "&cantidad=" + cantidad,
        success: function (result) {
          var response = JSON.parse(result);
          if (response.status == 1) {
            $("#detalle_productos").html(response.data.detalle);
            $("#detalle_totales").html(response.data.totales);
            $("#txt_descripcion").text("-");
            $("#txt_existencia").text("-");
            $("#txt_cant_producto").text("-");
            $("#txt_precio").text("-");
            $("#txt_precio_total").text("-");
            $("#txt_cant_producto").attr("disabled", "disabled");
            $("#btn_agregar_producto").slideUp("disabled");
            viewProccess_factura();
          }
        },
      });
    }
  });

  $("#btn_facturar_venta").on("click", function (e) {});
});

function del_producto_detalle(id_prod_temp) {
  // alert(id_prod_temp);
  $.ajax({
    data: "action=delProductTemp&id=" + id_prod_temp,
    url: "factura_action.php",
    type: "post",
    success: function (result) {
      // alert(result);
      var response = JSON.parse(result);
      if (response.status == 1) {
        $("#detalle_productos").html(response.data.detalle);
        $("#detalle_totales").html(response.data.totales);
      }
      viewProccess_factura();
    },
  });
}

function viewProccess_factura() {
  if ($("#detalle_productos tr").length > 0) {
    $("#btn_facturar_venta").show();
  } else {
    $("#btn_facturar_venta").hide();
  }
}
