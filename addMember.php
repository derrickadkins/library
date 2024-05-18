<?php
/*
 * This file serves as the Add Member page for a library management system.
 * It ensures that a user is logged in and is an admin before granting access.
 * The HTML section sets up the page layout, including Bootstrap for styling.
 * The page includes a form for adding a new member to the library system.
 * JavaScript/jQuery is used to handle form submission and provide real-time 
 * feedback on success or error.
 */

session_start();
if (!isset($_SESSION['email']) || $_SESSION['admin'] !== true) {
    header('Location: ../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Member</title>
    <link rel="icon" href="icon.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include "util/admin_nav.php"; ?>
    <div class="container mt-5">
        <h1>Add Member</h1>
        <div id="error" class="alert alert-danger" role="alert" style="display: none;"></div>
        <div id="success" class="alert alert-success" role="alert" style="display: none;">Member added successfully.</div>
        <form>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="street1">Street</label>
                <input type="text" id="street1" name="street1" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="street2">Apartment, suite, etc.</label>
                <input type="text" id="street2" name="street2" class="form-control">
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="city" class="form-control" required>
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
                <input type="text" id="zipcode" name="zipcode" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Member</button>
        </form>
    </div>
    <footer class="bg-dark text-white text-center p-3 mt-5">
        <p>© 2024 Library. All rights reserved.</p>
    </footer>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    function validateInput(regex, input, errorMsg) {
        if (!regex.test(input)) {
            $("#error").html(errorMsg).show();
            $("#success").hide();
            return false;
        }
        return true;
    }

    $("form").on("submit", function(event){
        event.preventDefault();

        // Get form fields
        var name = $("#name").val();
        var email = $("#email").val();
        var street1 = $("#street1").val();
        var street2 = $("#street2").val();
        var city = $("#city").val();
        var zip = $("#zipcode").val();
        var phone = $("#phone").val();
        var password = $("#password").val();

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
        // Password Regex: at least 8 characters long, contains an uppercase letter, a lowercase letter, and a number.
        var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;

        if (
            !validateInput(nameRegex, name, "Please enter a valid name.") ||
            !validateInput(emailRegex, email, "Please enter a valid email address.") ||
            !validateInput(cityRegex, city, "Please enter a valid city.") ||
            !validateInput(zipRegex, zip, "Please enter a valid zip code.") ||
            !validateInput(phoneRegex, phone, "Please enter a valid phone number.") ||
            !validateInput(passwordRegex, password, "Please enter a valid password.")
        ) {
            return;
        }

        var form = this;
        $("button").prop("disabled", true);
        
        $.ajax({
            url: "php/insertMember.php",
            type: "post",
            data: $(this).serialize(),
            success: function(response){
                if(response.trim() == "success"){
                    $("#success").show();
                    $("#error").hide();
                    form.reset();
                }else{
                    $("#error").html(response).show();
                    $("#success").hide();
                }
                $("button").prop("disabled", false);
            },
            error: function(jqXHR, textStatus, errorThrown){
                console.error(textStatus, errorThrown);
                $("button").prop("disabled", false);
            }
        });
    });
});
</script>
</html>
