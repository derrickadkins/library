<?php
/*
 * This file serves as the Add Book page for a library management system.
 * It ensures that a user is logged in and is an admin before granting access.
 * The HTML section sets up the page layout, including Bootstrap for styling.
 * The page includes a form for adding a new book to the library system.
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
    <title>Add Book</title>
    <link rel="icon" href="icon.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include "util/admin_nav.php"; ?>
    <div class="container mt-5">
        <h1>Add Book</h1>
        <div id="error" class="alert alert-danger" role="alert" style="display: none;"></div>
        <div id="success" class="alert alert-success" role="alert" style="display: none;">Book added successfully.</div>
        <form>
            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" id="author" name="author" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="isbn">ISBN</label>
                <input type="text" id="isbn" name="isbn" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Book</button>
        </form>
    </div>
    <footer class="bg-dark text-white text-center p-3 mt-5">
        <p>© 2024 Library. All rights reserved.</p>
    </footer>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $("form").on("submit", function(event){
        event.preventDefault();

        // Get form fields
        var author = $('#author').val();
        var title = $('#title').val();
        var isbn = $('#isbn').val();

        // Author Regex: only letters, spaces, hyphens, and apostrophes.
        var authorRegex = /^[a-zA-Z\s\-']{1,100}$/;
        // Title Regex: only letters, numbers, spaces, and common punctuation marks.
        var titleRegex = /^[a-zA-Z0-9\s\-:',.?!]{1,200}$/;
        // ISBN Regex: Validates both ISBN-10 and ISBN-13 formats, including hyphens and spaces
        var isbnRegex = /((978[\--– ])?[0-9][0-9\--– ]{10}[\--– ][0-9xX])|((978)?[0-9]{9}[0-9Xx])/;

        // Validate form fields, show error message and return on first failure
        if (!authorRegex.test(author)) {
            $("#error").html("Please enter a valid author name.").show();
            $("#success").hide();
            return;
        }else if (!titleRegex.test(title)) {
            $("#error").html("Please enter a valid book title.").show();
            $("#success").hide();
            return;
        }else if (!isbnRegex.test(isbn)) {
            $("#error").html("Please enter a valid ISBN-10 or ISBN-13.").show();
            $("#success").hide();
            return;
        }

        var form = this;
        $("button").prop("disabled", true);

        $.ajax({
            url: "php/insertBook.php",
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
