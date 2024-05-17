<?php
/*
 * This script is used to download a report of all the books that are currently checked out in the library system.
 * It includes the db_connect.php file to establish a connection with the database.
 * 
 * The script prepares an SQL statement to select the title, author, ISBN, name of the member who checked out the 
 * book, due date, and days until due from the Checkouts, Books, and Members tables.
 * It executes the SQL statement and fetches the result.
 * 
 * The script creates a file pointer connected to the output stream and outputs headers so that the file is 
 * downloaded rather than displayed.
 * It then outputs the column headings and fetches the data row by row, outputting each row to the CSV file.
 * 
 * After all the data has been outputted, the script closes the file pointer and the database connection.
 */

include '../db/db_connect.php';

// Prepare the SQL query
$sql = "SELECT 
            Books.Title,
            Books.Author,
            CONCAT('\'', Books.ISBN),
            Members.Name, 
            DATE(DATE_ADD(Checkouts.CheckedOutDate, INTERVAL 7 DAY)) AS DueDate, 
            DATEDIFF(DATE_ADD(Checkouts.CheckedOutDate, INTERVAL 7 DAY), CURDATE()) AS DaysUntilDue 
        FROM Checkouts 
        INNER JOIN Books ON Checkouts.BookID = Books.BookID 
        INNER JOIN Members ON Checkouts.PersonID = Members.PersonID
        WHERE Checkouts.CheckedInDate IS NULL";

$result = $conn->query($sql);

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=CheckoutsReport_' . date('Y_m_d_H_i_s') . '.csv');

// Output the column headings
fputcsv($output, array('Book Title', 'Author', 'ISBN', 'Checked Out By', 'Due Date', 'Days Until Due'));

// Fetch the data
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

// Close the file pointer
fclose($output);

$conn->close();
?>