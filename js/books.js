$(document).ready(function () {
  $.ajax({
    url: "php/book/getBooks.php",
    type: "GET",
    dataType: "json",
    success: function (books) {
      // console.log(books);
      var tbody = $("tbody");
      tbody.empty();

      $.each(books, function (i, book) {
        var tr = $("<tr id='" + book.RecID + "'>");
        tr.append($("<td>").text(book.Author));
        tr.append($("<td>").text(book.Title));
        tr.append($("<td>").text(book.ISBN));

        if (book.CheckedOutBy) {
          tr.append($("<td>").text("Checked Out by " + book.CheckedOutBy));
        } else {
          tr.append($("<td>").text("Available"));
        }

        tbody.append(tr);
      });

      $("tr:has(td)").on("click", function (event) {
        var recId = $(this).attr("id");
        window.location.href = "book.php?id=" + recId;
      });

      $("table").DataTable();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error(textStatus, errorThrown);
    },
  });
});
