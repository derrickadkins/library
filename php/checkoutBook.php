<?php
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