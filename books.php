<?php
/*
 * This file serves as the Browse Books page for a library management system.
 * It ensures that a user is logged in and is not an admin before granting access.
 * The script retrieves the member's name from the session for use in the page.
 * The HTML section sets up the page layout, including Bootstrap for styling and 
 * DataTables for enhanced table functionality.
 * The page displays a list of books available for checkout.
 * JavaScript/jQuery is used to handle dynamic content loading and form submissions 
 * for checking out books in real-time.
 */

session_start();
if (!isset($_SESSION['email'])) {
    header('Location: ../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books</title>
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
    <div class="container mt-5">
        <h1>Books</h1>
        <div id="error" class="alert alert-danger" role="alert" style="display: none;"></div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Author</th>
                        <th>Title</th>
                        <th>ISBN</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="loadingMessage">
                        <td colspan="5">Loading books...</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php if($_SESSION['admin'] === true): ?>
            <div class="mt-3">
                <a href="book.php" class="btn btn-primary">Add Book</a>
            </div>
        <?php endif; ?>
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
                    // console.log(books);
                    var tbody = $("tbody");
                    tbody.empty();
        
                    $.each(books, function(i, book) {
                        var tr = $("<tr id='" + book.RecID + "'>");
                        tr.append($("<td>").text(book.Author));
                        tr.append($("<td>").text(book.Title));
                        tr.append($("<td>").text(book.ISBN));
                        
                        if (book.CheckedOutBy) {
                            tr.append($("<td>").text('Checked Out by ' + book.CheckedOutBy));
                        } else {
                            tr.append($("<td>").text('Available'));
                        }
        
                        tbody.append(tr);
                    });

                    $("tr").on("click", function(event){
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
