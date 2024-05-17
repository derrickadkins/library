<?php
/*
 * This script is used to check out a book in the library system.
 * It starts a session and includes the db_connect.php file to establish a connection with the database.
 * 
 * The script retrieves the email of the logged-in user from the session and fetches the user's details from the Members table.
 * 
 * If the request method is POST and the book_id is set in the POST data, the script retrieves the book ID from the POST data and gets the current date and time.
 * It then prepares an SQL statement to insert a new row into the Checkouts table with the user's ID, the book ID, and the current date and time.
 * 
 * The script binds the user's ID, the book ID, and the current date and time to the SQL statement and executes it.
 * If the SQL statement executes successfully, the script outputs "success". Otherwise, it outputs an error message.
 * 
 * The script then closes the SQL statement and the database connection.
 * If the request method is not POST or the book_id is not set in the POST data, the script outputs "Invalid request.".
 */

session_start();

include '../db/db_connect.php';

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