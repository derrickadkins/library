<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['admin'] === true) {
    header('Location: ../index.html');
    exit();
}

include 'db/db_connect.php';

$email = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if($_POST['book_id']){
    $bookId = $_POST['book_id'];
    $checkInDate = date('Y-m-d H:i:s');

    $checkIn_sql = "UPDATE Checkouts SET
                    CheckedInDate = '$checkInDate'
                    WHERE BookID = '$bookId'";
    
    if ($conn->query($checkIn_sql) === TRUE) {
        echo "Book checked in successfully.";
    } else {
        echo "Error checking in book: " . $conn->error;
    }
  }else{
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $street1 = $_POST['street1'];
    $street2 = $_POST['street2'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zipcode = $_POST['zipcode'];
    $phone = $_POST['phone'];

    $update_sql = "UPDATE Members SET 
                    Name = '$name',
                    DOB = '$dob',
                    Email = '$email',
                    Street1 = '$street1',
                    Street2 = '$street2',
                    City = '$city',
                    State = '$state',
                    ZipCode = '$zipcode',
                    Phone = '$phone'
                    WHERE Email = '$email'";

    if ($conn->query($update_sql) === TRUE) {
        echo "Profile updated successfully.";
    } else {
        echo "Error updating profile: " . $conn->error;
    }
  }
}

// Fetch member details
$member_sql = "SELECT * FROM Members WHERE Email='$email'";
$member_result = $conn->query($member_sql);
$member = $member_result->fetch_assoc();

// Fetch checked out books
$checkouts_sql = "SELECT Books.BookID, Books.Title, Checkouts.CheckedOutDate, Checkouts.CheckedInDate 
                  FROM Checkouts 
                  JOIN Books ON Checkouts.BookID = Books.BookID 
                  WHERE Checkouts.PersonID = " . $member['PersonID'];
$checkouts_result = $conn->query($checkouts_sql);
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
          <tbody>
              <?php while ($checkout = $checkouts_result->fetch_assoc()): ?>
                  <tr>
                      <td><?php echo htmlspecialchars($checkout['Title']); ?></td>
                      <td><?php echo htmlspecialchars($checkout['CheckedOutDate']); ?></td>
                      <td><?php echo date('Y-m-d H:i:s', strtotime($checkout['CheckedOutDate'] . ' + 7 days')); ?></td>
                      <td><?php echo ($checkout['CheckedInDate'] ? 'Returned' : 'Due'); ?></td>
                      <td>
                          <?php if (!$checkout['CheckedInDate']): ?>
                              <form action="dashboard.php" method="POST">
                                  <input type="hidden" name="book_id" value="<?php echo $checkout['BookID']; ?>">
                                  <input type="submit" value="Check In" class="btn btn-primary">
                              </form>
                          <?php endif; ?>
                      </td>
                  </tr>
              <?php endwhile; ?>
          </tbody>
      </table>
      <div class="mb-3">
          <a href="books.php" class="btn btn-primary">Checkout New Book</a>
      </div>
      <h2>Update Profile</h2>
      <form action="dashboard.php" method="POST">
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
              <select class="form-control" id="state" name="state" value="<?php echo htmlspecialchars($member['State']); ?>" required>
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
</html>
