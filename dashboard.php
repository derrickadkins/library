<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['admin'] === true) {
    header('Location: ../index.html');
    exit();
}

include 'db/db_connect.php';

$email = $_SESSION['email'];

// Fetch member details
$member_sql = "SELECT * FROM Members WHERE Email='$email'";
$member_result = $conn->query($member_sql);
$member = $member_result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include "util/nav.php"; ?>
    <div class="container mt-5">
      <h1>Welcome, <?php echo htmlspecialchars($member['Name']); ?></h1>
      <h2>Checked Out Books</h2>
      <div id="errorBooks" class="alert alert-danger" role="alert" style="display: none;"></div>
      <table class="table table-striped">
          <thead>
              <tr>
                  <th>Title</th>
                  <th>Checked Out Date</th>
                  <th>Due Date</th>
                  <th>Status</th>
                  <th>Action</th>
              </tr>
          </thead>
          <tbody id="checkoutsTable">
            <tr id="loadingMessage">
                <td colspan="5">Loading books...</td>
            </tr>
          </tbody>
      </table>
      <div class="mb-3">
          <a href="books.php" class="btn btn-primary">Checkout New Book</a>
      </div>
      <h2>Update Profile</h2>
      <div id="errorProfile" class="alert alert-danger" role="alert" style="display: none;"></div>
      <div id="successProfile" class="alert alert-success" role="alert" style="display: none;">Profile updated successfully.</div>
      <form id="profileForm">
          <div class="form-group">
              <label for="name">Name</label>
              <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($member['Name']); ?>" required>
          </div>
          <div class="form-group">
              <label for="dob">Date of Birth</label>
              <input type="date" id="dob" name="dob" class="form-control" value="<?php echo htmlspecialchars($member['DOB']); ?>" required>
          </div>
          <div class="form-group">
              <label for="email">Email</label>
              <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($member['Email']); ?>" required>
          </div>
          <div class="form-group">
              <label for="street1">Street</label>
              <input type="text" id="street1" name="street1" class="form-control" value="<?php echo htmlspecialchars($member['Street1']); ?>" required>
          </div>
          <div class="form-group">
              <label for="street2">Apartment, suite, etc.</label>
              <input type="text" id="street2" name="street2" class="form-control" value="<?php echo htmlspecialchars($member['Street2']); ?>">
          </div>
          <div class="form-group">
              <label for="city">City</label>
              <input type="text" id="city" name="city" class="form-control" value="<?php echo htmlspecialchars($member['City']); ?>" required>
          </div>
          <div class="form-group">
              <label for="state">State</label>
              <select class="form-control" id="state" name="state" required>
                  <option value="">Select a state</option>
                  <?php include "util/states.php"; ?>
              </select>
          </div>
          <div class="form-group">
              <label for="zipcode">Zip Code</label>
              <input type="text" id="zipcode" name="zipcode" class="form-control" value="<?php echo htmlspecialchars($member['ZipCode']); ?>" required>
          </div>
          <div class="form-group">
              <label for="phone">Phone Number</label>
              <input type="text" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($member['Phone']); ?>" required>
          </div>
          <button type="submit" class="btn btn-primary">Update Profile</button>
      </form>
    </div>

    <footer class="bg-dark text-white text-center p-3 mt-5">
        <p>© 2024 Library. All rights reserved.</p>
    </footer>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    console.log(<?php echo json_encode($member); ?>);
    // Set the state dropdown
    $("#state").val("<?php echo $member['State']; ?>");

    $.ajax({
        url: 'php/getCheckedOutBooks.php',
        type: 'GET',
        dataType: 'json',
        success: function(checkouts) {
            $("tbody").empty();
            $.each(checkouts, function(i, checkout) {
                var tr = $("<tr>");
                tr.append($("<td>").text(checkout.Title));
                tr.append($("<td>").text(checkout.CheckedOutDate));
                tr.append($("<td>").text(new Date(checkout.CheckedOutDate).setDate(new Date(checkout.CheckedOutDate).getDate() + 7)));
                tr.append($("<td>").text(checkout.CheckedInDate ? 'Returned' : 'Due'));

                if (!checkout.CheckedInDate) {
                    var form = $("<form>", {class: "checkIn"});
                    form.append($("<input>", {type: "hidden", name: "book_id", value: checkout.BookID}));
                    form.append($("<input>", {type: "submit", value: "Check In", class: "btn btn-primary"}));
                    tr.append($("<td>").append(form));
                } else {
                    tr.append($("<td>"));
                }

                $("tbody").append(tr);
            });

            $("form.checkIn").on("submit", function(event){
                event.preventDefault();

                var form = $(this);

                $.ajax({
                url: 'php/checkInBook.php',
                type: 'post',
                data: form.serialize(),
                success: function(response){
                    // handle the response from the server
                    console.log(response);
                    if (response.trim() == "success") {
                        form.hide();
                        form.parent().prev().text("Returned");
                    } else {
                        // display the error message
                        $("#errorBooks").html(response).show();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    // handle any errors
                    console.error(textStatus, errorThrown);
                }
                });
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error(textStatus, errorThrown);
        }
    });

    $("#profileForm").on("submit", function(event){
        event.preventDefault();
        $.ajax({
            url: "php/updateProfile.php",
            type: "post",
            data: $(this).serialize(),
            success: function(response){
                console.log(response);
                if(response.trim() == "success"){
                    $("#successProfile").show();
                    $("#errorProfile").hide();
                } else {
                    $("#errorProfile").html(response).show();
                    $("#successProfile").hide();
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                // handle any errors
                console.error(textStatus, errorThrown);
            }
        })
    });
});
</script>
</html>
