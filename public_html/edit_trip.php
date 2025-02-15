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

// Get the trip details to edit
$trip_id = $_GET['id'];
$query = "SELECT * FROM trips WHERE id = '$trip_id' AND user_id = '$user_id'";
$result = $conn->query($query);

if ($result->num_rows == 1) {
    $trip = $result->fetch_assoc();
} else {
    echo "No trip found.";
    exit();
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $trip_name = $_POST['trip_name'];
    $destination = $_POST['destination'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $accommodation = $_POST['accommodation'];
    $transportation = $_POST['transportation'];
    $budget = $_POST['budget'];

    // Validate the input
    if (!empty($trip_name) && !empty($destination) && !empty($start_date) && !empty($end_date) && !empty($budget)) {
        if ($start_date > $end_date) {
            $error = "Start date cannot be later than end date!";
        } elseif (!is_numeric($budget) || $budget <= 0) {
            $error = "Please enter a valid budget!";
        } else {
            // Update the trip details in the database
            $update_query = "UPDATE trips 
                             SET trip_name = '$trip_name', destination = '$destination', start_date = '$start_date', 
                                 end_date = '$end_date', accommodation = '$accommodation', transportation = '$transportation', 
                                 budget = '$budget'
                             WHERE id = '$trip_id' AND user_id = '$user_id'";
            if ($conn->query($update_query)) {
                header("Location: view_trip.php");
                exit();
            } else {
                $error = "Error updating trip: " . $conn->error;
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
    <title>Edit Trip</title>
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
  <a class="navbar-brand" href="#">Travel Planner Application</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <span class="nav-link">Hi, <?php echo $username; ?> </span>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="view_trip.php">View Trips</a>
      </li>
       <li class="nav-item">
        <a class="nav-link" href="post_view.php">Past Posts</a>
      </li>
     
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Logout</a>
      </li>
    </ul>
  </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center">Edit Trip</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="form-group">
            <label for="trip_name">Trip Name:</label>
            <input type="text" class="form-control" id="trip_name" name="trip_name" value="<?php echo $trip['trip_name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="destination">Destination:</label>
            <input type="text" class="form-control" id="destination" name="destination" value="<?php echo $trip['destination']; ?>" required>
        </div>
        <div class="form-group">
            <label for="start_date">Start Date:</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $trip['start_date']; ?>" required>
        </div>
        <div class="form-group">
            <label for="end_date">End Date:</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $trip['end_date']; ?>" required>
        </div>
        <div class="form-group">
            <label for="accommodation">Accommodation:</label>
            <input type="text" class="form-control" id="accommodation" name="accommodation" value="<?php echo $trip['accommodation']; ?>">
        </div>
        <div class="form-group">
            <label for="transportation">Transportation:</label>
            <input type="text" class="form-control" id="transportation" name="transportation" value="<?php echo $trip['transportation']; ?>">
        </div>
        <div class="form-group">
            <label for="budget">Budget:</label>
            <input type="number" class="form-control" id="budget" name="budget" value="<?php echo $trip['budget']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Update Trip</button>
    </form>
</div>
</body>
</html>
