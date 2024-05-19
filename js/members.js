$(document).ready(function () {
  $.ajax({
    url: "php/member/getMembers.php",
    type: "GET",
    dataType: "json",
    success: function (members) {
      // console.log(members);
      var tbody = $("tbody");
      tbody.empty();

      $.each(members, function (i, member) {
        var tr = $("<tr id='" + member.RecID + "'>");
        tr.append($("<td>").text(member.Name));
        tr.append($("<td>").text(member.Email));
        tr.append($("<td>").text(member.Phone));

        tbody.append(tr);
      });

      $("tr:has(td)").on("click", function (event) {
        var recId = $(this).attr("id");
        window.location.href = "profile.php?id=" + recId;
      });

      $("table").DataTable();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error(textStatus, errorThrown);
    },
  });
});
