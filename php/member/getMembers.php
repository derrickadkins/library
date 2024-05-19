<?php
/*
 * php/member/getMembers.php
 * This script is used to fetch all the members from the library system.
 * 
 * It first checks if the user is logged in and has admin privileges by verifying the email 
 * and admin status in the session. If the user is not an admin or not logged in, they are 
 * redirected to the index page.
 * 
 * It includes the db_connect.php file to establish a connection with the database.
 * 
 * The script prepares an SQL statement to select all columns from the Members table.
 * It executes the SQL statement and fetches the result.
 * 
 * If there are rows in the result, the script fetches each row and stores it in an array.
 * 
 * The script then outputs the array as a JSON object.
 * 
 * Finally, the script closes the database connection.
 */

session_start();
if (!isset($_SESSION['email']) || $_SESSION['admin'] !== true) {
    header('Location: ../../index.php');
    exit();
}

include '../db_connect.php';

$sql = "SELECT * FROM Members";
$result = $conn->query($sql);

$members = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $members[] = $row;
    }
}

echo json_encode($members);

$conn->close();
?>