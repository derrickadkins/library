$(document).ready(function () {
  // Handle form submission for login
  $("form").on("submit", function (event) {
    event.preventDefault(); // Prevent the default form submission behavior

    $("#loginButton").prop("disabled", true); // Disable the login button to prevent multiple submissions

    // Make an AJAX request to the login endpoint
    $.ajax({
      url: "php/auth/login.php",
      type: "post",
      data: $(this).serialize(), // Serialize the form data
      success: function (response) {
        console.log(response); // Log the response for debugging
        if (response.trim() == "success") {
          window.location.href = "dashboard.php"; // Redirect to the dashboard on successful login
        } else {
          $("#error").html(response).show(); // Display the error message
          $("#loginButton").prop("disabled", false); // Re-enable the login button
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error(textStatus, errorThrown); // Log any errors that occur during the request
        $("#loginButton").prop("disabled", false); // Re-enable the login button
      },
    });
  });

  // Handle password visibility toggle
  $("#toggle-password").click(function () {
    $(this).toggleClass("fa-eye fa-eye-slash"); // Toggle the eye icon classes
    var input = $("#password");
    if (input.attr("type") === "password") {
      input.attr("type", "text"); // Show the password
    } else {
      input.attr("type", "password"); // Hide the password
    }
  });
});
