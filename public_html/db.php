<?php
$servername = "51.81.160.154";
$username = "jxv3709_user";
$password = "-@vuRE5TMEHf";
$dbname = "jxv3709_se2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
