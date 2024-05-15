<?php
$servername = "localhost:3308";
$username = "dadkins";
$password = "+ix?O)2UKhao";
$dbname = "compound_dadkins";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("<p>Connection failed: " . $conn->connect_error . "</p>");
}
?>
