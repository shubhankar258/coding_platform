<?php
session_start();
include '../config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: ../php_auth/login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$message = "";

// Handle password update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = trim($_POST["current_password"]);
    $new_password = trim($_POST["new_password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    // Check if new password and confirm password match
    if ($new_password !== $confirm_password) {
        $message = "New password and confirm password do not match!";
    } else {
        // Fetch current password from database
        $sql = "SELECT password FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        $stmt->close();

        // Verify current password
        if (password_verify($current_password, $hashed_password)) {
            // Hash new password and update it
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE users SET password = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("si", $new_hashed_password, $user_id);

            if ($update_stmt->execute()) {
                $message = "Password updated successfully!";
            } else {
                $message = "Error updating password!";
            }
            $update_stmt->close();
        } else {
            $message = "Incorrect current password!";
        }
    }
}
$conn->close();
?>

<h2>Change Password</h2>
<?php if ($message): ?>
    <p style="color: red;"><?php echo $message; ?></p>
<?php endif; ?>
<form method="post">
    <label>Current Password:</label>
    <input type="password" name="current_password" required>
    <br>
    <label>New Password:</label>
    <input type="password" name="new_password" required>
    <br>
    <label>Confirm Password:</label>
    <input type="password" name="confirm_password" required>
    <br>
    <button type="submit">Update Password</button>
</form>
<a href="profile.php">Back to Profile</a>
