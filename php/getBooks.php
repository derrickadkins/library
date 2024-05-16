<?php

include '../db/db_connect.php';

$books_sql = "
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
";

$books_result = $conn->query($books_sql);
$books = array();
while($row = $books_result->fetch_assoc()) {
    $books[] = $row;
}

header('Content-Type: application/json');
echo json_encode($books);

?>