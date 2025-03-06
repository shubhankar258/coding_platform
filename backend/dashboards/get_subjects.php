<?php
session_start();
header('Content-Type: application/json');
include '../config.php';

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["error" => "Unauthorized access!"]);
    exit();
}

// Fetch subjects from database
$sql = "SELECT DISTINCT subject FROM notes";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => "Database error: " . $conn->error]);
    exit();
}

$subjects = [];
while ($row = $result->fetch_assoc()) {
    $subjects[] = $row["subject"];
}

// Debugging Output
if (empty($subjects)) {
    echo json_encode(["error" => "No subjects found in database."]);
} else {
    echo json_encode($subjects);
}

$conn->close();
?>
