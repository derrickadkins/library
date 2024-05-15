<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['admin'] !== true) {
    header('Location: ../index.php');
    exit();
}

include 'db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    if ($conn->query($insert_member_sql) === TRUE) {
        echo "Member added successfully<br>";
    } else {
        echo "Error adding member: " . $conn->error . "<br>";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Member</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include "util/admin_nav.php"; ?>
    <div class="container mt-5">
        <h1>Add Member</h1>
        <form action="addMember.php" method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="street1">Street</label>
                <input type="text" id="street1" name="street1" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="street2">Apartment, suite, etc.</label>
                <input type="text" id="street2" name="street2" class="form-control">
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="city" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="state">State</label>
                <select class="form-control" id="state" name="state" required>
                    <option value="">Select a state</option>
                    <?php include "util/states.php"; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="zipcode">Zip Code</label>
                <input type="text" id="zipcode" name="zipcode" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Member</button>
        </form>
    </div>
    <footer class="bg-dark text-white text-center p-3 mt-5">
        <p>© 2024 Library. All rights reserved.</p>
    </footer>
</body>
</html>
