<?php
/*
 * This script is used to fetch all the books that are currently checked out by the 
 * logged-in member.
 * It starts a session and includes the db_connect.php file to establish a connection 
 * with the database.
 * 
 * The script retrieves the email of the logged-in user from the session and fetches 
 * the user's details from the Members table.
 * 
 * It then prepares an SQL statement to select the book ID, title, author, and 
 * checkout date of all books that are currently checked out by the user.
 * This is done by joining the Books and Checkouts tables on the book ID and 
 * filtering for rows where the person ID matches the user's ID and the checked in 
 * date is null.
 * 
 * The script binds the user's ID to the SQL statement and executes it.
 * It fetches the result and stores each row in an array.
 * 
 * The script then outputs the array as a JSON object.
 * 
 * Finally, the script closes the SQL statement and the database connection.
 */

session_start();

include '../db/db_connect.php';

$email = $_SESSION['email'];

// Fetch member details
$stmt = $conn->prepare("SELECT * FROM Members WHERE Email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$member_result = $stmt->get_result();
$member = $member_result->fetch_assoc();
$person_id = $member['PersonID'];

$books_sql = "
    SELECT Books.RecID, Books.Title, Books.Author, Checkouts.CheckedOutDate
    FROM Books
    INNER JOIN Checkouts ON Books.BookID = Checkouts.BookID
    WHERE Checkouts.PersonID = ? AND Checkouts.CheckedInDate IS NULL
";

$stmt = $conn->prepare($books_sql);
$stmt->bind_param("s", $person_id);
$stmt->execute();

$books_result = $stmt->get_result();
$books = array();
while($row = $books_result->fetch_assoc()) {
    $books[] = $row;
}

header('Content-Type: application/json');
echo json_encode($books);

$stmt->close();
$conn->close();
?>