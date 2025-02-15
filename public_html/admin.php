<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'db.php';

// Fetch all users
$user_query = "SELECT * FROM users WHERE role = 'user'";
$user_result = $conn->query($user_query);

// Fetch all trips
$trip_query = "SELECT * FROM trips";
$trip_result = $conn->query($trip_query);

// Handle delete requests for users
if (isset($_GET['delete_user'])) {
    $user_id = $_GET['delete_user'];
    $delete_user_query = "DELETE FROM users WHERE id = '$user_id'";
    $conn->query($delete_user_query);
    header('Location: admin_dashboard.php');
}

// Handle delete requests for trips
if (isset($_GET['delete_trip'])) {
    $trip_id = $_GET['delete_trip'];
    $delete_trip_query = "DELETE FROM trips WHERE id = '$trip_id'";
    $conn->query($delete_trip_query);
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
    <h2 class="text-center">Admin Dashboard</h2>
    
    <h3>Manage Users</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $user_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $user['name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['phone']; ?></td>
                <td><?php echo $user['created_at']; ?></td>
                <td>
                    <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="admin_dashboard.php?delete_user=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h3>Manage Trips</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Trip Name</th>
                <th>Destination</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Budget</th>
                <th>User ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($trip = $trip_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $trip['trip_name']; ?></td>
                <td><?php echo $trip['destination']; ?></td>
                <td><?php echo $trip['start_date']; ?></td>
                <td><?php echo $trip['end_date']; ?></td>
                <td><?php echo $trip['budget']; ?></td>
                <td><?php echo $trip['user_id']; ?></td>
                <td>
                    <a href="edit_trip_admin.php?id=<?php echo $trip['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="admin_dashboard.php?delete_trip=<?php echo $trip['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this trip?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</div>
</body>
</html>
