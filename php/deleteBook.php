<?php
/*
 * This script is used to delete a book from the library system.
 * It includes the db_connect.php file to establish a connection with the database.
 * 
 * The script checks if the request method is POST and if the book_id is set in the POST data.
 * If it is, it retrieves the book ID from the POST data.
 * 
 * It then checks if the book is currently checked out.
 * If the book is checked out, it automatically checks in the book.
 * 
 * After that, it prepares an SQL statement to delete the row from the Books table where the BookID matches the given book ID.
 * 
 * The script binds the book ID to the SQL statement and executes it.
 * If the SQL statement executes successfully, the script outputs "success". Otherwise, it outputs an error message.
 * 
 * The script then closes the SQL statement and the database connection.
 * If the request method is not POST or the book_id is not set in the POST data, the script outputs "Invalid request.".
 */

include '../db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_id'])) {
    $bookId = $_POST['book_id']; // Get the book ID from the POST parameters

    // Check if the book is currently checked out
    $checkout_sql = "SELECT * FROM Checkouts WHERE BookID = ? AND CheckedInDate IS NULL";
    $stmt = $conn->prepare($checkout_sql);
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Check in the book
        $checkin_sql = "UPDATE Checkouts SET CheckedInDate = NOW() WHERE BookID = ? AND CheckedInDate IS NULL";
        $stmt = $conn->prepare($checkin_sql);
        $stmt->bind_param("i", $bookId);
        $stmt->execute();
    }

    // Delete the book
    $delete_sql = "DELETE FROM Books WHERE BookID = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $bookId);

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