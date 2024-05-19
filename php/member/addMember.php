<?php
/*
 * This script is used to add a new member to the library system.
 * It includes the db_connect.php file to establish a connection with the database.
 * 
 * The script checks if the request method is POST and if the name is set in the POST 
 * data.
 * If it is, it retrieves the name, date of birth, email, address, phone, and 
 * password from the POST data and generates a unique RecID.
 * The password is securely hashed using the password_hash function.
 * 
 * It then prepares an SQL statement to insert a new row into the Members table with 
 * the name, date of birth, email, address, phone, hashed password, and RecID.
 * 
 * The script binds the name, date of birth, email, address, phone, hashed password, 
 * and RecID to the SQL statement and executes it.
 * If the SQL statement executes successfully, the script outputs "success". If a 
 * member with the same email already exists, it outputs "A member with this email 
 * already exists.".
 * Otherwise, it outputs an error message.
 * 
 * The script then closes the SQL statement.
 * If the request method is not POST or the name is not set in the POST data, the 
 * script outputs "Invalid request.".
 */

include '../db_connect.php';

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
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($insert_member_sql);
    $stmt->bind_param("sssssssssss", $name, $dob, $email, $street1, $street2, $city, $state, $zipcode, $phone, $password, $recid);

    try {
        $stmt->execute();
        echo "success";
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            echo "A member with this email already exists.";
        } else {
            echo "An error occurred: " . $e->getMessage();
        }
    } finally {
        $stmt->close();
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>