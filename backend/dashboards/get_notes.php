<?php
include '../config.php';

if (!isset($_GET["subject"])) {
    echo json_encode(["error" => "No subject provided."]);
    exit();
}

$subject = $_GET["subject"];
$sql = "SELECT title, file_path, uploaded_by FROM notes WHERE subject = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $subject);
$stmt->execute();
$result = $stmt->get_result();

$notes = [];
while ($row = $result->fetch_assoc()) {
    $notes[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($notes);
?>
