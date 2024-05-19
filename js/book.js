$(document).ready(function () {
  // Handle form submission for checking out and checking in books
  $(".checkBookForm").on("submit", function (event) {
    event.preventDefault(); // Prevent the default form submission behavior

    var form = $(this);
    form.find('input[type="submit"]').prop("disabled", true); // Disable the submit button

    var isAdmin = $("#isAdmin").val() == "1"; // Check if the user is an admin
    var isAvailable = $("#isAvailable").val() == "1"; // Check if the book is available

    // Determine the appropriate URL for checking out or checking in the book
    var checkBookUrl = isAvailable
      ? "php/book/checkOutBook.php"
      : "php/book/checkInBook.php";

    // Make an AJAX request to check out or check in the book
    $.ajax({
      url: checkBookUrl,
      type: "post",
      data: $(this).serialize(),
      success: function (response) {
        if (response.trim() == "success") {
          // Update the status of the book based on the action taken
          var today = new Date().toLocaleDateString("en-US", {
            month: "2-digit",
            day: "2-digit",
            year: "2-digit",
          });

          var status =
            "Checked Out by " + $("#username").val() + " on " + today;
          if (!isAvailable && isAdmin) {
            status =
              "Available: Checked In by " +
              $("#username").val() +
              " on " +
              today;
          } else if (!isAvailable) {
            status = "Available";
          }

          console.log(status);

          $("#status").val(status); // Update the status field
          var disabledButtonID = isAvailable
            ? "#checkInButton"
            : "#checkOutButton";
          $(disabledButtonID).prop("disabled", false); // Enable the appropriate button
          $("#isAvailable").val(isAvailable ? "0" : "1"); // Toggle the availability status
        } else if (response.trim() == "checked out") {
          alert("The book is already checked out."); // Show an alert if the book is already checked out
          location.reload(); // Reload the page
        } else {
          $("#error").html(response).show(); // Show an error message
          form.find('input[type="submit"]').prop("disabled", false); // Enable the submit button
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error(textStatus, errorThrown); // Log any errors
        form.find('input[type="submit"]').prop("disabled", false); // Enable the submit button
      },
    });
  });

  // Handle form submission for deleting a book
  $("#deleteForm").on("submit", function (event) {
    event.preventDefault(); // Prevent the default form submission behavior

    var confirmDelete = confirm(
      "Are you sure you want to delete this book? This action is permanent and cannot be undone."
    );
    if (!confirmDelete) {
      return; // Exit if the user cancels the delete action
    }

    var form = $(this);
    form.find('input[type="submit"]').prop("disabled", true); // Disable the submit button

    // Make an AJAX request to delete the book
    $.ajax({
      url: "php/book/deleteBook.php",
      type: "post",
      data: $(this).serialize(),
      success: function (response) {
        if (response.trim() == "success") {
          window.location.href = "dashboard.php"; // Redirect to the dashboard on success
        } else {
          $("#errorBooks").html(response).show(); // Show an error message
          form.find('input[type="submit"]').prop("disabled", false); // Enable the submit button
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error(textStatus, errorThrown); // Log any errors
        form.find('input[type="submit"]').prop("disabled", false); // Enable the submit button
      },
    });
  });

  // Handle form submission for adding or updating a book
  $("#bookForm").on("submit", function (event) {
    event.preventDefault(); // Prevent the default form submission behavior

    // Get form fields
    var author = $("#author").val();
    var title = $("#title").val();
    var isbn = $("#isbn").val();

    // Author Regex: only letters, spaces, hyphens, apostrophes, and periods.
    var authorRegex = /^[a-zA-Z\s\-'.]{1,100}$/;
    // Title Regex: only letters, numbers, spaces, and common punctuation marks.
    var titleRegex = /^[a-zA-Z0-9\s\-:',.?!]{1,200}$/;
    // ISBN Regex: Validates both ISBN-10 and ISBN-13 formats, including hyphens and spaces
    var isbnRegex =
      /((978[\--– ])?[0-9][0-9\--– ]{10}[\--– ][0-9xX])|((978)?[0-9]{9}[0-9Xx])/;

    // Validate form fields, show error message and return on first failure
    if (!authorRegex.test(author)) {
      $("#error").html("Please enter a valid author name.").show();
      $("#success").hide();
      return;
    } else if (!titleRegex.test(title)) {
      $("#error").html("Please enter a valid book title.").show();
      $("#success").hide();
      return;
    } else if (!isbnRegex.test(isbn)) {
      $("#error").html("Please enter a valid ISBN-10 or ISBN-13.").show();
      $("#success").hide();
      return;
    }

    var form = $(this);
    var bookForm = this;
    var isUpdate = $("#isUpdate").val() == "1"; // Check if this is an update operation
    form.find('input[type="submit"]').prop("disabled", true); // Disable the submit button

    // Determine the appropriate URL for adding or updating a book
    var bookActionUrl = isUpdate
      ? "php/book/updateBook.php"
      : "php/book/addBook.php";

    // Make an AJAX request to add or update the book
    $.ajax({
      url: bookActionUrl,
      type: "post",
      data: $(this).serialize(),
      success: function (response) {
        if (response.trim() == "success") {
          $("#success").show(); // Show a success message
          $("#error").hide();
          if (!isUpdate) bookForm.reset(); // Reset the form if adding a new book
        } else {
          $("#error").html(response).show(); // Show an error message
          $("#success").hide();
        }
        form.find('input[type="submit"]').prop("disabled", false); // Enable the submit button
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error(textStatus, errorThrown); // Log any errors
        form.find('input[type="submit"]').prop("disabled", false); // Enable the submit button
      },
    });
  });
});
