<?php
session_start();
include '../config.php';

// Check if user is a mentor
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "mentor") {
    echo "Unauthorized access!";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $input_format = $_POST["input_format"];
    $output_format = $_POST["output_format"];
    $mentor_id = $_SESSION["user_id"];

    $sql = "INSERT INTO programming_questions (title, description, input_format, output_format, uploaded_by) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $title, $description, $input_format, $output_format, $mentor_id);

    if ($stmt->execute()) {
        echo "Question uploaded successfully!";
    } else {
        echo "Error uploading question.";
    }

    $stmt->close();
    $conn->close();
}
?>
