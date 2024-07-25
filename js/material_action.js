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

$(".saveBtn").on("click", function () {
  var ID = $(this).closest("tr").attr("id");
  var trObj = $(this).closest("tr");
  var inputData = $(this).closest("tr").find(".editInput").serialize();
  //   alert(inputData);
  $.ajax({
    data: "action=edit&id=" + ID + "&" + inputData,
    url: "material_action.php",
    // dataType: "JSON",
    type: "post",

    success: function (result) {
      //   alert("Hola");
      alert(result);
      var response = JSON.parse(result);
      //   $(".resultados").html(response);

      if (response.status == 1) {
        $(".resultados").html(response.msg);
        trObj
          .find(".editSpan.nombre_material")
          .text(response.data.nombre_material);
        trObj.find(".editSpan.nombre_unidad").text(response.data.nombre_unidad);
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
    url: "material_action.php",
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
    url: "material_action.php",
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
    url: "material_action.php",
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

$(".addBtn").click(function () {
  var ID = $(this).closest("tr").attr("id");
  var trObj = $(this).closest("input");
  // alert(ID);
  $.ajax({
    data: "action=add&id=" + ID,
    url: "material_action.php",
    type: "post",
    success: function (result) {
      // alert(result);
      var response = JSON.parse(result);
      if (response.status == 1) {
        $(".addnombre_material").html(
          "Agregar " + response.data.nombre_material
        );
        $("#addId_material").val(response.data.id_material);
        // $(".content").html(response);
      }
    },
  });
});

function sendDataMaterial() {
  var inputData = $("#form_add_material").serialize();
  // alert(inputData);
  $.ajax({
    data: "action=addMaterial&" + inputData,
    url: "material_action.php",
    type: "post",
    success: function (result) {
      console.log(result);
      // alert(result);
      var response = JSON.parse(result);
      if (response.status == 1) {
        $(".resultados").html(response.msg);
        $("#addCantidad_material").val("");
        $("#addcostounidad_material").val("");
      } else {
        alert(response.msg);
      }
    },
  });
}

function closeModal() {
  $("#addCantidad_material").val("");
  $("#addcostounidad_material").val("");
}
