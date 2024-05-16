<?php
session_start();

include '../db/db_connect.php';

$email = $_SESSION['email'];

// Fetch member details
$member_sql = "SELECT * FROM Members WHERE Email='$email'";
$member_result = $conn->query($member_sql);
$member = $member_result->fetch_assoc();

// Fetch checked out books
$checkouts_sql = "SELECT Books.BookID, Books.Title, Checkouts.CheckedOutDate, Checkouts.CheckedInDate 
                  FROM Checkouts 
                  JOIN Books ON Checkouts.BookID = Books.BookID 
                  WHERE Checkouts.PersonID = " . $member['PersonID'];
$checkouts_result = $conn->query($checkouts_sql);

$checkouts = array();
while($row = $checkouts_result->fetch_assoc()) {
    $checkouts[] = $row;
}

header('Content-Type: application/json');
echo json_encode($checkouts);

?>