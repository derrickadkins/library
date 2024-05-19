$(document).ready(function () {
  if ($("#isUpdate").val() == "1") {
    $("#state").val($("#memberState").val());
  }

  function validateInput(regex, input, errorMsg) {
    if (!regex.test(input)) {
      $("#error").html(errorMsg).show();
      $("#success").hide();
      return false;
    }
    return true;
  }

  $("#profileForm").on("submit", function (event) {
    event.preventDefault();

    // Get form fields
    var name = $("#name").val();
    var email = $("#email").val();
    var street1 = $("#street1").val();
    var street2 = $("#street2").val();
    var city = $("#city").val();
    var zip = $("#zipcode").val();
    var phone = $("#phone").val();

    // Name Regex: only letters, spaces, hyphens, and apostrophes.
    var nameRegex = /^[a-zA-Z\s\-']{1,100}$/;
    // Email Regex: standard email format.
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    // City Regex: only letters and spaces.
    var cityRegex = /^[a-zA-Z\s]{1,100}$/;
    // Zip Code Regex: 5 digits or 5 digits followed by a hyphen and 4 digits (US zip code format).
    var zipRegex = /^\d{5}(-\d{4})?$/;
    // Phone Number Regex: 10 digits, allows for common formatting characters like spaces, hyphens, parentheses.
    var phoneRegex = /^\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}$/;

    if (
      !validateInput(nameRegex, name, "Please enter a valid name.") ||
      !validateInput(
        emailRegex,
        email,
        "Please enter a valid email address."
      ) ||
      !validateInput(cityRegex, city, "Please enter a valid city.") ||
      !validateInput(zipRegex, zip, "Please enter a valid zip code.") ||
      !validateInput(phoneRegex, phone, "Please enter a valid phone number.")
    ) {
      return;
    }

    var isUpdate = $("#isUpdate").val() == "1";

    if (!isUpdate) {
      var password = $("#password").val();
      // Password Regex: at least 8 characters long, contains an uppercase letter, a lowercase letter, and a number.
      var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;
      if (
        !validateInput(
          passwordRegex,
          password,
          "Please enter a valid password."
        )
      ) {
        return;
      }
    }

    var form = $(this);
    var profileForm = this;
    var profileUrl = isUpdate
      ? "php/member/updateProfile.php"
      : "php/member/addMember.php";
    form.find('input[type="submit"]').prop("disabled", true);

    $.ajax({
      url: profileUrl,
      type: "post",
      data: $(this).serialize(),
      success: function (response) {
        if (response.trim() == "success") {
          $("#success").show();
          $("#error").hide();
          if (!isUpdate) profileForm.reset();
        } else {
          $("#error").html(response).show();
          $("#success").hide();
        }
        form.find('input[type="submit"]').prop("disabled", false);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error(textStatus, errorThrown);
        form.find('input[type="submit"]').prop("disabled", false);
      },
    });
  });

  $("#togglePassword").click(function () {
    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $("#password");
    if (input.attr("type") === "password") {
      input.attr("type", "text");
    } else {
      input.attr("type", "password");
    }
  });

  $("#toggleOldPassword").click(function () {
    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $("#old_password");
    if (input.attr("type") === "password") {
      input.attr("type", "text");
    } else {
      input.attr("type", "password");
    }
  });

  $("#toggleNewPassword").click(function () {
    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $("#new_password");
    if (input.attr("type") === "password") {
      input.attr("type", "text");
    } else {
      input.attr("type", "password");
    }
  });

  $("#updatePasswordForm").on("submit", function (event) {
    event.preventDefault();

    var form = this;
    var newPassword = $("#new_password").val();
    var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;
    if (!regex.test(newPassword)) {
      $("#errorPassword")
        .html("Password does not meet the strength requirements.")
        .show();
      $("#successPassword").hide();
      return;
    }

    $("#updatePasswordButton").prop("disabled", true);

    $.ajax({
      url: "php/member/updatePassword.php",
      type: "post",
      data: $(this).serialize(),
      success: function (response) {
        if (response.trim() == "success") {
          $("#successPassword").show();
          $("#errorPassword").hide();
          form.reset();
        } else {
          $("#errorPassword").html(response).show();
          $("#successPassword").hide();
        }
        $("#updatePasswordButton").prop("disabled", false);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error(textStatus, errorThrown);
        $("#updatePasswordButton").prop("disabled", false);
      },
    });
  });

  $("#deleteForm").on("submit", function (event) {
    event.preventDefault();

    var confirmDelete = confirm(
      "Are you sure you want to delete this profile? This action is permanent and cannot be undone."
    );
    if (!confirmDelete) {
      return;
    }

    var form = $(this);
    form.find('input[type="submit"]').prop("disabled", true);

    $.ajax({
      url: "php/member/deleteMember.php",
      type: "post",
      data: form.serialize(),
      success: function (response) {
        // console.log(response);
        if (response.trim() == "success") {
          window.location.href = "dashboard.php";
        } else {
          $("#errorMembers").html(response).show();
          form.find('input[type="submit"]').prop("disabled", false);
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error(textStatus, errorThrown);
        form.find('input[type="submit"]').prop("disabled", false);
      },
    });
  });
});
