<?php
include '../db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['author'])) {
    $author = $_POST['author'];
    $title = $_POST['title'];
    $isbn = $_POST['isbn'];
    $recid = uniqid(); // Generate a unique RecID

    $stmt = $conn->prepare("INSERT INTO Books (Author, Title, ISBN, RecID) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $author, $title, $isbn, $recid);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error adding book: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>