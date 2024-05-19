<?php
/*
 * This excerpt is responsible for authenticating a user trying to log in.
 * 
 * It first fetches the result of a previously prepared SQL statement that selects 
 * the user with the given email.
 * If a user is found, it fetches the user's details and verifies the given password 
 * against the hashed password stored in the database.
 * 
 * If the password is correct, it stores the user's email, person ID, and name in the 
 * session.
 * It then checks if the user is an admin by preparing and executing an SQL statement 
 * that selects the admin with the given email.
 * If an admin is found, it stores true in the session under 'admin' and outputs "admin".
 * Otherwise, it stores false in the session under 'admin' and outputs "member".
 * 
 * If the password is incorrect, it outputs "Invalid password.".
 * If no user is found with the given email, it outputs "No user found with that email.".
 * 
 * Finally, it closes the SQL statements and the database connection.
 */

session_start();
include '../db/db_connect.php';

$email = $_POST['email'];
$password = $_POST['password'];

// Query to find the user
$sql = "SELECT * FROM Members WHERE Email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Verify password
    if (password_verify($password, $user['Password'])) {
        $_SESSION['email'] = $user['Email'];
        $_SESSION['person_id'] = $user['PersonID'];
        $_SESSION['name'] = $user['Name'];
        $_SESSION['rec_id'] = $user['RecID'];
        
        // Check if the user is an admin
        $admin_sql = "SELECT * FROM Admins WHERE Email = ?";
        $admin_stmt = $conn->prepare($admin_sql);
        $admin_stmt->bind_param("s", $email);
        $admin_stmt->execute();

        $admin_result = $admin_stmt->get_result();
        $_SESSION['admin'] = $admin_result->num_rows > 0;
        $admin_stmt->close();
        
        echo "success";
    } else {
        echo "Invalid password.";
    }
} else {
    echo "No user found with that email.";
}

$stmt->close();
$conn->close();
?>