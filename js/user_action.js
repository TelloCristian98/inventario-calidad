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
    $(".status").html("Contraseña");

    // $(this).closest("tr").find(".status").hide();
  });
});

$(".saveBtn").on("click", function () {
  var ID = $(this).closest("tr").attr("id");
  var trObj = $(this).closest("tr");
  var inputData = $(this).closest("tr").find(".editInput").serialize();
  var selectedFile = $(this).closest("tr").find(".editInput[name='photo']")[0]
    .files[0];
  //   inputData.append("photo", imgName);
  if (selectedFile) {
    inputData += "&photo=" + encodeURIComponent(selectedFile.name);
    // movingPhoto();
    const formData = new FormData();
    formData.append("image", selectedFile);
    formData.append("id", ID);

    $.ajax({
      url: "upload_img.php",
      type: "POST",
      data: formData,
      success: function (msg) {
        // alert(msg);
        $(".resultadosImg").html(msg);
      },
      cache: false,
      contentType: false,
      processData: false,
      error: function () {
        alert("Error al subir la imagen: ");
      },
    });
  }
  //   alert(inputData);
  $.ajax({
    data: "action=edit&id=" + ID + "&" + inputData,
    url: "user_action.php",
    // dataType: "JSON",
    type: "post",

    success: function (result) {
      //   alert("Hola");
      //   alert(result);
      var response = JSON.parse(result);

      //   $(".resultados").html(response);

      if (response.status == 1) {
        $(".resultados").html(response.msg);
        trObj.find(".editSpan.first_name").text(response.data.first_name);
        trObj.find(".editSpan.last_name").text(response.data.last_name);
        trObj.find(".editSpan.correo").text(response.data.correo);
        trObj.find(".editSpan.user").text(response.data.user);
        trObj.find(".editSpan.rol").text(response.data.rol);
        trObj.find(".editSpan.photo").text(response.data.photo);
        trObj.find(".editInput").hide();
        trObj.find(".editSpan").show();
        trObj.find(".editBtn").show();
        trObj.find(".saveBtn").hide();
        trObj.find(".cancelBtn").hide();
        trObj.find(".deleteBtn").show();
        $(".status").html("Estado");
      } else {
        $(".resultados").html(response.msg);
        trObj.find(".editInput").hide();
        trObj.find(".editSpan").show();
        trObj.find(".editBtn").show();
        trObj.find(".saveBtn").hide();
        trObj.find(".cancelBtn").hide();
        trObj.find(".deleteBtn").show();
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
  $(".status").html("Estado");
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
    url: "user_action.php",
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
    url: "user_action.php",
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
    url: "user_action.php",
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
