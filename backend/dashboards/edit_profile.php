<?php
session_start();
include '../config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: ../php_auth/login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$message = "";

// Fetch current user details
$sql = "SELECT name, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email);
$stmt->fetch();
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = trim($_POST["name"]);
    $new_email = trim($_POST["email"]);

    $update_sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssi", $new_name, $new_email, $user_id);

    if ($update_stmt->execute()) {
        $_SESSION["user_name"] = $new_name; // Update session
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile!";
    }
    $update_stmt->close();
}
$conn->close();
?>

<h2>Edit Profile</h2>
<?php if ($message): ?>
    <p style="color: green;"><?php echo $message; ?></p>
<?php endif; ?>
<form method="post">
    <label>Name:</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
    <br>
    <label>Email:</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
    <br>
    <button type="submit">Update</button>
</form>
<a href="profile.php">Back to Profile</a>
