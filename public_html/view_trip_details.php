<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';

// Get the trip ID from the query parameter
$trip_id = isset($_GET['id']) ? $_GET['id'] : null;
$user_id = $_SESSION['user_id'];

if ($trip_id) {
    // Fetch trip details for the logged-in user
    $query = "SELECT * FROM trips WHERE id = '$trip_id' AND user_id = '$user_id'";
    $result = $conn->query($query);
    $trip = $result->fetch_assoc();
    
    if (!$trip) {
        echo "Trip not found or access denied!";
        exit();
    }
} else {
    echo "Invalid trip ID!";
    exit();
}

// Get the user's name
$user_query = "SELECT name FROM users WHERE id = '$user_id'";
$user_result = $conn->query($user_query);
$user = $user_result->fetch_assoc();
$username = $user['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trip Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f7e9fa;
        }
        .navbar {
            background-color: #9b59b6;
        }
        .navbar .nav-link {
            color: #fff;
        }
        .navbar .nav-link:hover {
            color: #d1c4e9;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
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

<div class="container">
    <h2 class="text-center">Trip Details</h2>
    <ul class="list-group">
        <li class="list-group-item"><strong>Trip Name:</strong> <?php echo $trip['trip_name']; ?></li>
        <li class="list-group-item"><strong>Destination:</strong> <?php echo $trip['destination']; ?></li>
        <li class="list-group-item"><strong>Start Date:</strong> <?php echo $trip['start_date']; ?></li>
        <li class="list-group-item"><strong>End Date:</strong> <?php echo $trip['end_date']; ?></li>
        <li class="list-group-item"><strong>Accommodation:</strong> <?php echo $trip['accommodation']; ?></li>
        <li class="list-group-item"><strong>Transportation:</strong> <?php echo $trip['transportation']; ?></li>
        <li class="list-group-item"><strong>Budget:</strong> $<?php echo $trip['budget']; ?></li>
    </ul>
</div>

<!-- Add JavaScript dependencies for Bootstrap's navbar toggle functionality -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
