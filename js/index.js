$(document).ready(function () {
  $("form").on("submit", function (event) {
    event.preventDefault();

    $("#loginButton").prop("disabled", true);

    $.ajax({
      url: "php/auth/login.php",
      type: "post",
      data: $(this).serialize(),
      success: function (response) {
        console.log(response);
        if (response.trim() == "success") {
          window.location.href = "dashboard.php";
        } else {
          $("#error").html(response).show();
          $("#loginButton").prop("disabled", false);
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error(textStatus, errorThrown);
        $("#loginButton").prop("disabled", false);
      },
    });
  });

  $("#toggle-password").click(function () {
    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $("#password");
    if (input.attr("type") === "password") {
      input.attr("type", "text");
    } else {
      input.attr("type", "password");
    }
  });
});
