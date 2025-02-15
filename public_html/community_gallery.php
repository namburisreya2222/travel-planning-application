<?php
session_start();
include 'db.php';

// Fetch photos, destination, experience text, and worth_visiting status from the database
$query = "SELECT photo, destination, experience_text, worth_visiting 
          FROM post_trip_experience 
          ORDER BY upload_date DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Gallery</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f7e9fa; /* Light lilac background */
        }
        h2 {
            color: #9b59b6; /* Dark lilac heading */
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .card-img-top {
            border-radius: 10px 10px 0 0;
            height: 200px;
            object-fit: cover;
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
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #9b59b6;">
        <a class="navbar-brand" href="index.php">Travel Planner Application</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="signup.php">Sign Up</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="community_gallery.php">Community Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="feedback.php">Feedback</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact Us</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center">Community Gallery</h2>
        <div class="row">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="uploads/<?php echo $row['photo']; ?>" class="card-img-top" alt="Travel Photo">
                        <div class="card-body">
                            <h5 class="card-title" style="color: #9b59b6;"><?php echo $row['destination']; ?></h5>
                            <p class="card-text"><?php echo $row['experience_text']; ?></p>
                            <p class="card-text">
                                <strong>Worth Visiting:</strong> 
                                <?php echo $row['worth_visiting'] ? 'Yes' : 'No'; ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="post_trip.php" class="btn btn-primary btn-block">Share Your Travel Experience</a>
        <?php else: ?>
            <p class="text-center"><a href="login.php">Log in</a> to share your own travel experiences.</p>
        <?php endif; ?>
    </div>
</body>
</html>
