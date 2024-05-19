<?php
/*
 * php/book/checkInBook.php
 * This script is used to check in a book in the library system.
 * 
 * It first checks if the user is logged in by verifying the email 
 * in the session. If the user is not logged in, they are 
 * redirected to the index page.
 * 
 * It includes the db_connect.php file to establish a connection with the database.
 * 
 * The script checks if the request method is POST. If it is, it retrieves the book ID from the POST data.
 * It then prepares an SQL statement to update the CheckedInDate field in the Checkouts table for the given book ID.
 * 
 * The script binds the current date and time, and the book ID to the SQL statement and executes it.
 * If the SQL statement executes successfully, the script outputs "success". Otherwise, it outputs an error message.
 * 
 * The script then closes the SQL statement and the database connection.
 * If the request method is not POST, the script outputs "Invalid request.".
 */
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: ../../index.php');
    exit();
}

include "../php/db_connect.php";

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