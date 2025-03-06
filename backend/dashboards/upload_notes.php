<?php
session_start();
header('Content-Type: application/json'); // ✅ Ensure JSON response
include '../config.php';

// Ensure user is a mentor
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "mentor") {
    echo json_encode(["error" => "Unauthorized access!"]);
    exit();
}

// Debugging: Check if POST data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    file_put_contents("debug_log.txt", "POST Data: " . json_encode($_POST) . PHP_EOL, FILE_APPEND);
    file_put_contents("debug_log.txt", "FILES Data: " . json_encode($_FILES) . PHP_EOL, FILE_APPEND);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"] ?? "";
    $subject = $_POST["subject"] ?? "";
    $mentor_id = $_SESSION["user_id"];

    // Check if file exists
    if (!isset($_FILES["note_file"]) || $_FILES["note_file"]["error"] != 0) {
        echo json_encode(["error" => "No file uploaded or file upload error!"]);
        exit();
    }

    // File upload settings
    $target_dir = "../../uploads/notes/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = basename($_FILES["note_file"]["name"]);
    $target_file = $target_dir . $file_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Allow only PDF and DOC files
    $allowed_types = ["pdf", "doc", "docx"];
    if (!in_array($file_type, $allowed_types)) {
        echo json_encode(["error" => "Only PDF, DOC, and DOCX files are allowed!"]);
        exit();
    }

    // Move the uploaded file
    if (move_uploaded_file($_FILES["note_file"]["tmp_name"], $target_file)) {
        // Debugging: Log successful file move
        file_put_contents("debug_log.txt", "File moved successfully to: " . $target_file . PHP_EOL, FILE_APPEND);

        // Save file info to database
        $sql = "INSERT INTO notes (title, subject, file_path, uploaded_by) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            echo json_encode(["error" => "SQL Prepare Error: " . $conn->error]);
            exit();
        }

        $stmt->bind_param("sssi", $title, $subject, $target_file, $mentor_id);

        if ($stmt->execute()) {
            echo json_encode(["success" => "Note uploaded successfully!"]);
        } else {
            echo json_encode(["error" => "Error saving file info: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        file_put_contents("debug_log.txt", "File move failed!" . PHP_EOL, FILE_APPEND);
        echo json_encode(["error" => "Error uploading file!"]);
    }

    $conn->close();
}
?>
