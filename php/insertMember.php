<?php
include '../db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $street1 = $_POST['street1'];
    $street2 = $_POST['street2'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zipcode = $_POST['zipcode'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Securely hash the password
    $recid = uniqid(); // Generate a unique RecID

    $insert_member_sql = "INSERT INTO Members (Name, DOB, Email, Street1, Street2, City, State, ZipCode, Phone, Password, RecID)
                          VALUES ('$name', '$dob', '$email', '$street1', '$street2', '$city', '$state', '$zipcode', '$phone', '$password', '$recid')";

    try {
        $conn->query($insert_member_sql);
        echo "success";
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            echo "A member with this email already exists.";
        } else {
            echo "An error occurred: " . $e->getMessage();
        }
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>