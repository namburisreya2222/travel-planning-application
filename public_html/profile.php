<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';
// Set timezone to Central Time (or your specific timezone)
date_default_timezone_set('America/Chicago');
$user_id = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = $conn->query($query);
$user = $result->fetch_assoc();

// Calculate points based on the number of experiences in `post_trip_experience` table, counting only new ones
$experience_query = "SELECT COUNT(*) AS new_experience_count FROM post_trip_experience WHERE user_id = '$user_id' AND points_counted = 0";
$experience_result = $conn->query($experience_query);

if ($experience_result) {
    $new_experience_count = $experience_result->fetch_assoc()['new_experience_count'];
    $new_points = $new_experience_count * 10;

    if ($new_points > 0) {
        // Update points in the rewards table
        $points_query = "SELECT points FROM rewards WHERE user_id = '$user_id'";
        $points_result = $conn->query($points_query);

        if ($points_result && $points_result->num_rows > 0) {
            $current_points = $points_result->fetch_assoc()['points'];
            $total_points = $current_points + $new_points;
            $update_points_query = "UPDATE rewards SET points = '$total_points' WHERE user_id = '$user_id'";
            $conn->query($update_points_query);
        } else {
            $insert_points_query = "INSERT INTO rewards (user_id, points) VALUES ('$user_id', '$new_points')";
            $conn->query($insert_points_query);
        }

        // Mark experiences as counted
        $update_experience_query = "UPDATE post_trip_experience SET points_counted = 1 WHERE user_id = '$user_id' AND points_counted = 0";
        $conn->query($update_experience_query);
    }
}

// Fetch the updated points for display
$updated_points_query = "SELECT points FROM rewards WHERE user_id = '$user_id'";
$updated_points_result = $conn->query($updated_points_query);
$points = $updated_points_result->num_rows > 0 ? $updated_points_result->fetch_assoc()['points'] : 0;

// Fetch the latest redeemed voucher for each voucher type
$redeemed_query = "
    SELECT v.voucher_name, rv.voucher_code, v.voucher_value, rv.redeemed_at
    FROM redeemed_vouchers rv
    JOIN vouchers v ON rv.voucher_id = v.id
    WHERE rv.user_id = '$user_id'
    AND rv.redeemed_at = (
        SELECT MAX(rv_inner.redeemed_at)
        FROM redeemed_vouchers rv_inner
        WHERE rv_inner.user_id = '$user_id' AND rv_inner.voucher_id = rv.voucher_id
    )
    ORDER BY rv.redeemed_at DESC";
$redeemed_result = $conn->query($redeemed_query);

if (!$redeemed_result) {
    die("Error executing redeemed vouchers query: " . $conn->error);
}

// Fetch remaining points (total in rewards table after reset or redemption)
$remaining_points_query = "SELECT points FROM rewards WHERE user_id = '$user_id'";
$remaining_points_result = $conn->query($remaining_points_query);
$remaining_points = $remaining_points_result->num_rows > 0 ? $remaining_points_result->fetch_assoc()['points'] : 0;

// Handle profile update form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if (!empty($name) && !empty($email) && !empty($phone)) {
        $update_query = "UPDATE users SET name = '$name', email = '$email', phone = '$phone' WHERE id = '$user_id'";
        if ($conn->query($update_query)) {
            $success = "Profile updated successfully!";
            $user = $conn->query($query)->fetch_assoc();
        } else {
            $error = "Error updating profile: " . $conn->error;
        }
    } else {
        $error = "Please fill in all required fields!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { background-color: #f7e9fa; }
        .navbar { background-color: #9b59b6; }
        .navbar .nav-link { color: #fff; }
        .navbar .nav-link:hover { color: #d1c4e9; }
        .btn-primary { background-color: #9b59b6; border-color: #9b59b6; }
        .btn-primary:hover { background-color: #8e44ad; border-color: #8e44ad; }
        h2 { color: #9b59b6; }
        .container { background-color: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); }
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
        <span class="nav-link">Hi, <?php echo $user['name']; ?></span>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="profile.php">Profile</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="view_trip.php">View Trips</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Logout</a>
      </li>
    </ul>
  </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center">Your Profile</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php elseif (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <div class="mb-4">
        <h4>Account Details</h4>
        <p><strong>Name:</strong> <?php echo $user['name']; ?></p>
        <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
        <p><strong>Phone:</strong> <?php echo $user['phone']; ?></p>
       
    </div>

    <h4>Redeemed Vouchers</h4>
     <p><strong>Remaining Points:</strong> <?php echo $remaining_points; ?></p>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Voucher Name</th>
                <th>Voucher Code</th>
                <th>Voucher Value</th>
                <th>Redeemed At</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($redeemed_result->num_rows > 0): ?>
                <?php while ($row = $redeemed_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['voucher_name']; ?></td>
                        <td><?php echo $row['voucher_code']; ?></td>
                        <td><?php echo '$' . $row['voucher_value']; ?></td>
                        <td><?php echo $row['redeemed_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No redeemed vouchers found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <hr>

    <h4>Edit Profile</h4>
    <form method="POST" action="profile.php">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $user['phone']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Update Profile</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
