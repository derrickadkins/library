<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['admin'] !== true) {
    header('Location: ../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members</title>
    <link rel="icon" href="icon.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.25/datatables.min.css"/>
    <style>
        tr:hover td {
            background-color: #99ccff;
        }
    </style>
</head>
<body>
    <?php include "util/nav.php"; ?>
    <div class="container mt-3">
        <h1>Members</h1>
        <div id="error" class="alert alert-danger" role="alert" style="display: none;"></div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="loadingMessage">
                        <td colspan="5">Loading members...</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <a href="profile.php" class="btn btn-primary">Add Profile</a>
        </div>
    </div>
    <footer class="bg-dark text-white text-center p-3 mt-5">
        <p>© 2024 Library. All rights reserved.</p>
    </footer>

    <script>
        $(document).ready(function() {
            $.ajax({
                url: 'php/getMembers.php',
                type: 'GET',
                dataType: 'json',
                success: function(members) {
                    // console.log(members);
                    var tbody = $("tbody");
                    tbody.empty();

                    $.each(members, function(i, member) {
                        var tr = $("<tr id='" + member.RecID + "'>");
                        tr.append($("<td>").text(member.Name));
                        tr.append($("<td>").text(member.Email));
                        tr.append($("<td>").text(member.Phone));

                        tbody.append(tr);
                    });

                    $("tr:has(td)").on("click", function(event){
                        var recId = $(this).attr('id');
                        window.location.href = "profile.php?id=" + recId;
                    });

                    $("table").DataTable();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(textStatus, errorThrown);
                }
            });
        });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.25/datatables.min.js"></script>
</body>
</html>
