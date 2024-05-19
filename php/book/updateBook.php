<?php
/*
    php/book/updateBook.php
    This script handles the process of updating book details in the library system.
    
    It first checks if the user is logged in and has admin privileges by verifying the email 
    and admin status in the session. If the user is not an admin or not logged in, they are 
    redirected to the index page.
    
    When a POST request is received, the script retrieves the book ID, author, title, and ISBN 
    from the request. It then prepares and executes an SQL statement to update the book's details 
    in the Books table. If the update is successful, it outputs "success". If there is an error 
    during the update, it outputs an error message.
    
    If the request method is not POST, it outputs "Invalid request.".
    
    Finally, it closes the prepared statement and the database connection.
*/

session_start();
if (!isset($_SESSION['email']) || $_SESSION['admin'] !== true) {
    header('Location: ../../index.php');
    exit();
}

include '../db_connect.php';

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