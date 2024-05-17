<?php
include "../db/db_connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bookId = $_POST['book_id'];
    $checkInDate = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("UPDATE Checkouts SET CheckedInDate = ? WHERE BookID = ?");
    $stmt->bind_param("ss", $checkInDate, $bookId);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error checking in book: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>