<?php
/*
 * This script is used to add a new book to the library system.
 * It includes the db_connect.php file to establish a connection with the database.
 * 
 * The script checks if the request method is POST and if the author is set in the 
 * POST data.
 * If it is, it retrieves the author, title, and ISBN from the POST data and 
 * generates a unique RecID.
 * 
 * It then prepares an SQL statement to insert a new row into the Books table with 
 * the author, title, ISBN, and RecID.
 * 
 * The script binds the author, title, ISBN, and RecID to the SQL statement and 
 * executes it.
 * If the SQL statement executes successfully, the script outputs "success". 
 * Otherwise, it outputs an error message.
 * 
 * The script then closes the SQL statement and the database connection.
 * If the request method is not POST or the author is not set in the POST data, the 
 * script outputs "Invalid request.".
 */

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