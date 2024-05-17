<?php
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
        
        // Check if the user is an admin
        $admin_sql = "SELECT * FROM Admins WHERE Email = ?";
        $admin_stmt = $conn->prepare($admin_sql);
        $admin_stmt->bind_param("s", $email);
        $admin_stmt->execute();

        $admin_result = $admin_stmt->get_result();
        
        if ($admin_result->num_rows > 0) {
            $_SESSION['admin'] = true;
            echo "admin";
        } else {
            $_SESSION['admin'] = false;
            echo "member";
        }

        $admin_stmt->close();
    } else {
        echo "Invalid password.";
    }
} else {
    echo "No user found with that email.";
}

$stmt->close();
$conn->close();
?>