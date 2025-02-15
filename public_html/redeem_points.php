<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';
require 'phpqrcode/qrlib.php';
require 'fpdf.php'; // Include FPDF library


$user_id = $_SESSION['user_id'];

// Fetch user details
$user_query = "SELECT name FROM users WHERE id = '$user_id'";
$user_result = $conn->query($user_query);
$user = $user_result->fetch_assoc();
$username = $user['name'];

// Fetch total points for the user
$points_query = "SELECT SUM(points) as total_points FROM rewards WHERE user_id = '$user_id'";
$points_result = $conn->query($points_query);
$total_points = $points_result->fetch_assoc()['total_points'] ?? 0;

// Fetch available vouchers
$vouchers_query = "SELECT * FROM vouchers";
$vouchers_result = $conn->query($vouchers_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['voucher_id'])) {
    $voucher_id = $_POST['voucher_id'];
    $voucher_points_needed = $_POST['points_needed'];
    $voucher_value = $_POST['voucher_value'];

    // Fetch selected voucher details for PDF generation
    $selected_voucher_query = "SELECT * FROM vouchers WHERE id = '$voucher_id'";
    $selected_voucher_result = $conn->query($selected_voucher_query);
    $voucher = $selected_voucher_result->fetch_assoc(); // Now $voucher contains all details about the selected voucher

    if ($total_points >= $voucher_points_needed) {
        // Deduct points and add redeemed voucher
        $new_points = $total_points - $voucher_points_needed;
        $conn->query("UPDATE rewards SET points = '$new_points' WHERE user_id = '$user_id'");

        // Generate a unique voucher code
        $voucher_code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 10));
        $redeem_query = "INSERT INTO redeemed_vouchers (user_id, voucher_id, voucher_code) VALUES ('$user_id', '$voucher_id', '$voucher_code')";

        if ($conn->query($redeem_query)) {
            // Generate QR code image
            $qr_image_path = "qrcodes/$voucher_code.png";
            QRcode::png($voucher_code, $qr_image_path);

            // Create PDF with QR code
            class PDF extends FPDF {
                function Header() {
                    $this->SetFont('Arial', 'B', 14);
                    $this->Cell(0, 10, 'Your Redeemed Voucher', 0, 1, 'C');
                }
                function Footer() {
                    $this->SetY(-15);
                    $this->SetFont('Arial', 'I', 8);
                    $this->Cell(0, 10, 'Thank you for using our service!', 0, 0, 'C');
                }
            }

            $pdf = new PDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', '', 12);

            // Adding voucher details to the PDF
            $pdf->Cell(0, 10, "Voucher Name: " . $voucher['voucher_name'], 0, 1);
            $pdf->Cell(0, 10, "Voucher Code: $voucher_code", 0, 1);
            $pdf->Cell(0, 10, "Voucher Value: $$voucher_value", 0, 1);
            $pdf->Cell(0, 10, "Points Needed: " . $voucher['points_needed'], 0, 1);
            $pdf->Cell(0, 10, "Redeemed on: " . date("Y-m-d H:i:s"), 0, 1);
            $pdf->Ln(10);

            // Adding the voucher description with a multi-line cell to handle longer text
            $pdf->Cell(0, 10, "Voucher Description:", 0, 1);
            $pdf->MultiCell(0, 10, $voucher['description']);
            $pdf->Ln(10);

            // Adding the QR code image to the PDF
            $pdf->Image($qr_image_path, 10, $pdf->GetY(), 50, 50);

            // Saving the PDF file
            $pdf_file_path = "vouchers/$voucher_code.pdf";
            $pdf->Output('F', $pdf_file_path);

            // Provide link to download the PDF
            $success = "Voucher redeemed! <a href='$pdf_file_path' download>Click here to download your voucher</a>";
            $total_points -= $voucher_points_needed;
        } else {
            $error = "Error redeeming voucher: " . $conn->error;
        }
    } else {
        $error = "You don't have enough points to redeem this voucher.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redeem Points</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { background-color: #f7e9fa; }
        .navbar { background-color: #9b59b6; }
        .navbar .nav-link { color: #fff; }
        .container { background-color: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); margin-top: 30px; }
        .btn-primary { background-color: #9b59b6; border-color: #9b59b6; }
        .btn-primary:hover { background-color: #8e44ad; border-color: #8e44ad; }
        .card { margin: 10px 0; }
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
      <li class="nav-item"><span class="nav-link">Hi, <?php echo $username; ?></span></li>
      <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
      <li class="nav-item"><a class="nav-link" href="view_trip.php">View Trips</a></li>
      <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
    </ul>
  </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center">Redeem Your Points</h2>
    <?php if (isset($error)): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
    <?php if (isset($success)): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
    
    <p><strong>Total Points:</strong> <?php echo $total_points; ?></p>
    
    <h4>Available Vouchers</h4>
    <div class="row">
        <?php while ($voucher = $vouchers_result->fetch_assoc()): ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?php echo $voucher['voucher_name']; ?></h5>
                        <p class="card-text"><?php echo $voucher['description']; ?></p>
                        <p><strong>Value:</strong> $<?php echo $voucher['voucher_value']; ?></p>
                        <p><strong>Points Needed:</strong> <?php echo $voucher['points_needed']; ?></p>
                        <form method="POST" action="">
                            <input type="hidden" name="voucher_id" value="<?php echo $voucher['id']; ?>">
                            <input type="hidden" name="points_needed" value="<?php echo $voucher['points_needed']; ?>">
                            <input type="hidden" name="voucher_value" value="<?php echo $voucher['voucher_value']; ?>">
                            <button type="submit" class="btn btn-primary" <?php echo $total_points < $voucher['points_needed'] ? 'disabled' : ''; ?>>Redeem</button>
                        </form>
                    </div>
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
