<?php
/*
    dashboard.php
    This script serves as the dashboard page for the library system. It checks if a user is logged in by
    verifying the presence of an email in the session. If the user is not logged in, they are redirected to 
    the index page. The script also checks if the logged-in user is an admin. The HTML section sets up the 
    page layout, including Bootstrap for styling and DataTables for enhanced table functionality. The page 
    displays the user's name, a list of books they have checked out, and additional admin-specific content 
    if the user is an admin. JavaScript/jQuery is used to dynamically load and handle the data displayed on 
    the page.
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
    <title><?php if($isAdmin) echo "Admin"; ?> Dashboard</title>
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
    <div class="container mt-3">
        <h1 class="display-4">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h1>
        <?php if($isAdmin): ?>
            <h1>Admin Dashboard</h1>
        <?php endif; ?>
        <h2 class="mt-3">Checked Out Books</h2>
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
        <?php if($isAdmin): ?>
            <hr />
            <h2>Checkouts Report</h2>
            <div class="mb-3">
                <a href="php/downloadCheckoutsReport.php" class="btn btn-primary">Download</a>
            </div>
        <?php endif; ?>
    </div>
    <footer class="bg-dark text-white text-center p-3 mt-5">
        <p>© 2024 Library. All rights reserved.</p>
    </footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.25/datatables.min.js"></script>
    <script src="js/dashboard.js"></script>
</body>
</html>
