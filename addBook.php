<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['admin'] !== true) {
    header('Location: ../index.php');
    exit();
}

include 'db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $author = $_POST['author'];
    $title = $_POST['title'];
    $isbn = $_POST['isbn'];
    $recid = uniqid(); // Generate a unique RecID

    $insert_book_sql = "INSERT INTO Books (Author, Title, ISBN, RecID) VALUES ('$author', '$title', '$isbn', '$recid')";

    if ($conn->query($insert_book_sql) === TRUE) {
        echo "Book added successfully<br>";
    } else {
        echo "Error adding book: " . $conn->error . "<br>";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include "util/admin_nav.php"; ?>
    <div class="container mt-5">
        <h1>Add Book</h1>
        <form action="addBook.php" method="POST">
            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" id="author" name="author" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="isbn">ISBN</label>
                <input type="text" id="isbn" name="isbn" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Book</button>
        </form>
    </div>
    <footer class="bg-dark text-white text-center p-3 mt-5">
        <p>© 2024 Library. All rights reserved.</p>
    </footer>
</body>
</html>
