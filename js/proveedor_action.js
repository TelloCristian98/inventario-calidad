$(document).ready(function () {
  $(".editBtn").on("click", function () {
    $(this).closest("tr").find(".editSpan").hide();
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
  $.ajax({
    data: "action=update&id_proveedor=" + ID + "&" + inputData,
    url: "proveedor_action.php",
    type: "post",
    success: function (result) {
      var response = JSON.parse(result);
      if (response.status == 1) {
        $(".resultados").html(response.msg);
        trObj
          .find(".editSpan.nombre_proveedor")
          .text(response.data.nombre_proveedor);
        trObj
          .find(".editSpan.direccion_proveedor")
          .text(response.data.direccion_proveedor);
        trObj
          .find(".editSpan.telefono_proveedor")
          .text(response.data.telefono_proveedor);
        trObj
          .find(".editSpan.email_proveedor")
          .text(response.data.email_proveedor);
        trObj.find(".editInput").hide();
        trObj.find(".editSpan").show();
        trObj.find(".editBtn").show();
        trObj.find(".deleteBtn").show();
        trObj.find(".saveBtn").hide();
        trObj.find(".cancelBtn").hide();
      } else {
        $(".resultados").html(response.msg);
        trObj.find(".editInput").hide();
        trObj.find(".editSpan").show();
        trObj.find(".editBtn").show();
        trObj.find(".saveBtn").hide();
        trObj.find(".cancelBtn").hide();
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
  $(this).closest("tr").css("background-color", "#ffb0b0");
  var ID = $(this).closest("tr").attr("id");
  $.ajax({
    data: "action=delete&id=" + ID,
    url: "proveedor_action.php",
    type: "post",
    success: function (result) {
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
    .find(".deleteBtn")
    .show()
    .css({ color: "#ff0060", cursor: "pointer" });
  $(this).closest("tr").css("background-color", "");
  var ID = $(this).closest("tr").attr("id");
  $.ajax({
    data: "action=active&id=" + ID,
    url: "proveedor_action.php",
    type: "post",
    success: function (result) {
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

$("#campo").on("keyup", function () {
  var inputData = $(this).val();
  $.ajax({
    data: "action=search&campo=" + inputData,
    url: "proveedor_action.php",
    type: "post",
    success: function (result) {
      console.log(result);
      var response = JSON.parse(result);
      if (response !== "") {
        $(".content").html(response);
      }
    },
  });
});
