<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = password_hash(trim($_POST["password"]), PASSWORD_BCRYPT);
    $role = trim($_POST["role"]); // student, mentor, admin

    // Default values for all
    $gender = $dob = $department = $year_of_education = $mobile = NULL;

    if ($role === "student") {
        $gender = trim($_POST["gender"]);
        $dob = trim($_POST["dob"]);
        $department = trim($_POST["department"]);
        $year_of_education = trim($_POST["year_of_education"]);
        $mobile = trim($_POST["mobile"]);
    }

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, gender, dob, department, year_of_education, mobile) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $name, $email, $password, $role, $gender, $dob, $department, $year_of_education, $mobile);

    if ($stmt->execute()) {
        echo "<script>
                alert('Registration successful! Redirecting to login page...');
                window.location.href = 'login.html';
              </script>";
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
