<?php
/*
    books.php
    This script serves as the books page for the library system. It checks if a user is logged in by
    verifying the presence of an email in the session. If the user is not logged in, they are redirected to 
    the index page. The HTML section sets up the page layout, including Bootstrap for styling and DataTables 
    for enhanced table functionality. The page displays a list of books available in the library, with their 
    author, title, ISBN, and status. If the logged-in user is an admin, they are provided with an option to 
    add new books. JavaScript/jQuery is used to dynamically load and handle the data displayed on the page.
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
    <div class="container mt-3">
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.25/datatables.min.js"></script>
    <script src="js/books.js"></script>
</body>
</html>
