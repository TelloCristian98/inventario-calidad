var productError = document.getElementById("productError");
var submitError = document.getElementById("submitError");
function validate_product() {
  //   $(this).preventDefault();
  var product_name = document.getElementById("product_name").value;
  if (product_name.length == 0) {
    productError.innerHTML = "Nombre del producto es requerido";
    return false;
  }
  if (product_name.length <= 5) {
    productError.innerHTML =
      "El nombre del producto debe tener mas de tres caracteres";
    return false;
  }
  if (product_name.match(/[\'";\\;%<>&\(\)\[\]{}]/)) {
    productError.innerHTML = "Caracter no permitido";
    return false;
  }
  productError.innerHTML = "<i class='fa fa-check-circle'></i>";
  return true;
}

$(document).ready(function () {
  $(".editBtn").on("click", function () {
    $(this).closest("tr").find(".editSpan").hide();
    $(this).closest("tr").find(".addBtn").hide();
    $(this).closest("tr").find(".deleteBtn").hide();
    $(this).closest("tr").find(".editInput").show();
    $(this)
      .closest("tr")
      .find(".saveBtn")
      .show()
      .css({ color: "#ff0060", cursor: "pointer" });
    $(this)
      .closest("tr")
      .find(".cancelBtn")
      .show()
      .css({ color: "#ff0060", cursor: "pointer" });
    $(this).closest("tr").find(".editBtn").hide();
  });
});

$("#btn_crear_producto").click(function (e) {
  e.preventDefault();
  var rows = $("#detalle_material tr").length;
  var product_name = $("#product_name").val();
  if (rows > 0 && validate_product()) {
    alert(product_name);
    $.ajax({
      data: "action=createProduct&product_name=" + product_name,
      url: "product_action.php",
      type: "post",
      success: function (result) {
        // console.log(result);
        var response = JSON.parse(result);
        if (response.status == 1) {
          window.location.reload(true);
        }
      },
    });
  } else {
    submitError.innerHTML = "Falta el nombre del producto";
  }
});

$(document).ready(function () {
  $("#txt_cod_material").on("keyup", function () {
    var id_material = $(this).val();
    // alert(id_material);
    $.ajax({
      data: "action=search&campo=" + id_material,
      url: "product_action.php",
      type: "post",
      success: function (result) {
        // console.log(result);
        var response = JSON.parse(result);
        // console.log(response);
        if (response.status == 1) {
          $("#txt_descripcion").text(response.data.nombre_material);
          $("#txt_existencia").text(response.data.cantidad_material);
          $("#txt_cant_material").val(1);
          $("#txt_precio").text(response.data.costo_material);
          $("#txt_precio_total").text(response.data.costo_material);
          $("#txt_cant_material").removeAttr("disabled");
          $("#btn_agregar_material").slideDown("disabled");
        } else {
          $("#txt_descripcion").text("-");
          $("#txt_existencia").text("-");
          $("#txt_cant_material").val("-");
          $("#txt_precio").text("0.00");
          $("#txt_precio_total").text("0.00");
          $("#txt_cant_material").attr("disabled", "disabled");
          $("#btn_agregar_material").slideUp("disabled");
        }
      },
    });
  });
});

$("#txt_cant_material").keyup(function (e) {
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
    $("#btn_agregar_material").slideUp("disabled");
  } else {
    $("#btn_agregar_material").slideDown("disabled");
  }
});

$("#btn_agregar_material").click(function (e) {
  e.preventDefault();
  var id_material = $("#txt_cod_material").val();
  var cantidad = $("#txt_cant_material").val();
  if (cantidad > 0) {
    $.ajax({
      data:
        "action=addMaterialTemp&id=" + id_material + "&cantidad=" + cantidad,
      url: "product_action.php",
      type: "post",
      success: function (result) {
        // console.log(result);
        var response = JSON.parse(result);
        if (response.status == 1) {
          $("#detalle_material").html(response.data.detalle);
          $("#detalle_totales").html(response.data.totales);
          $("#txt_cod_material").val("");
          $("#txt_descripcion").text("-");
          $("#txt_existencia").text("-");
          $("#txt_cant_material").val("0");
          $("#txt_precio").text("0.00");
          $("#txt_precio_total").text("0.00");
          $("#txt_cant_material").attr("disabled", "disabled");
          $("#btn_agregar_material").slideUp("disabled");
        }
        viewProccess_product();
      },
    });
  }
});

function del_material_detalle(id_prod_temp) {
  $.ajax({
    data: "action=delMaterialTemp&id=" + id_prod_temp,
    url: "product_action.php",
    type: "post",
    success: function (result) {
      // console.log(result);
      var response = JSON.parse(result);
      if (response.status == 1) {
        $("#detalle_material").html(response.data.detalle);
        $("#detalle_totales").html(response.data.totales);
      }
      viewProccess_product();
    },
  });
}

function viewProccess_product() {
  if ($("#detalle_material tr").length > 0) {
    $("#btn_crear_producto").show();
  } else {
    $("#btn_crear_producto").hide();
  }
}

$(".cancelBtn").on("click", function () {
  var trObj = $(this).closest("tr");
  trObj.find(".editInput").hide();
  trObj.find(".editSpan").show();
  trObj.find(".editBtn").show();
  trObj.find(".saveBtn").hide();
  trObj.find(".cancelBtn").hide();
  trObj.find(".deleteBtn").show();
  trObj.find(".addBtn").show();
});

$(".deleteBtn").on("click", function () {
  var trObj = $(this).closest("tr");
  $(this)
    .closest("tr")
    .find(".confirmBtn")
    .show()
    .css({ color: "blue", cursor: "pointer" });
  $(this).closest("tr").find(".editBtn").hide();
  $(this).closest("tr").find(".deleteBtn").hide();
  $(this).closest("tr").find(".addBtn").hide();
  $(this).closest("tr").css("background-color", "#ffb0b0");
  var ID = $(this).closest("tr").attr("id");
  //   alert("ID: " + ID);
  $.ajax({
    data: "action=delete&id=" + ID,
    url: "product_action.php",
    type: "post",
    success: function (result) {
      //   alert(result);
      var response = JSON.parse(result);

      if (response.status == 1) {
        $(".resultados").html(response.msg);
        trObj.find(".editSpan.password").text(response.data);
      } else {
        $(".resultados").html(response.msg);
        $(this).closest("tr").find(".confirmBtn").hide();
        $(this).closest("tr").find(".editBtn").show();
        $(this).closest("tr").find(".deleteBtn").show();
        $(this).closest("tr").css("background-color", "");
      }
    },
  });
});

$(".confirmBtn").on("click", function () {
  var trObj = $(this).closest("tr");
  $(this).closest("tr").find(".confirmBtn").hide();
  $(this)
    .closest("tr")
    .find(".editBtn")
    .show()
    .css({ color: "#ff0060", cursor: "pointer" });
  $(this)
    .closest("tr")
    .find(".addBtn")
    .show()
    .css({ color: "#ff0060", cursor: "pointer" });
  $(this)
    .closest("tr")
    .find(".deleteBtn")
    .show()
    .css({ color: "#ff0060", cursor: "pointer" });
  $(this).closest("tr").css("background-color", "");
  var ID = $(this).closest("tr").attr("id");
  $.ajax({
    data: "action=active&id=" + ID,
    url: "product_action.php",
    type: "post",
    success: function (result) {
      //   alert(result);
      var response = JSON.parse(result);

      if (response.status == 1) {
        $(".resultados").html(response.msg);
        trObj.find(".editSpan.password").text(response.data);
      } else {
        $(".resultados").html(response.msg);
        $(this).closest("tr").find(".confirmBtn").hide();
        $(this).closest("tr").find(".editBtn").show();
        $(this).closest("tr").find(".deleteBtn").show();
        $(this).closest("tr").css("background-color", "");
      }
    },
  });
});

$(".campo").on("keyup", function () {
  var inputData = $(this).val();
  // alert(inputData);
  $.ajax({
    data: "action=search&campo=" + inputData,
    url: "product_action.php",
    type: "post",
    success: function (result) {
      console.log(result);
      var response = JSON.parse(result);
      if (response !== "") {
        $(".content").html(response);
      }
    },
  });
  // alert(inputData);
});

$(".saveBtn").on("click", function () {
  var ID = $(this).closest("tr").attr("id");
  var trObj = $(this).closest("tr");
  var inputData = $(this).closest("tr").find(".editInput").serialize();
  // alert(inputData);
  $.ajax({
    data: "action=edit&id=" + ID + "&" + inputData,
    url: "product_action.php",
    // dataType: "JSON",
    type: "post",

    success: function (result) {
      //   alert("Hola");
      // alert(result);
      var response = JSON.parse(result);
      //   $(".resultados").html(response);

      if (response.status == 1) {
        $(".resultados").html(response.msg);
        trObj
          .find(".editSpan.nombre_producto")
          .text(response.data.nombre_producto);
        // trObj.find(".editSpan.costo_unidad").text(response.data.costo_unidad);
        // trObj
        //   .find(".editSpan.cantidad_material")
        //   .text(response.data.cantidad_material);
        trObj.find(".editInput").hide();
        trObj.find(".editSpan").show();
        trObj.find(".editBtn").show();
        trObj.find(".deleteBtn").show();
        trObj.find(".saveBtn").hide();
        trObj.find(".cancelBtn").hide();
        trObj.find(".addBtn").show();
      } else {
        $(".resultados").html(response.msg);
        trObj.find(".editInput").hide();
        trObj.find(".editSpan").show();
        trObj.find(".editBtn").show();
        trObj.find(".saveBtn").hide();
        trObj.find(".cancelBtn").hide();
        trObj.find(".addBtn").show();
      }
    },
  });
});
