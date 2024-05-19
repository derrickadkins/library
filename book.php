<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: ../index.php');
    exit();
}

include "php/db_connect.php";

$isAdmin = $_SESSION['admin'] === true;
$isUpdate = isset($_GET['id']);

if ($isUpdate) {
    $recId = $_GET['id'];
    $recId = mysqli_real_escape_string($conn, $recId);
    $query = "
        SELECT Books.*,
            LatestCheckouts.PersonID,
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
    $conn->close();

    if ($book === null) {
        header('Location: ../index.php');
        exit();
    }

    $isAvailable = empty($book['CheckedOutBy']);
    $canCheckIn = !$isAvailable && $book['PersonID'] == $_SESSION['person_id'];
    
    if (!$isAvailable) {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $book['CheckedOutDate']);
        $formattedDate = $date->format('m/d/y');
        $status = 'Checked Out by ' . $book['CheckedOutBy'] . ' on ' . $formattedDate;
    } elseif ($isAdmin && !empty($book['CheckedInDate'])) {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $book['CheckedInDate']);
        $formattedDate = $date->format('m/d/y');
        $status = 'Available: Checked In by ' . $book['CheckedInBy'] . ' on ' . $formattedDate;
    } else {
        $status = 'Available';
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
    <title><?php echo $isUpdate ? $book['Title'] : "Add Book"; ?></title>
    <link rel="icon" href="icon.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="js/book.js"></script>
</head>
<body>
    <?php include "util/nav.php"; ?>
    <input id="username" type="hidden" value="<?php echo $_SESSION['name']; ?>">
    <input id="isAdmin" type="hidden" value="<?php echo $isAdmin; ?>">
    <input id="isUpdate" type="hidden" value="<?php echo $isUpdate; ?>">
    <?php if($isUpdate): ?>
        <input id="isAvailable" type="hidden" value="<?php echo $isAvailable; ?>">
    <?php endif; ?>
    <div class="container mt-3">
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
                <input type="hidden" name="book_id" value="<?php echo $book['BookID'] ?>">
                <div class="form-group">
                    <label for="status">Status</label>
                    <input type="text" id="status" name="status" class="form-control" value="<?php if($isUpdate) echo $status; ?>" required disabled>
                </div>
            <?php endif; ?>
            <?php if ($isAdmin): ?>
                <input type="submit" class="btn btn-primary" value="<?php echo $isUpdate ? "Update" : "Add Book"; ?>">
            <?php endif; ?>
        </form>
        <?php if ($isUpdate): ?>
            <div class="mt-3">
                <form class="checkBookForm">
                    <input type="hidden" name="book_id" value="<?php echo $book['BookID'] ?>">
                    <input id="checkOutButton" type="submit" class="btn btn-primary" value="Check Out" <?php if(!$isAvailable) echo "disabled"; ?>>
                </form>
            </div>
            <div class="mt-3">
                <form class="checkBookForm">
                    <input type="hidden" name="book_id" value="<?php echo $book['BookID'] ?>">
                    <input id="checkInButton" type="submit" class="btn btn-primary" value="Check In" <?php if(!$canCheckIn) echo "disabled"; ?>>
                </form>
            </div>
        <?php endif; ?>
        <?php if ($isAdmin && $isUpdate): ?>
            <hr />
            <div class="mt-3">
                <h2>Delete Book</h2>
                <form id="deleteForm">
                    <input type="hidden" id="book_id" name="book_id" value="<?php echo $book['BookID'] ?>">
                    <input type="submit" id="deleteButton" class="btn btn-danger" value="Delete">
                </form>
            </div>
        <?php endif; ?>
    </div>
    <footer class="bg-dark text-white text-center p-3 mt-5">
        <p>© 2024 Library. All rights reserved.</p>
    </footer>
</body>
</html>
