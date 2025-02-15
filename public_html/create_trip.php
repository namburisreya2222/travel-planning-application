<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';

// Get the user's name
$user_id = $_SESSION['user_id'];
$user_query = "SELECT name FROM users WHERE id = '$user_id'";
$user_result = $conn->query($user_query);
$user = $user_result->fetch_assoc();
$username = $user['name'];

// Check if the user has completed their latest trip
$query = "SELECT * FROM trips WHERE user_id = '$user_id' ORDER BY end_date DESC LIMIT 1";
$result = $conn->query($query);
$trip_completed = false;

if ($result->num_rows > 0) {
    $trip = $result->fetch_assoc();
    $today = date("Y-m-d");

    // If the trip's end date is before today, allow the user to post the trip experience
    if ($today > $trip['end_date']) {
        $trip_completed = true;
    }
}

// Handle trip creation form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $trip_name = $_POST['trip_name'];
    $destination = $_POST['destination'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $accommodation = $_POST['accommodation'];
    $transportation = $_POST['transportation'];
    $budget = $_POST['budget'];

    // Server-side validation
    if (!empty($trip_name) && !empty($destination) && !empty($start_date) && !empty($end_date) && !empty($budget)) {
        if ($start_date > $end_date) {
            $error = "Start date cannot be later than end date!";
        } elseif (!is_numeric($budget) || $budget <= 0) {
            $error = "Please enter a valid budget!";
        } else {
            // Insert data into the trips table
            $query = "INSERT INTO trips (user_id, trip_name, destination, start_date, end_date, accommodation, transportation, budget) 
                      VALUES ('$user_id', '$trip_name', '$destination', '$start_date', '$end_date', '$accommodation', '$transportation', '$budget')";
            if ($conn->query($query)) {
                $success = "Trip created successfully!";
                // Optionally, redirect to view_trip.php
                // header('Location: view_trip.php');
                // exit();
            } else {
                $error = "Error: " . $conn->error;
            }
        }
    } else {
        $error = "Please fill in all required fields!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Trip</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f7e9fa; /* Light lilac background */
        }
        .navbar {
            background-color: #9b59b6; /* Dark lilac navbar */
        }
        .navbar .nav-link {
            color: #fff;
        }
        .navbar .nav-link:hover {
            color: #d1c4e9;
        }
        .btn-primary {
            background-color: #9b59b6;
            border-color: #9b59b6;
        }
        .btn-primary:hover {
            background-color: #8e44ad;
            border-color: #8e44ad;
        }
        h2 {
            color: #9b59b6;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <a class="navbar-brand" href="create_trip.php">Travel Planner Application</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <span class="nav-link">Hi, <?php echo $username; ?></span>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="profile.php">Profile</a> <!-- Profile Link -->
      </li>
      <li class="nav-item">
        <a class="nav-link" href="redeem_points.php">Rewards</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="view_trip.php">View Trips</a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Logout</a>
      </li>
    </ul>
  </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center">Plan Your Trip</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php elseif (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    <form id="tripForm" method="POST" action="">
        <div class="form-group">
            <label for="trip_name">Trip Name:</label>
            <input type="text" class="form-control" id="trip_name" name="trip_name" required>
        </div>
        <div class="form-group">
            <label for="destination">Destination:</label>
            <input type="text" class="form-control" id="destination" name="destination" required>
        </div>
        <div class="form-group">
            <label for="start_date">Start Date:</label>
            <input type="date" class="form-control" id="start_date" name="start_date" required>
        </div>
        <div class="form-group">
            <label for="end_date">End Date:</label>
            <input type="date" class="form-control" id="end_date" name="end_date" required>
        </div>
        <div class="form-group">
            <label for="accommodation">Accommodation:</label>
            <input type="text" class="form-control" id="accommodation" name="accommodation">
        </div>
        <div class="form-group">
            <label for="transportation">Transportation:</label>
            <input type="text" class="form-control" id="transportation" name="transportation">
        </div>
        <div class="form-group">
            <label for="budget">Budget:</label>
            <input type="number" class="form-control" id="budget" name="budget" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Create Trip</button>
    </form>
</div>
<script>
document.getElementById('tripForm').addEventListener('submit', function(e) {
    const tripName = document.getElementById('trip_name').value.trim();
    const destination = document.getElementById('destination').value.trim();
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const accommodation = document.getElementById('accommodation').value.trim();
    const transportation = document.getElementById('transportation').value.trim();
    const budget = document.getElementById('budget').value.trim();
    let isValid = true;

    // Validation for Trip Name
    if (tripName === '') {
        alert("Trip Name is required.");
        isValid = false;
    } else if (tripName.length < 3) {
        alert("Trip Name should be at least 3 characters.");
        isValid = false;
    }

    // Validation for Destination
    if (destination === '') {
        alert("Destination is required.");
        isValid = false;
    } else if (destination.length < 3) {
        alert("Destination should be at least 3 characters.");
        isValid = false;
    }

    // Validation for Start Date
    if (startDate === '') {
        alert("Start Date is required.");
        isValid = false;
    }

    // Validation for End Date
    if (endDate === '') {
        alert("End Date is required.");
        isValid = false;
    } else if (startDate > endDate) {
        alert("Start Date cannot be later than End Date.");
        isValid = false;
    }

    // Validation for Accommodation (optional)
    if (accommodation !== '' && accommodation.length < 3) {
        alert("Accommodation should be at least 3 characters if provided.");
        isValid = false;
    }

    // Validation for Transportation (optional)
    if (transportation !== '' && transportation.length < 3) {
        alert("Transportation should be at least 3 characters if provided.");
        isValid = false;
    }

    // Validation for Budget
    if (budget === '') {
        alert("Budget is required.");
        isValid = false;
    } else if (isNaN(budget) || budget <= 0) {
        alert("Please enter a valid positive number for Budget.");
        isValid = false;
    }

    // Prevent form submission if any validation fails
    if (!isValid) {
        e.preventDefault();
    }
});
</script>


<?php if (isset($success)): ?>
    // Reset form after successful submission
    document.getElementById('tripForm').reset();
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
