<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Member Dashboard</title>
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
    />
    <link rel="stylesheet" href="css/styles.css" />
  </head>
  <body>
    <div class="container mt-5">
      <h1>Welcome, John Doe</h1>
      <h2>Checked Out Books</h2>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Title</th>
            <th>Checked Out Date</th>
            <th>Due Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>The Great Gatsby</td>
            <td>2024-05-01 10:00:00</td>
            <td>2024-05-08 10:00:00</td>
            <td>Due</td>
          </tr>
          <tr>
            <td>1984</td>
            <td>2024-04-25 09:30:00</td>
            <td>2024-05-02 09:30:00</td>
            <td>Returned</td>
          </tr>
        </tbody>
      </table>
      <h2>Update Profile</h2>
      <form action="update_profile.php" method="POST">
        <div class="form-group">
          <label for="name">Name</label>
          <input
            type="text"
            id="name"
            name="name"
            class="form-control"
            value="John Doe"
            required
          />
        </div>
        <div class="form-group">
          <label for="dob">Date of Birth</label>
          <input
            type="date"
            id="dob"
            name="dob"
            class="form-control"
            value="1990-01-01"
            required
          />
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input
            type="email"
            id="email"
            name="email"
            class="form-control"
            value="john.doe@example.com"
            required
          />
        </div>
        <div class="form-group">
          <label for="street1">Street</label><br />
          <input
            class="form-control"
            type="text"
            id="street1"
            name="street1"
          /><br />
          <label for="street2">Apartment, suite, etc.</label><br />
          <input
            class="form-control"
            type="text"
            id="street2"
            name="street2"
          /><br />
          <label for="state">State</label><br />
          <select class="form-control" id="state" name="state">
            <option value="">Select a state</option>
            <option value="AL">Alabama</option>
            <option value="AK">Alaska</option>
            <option value="AZ">Arizona</option>
            <option value="AR">Arkansas</option>
            <option value="CA">California</option>
            <option value="CO">Colorado</option>
            <option value="CT">Connecticut</option>
            <option value="DE">Delaware</option>
            <option value="FL">Florida</option>
            <option value="GA">Georgia</option>
            <option value="HI">Hawaii</option>
            <option value="ID">Idaho</option>
            <option value="IL">Illinois</option>
            <option value="IN">Indiana</option>
            <option value="IA">Iowa</option>
            <option value="KS">Kansas</option>
            <option value="KY">Kentucky</option>
            <option value="LA">Louisiana</option>
            <option value="ME">Maine</option>
            <option value="MD">Maryland</option>
            <option value="MA">Massachusetts</option>
            <option value="MI">Michigan</option>
            <option value="MN">Minnesota</option>
            <option value="MS">Mississippi</option>
            <option value="MO">Missouri</option>
            <option value="MT">Montana</option>
            <option value="NE">Nebraska</option>
            <option value="NV">Nevada</option>
            <option value="NH">New Hampshire</option>
            <option value="NJ">New Jersey</option>
            <option value="NM">New Mexico</option>
            <option value="NY">New York</option>
            <option value="NC">North Carolina</option>
            <option value="ND">North Dakota</option>
            <option value="OH">Ohio</option>
            <option value="OK">Oklahoma</option>
            <option value="OR">Oregon</option>
            <option value="PA">Pennsylvania</option>
            <option value="RI">Rhode Island</option>
            <option value="SC">South Carolina</option>
            <option value="SD">South Dakota</option>
            <option value="TN">Tennessee</option>
            <option value="TX">Texas</option>
            <option value="UT">Utah</option>
            <option value="VT">Vermont</option>
            <option value="VA">Virginia</option>
            <option value="WA">Washington</option>
            <option value="WV">West Virginia</option>
            <option value="WI">Wisconsin</option>
            <option value="WY">Wyoming</option>
          </select>
          <br />
          <label for="zip">Zip code</label><br />
          <input class="form-control" type="text" id="zip" name="zip" />
        </div>
        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input
            type="text"
            id="phone"
            name="phone"
            class="form-control"
            value="555-1234"
            required
          />
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
      </form>
    </div>
  </body>
</html>
