<?php
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
