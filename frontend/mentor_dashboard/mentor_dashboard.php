<?php
session_start();
include '../../backend/config.php';

// Redirect if not logged in or not a mentor
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "mentor") {
    header("Location: ../../backend/php_auth/login.php");
    exit();
}

$user_name = $_SESSION["user_name"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentor Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">
        <nav class="sidebar">
            <h2>Welcome, <?php echo $user_name; ?></h2>
            <ul>
                <li><a href="#" onclick="showSection('upload_notes')">Upload Notes</a></li>
                <li><a href="#" onclick="showSection('upload_questions')">Upload Programming Questions</a></li>
                <li><a href="../../backend/php_auth/logout.php">Logout</a></li>
            </ul>
        </nav>

        <main class="content">
            <section id="upload_notes" class="section active">
                <h1>Upload Notes</h1>
                <form id="uploadForm" enctype="multipart/form-data">
                    <input type="text" name="title" id="title" placeholder="Note Title" required>
                    <select name="subject" id="subject" required>
                        <option value="DSA">DSA</option>
                        <option value="Java">Java</option>
                        <option value="Web Development">Web Development</option>
                    </select>
                    <input type="file" name="note_file" id="note_file" accept=".pdf,.doc,.docx" required>
                    <button type="submit">Upload</button>
                </form>

                <!-- Success Popup -->
                <div id="successPopup" class="popup" style="display: none;">
                    <p></p>
                    <span class="close-btn" onclick="closePopup()">✖</span>
                </div>
            </section>
        </main>
    </div>

    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.section').forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(sectionId).classList.add('active');
        }
    </script>
    <script src="main.js"></script>

</body>
</html>
