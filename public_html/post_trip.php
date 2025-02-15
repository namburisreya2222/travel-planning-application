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

    if (!$result || $result->num_rows === 0) {
        echo "No trip found for this ID or access denied!";
        exit();
    }

    $trip = $result->fetch_assoc();

    // Check if an experience has already been posted for this trip
    $experience_query = "SELECT id FROM post_trip_experience WHERE trip_id = '$trip_id' AND user_id = '$user_id'";
    $experience_result = $conn->query($experience_query);

    if (!$experience_result) {
        echo "Error executing experience query: " . $conn->error;
        exit();
    }

    if ($experience_result->num_rows > 0) {
        echo "<script>alert('You have already posted an experience for this trip. Please go to Past Posts to edit it.'); window.location.href='post_view.php';</script>";
        exit();
    }
} else {
    echo "Invalid trip ID!";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $experience_text = $_POST['experience_text'];
    $worth_visiting = isset($_POST['worth_visiting']) ? 1 : 0;

    // Handle file upload
    $photo = $_FILES['photo']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($photo);

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
        // Insert the experience into post_trip_experience table
        $insert_query = "INSERT INTO post_trip_experience (user_id, trip_id, destination, experience_text, worth_visiting, photo) 
                         VALUES ('$user_id', '$trip_id', '{$trip['destination']}', '$experience_text', '$worth_visiting', '$photo')";
        if ($conn->query($insert_query)) {
            // Add points if the experience was successfully posted
            $points_query = "UPDATE users SET points = points + 10 WHERE id = '$user_id'";
            $conn->query($points_query);

            header('Location: post_view.php');
            exit();
        } else {
            $error = "Error: " . $conn->error;
        }
    } else {
        $error = "Error uploading photo.";
    }
}

// Fetch user's name for the navbar
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
    <title>Post Trip Experience</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { background-color: #f7e9fa; }
        .navbar { background-color: #9b59b6; }
        .navbar .nav-link { color: #fff; }
        .container { background-color: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); margin-top: 30px; }
        .btn-primary { background-color: #9b59b6; border-color: #9b59b6; }
        .btn-primary:hover { background-color: #8e44ad; border-color: #8e44ad; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <a class="navbar-brand" href="#">Travel Planner Application</a>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item"><span class="nav-link">Hi, <?php echo $username; ?></span></li>
      <li class="nav-item"><a class="nav-link" href="view_trip.php">View Trips</a></li>
      <li class="nav-item"><a class="nav-link" href="post_view.php">Past Posts</a></li>
      <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
    </ul>
  </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center">Share Your Experience for <?php echo $trip['destination']; ?></h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST" action="post_trip.php?id=<?php echo $trip_id; ?>" enctype="multipart/form-data">
        <div class="form-group">
            <label for="experience_text">Share your experience:</label>
            <textarea class="form-control" id="experience_text" name="experience_text" rows="4" required></textarea>
        </div>
        <div class="form-group">
            <label for="worth_visiting">Was this destination worth visiting?</label>
            <input type="checkbox" id="worth_visiting" name="worth_visiting"> Yes
        </div>
        <div class="form-group">
            <label for="photo">Select a photo:</label>
            <input type="file" class="form-control" id="photo" name="photo" accept="image/jpeg, image/png" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Submit Experience</button>
    </form>
</div>

</body>
</html>
