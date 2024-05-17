<?php
/*
 * This script is used to delete a member from the library system.
 * It includes the db_connect.php file to establish a connection with the database.
 * 
 * The script checks if the request method is POST and if the person_id is set in the POST data.
 * If it is, it retrieves the person ID from the POST data.
 * 
 * It then prepares an SQL statement to delete the row from the Members table where the PersonID matches the given person ID.
 * 
 * The script binds the person ID to the SQL statement and executes it.
 * If the SQL statement executes successfully, the script outputs "success". Otherwise, it outputs an error message.
 * 
 * The script then closes the SQL statement and the database connection.
 * If the request method is not POST or the person_id is not set in the POST data, the script outputs "Invalid request.".
 */

include '../db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['person_id'])) {
    $person_id = $_POST['person_id'];

    $stmt = $conn->prepare("DELETE FROM Members WHERE PersonID = ?");
    $stmt->bind_param("s", $person_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error deleting member: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>