<?php
session_start();

include '../db/db_connect.php';

$email = $_SESSION['email'];

// Fetch member details
$member_sql = "SELECT * FROM Members WHERE Email='$email'";
$member_result = $conn->query($member_sql);
$member = $member_result->fetch_assoc();
$person_id = $member['PersonID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];
    $checkout_date = date('Y-m-d H:i:s');

    $checkout_sql = "INSERT INTO Checkouts (PersonID, BookID, CheckedOutDate) 
                    VALUES ('$person_id', '$book_id', '$checkout_date')";

    if ($conn->query($checkout_sql) === TRUE) {
        echo "success";
    } else {
        echo "Error checking out book: " . $conn->error;
    }
}else{
    echo "Invalid request.";
}
?>