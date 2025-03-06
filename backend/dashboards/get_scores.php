<?php
session_start();
include '../config.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "student") {
    echo json_encode(["error" => "Unauthorized access"]);
    exit();
}

$user_id = $_SESSION["user_id"];  // Get logged-in student ID

// Fetch scores only for this student
$sql = "SELECT subject, score FROM student_scores WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$scores = [];
while ($row = $result->fetch_assoc()) {
    $scores[] = $row;
}

echo json_encode($scores);
$stmt->close();
$conn->close();
?>
