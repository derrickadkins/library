<?php
/*
 * php/book/checkOutBook.php
 * This script handles the process of checking out a book for a logged-in user.
 * 
 * It first checks if the user is logged in by verifying the email 
 * in the session. If the user is not logged in, they are 
 * redirected to the index page.
 * 
 * It then fetches the member details from the Members table using the email 
 * stored in the session. The member's PersonID is retrieved for use in the checkout process.
 * 
 * When a POST request is received with a book ID, the script checks if the book is already 
 * checked out by querying the Checkouts table for entries with the given BookID that have 
 * no CheckedInDate. If the book is already checked out, it outputs "checked out" and terminates.
 * 
 * If the book is not already checked out, the script inserts a new record into the Checkouts 
 * table with the member's PersonID, the book's ID, and the current date and time as the 
 * CheckedOutDate. If the insertion is successful, it outputs "success". If there is an error 
 * during the insertion, it outputs an error message.
 * 
 * If the request method is not POST or the book ID is not set, it outputs "Invalid request.".
 * 
 * Finally, it closes the prepared statement and the database connection.
 */

 session_start();
 if (!isset($_SESSION['email'])) {
     header('Location: ../../index.php');
     exit();
 }

include '../db_connect.php';

$email = $_SESSION['email'];

// Fetch member details
$stmt = $conn->prepare("SELECT * FROM Members WHERE Email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$member_result = $stmt->get_result();
$member = $member_result->fetch_assoc();
$person_id = $member['PersonID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];
    $checkout_date = date('Y-m-d H:i:s');

    // Check if the book is already checked out
    $stmt = $conn->prepare("SELECT * FROM Checkouts WHERE BookID = ? AND CheckedInDate IS NULL");
    $stmt->bind_param("s", $book_id);
    $stmt->execute();
    $checkout_result = $stmt->get_result();
    if ($checkout_result->num_rows > 0) {
        echo "checked out";
        $stmt->close();
        $conn->close();
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO Checkouts (PersonID, BookID, CheckedOutDate) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $person_id, $book_id, $checkout_date);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error checking out book: " . $stmt->error;
    }

    $stmt->close();
}else{
    echo "Invalid request.";
}

$conn->close();
?>