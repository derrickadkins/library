$(document).ready(function () {
  // Make an AJAX request to fetch checked-out books data
  $.ajax({
    url: "php/book/getCheckedOutBooks.php",
    type: "GET",
    dataType: "json",
    success: function (checkouts) {
      $("tbody").empty(); // Clear the table body

      // Iterate over each checked-out book in the response
      $.each(checkouts, function (i, checkout) {
        var tr = $("<tr id='" + checkout.RecID + "'>"); // Create a new table row with the checkout's RecID as its ID
        tr.append($("<td>").text(checkout.Author)); // Add the author cell
        tr.append($("<td>").text(checkout.Title)); // Add the title cell

        // Add the name or checked-out date cell based on whether the user is an admin
        if ($("#isAdmin").val() == "1") {
          tr.append($("<td>").text(checkout.Name));
        } else {
          tr.append(
            $("<td>").text(
              new Date(checkout.CheckedOutDate).toLocaleDateString("en-US", {
                month: "2-digit",
                day: "2-digit",
                year: "2-digit",
              })
            )
          );
        }

        // Calculate and add the due date cell
        var dueDate = new Date(
          new Date(checkout.CheckedOutDate).setDate(
            new Date(checkout.CheckedOutDate).getDate() + 7
          )
        );
        tr.append(
          $("<td>").text(
            dueDate.toLocaleDateString("en-US", {
              month: "2-digit",
              day: "2-digit",
              year: "2-digit",
            })
          )
        );

        // Determine the status of the checked-out book
        var status;
        if (checkout.CheckedInDate) {
          status =
            "Returned on " +
            new Date(checkout.CheckedInDate).toLocaleDateString("en-US", {
              month: "2-digit",
              day: "2-digit",
              year: "2-digit",
            });
        } else {
          // Calculate the difference in days between the due date and the current date
          var diff = Math.ceil((dueDate - new Date()) / (1000 * 60 * 60 * 24));
          if (diff > 0) {
            status = "Due in " + diff + " days";
          } else {
            status = "Overdue by " + Math.abs(diff) + " days";
          }
        }
        tr.append($("<td>").text(status)); // Add the status cell

        $("tbody").append(tr); // Append the row to the table body
      });

      // Add a click event listener to each table row
      $("tr:has(td)").on("click", function (event) {
        var recId = $(this).attr("id"); // Get the RecID of the clicked row
        window.location.href = "book.php?id=" + recId; // Redirect to the book details page
      });

      // Initialize the DataTable plugin with sorting on the due date column
      $("table").DataTable({
        order: [[3, "desc"]],
      });
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error(textStatus, errorThrown); // Log any errors that occur during the request
    },
  });
});
