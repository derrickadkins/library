<?php
/*
 * This script sets up the database schema for a library management system. 
 * It includes logic to drop existing tables and create new ones, ensuring the 
 * database is initialized correctly. The schema consists of four tables: 
 * Members, Books, Checkouts, and Admins. Below is a detailed description of each 
 * table and the associated logic in this script:

 * Members Table:
 * - PersonID: An auto-incremented primary key that uniquely identifies each member.
 * - Name: A VARCHAR field to store the member's name.
 * - DOB: A DATE field to store the member's date of birth.
 * - Email: A VARCHAR field to store the member's email address, which must be unique.
 * - Street1: A VARCHAR field to store the primary street address of the member.
 * - Street2: A VARCHAR field to store additional address information (e.g., apartment, suite).
 * - City: A VARCHAR field to store the city of the member's address.
 * - State: A VARCHAR field to store the state of the member's address.
 * - ZipCode: A VARCHAR field to store the zip code of the member's address.
 * - Phone: A VARCHAR field to store the member's phone number.
 * - Password: A VARCHAR field to store the member's hashed password.
 * - RecID: A VARCHAR field to store a unique identifier for the record, which must be unique.

 * Books Table:
 * - BookID: An auto-incremented primary key that uniquely identifies each book.
 * - Author: A VARCHAR field to store the author's name.
 * - Title: A VARCHAR field to store the book's title.
 * - ISBN: A VARCHAR field to store the book's ISBN.
 * - RecID: A VARCHAR field to store a unique identifier for the book record, which must be unique.

 * Checkouts Table:
 * - CheckoutID: An auto-incremented primary key that uniquely identifies each checkout record.
 * - PersonID: An INT field that stores the ID of the member who checked out the book, linking to the Members table.
 * - BookID: An INT field that stores the ID of the checked-out book, linking to the Books table.
 * - CheckedOutDate: A DATETIME field to store the date and time when the book was checked out.
 * - CheckedInDate: A DATETIME field to store the date and time when the book was checked in (if applicable).

 * Admins Table:
 * - AdminID: An auto-incremented primary key that uniquely identifies each admin.
 * - Email: A VARCHAR field to store the admin's email address, which must be unique and not null.

 * Logic:
 * 1. The script starts by including the database connection file (`db_connect.php`).
 * 2. It then defines an array of SQL queries to drop existing tables (if they exist) and create new tables with the specified schema.
 * 3. The script iterates through each query in the array, executing them against the database connection.
 * 4. If a query executes successfully, a success message is displayed; if there is an error, an error message with the query and error details is displayed.
 * 5. Finally, the database connection is closed.

 * This initialization script ensures that the database is set up with the necessary tables and fields for the library management system to function correctly.
 */

include 'db_connect.php';

$queries = [
    "DROP TABLE IF EXISTS Admins",
    "DROP TABLE IF EXISTS Checkouts",
    "DROP TABLE IF EXISTS Books",
    "DROP TABLE IF EXISTS Members",

    "CREATE TABLE Members (
        PersonID INT AUTO_INCREMENT PRIMARY KEY,
        Name VARCHAR(100),
        DOB DATE,
        Email VARCHAR(100) UNIQUE,
        Street1 VARCHAR(255),
        Street2 VARCHAR(255),
        City VARCHAR(100),
        State VARCHAR(100),
        ZipCode VARCHAR(20),
        Phone VARCHAR(15),
        Password VARCHAR(255),
        RecID VARCHAR(50) UNIQUE
    )",

    "CREATE TABLE Books (
        BookID INT AUTO_INCREMENT PRIMARY KEY,
        Author VARCHAR(100),
        Title VARCHAR(255),
        ISBN VARCHAR(50),
        RecID VARCHAR(50) UNIQUE
    )",

    "CREATE TABLE Checkouts (
        CheckoutID INT AUTO_INCREMENT PRIMARY KEY,
        PersonID INT,
        BookID INT,
        CheckedOutDate DATETIME,
        CheckedInDate DATETIME
    )",

    "CREATE TABLE Admins (
        AdminID INT AUTO_INCREMENT PRIMARY KEY,
        Email VARCHAR(100) UNIQUE NOT NULL
    )"
];

foreach ($queries as $query) {
    if ($conn->query($query) === TRUE) {
        echo "Query executed successfully: " . substr($query, 0, 50) . "<br>";
    } else {
        echo "Error executing query: " . substr($query, 0, 50) . " - " . $conn->error . "<br>";
    }
}

$conn->close();
?>
