<?php
include '../db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];

    $delete_sql = "DELETE FROM Books WHERE BookID='$book_id'";

    if ($conn->query($delete_sql) === TRUE) {
        echo "success";
    } else {
        echo "Error deleting book: " . $conn->error;
    }
}
?>