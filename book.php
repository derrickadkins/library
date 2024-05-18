<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: ../index.php');
    exit();
}

include "db/db_connect.php";

$isAdmin = $_SESSION['admin'] === true;
$isUpdate = isset($_GET['RecID']);

if ($isUpdate) {
    $recId = $_GET['RecID'];
    $recId = mysqli_real_escape_string($conn, $recId);
    $query = "
        SELECT Books.*, 
            CASE 
                WHEN LatestCheckouts.PersonID IS NULL THEN ''
                WHEN CheckedInDate IS NULL THEN Members.Name 
                ELSE '' 
            END AS CheckedOutBy,
            CASE 
                WHEN LatestCheckouts.PersonID IS NULL THEN ''
                WHEN CheckedInDate IS NULL THEN CheckedOutDate 
                ELSE '' 
            END AS CheckedOutDate,
            CASE 
                WHEN LatestCheckouts.PersonID IS NULL THEN ''
                WHEN CheckedInDate IS NOT NULL THEN Members.Name 
                ELSE '' 
            END AS CheckedInBy,
            CASE 
                WHEN LatestCheckouts.PersonID IS NULL THEN ''
                WHEN CheckedInDate IS NOT NULL THEN CheckedInDate 
                ELSE '' 
            END AS CheckedInDate
        FROM Books
        LEFT JOIN (
            SELECT Checkouts.BookID, Checkouts.PersonID, Checkouts.CheckedInDate, Checkouts.CheckedOutDate
            FROM Checkouts
            INNER JOIN (
                SELECT BookID, MAX(CheckedOutDate) as MaxCheckedOutDate
                FROM Checkouts
                GROUP BY BookID
            ) as MaxCheckouts ON Checkouts.BookID = MaxCheckouts.BookID AND Checkouts.CheckedOutDate = MaxCheckouts.MaxCheckedOutDate
        ) as LatestCheckouts ON Books.BookID = LatestCheckouts.BookID
        LEFT JOIN Members ON LatestCheckouts.PersonID = Members.PersonID
        WHERE Books.RecID = '$recId'
    ";
    $result = mysqli_query($conn, $query);
    $book = mysqli_fetch_assoc($result);
    
    if (!empty($book['CheckedOutBy'])) {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $book['CheckedOutDate']);
        $formattedDate = $date->format('m/d/y');
        $status = 'Checked Out by ' . $book['CheckedOutBy'] . ' on ' . $formattedDate;
    } elseif (!empty($book['CheckedInBy']) && $isAdmin) {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $book['CheckedInDate']);
        $formattedDate = $date->format('m/d/y');
        $status = 'Available: Last checked in by ' . $book['CheckedInBy'] . ' on ' . $formattedDate;
    } else {
        $status = 'Available';
    }
    $isAvailable = empty($book['CheckedOutBy']);

    // echo '<pre>';
    // var_dump($book);
    // echo '</pre>';
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
    <title><?php echo $isUpdate ? $book['Title'] : "Add Book"; ?></title>
    <link rel="icon" href="icon.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include $isAdmin ? "util/admin_nav.php" : "util/nav.php"; ?>
    <div class="container mt-5">
        <h1><?php echo $isUpdate ? $book['Title'] : "Add Book"; ?></h1>
        <div id="error" class="alert alert-danger" role="alert" style="display: none;"></div>
        <div id="success" class="alert alert-success" role="alert" style="display: none;">
            Book <?php echo $isUpdate ? "updated" : "added"; ?> successfully.
        </div>
        <form id="bookForm">
            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" id="author" name="author" class="form-control" value="<?php if($isUpdate) echo $book['Author']; ?>" required <?php if(!$isAdmin) echo "disabled"; ?>>
            </div>
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" class="form-control" value="<?php if($isUpdate) echo $book['Title']; ?>" required <?php if(!$isAdmin) echo "disabled"; ?>>
            </div>
            <div class="form-group">
                <label for="isbn">ISBN</label>
                <input type="text" id="isbn" name="isbn" class="form-control" value="<?php if($isUpdate) echo $book['ISBN']; ?>" required <?php if(!$isAdmin) echo "disabled"; ?>>
            </div>
            <?php if ($isUpdate): ?>
                <input type="hidden" id="book_id" name="book_id" value="<?php echo $book['BookID'] ?>">
                <div class="form-group">
                    <label for="status">Status</label>
                    <input type="text" id="status" name="status" class="form-control" value="<?php if($isUpdate) echo $status; ?>" required disabled>
                </div>
                <button id="checkoutButton" class="btn btn-primary" <?php if(!$isAvailable) echo "disabled"; ?>>Check Out</button>
            <?php endif; ?>
            <?php if ($isAdmin): ?>
                <button type="submit" class="btn btn-primary"><?php echo $isUpdate ? "Update" : "Add Book"; ?></button>
            <?php endif; ?>
        </form>
    </div>
    <?php if ($isAdmin): ?>
        <div class="container mt-5">
            <h2>Delete Book</h2>
            <form id="deleteForm">
                <input type="hidden" id="book_id" name="book_id" value="<?php echo $book['BookID'] ?>">
                <button type="submit" id="deleteButton" class="btn btn-danger">Delete</button>
            </form>
        </div>
    <?php endif; ?>
    <footer class="bg-dark text-white text-center p-3 mt-5">
        <p>© 2024 Library. All rights reserved.</p>
    </footer>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $("#deleteForm").on("submit", function(event){
        event.preventDefault();

        var confirmDelete = confirm("Are you sure you want to delete this book? This action is permanent and cannot be undone.");
        if (!confirmDelete) {
            return;
        }

        $(this).find('input[type="submit"]').prop('disabled', true);

        $.ajax({
        url: 'php/deleteBook.php',
        type: 'post',
        data: $(this).serialize(),
        success: function(response){
            if (response.trim() == "success") {
                window.location.href = "admin.php";
            } else {
                $("#errorBooks").html(response).show();
                $(this).find('input[type="submit"]').prop('disabled', false);
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.error(textStatus, errorThrown);
            $(this).find('input[type="submit"]').prop('disabled', false);
        }
        });
    });

    $("#bookForm").on("submit", function(event){
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
            url: "php/<?php echo $isUpdate ? "updateBook" : "addBook"; ?>.php",
            type: "post",
            data: $(this).serialize(),
            success: function(response){
                if(response.trim() == "success"){
                    $("#success").show();
                    $("#error").hide();
                    <?php if(!$isUpdate) echo "form.reset();"; ?>
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
