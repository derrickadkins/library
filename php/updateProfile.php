<?php
session_start();
include '../db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_SESSION['email'];
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $street1 = $_POST['street1'];
    $street2 = $_POST['street2'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zipcode = $_POST['zipcode'];
    $phone = $_POST['phone'];

    $update_sql = "UPDATE Members SET 
                    Name = ?, 
                    DOB = ?,
                    Street1 = ?, 
                    Street2 = ?, 
                    City = ?, 
                    State = ?, 
                    Zipcode = ?, 
                    Phone = ? 
                    WHERE Email = ?";

    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssssssss", $name, $dob, $street1, $street2, $city, $state, $zipcode, $phone, $email);

    if (!empty($_POST['password'])) {
        $password = $_POST['password'];
        // Hash the password before storing it in the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // Add the password to the update query
        $update_password_sql = "UPDATE Members SET Password = ? WHERE Email = ?";
        $password_stmt = $conn->prepare($update_password_sql);
        $password_stmt->bind_param("ss", $hashed_password, $email);
        $password_stmt->execute();
        $password_stmt->close();
    }

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>