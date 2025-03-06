<?php
session_start();
include '../config.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "student") {
    echo json_encode(["error" => "Unauthorized access"]);
    exit();
}

// Fetch programming questions
$sql = "SELECT id, title, description FROM programming_questions ORDER BY id DESC";
$result = $conn->query($sql);

$questions = [];
while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
}

echo json_encode($questions);
$conn->close();
?>
