$(document).ready(function () {
  // If this is an update, set the state select value to the member's state
  if ($("#isUpdate").val() == "1") {
    $("#state").val($("#memberState").val());
  }

  // Function to validate input fields using regex
  function validateInput(regex, input, errorMsg) {
    if (!regex.test(input)) {
      $("#error").html(errorMsg).show();
      $("#success").hide();
      return false;
    }
    return true;
  }

  // Handle form submission for updating or adding a profile
  $("#profileForm").on("submit", function (event) {
    event.preventDefault(); // Prevent the default form submission behavior

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

    // Validate form fields, show error message and return on first failure
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

    var isUpdate = $("#isUpdate").val() == "1"; // Check if this is an update operation

    // If not an update, validate the password
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
      : "php/member/addMember.php"; // Determine the appropriate URL for adding or updating a profile
    form.find('input[type="submit"]').prop("disabled", true); // Disable the submit button

    // Make an AJAX request to add or update the profile
    $.ajax({
      url: profileUrl,
      type: "post",
      data: $(this).serialize(), // Serialize the form data
      success: function (response) {
        if (response.trim() == "success") {
          $("#success").show(); // Show success message
          $("#error").hide();
          if (!isUpdate) profileForm.reset(); // Reset the form if adding a new profile
        } else {
          $("#error").html(response).show(); // Show error message
          $("#success").hide();
        }
        form.find('input[type="submit"]').prop("disabled", false); // Re-enable the submit button
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error(textStatus, errorThrown); // Log any errors
        form.find('input[type="submit"]').prop("disabled", false); // Re-enable the submit button
      },
    });
  });

  // Toggle password visibility for the password field
  $("#togglePassword").click(function () {
    $(this).toggleClass("fa-eye fa-eye-slash"); // Toggle the eye icon classes
    var input = $("#password");
    if (input.attr("type") === "password") {
      input.attr("type", "text"); // Show the password
    } else {
      input.attr("type", "password"); // Hide the password
    }
  });

  // Toggle password visibility for the old password field
  $("#toggleOldPassword").click(function () {
    $(this).toggleClass("fa-eye fa-eye-slash"); // Toggle the eye icon classes
    var input = $("#old_password");
    if (input.attr("type") === "password") {
      input.attr("type", "text"); // Show the old password
    } else {
      input.attr("type", "password"); // Hide the old password
    }
  });

  // Toggle password visibility for the new password field
  $("#toggleNewPassword").click(function () {
    $(this).toggleClass("fa-eye fa-eye-slash"); // Toggle the eye icon classes
    var input = $("#new_password");
    if (input.attr("type") === "password") {
      input.attr("type", "text"); // Show the new password
    } else {
      input.attr("type", "password"); // Hide the new password
    }
  });

  // Handle form submission for updating the password
  $("#updatePasswordForm").on("submit", function (event) {
    event.preventDefault(); // Prevent the default form submission behavior

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

    $("#updatePasswordButton").prop("disabled", true); // Disable the submit button

    // Make an AJAX request to update the password
    $.ajax({
      url: "php/member/updatePassword.php",
      type: "post",
      data: $(this).serialize(), // Serialize the form data
      success: function (response) {
        if (response.trim() == "success") {
          $("#successPassword").show(); // Show success message
          $("#errorPassword").hide();
          form.reset(); // Reset the form
        } else {
          $("#errorPassword").html(response).show(); // Show error message
          $("#successPassword").hide();
        }
        $("#updatePasswordButton").prop("disabled", false); // Re-enable the submit button
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error(textStatus, errorThrown); // Log any errors
        $("#updatePasswordButton").prop("disabled", false); // Re-enable the submit button
      },
    });
  });

  // Handle form submission for deleting a profile
  $("#deleteForm").on("submit", function (event) {
    event.preventDefault(); // Prevent the default form submission behavior

    var confirmDelete = confirm(
      "Are you sure you want to delete this profile? This action is permanent and cannot be undone."
    );
    if (!confirmDelete) {
      return; // Exit if the user cancels the delete action
    }

    var form = $(this);
    form.find('input[type="submit"]').prop("disabled", true); // Disable the submit button

    // Make an AJAX request to delete the profile
    $.ajax({
      url: "php/member/deleteMember.php",
      type: "post",
      data: form.serialize(), // Serialize the form data
      success: function (response) {
        if (response.trim() == "success") {
          window.location.href = "dashboard.php"; // Redirect to the dashboard on success
        } else {
          $("#errorMembers").html(response).show(); // Show error message
          form.find('input[type="submit"]').prop("disabled", false); // Re-enable the submit button
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error(textStatus, errorThrown); // Log any errors
        form.find('input[type="submit"]').prop("disabled", false); // Re-enable the submit button
      },
    });
  });
});
