<?php
/*
 * This script is used to fetch all the members from the library system.
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

include '../db/db_connect.php';

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