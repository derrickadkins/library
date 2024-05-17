<?php
/*
 * This file serves as the Admin Dashboard page for a library management system.
 * It ensures that a user is logged in and is an admin before granting access.
 * The HTML section sets up the page layout, including Bootstrap and FontAwesome for 
 * styling, and DataTables for enhanced table functionality.
 * The page displays an admin dashboard with sections for viewing and managing books, 
 * members, and generating reports.
 * JavaScript/jQuery is used to handle dynamic content loading, form submissions for 
 * adding/deleting books and members, and updating passwords in real-time.
 */

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
    <link rel="icon" href="icon.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.25/datatables.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
        <h2>Checkouts Report</h2>
        <div class="mb-3">
            <a href="php/downloadCheckoutsReport.php" class="btn btn-primary">Download</a>
        </div>
        <h2>Books</h2>
        <div id="errorBooks" class="alert alert-danger" role="alert" style="display: none;"></div>
        <div id="successBooks" class="alert alert-success" role="alert" style="display: none;"></div>
        <div class="table-responsive">
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
        </div>
        <div class="mb-3">
            <a href="addBook.php" class="btn btn-primary">Add Book</a>
        </div>
        <hr />
        <h2>Members</h2>
        <div id="errorMembers" class="alert alert-danger" role="alert" style="display: none;"></div>
        <div id="successMembers" class="alert alert-success" role="alert" style="display: none;"></div>
        <div class="table-responsive">
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
        </div>
        <div class="mb-3">
            <a href="addMember.php" class="btn btn-primary">Add Member</a>
        </div>
        <hr />
        <h2>Update Password</h2>
        <div id="errorPassword" class="alert alert-danger" role="alert" style="display: none;"></div>
        <div id="successPassword" class="alert alert-success" role="alert" style="display: none;">Password updated successfully.</div>
        <form id="updatePasswordForm">
            <div class="form-group">
                <label for="old_password">Old Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="old_password" name="old_password" required>
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fa fa-eye-slash" id="toggleOldPassword"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="new_password">New Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fa fa-eye-slash" id="toggleNewPassword"></i>
                        </span>
                    </div>
                </div>
                <small id="passwordHelp" class="form-text text-muted">
                    Password must be at least 8 characters long, contain an 
                    uppercase letter, a lowercase letter, and a number.
                </small>
            </div>
            <button id="updatePasswordButton" type="submit" class="btn btn-primary">Update Password</button>
        </form>
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
                    $(this).find('input[type="submit"]').prop('disabled', true);

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
                            $(this).find('input[type="submit"]').prop('disabled', false);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        console.error(textStatus, errorThrown);
                        $(this).find('input[type="submit"]').prop('disabled', false);
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
                    $(this).find('input[type="submit"]').prop('disabled', true);

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
                            $(this).find('input[type="submit"]').prop('disabled', false);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        console.error(textStatus, errorThrown);
                        $(this).find('input[type="submit"]').prop('disabled', false);
                    }
                    });
                });

                $("#membersTable").DataTable();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown);
            }
        });

        $("#toggleOldPassword").click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $("#old_password");
            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });

        $("#toggleNewPassword").click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $("#new_password");
            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });

        $("#updatePasswordForm").on("submit", function(event){
            event.preventDefault();

            var form = this;
            var newPassword = $("#new_password").val();
            var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;
            if (!regex.test(newPassword)) {
                $("#errorPassword").html("Password does not meet the strength requirements.").show();
                $("#successPassword").hide();
                return;
            }

            $("#updatePasswordButton").prop("disabled", true);

            $.ajax({
                url: "php/updatePassword.php",
                type: "post",
                data: $(this).serialize(),
                success: function(response){
                    if(response.trim() == "success"){
                        $("#successPassword").show();
                        $("#errorPassword").hide();
                        form.reset();
                    }else{
                        $("#errorPassword").html(response).show();
                        $("#successPassword").hide();
                    }
                    $("#updatePasswordButton").prop("disabled", false);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(textStatus, errorThrown);
                    $("#updatePasswordButton").prop("disabled", false);
                }
            });
        });
    });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.25/datatables.min.js"></script>
</body>
</html>
