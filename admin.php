<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['admin'] !== true) {
    header('Location: ../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.25/datatables.min.css"/>
    <style>
        .delete-book, .delete-member {
            display: none;
        }
        
        tr:hover .delete-book, tr:hover .delete-member {
            display: block;
        }
    </style>
</head>
<body>
    <?php include "util/admin_nav.php"; ?>
    <div class="container mt-5">
        <h1>Admin Dashboard</h1>
        <h2>Books</h2>
        <div id="errorBooks" class="alert alert-danger" role="alert" style="display: none;"></div>
        <div id="successBooks" class="alert alert-success" role="alert" style="display: none;"></div>
        <table id="booksTable" class="table table-striped">
            <thead>
                <tr>
                    <th>Author</th>
                    <th>Title</th>
                    <th>ISBN</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr id="loadingBooks">
                    <td colspan="5">Loading books...</td>
                </tr>
            </tbody>
        </table>
        <div class="mb-3">
            <a href="addBook.php" class="btn btn-primary">Add Book</a>
        </div>

        <h2>Members</h2>
        <div id="errorMembers" class="alert alert-danger" role="alert" style="display: none;"></div>
        <div id="successMembers" class="alert alert-success" role="alert" style="display: none;"></div>
        <table id="membersTable" class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr id="loadingMembers">
                    <td colspan="5">Loading members...</td>
                </tr>
            </tbody>
        </table>
        <div class="mb-3">
            <a href="addMember.php" class="btn btn-primary">Add Member</a>
        </div>
    </div>
    <footer class="bg-dark text-white text-center p-3 mt-5">
        <p>© 2024 Library. All rights reserved.</p>
    </footer>

    <script>
    $(document).ready(function() {
        $.ajax({
            url: 'php/getBooks.php',
            type: 'GET',
            dataType: 'json',
            success: function(books) {
                console.log(books);
                var tbody = $("#booksTable tbody");
                tbody.empty();

                $.each(books, function(i, book) {
                    var tr = $("<tr>");
                    tr.append($("<td>").text(book.Author));
                    tr.append($("<td>").text(book.Title));
                    tr.append($("<td>").text(book.ISBN));
                    if (book.CheckedOutBy) {
                        tr.append($("<td>").text('Checked Out by ' + book.CheckedOutBy + " on " + new Date(book.CheckedOutDate).toLocaleDateString('en-US', {month: '2-digit', day: '2-digit', year: '2-digit'})));
                    } else if (book.CheckedInBy) {
                        tr.append($("<td>").text('Checked In by ' + book.CheckedInBy + " on " + new Date(book.CheckedInDate).toLocaleDateString('en-US', {month: '2-digit', day: '2-digit', year: '2-digit'})));
                    } else {
                        tr.append($("<td>").text('Available'));
                    }

                    var deleteForm = $("<form>", {class: "delete-book", display: "none"});
                    deleteForm.append($("<input>", {type: "hidden", name: "book_id", value: book.BookID}));
                    deleteForm.append($("<button>", {type: "submit", name: "delete", class: "btn btn-danger"}).text("Delete"));
                    tr.append($("<td>").append(deleteForm));

                    tbody.append(tr);
                });

                $(".delete-book").on("submit", function(event){
                    event.preventDefault();

                    var form = $(this);
                    var row = $(this).closest('tr');

                    $.ajax({
                    url: 'php/deleteBook.php',
                    type: 'post',
                    data: form.serialize(),
                    success: function(response){
                        console.log(response);
                        if (response.trim() == "success") {
                            row.remove();
                        } else {
                            $("#errorBooks").html(response).show();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        console.error(textStatus, errorThrown);
                    }
                    });
                });

                $("#booksTable").DataTable();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown);
            }
        });

        $.ajax({
            url: 'php/getMembers.php',
            type: 'GET',
            dataType: 'json',
            success: function(members) {
                console.log(members);
                var tbody = $("#membersTable tbody");
                tbody.empty();

                $.each(members, function(i, member) {
                    var tr = $("<tr>");
                    tr.append($("<td>").text(member.Name));
                    tr.append($("<td>").text(member.Email));
                    tr.append($("<td>").text(member.Phone));

                    var deleteForm = $("<form>", {class: "delete-member", display: "none"});
                    deleteForm.append($("<input>", {type: "hidden", name: "person_id", value: member.PersonID}));
                    deleteForm.append($("<button>", {type: "submit", name: "delete", class: "btn btn-danger"}).text("Delete"));
                    tr.append($("<td>").append(deleteForm));

                    tbody.append(tr);
                });

                $(".delete-member").on("submit", function(event){
                    event.preventDefault();

                    var form = $(this);
                    var row = $(this).closest('tr');

                    $.ajax({
                    url: 'php/deleteMember.php',
                    type: 'post',
                    data: form.serialize(),
                    success: function(response){
                        console.log(response);
                        if (response.trim() == "success") {
                            row.remove();
                        } else {
                            $("#errorMembers").html(response).show();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        console.error(textStatus, errorThrown);
                    }
                    });
                });

                $("#membersTable").DataTable();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown);
            }
        });
    });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.25/datatables.min.js"></script>
</body>
</html>
