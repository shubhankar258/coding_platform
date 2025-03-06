<?php
session_start();
include '../config.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "student") {
    header("Location: ../php_auth/login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$user_name = $_SESSION["user_name"];

// Fetch student scores
$sql = "SELECT subject, score FROM student_scores WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$scores = [];
while ($row = $result->fetch_assoc()) {
    $scores[] = $row;
}
$stmt->close();
$conn->close();
?>

<h2>Welcome, <?php echo $user_name; ?> (Student)</h2>
<a href="profile.php">Edit Profile</a> | 
<a href="change_password.php">Change Password</a> | 
<a href="../auth/logout.php">Logout</a>

<h3>Your Scores:</h3>
<?php if (count($scores) > 0): ?>
    <table border="1">
        <tr>
            <th>Subject</th>
            <th>Score</th>
        </tr>
        <?php foreach ($scores as $score): ?>
            <tr>
                <td><?php echo $score["subject"]; ?></td>
                <td><?php echo $score["score"]; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No scores available.</p>
<?php endif; ?>
