<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$voucher_id = $_POST['voucher_id'];
$points_needed = 100;

// Fetch total points
$query = "SELECT SUM(points) as total_points FROM rewards WHERE user_id = '$user_id'";
$result = $conn->query($query);

// Debugging: Check total points fetched from rewards table
if (!$result) {
    echo "Error fetching points: " . $conn->error;
    exit();
}

$total_points = $result->fetch_assoc()['total_points'] ?? 0;

// Debugging: Display points to verify
echo "Total Points: $total_points<br>";
echo "Points Needed: $points_needed<br>";

// Check if user has enough points
if ($total_points >= $points_needed) {
    // Check voucher availability and redeem it
    $redeem_query = "UPDATE vouchers SET user_id = '$user_id', redeemed_at = NOW() WHERE id = '$voucher_id' AND (user_id = 0 OR user_id = '$user_id')";
    if ($conn->query($redeem_query) && $conn->affected_rows > 0) {
        
        // Deduct points if needed
        $deduct_query = "UPDATE rewards SET points = points - $points_needed WHERE user_id = '$user_id' AND points >= $points_needed LIMIT 1";
        if (!$conn->query($deduct_query)) {
            echo "Error deducting points: " . $conn->error;
            exit();
        }

        // Generate and serve a text file with the voucher code
        $voucher_code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 10));
        $file_content = "Voucher Code: $voucher_code\nDescription: Discount Voucher\nValue: $20\nRedeemed on: " . date("Y-m-d H:i:s");

        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="voucher.txt"');
        echo $file_content;
        exit();
    } else {
        echo "Error redeeming voucher or voucher already redeemed.";
    }
} else {
    echo "Not enough points to redeem this voucher.";
}
?>
