<?php
include "../db/db_connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bookId = $_POST['book_id'];
    $checkInDate = date('Y-m-d H:i:s');

    $checkIn_sql = "UPDATE Checkouts SET
                    CheckedInDate = '$checkInDate'
                    WHERE BookID = '$bookId'";
    
    if ($conn->query($checkIn_sql) === TRUE) {
        echo "success";
    } else {
        echo "Error checking in book: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>