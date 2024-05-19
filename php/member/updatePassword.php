<?php
/*
 * This script is used to update a user's password in the library system.
 * It starts a session and includes the db_connect.php file to establish a connection 
 * with the database.
 * 
 * The script checks if the request method is POST.
 * If it is, it retrieves the email of the logged-in user from the session and the old 
 * and new passwords from the POST data.
 * 
 * It then prepares an SQL statement to select the password of the user with the given 
 * email from the Members table.
 * The script binds the email to the SQL statement and executes it.
 * It fetches the result and stores the user's details in an associative array.
 * 
 * The script verifies the old password against the hashed password stored in the 
 * database.
 * If the old password is correct, it hashes the new password and prepares an SQL 
 * statement to update the password of the user with the given email in the Members 
 * table.
 * The script binds the hashed new password and the email to the SQL statement and 
 * executes it.
 * If the SQL statement executes successfully, the script outputs "success".
 * 
 * If the old password is incorrect, the script outputs "Old password is incorrect.".
 * 
 * The script then closes the SQL statement.
 * If the request method is not POST, the script outputs "Invalid request.".
 * 
 * Finally, the script closes the database connection.
 */

session_start();
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_SESSION['email'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    // Fetch the current password from the database
    $sql = "SELECT Password FROM Members WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify the old password
    if (password_verify($old_password, $user['Password'])) {
        // Hash the new password before storing it in the database
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        // Update the password in the database
        $update_sql = "UPDATE Members SET Password = ? WHERE Email = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ss", $hashed_password, $email);
        $update_stmt->execute();
        echo "success";
        $update_stmt->close();
    } else {
        echo "Old password is incorrect.";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>