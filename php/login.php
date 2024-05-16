<?php
session_start();
include '../db/db_connect.php';

$email = $_POST['email'];
$password = $_POST['password'];

// Query to find the user
$sql = "SELECT * FROM Members WHERE Email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Verify password
    if (password_verify($password, $user['Password'])) {
        $_SESSION['email'] = $user['Email'];
        $_SESSION['person_id'] = $user['PersonID'];
        
        // Check if the user is an admin
        $admin_sql = "SELECT * FROM Admins WHERE Email = '$email'";
        $admin_result = $conn->query($admin_sql);
        
        if ($admin_result->num_rows > 0) {
            $_SESSION['admin'] = true;
            // header('Location: ../admin.php');
            echo "admin";
        } else {
            $_SESSION['admin'] = false;
            // header('Location: ../dashboard.php');
            echo "member";
        }
    } else {
        echo "Invalid password.";
    }
} else {
    echo "No user found with that email.";
}

$conn->close();
?>
