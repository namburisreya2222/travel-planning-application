<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';

$post_id = isset($_GET['id']) ? $_GET['id'] : null;
$user_id = $_SESSION['user_id'];

// Fetch the post to be edited
if ($post_id) {
    $query = "SELECT * FROM post_trip_experience WHERE id = '$post_id' AND user_id = '$user_id'";
    $result = $conn->query($query);
    $post = $result->fetch_assoc();
    
    if (!$post) {
        echo "Post not found or access denied!";
        exit();
    }
} else {
    echo "Invalid post ID!";
    exit();
}

// Handle form submission for editing
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $experience_text = $_POST['experience_text'];
    $worth_visiting = isset($_POST['worth_visiting']) ? 1 : 0;
    
    // Handle file upload if a new image is uploaded
    $photo = $_FILES['photo']['name'];
    if (!empty($photo)) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($photo);
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            // If image upload is successful, update the post with the new image
            $image_update = ", photo = '$photo'";
        } else {
            $error = "Error uploading new photo.";
        }
    } else {
        // Keep the old image if no new image is uploaded
        $image_update = "";
    }
    
    // Update the post with the new details and possibly the new image
    $update_query = "UPDATE post_trip_experience 
                     SET experience_text = '$experience_text', worth_visiting = '$worth_visiting' $image_update 
                     WHERE id = '$post_id' AND user_id = '$user_id'";
    
    if ($conn->query($update_query)) {
        header('Location: post_view.php');
        exit();
    } else {
        $error = "Error: " . $conn->error;
    }
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
    <title>Edit Trip Experience</title>
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
  <a class="navbar-brand" href="#">Travel Planner Application</a>
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
    <h2 class="text-center">Edit Your Experience for <?php echo $post['destination']; ?></h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST" action="edit_post.php?id=<?php echo $post_id; ?>" enctype="multipart/form-data">
        <div class="form-group">
            <label for="experience_text">Edit your experience:</label>
            <textarea class="form-control" id="experience_text" name="experience_text" rows="4" required><?php echo $post['experience_text']; ?></textarea>
        </div>
        <div class="form-group">
            <label for="worth_visiting">Was this destination worth visiting?</label>
            <input type="checkbox" id="worth_visiting" name="worth_visiting" <?php echo $post['worth_visiting'] ? 'checked' : ''; ?>> Yes
        </div>
        <div class="form-group">
            <label for="photo">Change Photo (optional):</label>
            <input type="file" class="form-control" id="photo" name="photo" accept="image/jpeg, image/png">
            <small>Leave empty to keep the current photo</small>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Save Changes</button>
    </form>
</div>

</body>
</html>
