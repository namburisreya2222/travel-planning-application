<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $password = $_POST['password'];

    // Ensure the password is filled and token is valid
    if (!empty($password) && !empty($token)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        
        $query = "SELECT * FROM password_resets WHERE token = '$token'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $reset = $result->fetch_assoc();
            $email = $reset['email'];
            
            // Update user's password
            $update_query = "UPDATE users SET password = '$password' WHERE email = '$email'";
            $conn->query($update_query);
            
            // Delete the token after reset
            $delete_query = "DELETE FROM password_resets WHERE token = '$token'";
            $conn->query($delete_query);

            $success = "Password has been reset successfully!.Please login again.";
        } else {
            $error = "Invalid token!";
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
    <title>Reset Password</title>
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

    <!-- Reset Password Form -->
<div class="container mt-5">
    <h2 class="text-center">Reset Password</h2>

    <!-- Ensure the token exists before displaying the form -->
    <?php if (isset($_GET['token']) && !empty($_GET['token'])): ?>
        <form method="POST" action="">
            <!-- Escape the token to prevent XSS -->
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
            <div class="form-group">
                <label for="password">New Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
        </form>

        <!-- Alerts display after form -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
        <?php elseif (isset($success)): ?>
            <div class="alert alert-success mt-3"><?php echo $success; ?></div>
        <?php endif; ?>

    <?php else: ?>
        <div class="alert alert-danger">Invalid or missing token!</div>
    <?php endif; ?>
</div>

</body>
</html>
