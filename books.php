<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['admin'] === true) {
    header('Location: ../index.html');
    exit();
}

include 'db/db_connect.php';

$email = $_SESSION['email'];

// Fetch member details
$member_sql = "SELECT * FROM Members WHERE Email='$email'";
$member_result = $conn->query($member_sql);
$member = $member_result->fetch_assoc();
$person_id = $member['PersonID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout'])) {
    $book_id = $_POST['book_id'];
    $checkout_date = date('Y-m-d H:i:s');

    $checkout_sql = "INSERT INTO Checkouts (PersonID, BookID, CheckedOutDate) VALUES ('$person_id', '$book_id', '$checkout_date')";

    if ($conn->query($checkout_sql) === TRUE) {
        echo "Book checked out successfully.";
    } else {
        echo "Error checking out book: " . $conn->error;
    }
}

// Fetch books and their availability
$books_sql = "
    SELECT Books.*, LatestCheckouts.CheckedInDate IS NULL AS CheckedOut
    FROM Books
    LEFT JOIN (
        SELECT Checkouts.BookID, Checkouts.CheckedInDate
        FROM Checkouts
        INNER JOIN (
            SELECT BookID, MAX(CheckedOutDate) as MaxCheckedOutDate
            FROM Checkouts
            GROUP BY BookID
        ) as MaxCheckouts ON Checkouts.BookID = MaxCheckouts.BookID AND Checkouts.CheckedOutDate = MaxCheckouts.MaxCheckedOutDate
    ) as LatestCheckouts ON Books.BookID = LatestCheckouts.BookID
";
$books_result = $conn->query($books_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Books</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include "util/nav.php"; ?>
    <div class="container mt-5">
        <h1>Browse Books</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Author</th>
                    <th>Title</th>
                    <th>ISBN</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($book = $books_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($book['Author']); ?></td>
                        <td><?php echo htmlspecialchars($book['Title']); ?></td>
                        <td><?php echo htmlspecialchars($book['ISBN']); ?></td>
                        <td><?php echo $book['CheckedOut'] ? 'Checked Out' : 'Available'; ?></td>
                        <td>
                            <?php if (!$book['CheckedOut']): ?>
                                <form action="books.php" method="POST">
                                    <input type="hidden" name="book_id" value="<?php echo $book['BookID']; ?>">
                                    <button type="submit" name="checkout" class="btn btn-primary">Check Out</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <footer class="bg-dark text-white text-center p-3 mt-5">
        <p>© 2024 Library. All rights reserved.</p>
    </footer>
</body>
</html>
