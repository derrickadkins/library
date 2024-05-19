$(document).ready(function () {
  $.ajax({
    url: "php/book/getCheckedOutBooks.php",
    type: "GET",
    dataType: "json",
    success: function (checkouts) {
      $("tbody").empty();
      $.each(checkouts, function (i, checkout) {
        var tr = $("<tr id='" + checkout.RecID + "'>");
        tr.append($("<td>").text(checkout.Author));
        tr.append($("<td>").text(checkout.Title));
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
          var diff = Math.ceil((dueDate - new Date()) / (1000 * 60 * 60 * 24));
          if (diff > 0) {
            status = "Due in " + diff + " days";
          } else {
            status = "Overdue by " + Math.abs(diff) + " days";
          }
        }
        tr.append($("<td>").text(status));

        $("tbody").append(tr);
      });

      $("tr:has(td)").on("click", function (event) {
        var recId = $(this).attr("id");
        window.location.href = "book.php?id=" + recId;
      });

      $("table").DataTable({
        order: [[3, "desc"]],
      });
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error(textStatus, errorThrown);
    },
  });
});
