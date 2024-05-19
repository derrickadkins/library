<?php
/*
 * This file serves as the Admin Dashboard page for a library management system.
 * It ensures that a user is logged in and is an admin before granting access.
 * The HTML section sets up the page layout, including Bootstrap and FontAwesome for 
 * styling, and DataTables for enhanced table functionality.
 * The page displays an admin dashboard with sections for viewing and managing books, 
 * members, and generating reports.
 * JavaScript/jQuery is used to handle dynamic content loading, form submissions for 
 * adding/deleting books and members, and updating passwords in real-time.
 */

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
    <title>Admin Dashboard</title>
    <link rel="icon" href="icon.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.25/datatables.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .delete-book, .delete-member {
            display: none;
        }
        
        tr:hover .delete-book, tr:hover .delete-member {
            display: block;
        }

        tr:hover td {
            background-color: #99ccff;
        }
    </style>
</head>
<body>
    <?php include "util/nav.php"; ?>
    <div class="container mt-5">
        <h1>Admin Dashboard</h1>
        <h2>Checkouts Report</h2>
        <div class="mb-3">
            <a href="php/downloadCheckoutsReport.php" class="btn btn-primary">Download</a>
        </div>
        <h2>Books</h2>
        <div id="errorBooks" class="alert alert-danger" role="alert" style="display: none;"></div>
        <div id="successBooks" class="alert alert-success" role="alert" style="display: none;"></div>
        <div class="table-responsive">
            <table id="booksTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Author</th>
                        <th>Title</th>
                        <th>ISBN</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="loadingBooks">
                        <td colspan="5">Loading books...</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="mb-3">
            <a href="addBook.php" class="btn btn-primary">Add Book</a>
        </div>
        <div class="mb-3">
            <a href="profile.php" class="btn btn-primary">Add Profile</a>
        </div>
    </div>
    <footer class="bg-dark text-white text-center p-3 mt-5">
        <p>© 2024 Library. All rights reserved.</p>
    </footer>

    <script>
    $(document).ready(function() {
        $.ajax({
            url: 'php/getBooks.php',
            type: 'GET',
            dataType: 'json',
            success: function(books) {
                console.log(books);
                var tbody = $("#booksTable tbody");
                tbody.empty();

                $.each(books, function(i, book) {
                    var tr = $("<tr id='" + book.RecID + "' class='bookRow'>");
                    tr.append($("<td>").text(book.Author));
                    tr.append($("<td>").text(book.Title));
                    tr.append($("<td>").text(book.ISBN));
                    if (book.CheckedOutBy) {
                        tr.append($("<td>").text('Checked Out by ' + book.CheckedOutBy + " on " + new Date(book.CheckedOutDate).toLocaleDateString('en-US', {month: '2-digit', day: '2-digit', year: '2-digit'})));
                    } else if (book.CheckedInBy) {
                        tr.append($("<td>").text('Available: Checked In by ' + book.CheckedInBy + " on " + new Date(book.CheckedInDate).toLocaleDateString('en-US', {month: '2-digit', day: '2-digit', year: '2-digit'})));
                    } else {
                        tr.append($("<td>").text('Available'));
                    }

                    tbody.append(tr);
                });

                $("tr.bookRow").on("click", function(event){
                    var recId = $(this).attr('id');
                    window.location.href = "book.php?id=" + recId;
                });

                $("#booksTable").DataTable();
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
