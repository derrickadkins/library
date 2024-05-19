<?php
/*
 * This script is used to fetch all the books from the library system.
 * It includes the db_connect.php file to establish a connection with the database.
 * 
 * The script prepares an SQL statement to select all columns from the Books table, 
 * along with additional columns for who checked out the book, when it was checked 
 * out, who checked it in, and when it was checked in.
 * These additional columns are calculated using subqueries and CASE statements.
 * 
 * The subquery in the FROM clause (LatestCheckouts) gets the latest checkout record 
 * for each book.
 * The CASE statements then determine the values of the CheckedOutBy, CheckedOutDate, 
 * CheckedInBy, and CheckedInDate columns based on whether the book is currently 
 * checked out or not.
 * 
 * The script then executes the SQL statement and fetches the result.
 */

include '../db_connect.php';

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