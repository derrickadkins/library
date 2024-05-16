<?php
include "../db/db_connect.php";

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
    
    $update_sql = "UPDATE Members SET 
                    Name = '$name', 
                    DOB = '$dob',
                    Street1 = '$street1', 
                    Street2 = '$street2', 
                    City = '$city', 
                    State = '$state', 
                    Zipcode = '$zipcode', 
                    Phone = '$phone'";

    if (!empty($_POST['password'])) {
        $password = $_POST['password'];
        // Hash the password before storing it in the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // Add the password to the update query
        $update_sql .= ", Password = '$hashed_password'";
    }

    $update_sql .= " WHERE Email = '$email'";

    $conn->query($update_sql);

    if ($conn->query($update_sql) === TRUE) {
        echo "success";
    } else {
        echo "Error updating profile: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>