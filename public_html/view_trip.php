<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';

// Get the user's name and session ID
$user_id = $_SESSION['user_id'];
$user_query = "SELECT name FROM users WHERE id = '$user_id'";
$user_result = $conn->query($user_query);
$user = $user_result->fetch_assoc();
$username = $user['name'];

// Fetch all trips for the logged-in user
$query = "SELECT * FROM trips WHERE user_id = '$user_id'";
$result = $conn->query($query);

// Handle delete request
if (isset($_GET['delete'])) {
    $trip_id = $_GET['delete'];
    $delete_query = "DELETE FROM trips WHERE id = '$trip_id' AND user_id = '$user_id'";
    if ($conn->query($delete_query)) {
        header("Location: view_trip.php");
    } else {
        echo "Error deleting trip: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Itinerary</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f7e9fa; /* Light lilac background */
        }
        h2 {
            color: #9b59b6; /* Dark lilac heading */
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
        .list-group-item {
            background-color: #f7e9fa;
            border-color: #9b59b6;
        }
        .list-group-item:hover {
            background-color: #e1bee7; /* Slightly lighter lilac on hover */
        }
        .btn-primary {
            background-color: #9b59b6;
            border-color: #9b59b6;
        }
        .btn-primary:hover {
            background-color: #8e44ad;
            border-color: #8e44ad;
        }
        .btn-warning {
            background-color: #f39c12;
            border-color: #f39c12;
        }
        .btn-danger {
            background-color: #e74c3c;
            border-color: #e74c3c;
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
        <a class="nav-link" href="profile.php">Profile</a>
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
    <h2 class="text-center">Your Trips</h2>
    <div class="list-group">
        <?php 
        $today = date("Y-m-d");
        while ($row = $result->fetch_assoc()): 
            $is_completed = ($today > $row['end_date']); // Check if trip is completed
            
            // Check if the user has already posted an experience for this trip
            $post_check_query = "SELECT id FROM post_trip_experience WHERE user_id = '$user_id' AND destination = '{$row['destination']}'";
            $post_check_result = $conn->query($post_check_query);
            $has_posted = $post_check_result->fetch_assoc(); // Fetch a single row

        ?>
            <div class="list-group-item list-group-item-action">
                <h5 class="mb-1"><?php echo $row['trip_name']; ?> 
                    <?php if ($is_completed): ?>
                        <span class="badge badge-secondary">Completed</span>
                    <?php endif; ?>
                </h5>
                <p class="mb-1">Destination: <?php echo $row['destination']; ?></p>
                <small>From: <?php echo $row['start_date']; ?> To: <?php echo $row['end_date']; ?></small>
                <div class="mt-2">
                    <a href="view_trip_details.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">View</a> <!-- View Button -->
                    <?php if ($is_completed && !$has_posted): ?>
                        <a href="post_trip.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Post Your Experience</a> <!-- Post Experience Button -->
                    <?php elseif ($is_completed && $has_posted): ?>
                        <button class="btn btn-success btn-sm" disabled>Experience Posted</button> <!-- Disabled Post Button -->
                    <?php endif; ?>
                    <?php if (!$is_completed): ?>
                        <a href="edit_trip.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <?php endif; ?>
                    <a href="view_trip.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this trip?')">Delete</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- JavaScript dependencies for Bootstrap navbar toggle functionality -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
