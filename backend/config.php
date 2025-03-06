<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 86400, // Keep session active for 1 day
        'read_and_close' => false
    ]);
}
$servername = "localhost";
$username = "root"; // Change if needed
$password = "";
$dbname = "coding_platform";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>