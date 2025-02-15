<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'db.php';

$trip_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($trip_id) {
    // Fetch trip data
    $query = "SELECT * FROM trips WHERE id = '$trip_id'";
    $result = $conn->query($query);
    $trip = $result->fetch_assoc();
}

// Handle form submission for updating trip
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trip_name = $_POST['trip_name'];
    $destination = $_POST['destination'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $budget = $_POST['budget'];

    $update_query = "UPDATE trips SET trip_name = '$trip_name', destination = '$destination', start_date = '$start_date', end_date = '$end_date', budget = '$budget' WHERE id = '$trip_id'";
    $conn->query($update_query);

    header('Location: admin_dashboard.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f7e9fa; /* Light lilac background */
        }
        h2, h3 {
            color: #9b59b6; /* Dark lilac headings */
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
        .btn-danger {
            background-color: #e74c3c;
            border-color: #e74c3c;
        }
        .btn-warning {
            background-color: #f39c12;
            border-color: #f39c12;
        }
    </style>
</head>
<body>
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
        <a class="nav-link" href="logout.php">Logout</a>
      </li>
    </ul>
  </div>
</nav>
<div class="container mt-5">
    <h2>Edit Trip</h2>
    <form method="POST">
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
            <label for="budget">Budget:</label>
            <input type="number" class="form-control" id="budget" name="budget" value="<?php echo $trip['budget']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
</body>
</html>
