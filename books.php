<?php
// start session to access session objects and redirect to home if email is not set or if admin
session_start();
if (!isset($_SESSION['email']) || $_SESSION['admin'] === true) {
    header('Location: ../index.php');
    exit();
}

$name = $_SESSION['name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Books</title>
    <link rel="icon" href="icon.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.25/datatables.min.css"/>
</head>
<body>
    <?php include "util/nav.php"; ?>
    <div class="container mt-5">
        <h1>Browse Books</h1>
        <div id="error" class="alert alert-danger" role="alert" style="display: none;"></div>
        <div class="table-responsive">
            <table class="table table-striped">
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
                    <tr id="loadingMessage">
                        <td colspan="5">Loading books...</td>
                    </tr>
                </tbody>
            </table>
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
                    var tbody = $("tbody");
                    tbody.empty();
        
                    $.each(books, function(i, book) {
                        var tr = $("<tr>");
                        tr.append($("<td>").text(book.Author));
                        tr.append($("<td>").text(book.Title));
                        tr.append($("<td>").text(book.ISBN));
                        
                        if (book.CheckedOutBy) {
                            tr.append($("<td>").text('Checked Out by ' + book.CheckedOutBy));
                            tr.append($("<td>"));
                        } else {
                            tr.append($("<td>").text('Available'));
                            var form = $("<form>");
                            form.append($("<input>", {type: "hidden", name: "book_id", value: book.BookID}));
                            form.append($("<button>", {type: "submit", name: "checkout", class: "btn btn-primary"}).text("Check Out"));
                            tr.append($("<td>").append(form));
                        }
        
                        tbody.append(tr);
                    });

                    $("form").on("submit", function(event){
                        event.preventDefault();
                        var form = $(this);
                        $(this).find('input[type="submit"]').prop('disabled', true);

                        $.ajax({
                        url: 'php/checkoutBook.php',
                        type: 'post',
                        data: form.serialize(),
                        success: function(response){
                            console.log(response);
                            if (response.trim() == "success") {
                                form.parent().prev().text("Checked Out by <?php echo $name; ?>");
                                form.remove();
                            } else {
                                $("#error").html(response).show();
                                $(this).find('input[type="submit"]').prop('disabled', false);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            console.error(textStatus, errorThrown);
                            $(this).find('input[type="submit"]').prop('disabled', false);
                        }
                        });
                    });

                    $("table").DataTable();
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
