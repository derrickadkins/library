$(document).ready(function () {
  // Make an AJAX request to fetch members data
  $.ajax({
    url: "php/member/getMembers.php",
    type: "GET",
    dataType: "json",
    success: function (members) {
      var tbody = $("tbody");
      tbody.empty(); // Clear the table body

      // Iterate over each member in the response
      $.each(members, function (i, member) {
        var tr = $("<tr id='" + member.RecID + "'>"); // Create a new table row with the member's RecID as its ID
        tr.append($("<td>").text(member.Name)); // Add the name cell
        tr.append($("<td>").text(member.Email)); // Add the email cell
        tr.append($("<td>").text(member.Phone)); // Add the phone cell

        tbody.append(tr); // Append the row to the table body
      });

      // Add a click event listener to each table row
      $("tr:has(td)").on("click", function (event) {
        var recId = $(this).attr("id"); // Get the RecID of the clicked row
        window.location.href = "profile.php?id=" + recId; // Redirect to the profile details page
      });

      $("table").DataTable(); // Initialize the DataTable plugin
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error(textStatus, errorThrown); // Log any errors that occur during the request
    },
  });
});
