<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $rating = $_POST['rating'];
    $feedback_message = $_POST['feedback_message'];

    if (!empty($email) && !empty($rating) && !empty($feedback_message)) {
        $query = "INSERT INTO feedback (user_email, rating, feedback_message) VALUES ('$email', '$rating', '$feedback_message')";
        if ($conn->query($query)) {
            $success = "Thank you for your feedback!";
        } else {
            $error = "Error submitting feedback: " . $conn->error;
        }
    } else {
        $error = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - Travel Planner</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f7e9fa; /* Light lilac background */
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #9b59b6;">
        <a class="navbar-brand" href="index.php">Travel Planner Application</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
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

    <!-- Feedback Form -->
    <div class="container mt-5">
        <h2 class="text-center">We Value Your Feedback</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php elseif (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <form id="feedbackForm" method="POST" action="">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="rating">Rating (1-5):</label>
                <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" required>
            </div>
            <div class="form-group">
                <label for="feedback_message">Feedback:</label>
                <textarea class="form-control" id="feedback_message" name="feedback_message" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Submit Feedback</button>
        </form>
    </div>

    <script>
    // JavaScript validation
    document.getElementById('feedbackForm').addEventListener('submit', function(e) {
        const email = document.getElementById('email').value;
        const rating = document.getElementById('rating').value;
        const feedback_message = document.getElementById('feedback_message').value;

        if (email.trim() === '' || rating === '' || feedback_message.trim() === '') {
            e.preventDefault();
            alert("All fields are required.");
        }
    });
    </script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
