<?php
/*
    index.php
    This script serves as the landing page for the library system. It checks if a user is already logged in
    by verifying the presence of an email in the session. If the user is logged in, they are redirected to 
    the dashboard. The page includes a login form for users to enter their email and password to access the 
    library system. Bootstrap and FontAwesome are used for styling the page and enhancing the user interface. 
    JavaScript/jQuery is used for handling dynamic behavior on the page.
*/

session_start();
if (isset($_SESSION['email'])) {
  header("Location: dashboard.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Library System</title>
    <link rel="icon" href="icon.png" type="image/x-icon" />
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="js/index.js"></script>
  </head>
  <body>
    <div class="container">
      <div class="row justify-content-center mt-5">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header text-center">
              <h2>Welcome to the Library System</h2>
            </div>
            <div class="card-body">
              <div id="error" class="alert alert-danger" role="alert" style="display: none;"></div>
              <form action="php/auth/login.php" method="POST">
                <div class="form-group">
                  <label for="email">Email</label>
                  <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    required
                  />
                </div>
                <div class="form-group">
                  <label for="password">Password</label>
                  <div class="input-group">
                    <input
                      type="password"
                      id="password"
                      name="password"
                      class="form-control"
                      required
                    />
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <i id="toggle-password" class="fa fa-eye-slash" aria-hidden="true"></i>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="form-group text-center">
                  <button id="loginButton" type="submit" class="btn btn-primary">Login</button>
                </div>
              </form>
            </div>
            <div class="card-footer text-center">
              <small>&copy; 2024 Library System</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
