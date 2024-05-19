<?php
/*
 * This file serves as the Member Dashboard page for a library management system.
 * It ensures that a user is logged in and is not an admin before granting access.
 * The script includes the database connection file and retrieves member details from the 
 * database using the logged-in member's email.
 * The HTML section sets up the page layout, including Bootstrap and FontAwesome for 
 * styling, and DataTables for enhanced table functionality.
 * The page displays member details, a list of books checked out by the member, and forms 
 * for updating profile information and changing the password.
 * JavaScript/jQuery is used to handle dynamic content loading, form submissions, and 
 * real-time updates.
 */

session_start();
if (!isset($_SESSION['email'])) {
    header('Location: ../index.php');
    exit();
}
$isAdmin = $_SESSION['admin'] === true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
    <input type="hidden" id="isAdmin" value="<?php echo $isAdmin; ?>">
    <div class="container mt-5">
        <h1 class="display-4">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h1>
        <h2>Checked Out Books</h2>
        <div id="errorBooks" class="alert alert-danger" role="alert" style="display: none;"></div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Author</th>
                        <th>Title</th>
                        <th>Checked Out <?php echo $isAdmin ? "By" : "Date"; ?></th>
                        <th>Due Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="checkoutsTable">
                <tr id="loadingMessage">
                    <td colspan="5">Loading checkouts...</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <a href="books.php" class="btn btn-primary">Checkout New Book</a>
        </div>
    </div>
    <footer class="bg-dark text-white text-center p-3 mt-5">
        <p>© 2024 Library. All rights reserved.</p>
    </footer>
    <script>
    $(document).ready(function() {
        $.ajax({
            url: 'php/getCheckedOutBooks.php',
            type: 'GET',
            dataType: 'json',
            success: function(checkouts) {
                $("tbody").empty();
                $.each(checkouts, function(i, checkout) {
                    var tr = $("<tr id='" + checkout.RecID + "'>");
                    tr.append($("<td>").text(checkout.Author));
                    tr.append($("<td>").text(checkout.Title));
                    if($("#isAdmin").val() == "1"){
                        tr.append($("<td>").text(checkout.Name));
                    }else{
                        tr.append($("<td>").text(new Date(checkout.CheckedOutDate).toLocaleDateString('en-US', {month: '2-digit', day: '2-digit', year: '2-digit'})));
                    }
                    var dueDate = new Date(new Date(checkout.CheckedOutDate).setDate(new Date(checkout.CheckedOutDate).getDate() + 7));
                    tr.append($("<td>").text(dueDate.toLocaleDateString('en-US', {month: '2-digit', day: '2-digit', year: '2-digit'})));

                    var status;
                    if (checkout.CheckedInDate) {
                        status = 'Returned on ' + new Date(checkout.CheckedInDate).toLocaleDateString('en-US', {month: '2-digit', day: '2-digit', year: '2-digit'});
                    } else {
                        var diff = Math.ceil((dueDate - new Date()) / (1000 * 60 * 60 * 24));
                        if (diff > 0) {
                            status = 'Due in ' + diff + ' days';
                        } else {
                            status = 'Overdue by ' + Math.abs(diff) + ' days';
                        }
                    }
                    tr.append($("<td>").text(status));

                    $("tbody").append(tr);
                });

                $("tr td").on("click", function(event){
                    var recId = $(this).attr('id');
                    window.location.href = "book.php?id=" + recId;
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
