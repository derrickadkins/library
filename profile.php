<?php
/*
    profile.php
    Handles user profile operations, including viewing, updating, and adding profile information.
    
    This script ensures that a user is logged in and has the appropriate permissions before
    allowing access to profile operations. If a user is updating their profile, their details
    are fetched from the database and displayed in a form for editing. If a user is an admin,
    they can update other users' profiles.
*/
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: ../index.php');
    exit();
}

include 'php/db_connect.php';

$isAdmin = $_SESSION['admin'] === true;
$isUpdate = isset($_GET['id']);

if ($isUpdate) {
    $recId = $_GET['id'];
    if(!$isAdmin && $recId != $_SESSION['rec_id']){
        header('Location: ../index.php');
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM Members WHERE RecID = ?");
    $stmt->bind_param("s", $recId);
    $stmt->execute();
    $result = $stmt->get_result();
    $member = $result->fetch_assoc();
    $stmt->close();

    if($member === null){
        header('Location: ../index.php');
        exit();
    }
} else if (!$isAdmin){
    header('Location: ../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isUpdate ? "Update " : "Add "; ?>Profile</title>
    <link rel="icon" href="icon.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php include "util/nav.php"; ?>
    <input id="isAdmin" type="hidden" value="<?php echo $isAdmin; ?>">
    <input id="isUpdate" type="hidden" value="<?php echo $isUpdate; ?>">
    <?php if($isUpdate): ?>
        <input id="memberState" type="hidden" value="<?php echo $member['State']; ?>">
    <?php endif; ?>
    <div class="container mt-3">
        <h1><?php echo $isUpdate ? "Update " : "Add "; ?>Profile</h1>
        <div id="error" class="alert alert-danger" role="alert" style="display: none;"></div>
        <div id="success" class="alert alert-success" role="alert" style="display: none;">
            Profile <?php echo $isUpdate ? "updated" : "added"; ?> successfully.
        </div>
        <form id="profileForm">
            <?php if($isUpdate): ?>
                <input type="hidden" id="rec_id" name="rec_id" value="<?php echo $recId ?>">
            <?php endif; ?>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php if($isUpdate) echo $member['Name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" class="form-control" value="<?php if($isUpdate) echo $member['DOB']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php if($isUpdate) echo $member['Email']; ?>" required <?php if($isUpdate) echo "disabled"; ?>>
            </div>
            <div class="form-group">
                <label for="street1">Street</label>
                <input type="text" id="street1" name="street1" class="form-control" value="<?php if($isUpdate) echo $member['Street1']; ?>" required>
            </div>
            <div class="form-group">
                <label for="street2">Apartment, suite, etc.</label>
                <input type="text" id="street2" name="street2" value="<?php if($isUpdate) echo $member['Street2']; ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="city" class="form-control" value="<?php if($isUpdate) echo $member['City']; ?>" required>
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
                <input type="text" id="zipcode" name="zipcode" class="form-control" value="<?php if($isUpdate) echo $member['ZipCode']; ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" class="form-control" value="<?php if($isUpdate) echo $member['Phone']; ?>" required>
            </div>
            <?php if(!$isUpdate): ?>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fa fa-eye-slash" id="togglePassword"></i>
                            </span>
                        </div>
                    </div>
                    <small class="form-text text-muted">
                        Password must be at least 8 characters long, contain an 
                        uppercase letter, a lowercase letter, and a number.
                    </small>
                </div>
            <?php endif; ?>
            <input type="submit" class="btn btn-primary" value="<?php echo $isUpdate ? "Update" : "Add"; ?> Profile">
        </form>
        
        <?php if($isUpdate): ?>
            <hr />
            <h2>Update Password</h2>
            <div id="errorPassword" class="alert alert-danger" role="alert" style="display: none;"></div>
            <div id="successPassword" class="alert alert-success" role="alert" style="display: none;">Password updated successfully.</div>
            <form id="updatePasswordForm">
                <div class="form-group">
                    <label for="old_password">Old Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="old_password" name="old_password" required>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fa fa-eye-slash" id="toggleOldPassword"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fa fa-eye-slash" id="toggleNewPassword"></i>
                            </span>
                        </div>
                    </div>
                    <small class="form-text text-muted">
                        Password must be at least 8 characters long, contain an 
                        uppercase letter, a lowercase letter, and a number.
                    </small>
                </div>
                <input id="updatePasswordButton" type="submit" class="btn btn-primary" value="Update Password">
            </form>
        <?php endif; ?>
        <?php if ($isAdmin && $isUpdate && $recId != $_SESSION['rec_id']): ?>
            <hr />
            <h2>Delete Profile</h2>
            <form id="deleteForm">
                <input type="hidden" id="person_id" name="person_id" value="<?php echo $member['PersonID'] ?>">
                <input type="submit" id="deleteButton" class="btn btn-danger" value="Delete">
            </form>
        <?php endif; ?>
    </div>
    <footer class="bg-dark text-white text-center p-3 mt-5">
        <p>© 2024 Library. All rights reserved.</p>
    </footer>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="js/profile.js"></script>
</html>
