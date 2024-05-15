<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['admin'] !== true) {
    header('Location: ../index.html');
    exit();
}

include 'db/db_connect.php';

// Fetch books and members to display
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

$members_sql = "SELECT * FROM Members";
$members_result = $conn->query($members_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include "util/admin_nav.php"; ?>
    <div class="container mt-5">
        <h1>Admin Dashboard</h1>
        <h2>Books</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Author</th>
                    <th>Title</th>
                    <th>ISBN</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($book = $books_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($book['Author']); ?></td>
                        <td><?php echo htmlspecialchars($book['Title']); ?></td>
                        <td><?php echo htmlspecialchars($book['ISBN']); ?></td>
                        <td><?php echo $book['CheckedOut'] ? 'Checked Out' : 'Available'; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="mb-3">
            <a href="addBook.php" class="btn btn-primary">Add Book</a>
        </div>

        <h2>Members</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($member = $members_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($member['Name']); ?></td>
                        <td><?php echo htmlspecialchars($member['Email']); ?></td>
                        <td><?php echo htmlspecialchars($member['Phone']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="mb-3">
            <a href="addMember.php" class="btn btn-primary">Add Member</a>
        </div>
    </div>
    <footer class="bg-dark text-white text-center p-3 mt-5">
        <p>© 2024 Library. All rights reserved.</p>
    </footer>    
</body>
</html>
