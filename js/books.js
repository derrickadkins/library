$(document).ready(function () {
  // Make an AJAX request to fetch books data
  $.ajax({
    url: "php/book/getBooks.php",
    type: "GET",
    dataType: "json",
    success: function (books) {
      var tbody = $("tbody");
      tbody.empty(); // Clear the table body

      // Iterate over each book in the response
      $.each(books, function (i, book) {
        var tr = $("<tr id='" + book.RecID + "'>"); // Create a new table row with the book's RecID as its ID
        tr.append($("<td>").text(book.Author)); // Add the author cell
        tr.append($("<td>").text(book.Title)); // Add the title cell
        tr.append($("<td>").text(book.ISBN)); // Add the ISBN cell

        // Add the status cell based on whether the book is checked out or available
        if (book.CheckedOutBy) {
          tr.append($("<td>").text("Checked Out by " + book.CheckedOutBy));
        } else {
          tr.append($("<td>").text("Available"));
        }

        tbody.append(tr); // Append the row to the table body
      });

      // Add a click event listener to each table row
      $("tr:has(td)").on("click", function (event) {
        var recId = $(this).attr("id"); // Get the RecID of the clicked row
        window.location.href = "book.php?id=" + recId; // Redirect to the book details page
      });

      $("table").DataTable(); // Initialize the DataTable plugin
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error(textStatus, errorThrown); // Log any errors that occur during the request
    },
  });
});
