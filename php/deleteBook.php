<?php
include '../db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];

    $stmt = $conn->prepare("DELETE FROM Books WHERE BookID = ?");
    $stmt->bind_param("s", $book_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error deleting book: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>