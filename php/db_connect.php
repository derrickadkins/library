<?php
/*
    php/db_connect.php
*/

// Define connection parameters
$servername = "127.0.0.1:3306";
$username = "dadkins";
$password = "+ix?O)2UKhao";
$dbname = "library_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("<p>Connection failed: " . $conn->connect_error . "</p>");
}
?>
