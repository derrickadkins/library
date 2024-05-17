<?php
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

if ($result->num_rows > 0) {
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
} else {
    echo "No records found.";
}

$conn->close();
?>