<?php
session_start();
include '../config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: ../php_auth/login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$sql = "SELECT name, email, role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $role);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<h2>Profile</h2>
<p><strong>Name:</strong> <?php echo $name; ?></p>
<p><strong>Email:</strong> <?php echo $email; ?></p>
<p><strong>Role:</strong> <?php echo ucfirst($role); ?></p>

<a href="edit_profile.php">Edit Profile</a>
<a href="../php_auth/logout.php">Logout</a>
