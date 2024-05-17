<?php
session_start();
include '../db/db_connect.php';

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