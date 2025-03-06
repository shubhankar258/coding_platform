<?php
session_start();
include '../config.php';

header('Content-Type: application/json'); // Ensure JSON output

if (!isset($_SESSION["user_id"]) || !isset($_GET["subject"])) {
    echo json_encode(["error" => "Unauthorized access or missing subject parameter"]);
    exit();
}

$subject = trim($_GET["subject"]);

$sql = "SELECT title, file_path, users.name AS uploaded_by 
        FROM notes 
        JOIN users ON notes.uploaded_by = users.id 
        WHERE notes.subject = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "SQL prepare failed: " . $conn->error]);
    exit();
}

$stmt->bind_param("s", $subject);
$stmt->execute();
$result = $stmt->get_result();

$notes = [];
while ($row = $result->fetch_assoc()) {
    $notes[] = $row;
}

$stmt->close();
$conn->close();

if (empty($notes)) {
    echo json_encode(["error" => "No notes found for this subject."]);
    exit();
}

echo json_encode($notes);
?>
