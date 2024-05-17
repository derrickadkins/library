<?php
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