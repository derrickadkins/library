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

$books_sql = "
    SELECT Books.BookID, Books.Title, Books.Author, Checkouts.CheckedOutDate
    FROM Books
    INNER JOIN Checkouts ON Books.BookID = Checkouts.BookID
    WHERE Checkouts.PersonID = ? AND Checkouts.CheckedInDate IS NULL
";

$stmt = $conn->prepare($books_sql);
$stmt->bind_param("s", $person_id);
$stmt->execute();

$books_result = $stmt->get_result();
$books = array();
while($row = $books_result->fetch_assoc()) {
    $books[] = $row;
}

header('Content-Type: application/json');
echo json_encode($books);

$stmt->close();
$conn->close();
?>