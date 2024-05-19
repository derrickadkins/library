<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: ../index.php');
    exit();
}

include 'db/db_connect.php';

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
<script>
$(document).ready(function() {
    if($("#isUpdate").val() == "1"){
        $("#state").val($("#memberState").val());
    }

    function validateInput(regex, input, errorMsg) {
        if (!regex.test(input)) {
            $("#error").html(errorMsg).show();
            $("#success").hide();
            return false;
        }
        return true;
    }

    $("#profileForm").on("submit", function(event){
        event.preventDefault();

        // Get form fields
        var name = $("#name").val();
        var email = $("#email").val();
        var street1 = $("#street1").val();
        var street2 = $("#street2").val();
        var city = $("#city").val();
        var zip = $("#zipcode").val();
        var phone = $("#phone").val();

        // Name Regex: only letters, spaces, hyphens, and apostrophes.
        var nameRegex = /^[a-zA-Z\s\-']{1,100}$/;
        // Email Regex: standard email format.
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        // City Regex: only letters and spaces.
        var cityRegex = /^[a-zA-Z\s]{1,100}$/;
        // Zip Code Regex: 5 digits or 5 digits followed by a hyphen and 4 digits (US zip code format).
        var zipRegex = /^\d{5}(-\d{4})?$/;
        // Phone Number Regex: 10 digits, allows for common formatting characters like spaces, hyphens, parentheses.
        var phoneRegex = /^\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}$/;

        if (
            !validateInput(nameRegex, name, "Please enter a valid name.") ||
            !validateInput(emailRegex, email, "Please enter a valid email address.") ||
            !validateInput(cityRegex, city, "Please enter a valid city.") ||
            !validateInput(zipRegex, zip, "Please enter a valid zip code.") ||
            !validateInput(phoneRegex, phone, "Please enter a valid phone number.")
        ) {
            return;
        }

        var isUpdate = $("#isUpdate").val() == "1";

        if(!isUpdate){
            var password = $("#password").val();
            // Password Regex: at least 8 characters long, contains an uppercase letter, a lowercase letter, and a number.
            var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;
            if(!validateInput(passwordRegex, password, "Please enter a valid password.")){
                return;
            }
        }

        var form = $(this);
        var profileForm = this;
        var profileUrl = isUpdate ? "php/updateProfile.php" : "php/insertMember.php";
        form.find('input[type="submit"]').prop('disabled', true);
        
        $.ajax({
            url: profileUrl,
            type: "post",
            data: $(this).serialize(),
            success: function(response){
                if(response.trim() == "success"){
                    $("#success").show();
                    $("#error").hide();
                    if(!isUpdate) profileForm.reset();
                }else{
                    $("#error").html(response).show();
                    $("#success").hide();
                }
                form.find('input[type="submit"]').prop('disabled', false);
            },
            error: function(jqXHR, textStatus, errorThrown){
                console.error(textStatus, errorThrown);
                form.find('input[type="submit"]').prop('disabled', false);
            }
        });
    });

    $('#togglePassword').click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $("#password");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

    $('#toggleOldPassword').click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $("#old_password");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

    $('#toggleNewPassword').click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $("#new_password");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

    $("#updatePasswordForm").on("submit", function(event){
        event.preventDefault();

        var form = this;
        var newPassword = $("#new_password").val();
        var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;
        if (!regex.test(newPassword)) {
            $("#errorPassword").html("Password does not meet the strength requirements.").show();
            $("#successPassword").hide();
            return;
        }

        $("#updatePasswordButton").prop("disabled", true);

        $.ajax({
            url: "php/updatePassword.php",
            type: "post",
            data: $(this).serialize(),
            success: function(response){
                if(response.trim() == "success"){
                    $("#successPassword").show();
                    $("#errorPassword").hide();
                    form.reset();
                }else{
                    $("#errorPassword").html(response).show();
                    $("#successPassword").hide();
                }
                $("#updatePasswordButton").prop("disabled", false);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown);
                $("#updatePasswordButton").prop("disabled", false);
            }
        });
    });

    $("#deleteForm").on("submit", function(event){
        event.preventDefault();

        var confirmDelete = confirm("Are you sure you want to delete this profile? This action is permanent and cannot be undone.");
        if (!confirmDelete) {
            return;
        }

        var form = $(this);
        form.find('input[type="submit"]').prop('disabled', true);

        $.ajax({
        url: 'php/deleteMember.php',
        type: 'post',
        data: form.serialize(),
        success: function(response){
            // console.log(response);
            if (response.trim() == "success") {
                window.location.href = "dashboard.php";
            } else {
                $("#errorMembers").html(response).show();
                form.find('input[type="submit"]').prop('disabled', false);
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.error(textStatus, errorThrown);
            form.find('input[type="submit"]').prop('disabled', false);
        }
        });
    });
});
</script>
</html>
