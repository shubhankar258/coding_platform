<?php
session_start();
include '../../backend/config.php';

// Redirect to login if not logged in
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "student") {
    header("Location: ../../backend/php_auth/login.php");
    exit();
}

$user_name = $_SESSION["user_name"];
$user_email = $_SESSION["user_email"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="main.js"></script>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <h2>Welcome, <?php echo $user_name; ?></h2>
            <p><?php echo $user_email; ?></p>
            <ul>
                <li><a href="#" onclick="showSection('notes')">View Notes</a></li>
                <li><a href="#" onclick="showSection('scores')">Your Scores</a></li>
                <li><a href="profile.php">Edit Profile</a></li>
                <li><a href="../../backend/php_auth/logout.php">Logout</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="content">
        <section id="notes" class="section active">
            <h1>View Notes by Subject</h1>
            <div id="subjects-container">
                <p>Loading subjects...</p>
            </div>
            <div id="notes-container" style="display: none;">
                <h2 id="subject-title"></h2>
                <button onclick="goBack()">⬅ Back to Subjects</button>
                <div id="notes-list"></div>
            </div>
        </section>



            <section id="scores" class="section">
                <h1>Your Scores</h1>
                <div id="scores-container">
                    <p>Loading scores...</p>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
