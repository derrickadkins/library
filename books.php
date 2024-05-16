<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['admin'] === true) {
    header('Location: ../index.html');
    exit();
}

include 'db/db_connect.php';

$email = $_SESSION['email'];

// Fetch member details
$member_sql = "SELECT * FROM Members WHERE Email='$email'";
$member_result = $conn->query($member_sql);
$member = $member_result->fetch_assoc();
$person_id = $member['PersonID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout'])) {
    $book_id = $_POST['book_id'];
    $checkout_date = date('Y-m-d H:i:s');

    $checkout_sql = "INSERT INTO Checkouts (PersonID, BookID, CheckedOutDate) VALUES ('$person_id', '$book_id', '$checkout_date')";

    if ($conn->query($checkout_sql) === TRUE) {
        echo "Book checked out successfully.";
    } else {
        echo "Error checking out book: " . $conn->error;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Books</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include "util/nav.php"; ?>
    <div class="container mt-5">
        <h1>Browse Books</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Author</th>
                    <th>Title</th>
                    <th>ISBN</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr id="loadingMessage">
                    <td colspan="5">Loading books...</td>
                </tr>
            </tbody>
        </table>
    </div>
    <footer class="bg-dark text-white text-center p-3 mt-5">
        <p>© 2024 Library. All rights reserved.</p>
    </footer>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $.ajax({
            url: 'php/getBooks.php',
            type: 'GET',
            dataType: 'json',
            success: function(books) {
                console.log(books);
                var tbody = $("tbody");
                tbody.empty();
    
                $.each(books, function(i, book) {
                    var tr = $("<tr>");
                    tr.append($("<td>").text(book.Author));
                    tr.append($("<td>").text(book.Title));
                    tr.append($("<td>").text(book.ISBN));
                    tr.append($("<td>").text(book.CheckedOut == "1" ? 'Checked Out' : 'Available'));
    
                    if (book.CheckedOut == "0") {
                        var form = $("<form>", {action: "books.php", method: "POST"});
                        form.append($("<input>", {type: "hidden", name: "book_id", value: book.BookID}));
                        form.append($("<button>", {type: "submit", name: "checkout", class: "btn btn-primary"}).text("Check Out"));
                        tr.append($("<td>").append(form));
                    } else {
                        tr.append($("<td>"));
                    }
    
                    tbody.append(tr);
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown);
            }
        });
    });
</script>
</html>
