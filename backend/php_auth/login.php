<?php
session_start();
include '../config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $email, $hashed_password, $role);
        $stmt->fetch();

        // Verify hashed password
        if (password_verify($password, $hashed_password)) {
            session_regenerate_id(true);  // Security: Prevent session fixation

            $_SESSION["user_id"] = $id;
            $_SESSION["user_name"] = $name;
            $_SESSION["user_email"] = $email;  // ✅ Fix: Store user email in session
            $_SESSION["user_role"] = $role;

            // Redirect based on role
            if ($role == "student") {
                header("Location: ../../frontend/student_dashboard/student_dashboard.php");
            } elseif ($role == "mentor") {
                header("Location: ../../frontend/mentor_dashboard/mentor_dashboard.php");
            } elseif ($role == "admin") {
                header("Location: ../../frontend/admin_dashboard/admin_dashboard.php");
            }
            
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Invalid email! No user found.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!-- Login Form -->
<form action="login.php" method="post">
    <input type="email" name="email" placeholder="Enter Email" required>
    <input type="password" name="password" placeholder="Enter Password" required>
    <button type="submit">Login</button>
</form>

<!-- Show error message if login fails -->
<?php if ($error): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>

<!-- Sign-Up Link -->
<p>Don't have an account? <a href="register.html">Sign up here</a></p>
