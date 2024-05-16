<?php
include '../db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['person_id'])) {
    $person_id = $_POST['person_id'];

    $delete_sql = "DELETE FROM Members WHERE PersonID='$person_id'";

    if ($conn->query($delete_sql) === TRUE) {
        echo "success";
    } else {
        echo "Error deleting member: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>