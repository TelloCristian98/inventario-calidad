$(".campo").on("keyup", function () {
  var inputData = $(this).val();
  // alert(inputData);
  $.ajax({
    data: "action=search&campo=" + inputData,
    url: "inventario_action.php",
    type: "post",
    success: function (result) {
      //   console.log(result);
      var response = JSON.parse(result);
      if (response !== "") {
        $(".content").html(response);
      }
    },
  });
  // alert(inputData);
});
