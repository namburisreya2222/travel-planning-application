<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (!empty($name) && !empty($email) && !empty($phone) && !empty($_POST['password'])) {
        $query = "INSERT INTO users (name, email, phone, password, role) VALUES ('$name', '$email', '$phone', '$password', 'user')";
        if ($conn->query($query)) {
            header('Location: login.php');
            exit();
        } else {
            $error = "Error: " . $conn->error;
        }
    } else {
        $error = "Please fill in all fields!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Travel Planner</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f7e9fa; /* Light lilac background */
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #9b59b6;
            border-color: #9b59b6;
        }
        .btn-primary:hover {
            background-color: #8e44ad;
            border-color: #8e44ad;
        }
        .form-container {
            background-color: #f5e6f8;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .image-container {
            background-image: url('signup.webp');
            background-size: cover;
            background-position: center;
            border-radius: 10px 0 0 10px;
            min-height: 100%;
        }
    </style>
</head>
<body>
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

<div class="container mt-5">
    <div class="row">
        <!-- Image Column -->
        <div class="col-md-6 image-container"></div>
        <!-- Form Column -->
        <div class="col-md-6 d-flex align-items-center">
            <div class="form-container p-4 w-100">
                <h2 class="text-center" style="color: #9b59b6;">Sign Up</h2>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form id="signupForm" method="POST" action="">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
                    <p class="mt-2 text-center">Already have an account? <a href="login.php">Login</a></p>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// JavaScript validation for the signup form
document.getElementById('signupForm').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    const password = document.getElementById('password').value;

    // Regex for email validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    // Regex for phone validation (digits only)
    const phonePattern = /^\d{10,15}$/;
    // Password validation (at least 8 characters, letters and numbers)
    const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;

    // Basic validation
    if (name.trim() === '' || email.trim() === '' || phone.trim() === '' || password.trim() === '') {
        e.preventDefault();
        alert("All fields are required.");
        return;
    }

    // Email format validation
    if (!emailPattern.test(email)) {
        e.preventDefault();
        alert("Please enter a valid email address.");
        return;
    }

    // Phone format validation
    if (!phonePattern.test(phone)) {
        e.preventDefault();
        alert("Please enter a valid phone number with only digits (10-15 digits).");
        return;
    }

    // Password strength validation
    if (!passwordPattern.test(password)) {
        e.preventDefault();
        alert("Password must be at least 8 characters long and contain both letters and numbers.");
    }
});
</script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
