<?php
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