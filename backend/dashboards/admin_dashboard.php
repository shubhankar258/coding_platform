<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "admin") {
    header("Location: ../php_auth/login.php");    exit();
}
echo "<h2>Welcome, " . $_SESSION["user_name"] . " (Admin)</h2>";
echo '<a href="../php_auth/logout.php">Logout</a>';  // Fixed Quotes
?>
