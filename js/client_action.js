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

    // $(this).closest("tr").find(".status").hide();
  });
});

$(".saveBtn").on("click", function () {
  var ID = $(this).closest("tr").attr("id");
  var trObj = $(this).closest("tr");
  var inputData = $(this).closest("tr").find(".editInput").serialize();
  //   alert(inputData);
  $.ajax({
    data: "action=edit&id=" + ID + "&" + inputData,
    url: "client_action.php",
    // dataType: "JSON",
    type: "post",

    success: function (result) {
      //   alert("Hola");
      // alert(result);
      var response = JSON.parse(result);

      //   $(".resultados").html(response);

      if (response.status == 1) {
        $(".resultados").html(response.msg);
        trObj.find(".editSpan.first_name").text(response.data.first_name);
        trObj.find(".editSpan.last_name").text(response.data.last_name);
        trObj.find(".editSpan.ci").text(response.data.ci);
        trObj.find(".editSpan.phone").text(response.data.phone);
        trObj.find(".editSpan.adress").text(response.data.adress);
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
  //   alert("ID: " + ID);
  $.ajax({
    data: "action=delete&id=" + ID,
    url: "client_action.php",
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
    .find(".deleteBtn")
    .show()
    .css({ color: "#ff0060", cursor: "pointer" });
  $(this).closest("tr").css("background-color", "");
  var ID = $(this).closest("tr").attr("id");
  $.ajax({
    data: "action=active&id=" + ID,
    url: "client_action.php",
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

// document.getElementById("campo").addEventListener("keyup", getData);
$("#campo").on("keyup", function () {
  var inputData = $(this).val();
  $.ajax({
    data: "action=search&campo=" + inputData,
    url: "client_action.php",
    type: "post",
    success: function (result) {
      // alert(result);
      var response = JSON.parse(result);
      if (response !== "") {
        $(".content").html(response);
      }
    },
  });
  // alert(inputData);
});
