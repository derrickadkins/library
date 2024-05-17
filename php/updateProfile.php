<?php
/*
 * This script is used to update a user's profile in the library system.
 * It starts a session and includes the db_connect.php file to establish a connection 
 * with the database.
 * 
 * The script checks if the request method is POST.
 * If it is, it retrieves the email of the logged-in user from the session and the name, 
 * date of birth, address, and phone number from the POST data.
 * 
 * It then prepares an SQL statement to update the name, date of birth, address, and 
 * phone number of the user with the given email in the Members table.
 * The script binds the name, date of birth, address, phone number, and email to the 
 * SQL statement and executes it.
 * 
 * If the SQL statement executes successfully, the script outputs "success".
 * Otherwise, it outputs an error message.
 * 
 * The script then closes the SQL statement.
 * If the request method is not POST, the script outputs "Invalid request.".
 * 
 * Finally, the script closes the database connection.
 */

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