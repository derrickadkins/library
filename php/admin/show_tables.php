<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['admin'] !== true) {
    header('Location: ../../index.php');
    exit();
}

include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $table = $_POST['table_name'];

    if($action == "delete") {
        $id_name = $_POST['id_name'];
        $id = $_POST['record_id'];

        $delete_sql = "DELETE FROM $table WHERE $id_name = ?";

        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "Record deleted successfully.";
        } else {
            echo "Error deleting record: " . $stmt->error;
        }

        $stmt->close();
    } elseif($action == "add") {
        // Get the column names for the table
        $columns_sql = "SHOW COLUMNS FROM $table";
        $columns_result = $conn->query($columns_sql);
        $columns = array();
        while ($column_row = $columns_result->fetch_assoc()) {
            $columns[] = $column_row['Field'];
        }

        // Prepare the INSERT statement
        $placeholders = rtrim(str_repeat('?,', count($columns)), ',');
        $insert_sql = "INSERT INTO $table (" . implode(',', $columns) . ") VALUES ($placeholders)";

        $stmt = $conn->prepare($insert_sql);

        // Bind the form values to the statement
        $values = array();
        $types = str_repeat('s', count($columns));
        foreach ($columns as $column) {
            $values[] = $_POST[$column];
        }
        $stmt->bind_param($types, ...$values);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Record added successfully.";
        } else {
            echo "Error adding record: " . $stmt->error;
        }

        $stmt->close();
    }
}

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

        // Get the primary key field name
        $primary_key_sql = "SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY'";
        $primary_key_result = $conn->query($primary_key_sql);
        $primary_key_row = $primary_key_result->fetch_assoc();
        $id_name = $primary_key_row['Column_name'];

        if ($table_result->num_rows > 0) {
            echo "<table border='1' cellpadding='5' cellspacing='0'>";
            echo "<tr>";
            // Print table headers
            $fields = array();
            while ($field = $table_result->fetch_field()) {
                echo "<th>" . $field->name . "</th>";
                $fields[] = $field->name;
            }
            echo "<th>Action</th>"; // Add an extra header for the action column
            echo "</tr>";

            // Print table rows
            while ($table_row = $table_result->fetch_assoc()) {
                echo "<tr>";
                foreach ($table_row as $cell) {
                    echo "<td>" . htmlspecialchars($cell) . "</td>";
                }
                
                // Add a delete button in the action column
                echo "<td><form method='POST' action='show_tables.php'>";
                echo "<input type='hidden' name='action' value='delete'>";
                echo "<input type='hidden' name='table_name' value='$table'>";
                echo "<input type='hidden' name='id_name' value='$id_name'>";
                echo "<input type='hidden' name='record_id' value='" . $table_row[$id_name] . "'>";
                echo "<input type='submit' value='Delete'>";
                echo "</form></td>";
                echo "</tr>";
            }

            // Add a form row at the end of the table
            echo "<tr>";
            foreach ($fields as $field_name) {
                echo "<td><input type='text' name='$field_name'></td>";
            }
            echo "<td><form method='POST' action='show_tables.php'>";
            echo "<input type='hidden' name='action' value='add'>";
            echo "<input type='hidden' name='table_name' value='$table'>";
            echo "<input type='submit' value='Add'>";
            echo "</form></td>";
            echo "</tr>";

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
