<?php
include 'db_connect.php';

// Show tables
$sql = "SHOW TABLES";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h1>Tables in the database:</h1>";
    echo "<ul>";
    while($row = $result->fetch_array()) {
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";

    // Fetch and display contents of each table
    $result->data_seek(0); // Reset the result pointer to the start
    while($row = $result->fetch_array()) {
        $table = $row[0];
        echo "<h2>Contents of table: $table</h2>";
        $table_sql = "SELECT * FROM $table";
        $table_result = $conn->query($table_sql);

        if ($table_result->num_rows > 0) {
            echo "<table border='1' cellpadding='5' cellspacing='0'>";
            echo "<tr>";
            // Print table headers
            while ($field = $table_result->fetch_field()) {
                echo "<th>" . $field->name . "</th>";
            }
            echo "</tr>";

            // Print table rows
            while ($table_row = $table_result->fetch_assoc()) {
                echo "<tr>";
                foreach ($table_row as $cell) {
                    echo "<td>" . htmlspecialchars($cell) . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No records found in table $table.<br>";
        }
    }
} else {
    echo "No tables found in the database.";
}

$conn->close();
?>
