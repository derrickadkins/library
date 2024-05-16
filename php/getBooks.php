<?php

include '../db/db_connect.php';

$books_sql = "
    SELECT Books.*, CASE WHEN CheckedInDate IS NULL THEN Members.Name ELSE '' END AS CheckedOutBy
    FROM Books
    LEFT JOIN (
        SELECT Checkouts.BookID, Checkouts.PersonID, Checkouts.CheckedInDate
        FROM Checkouts
        INNER JOIN (
            SELECT BookID, MAX(CheckedOutDate) as MaxCheckedOutDate
            FROM Checkouts
            GROUP BY BookID
        ) as MaxCheckouts ON Checkouts.BookID = MaxCheckouts.BookID AND Checkouts.CheckedOutDate = MaxCheckouts.MaxCheckedOutDate
    ) as LatestCheckouts ON Books.BookID = LatestCheckouts.BookID
    LEFT JOIN Members ON LatestCheckouts.PersonID = Members.PersonID
";

$books_result = $conn->query($books_sql);
$books = array();
while($row = $books_result->fetch_assoc()) {
    $books[] = $row;
}

header('Content-Type: application/json');
echo json_encode($books);

?>