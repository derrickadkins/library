<?php
include '../db_connect.php';

// First member details
$name = 'John Doe';
$dob = '1990-01-01';
$email = 'john.doe@example.com';
$street1 = '123 Main St';
$street2 = '';
$city = 'Anytown';
$state = 'CA';
$zipcode = '12345';
$phone = '555-1234';
$password = password_hash('password123', PASSWORD_DEFAULT); // Securely hash the password
$recid = uniqid(); // Generate a unique RecID

// Insert first member
$insert_member_sql = "INSERT INTO Members (Name, DOB, Email, Street1, Street2, City, State, ZipCode, Phone, Password, RecID)
                      VALUES ('$name', '$dob', '$email', '$street1', '$street2', '$city', '$state', '$zipcode', '$phone', '$password', '$recid')";

if ($conn->query($insert_member_sql) === TRUE) {
    echo "Member added successfully<br>";
    
    // Insert admin
    $insert_admin_sql = "INSERT INTO Admins (Email) VALUES ('$email')";
    
    if ($conn->query($insert_admin_sql) === TRUE) {
        echo "Admin added successfully<br>";
    } else {
        echo "Error adding admin: " . $conn->error . "<br>";
    }
} else {
    echo "Error adding member: " . $conn->error . "<br>";
}

$conn->close();
?>
