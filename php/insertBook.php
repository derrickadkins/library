<?php
include '../db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['author'])) {
    $author = $_POST['author'];
    $title = $_POST['title'];
    $isbn = $_POST['isbn'];
    $recid = uniqid(); // Generate a unique RecID

    $insert_book_sql = "INSERT INTO Books (Author, Title, ISBN, RecID) VALUES ('$author', '$title', '$isbn', '$recid')";

    if ($conn->query($insert_book_sql) === TRUE) {
        echo "success";
    } else {
        echo "Error adding book: " . $conn->error . "<br>";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>