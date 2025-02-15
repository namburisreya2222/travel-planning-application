<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];

// Fetch all experiences posted by the user
$query = "SELECT * FROM post_trip_experience WHERE user_id = '$user_id'";
$result = $conn->query($query);

// Get the user's name
$user_query = "SELECT name FROM users WHERE id = '$user_id'";
$user_result = $conn->query($user_query);
$user = $user_result->fetch_assoc();
$username = $user['name'];

// Handle delete request
if (isset($_GET['delete'])) {
    $post_id = $_GET['delete'];
    $delete_query = "DELETE FROM post_trip_experience WHERE id = '$post_id' AND user_id = '$user_id'";
    if ($conn->query($delete_query)) {
        header("Location: post_view.php");
        exit();
    } else {
        echo "Error deleting experience: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Posted Experiences</title>
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
        .btn-primary, .btn-danger, .btn-warning {
            margin-top: 10px;
        }
        .btn-primary {
            background-color: #9b59b6;
            border-color: #9b59b6;
        }
        .btn-primary:hover {
            background-color: #8e44ad;
            border-color: #8e44ad;
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

<div class="container mt-5">
    <h2 class="text-center">Your Posted Experiences</h2>
    <div class="list-group">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="list-group-item list-group-item-action">
                <h5 class="mb-1">Destination: <?php echo $row['destination']; ?></h5>
                <p class="mb-1">Experience: <?php echo $row['experience_text']; ?></p>
                <p><strong>Worth Visiting: </strong><?php echo $row['worth_visiting'] ? 'Yes' : 'No'; ?></p>
                <p><strong>Photo:</strong><br><img src="uploads/<?php echo $row['photo']; ?>" alt="Experience Photo" class="img-fluid" width="200"></p>
                
                <div class="mt-2">
                    <a href="edit_post.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="post_view.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
