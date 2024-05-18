<?php
session_start();
include '../db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bookID = $_POST['book_id'];
    $author = $_POST['author'];
    $title = $_POST['title'];
    $isbn = $_POST['isbn'];

    $update_sql = "UPDATE Books SET 
                    Author = ?, 
                    Title = ?, 
                    ISBN = ? 
                    WHERE BookID = ?";

    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssss", $author, $title, $isbn, $bookID);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error updating book: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>