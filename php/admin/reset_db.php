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
    )",

    // Insert data into Admins table
    "INSERT INTO Admins (AdminID, Email) VALUES (1, 'john.doe@example.com')",

    // Insert data into Books table
    "INSERT INTO Books (BookID, Author, Title, ISBN, RecID) VALUES 
    (1, 'God', 'Bible', '979-8859457274', '66451b6ccd13a'),
    (5, 'Harper Lee', 'To Kill A Mockingbird', '978-0-06-112008-4', '664654af6eac8'),
    (6, 'J.K. Rowling', 'Harry Potter and the Sorcerer\'s Stone', '978-0-590-35340-3', '6646bf26bfb90'),
    (7, 'George Orwell', '1984', '978-0-452-28423-4', '6646c058506b8'),
    (8, 'F. Scott Fitzgerald', 'The Great Gatsby', '978-0-7432-7356-5', '664788ed95a62'),
    (9, 'Jane Austen', 'Pride and Prejudice', '978-0-14-143951-8', '664788ffd8681'),
    (10, 'J.R.R. Tolkien', 'The Hobbit', '978-0-618-00221-3', '6647891a1d14a'),
    (11, 'Mark Twain', 'The Adventures of Huckleberry Finn', '978-0-14-310732-3', '6647892f5dfcf'),
    (12, 'Mary Shelley', 'Frankenstein', '978-0-486-28211-4', '664789403c796'),
    (13, 'Leo Tolstoy', 'War and Peace', '978-0-14-303999-0', '6647895528131'),
    (14, 'Ernest Hemingway', 'The Old Man and the Sea', '978-0-684-80122-3', '6647896adbe16'),
    (15, 'Herman Melville', 'Moby Dick', '978-0-14-243724-7', '6647897e9c05f'),
    (16, 'George Orwell', 'Animal Farm', '978-0-452-28424-1', '6647898e119d6')",

    // Insert data into Checkouts table
    "INSERT INTO Checkouts (CheckoutID, PersonID, BookID, CheckedOutDate, CheckedInDate) VALUES 
    (1, 2, 1, '2024-05-15 17:00:11', '2024-05-16 14:44:01'),
    (2, 2, 1, '2024-05-15 17:24:22', '2024-05-16 14:44:01'),
    (3, 2, 1, '2024-05-15 17:43:38', '2024-05-16 14:44:01'),
    (4, 2, 1, '2024-05-15 20:57:41', '2024-05-16 14:44:01'),
    (5, 2, 1, '2024-05-15 21:02:02', '2024-05-16 14:44:01'),
    (6, 2, 2, '2024-05-16 12:21:07', '2024-05-16 14:31:10'),
    (7, 2, 2, '2024-05-16 12:31:55', '2024-05-16 14:31:10'),
    (8, 2, 2, '2024-05-16 12:32:19', '2024-05-16 14:31:10'),
    (9, 2, 2, '2024-05-16 13:13:23', '2024-05-16 14:31:10'),
    (10, 2, 2, '2024-05-16 14:31:32', '2024-05-18 12:14:11'),
    (11, 2, 1, '2024-05-16 14:31:34', '2024-05-16 14:44:01'),
    (12, 7, 6, '2024-05-16 22:31:01', NULL),
    (13, 7, 5, '2024-05-16 22:31:12', NULL),
    (14, 2, 14, '2024-05-18 11:04:35', '2024-05-18 11:04:43')",

    // Insert data into Members table
    "INSERT INTO Members (PersonID, Name, DOB, Email, Street1, Street2, City, State, ZipCode, Phone, Password, RecID) VALUES 
    (1, 'John Doe', '1990-01-01', 'john.doe@example.com', '123 Main St', '', 'Anytown', 'CA', '12345', '555-1234', '$2y$10$VR9AsG1Xqxxipk3qj3whEe4GTw0ZMLbKML9/C2tEY7LnVZsnXiIV6', '6644f819b276a'),
    (2, 'Derrick Adkins', '2024-05-15', 'derrick.l.adkins@gmail.com', '3732 Applegate Avenue', '', 'Cheviot', 'OH', '45211', '9379031098', '$2y$10$yHJ.VaYAlP.xK3FRwEHRQO.wrgYHxXq.QtCeHX.plAz8MBF7wn3aS', '66451bef6df91'),
    (7, 'Jane Smith', '1987-07-30', 'jane.smith@mail.com', '123 Main Street', 'Apt. 3', 'Metropolis', 'IN', '83940', '4729424723', '$2y$10$0rJ1Dlzm6EVjsaXZJemgOuSnDi.XehRMjdXCKNXdgsfgnAySy8LBq', '6646c1078169b'),
    (8, 'John Smith', '1985-04-02', 'john.smith@example.com', '123 Maple Street', 'Apt 4B', 'Springfield', 'IL', '62701', '555-1234', '$2y$10$wF28pcQJ2zr4s3LS3NV3tOAttBf1kjDxusWXJ8w8xJgc7SL7Q.11q', '66478ae6756ee'),
    (9, 'Emily Johnson', '1990-07-23', 'emily.johnson@example.com', '456 Oak Avenue', '', 'Columbus', 'OH', '43215', '555-5678', '$2y$10$X373CgAMst7E6cJJ.H4ng.M.ANtK5.WROep2D3UCLHahty2nXQ8fm', '6647b8d11b904'),
    (10, 'Michael Brown', '1978-12-05', 'michael.brown@example.com', '789 Pine Lane', '', 'Austin', 'TX', '73301', '555-9012', '$2y$10$MaQas5D3eZO763ATlIek5.YijG1Cmy/tUG3yJg81Tb6tmDl/lcgXC', '6647b91857213'),
    (11, 'Sarah Davis', '1982-03-14', 'sarah.davis@example.com', '101 Cherry Street', 'Suite 300', 'Denver', 'CO', '80203', '555-3456', '$2y$10$fs5xiTC7Z.8.JiBQRBB9duihQLCwc/98AI9V5QtHs5tNAG9Y/ZUAS', '6647b9756cf79'),
    (12, 'David Martinez', '1995-11-21', 'david.martinez@example.com', '202 Birch Boulevard', 'Apt 2A', 'Seattle', 'WA', '98101', '555-7890', '$2y$10$yo.IFrrcFjWyZzzbf1J8Se.rcQV1aYT3DeTdQ9Lws93dWoYwjHdxC', '6647b9bda0031')"
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
