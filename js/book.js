$(document).ready(function () {
  $(".checkBookForm").on("submit", function (event) {
    event.preventDefault();
    var form = $(this);
    form.find('input[type="submit"]').prop("disabled", true);

    var isAdmin = $("#isAdmin").val() == "1";
    var isAvailable = $("#isAvailable").val() == "1";
    var checkBookUrl = isAvailable
      ? "php/book/checkOutBook.php"
      : "php/book/checkInBook.php";

    $.ajax({
      url: checkBookUrl,
      type: "post",
      data: $(this).serialize(),
      success: function (response) {
        // console.log(response);
        if (response.trim() == "success") {
          var today = new Date().toLocaleDateString("en-US", {
            month: "2-digit",
            day: "2-digit",
            year: "2-digit",
          });

          var status =
            "Checked Out by " + $("#username").val() + " on " + today;
          if (!isAvailable && isAdmin)
            status =
              "Available: Checked In by " +
              $("#username").val() +
              " on " +
              today;
          else if (!isAvailable) status = "Available";

          console.log(status);

          $("#status").val(status);
          var disabledButtonID = isAvailable
            ? "#checkInButton"
            : "#checkOutButton";
          $(disabledButtonID).prop("disabled", false);
          $("#isAvailable").val(isAvailable ? "0" : "1");
        } else if (response.trim() == "checked out") {
          alert("The book is already checked out.");
          location.reload();
        } else {
          $("#error").html(response).show();
          form.find('input[type="submit"]').prop("disabled", false);
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error(textStatus, errorThrown);
        form.find('input[type="submit"]').prop("disabled", false);
      },
    });
  });

  $("#deleteForm").on("submit", function (event) {
    event.preventDefault();

    var confirmDelete = confirm(
      "Are you sure you want to delete this book? This action is permanent and cannot be undone."
    );
    if (!confirmDelete) {
      return;
    }

    var form = $(this);
    form.find('input[type="submit"]').prop("disabled", true);

    $.ajax({
      url: "php/book/deleteBook.php",
      type: "post",
      data: $(this).serialize(),
      success: function (response) {
        if (response.trim() == "success") {
          window.location.href = "dashboard.php";
        } else {
          $("#errorBooks").html(response).show();
          form.find('input[type="submit"]').prop("disabled", false);
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error(textStatus, errorThrown);
        form.find('input[type="submit"]').prop("disabled", false);
      },
    });
  });

  $("#bookForm").on("submit", function (event) {
    event.preventDefault();

    // Get form fields
    var author = $("#author").val();
    var title = $("#title").val();
    var isbn = $("#isbn").val();

    // Author Regex: only letters, spaces, hyphens, and apostrophes.
    var authorRegex = /^[a-zA-Z\s\-']{1,100}$/;
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
    var isUpdate = $("#isUpdate").val() == "1";
    form.find('input[type="submit"]').prop("disabled", true);
    var bookActionUrl = isUpdate
      ? "php/book/updateBook.php"
      : "php/book/addBook.php";

    $.ajax({
      url: bookActionUrl,
      type: "post",
      data: $(this).serialize(),
      success: function (response) {
        if (response.trim() == "success") {
          $("#success").show();
          $("#error").hide();
          if (!isUpdate) bookForm.reset();
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
});
