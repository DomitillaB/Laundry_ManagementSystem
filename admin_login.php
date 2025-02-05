<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session
session_start();

// Include database connection
 include('config.php');

// Variable to store login error message
$error_message = "";

// Process the login form when submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $admin['password'])) {
            // ✅ Store user details in session
            $_SESSION['user_id'] = $admin['user_id'];
            $_SESSION['username'] = $admin['username'];  // 🔹 Add this line
            $_SESSION['role'] = $admin['role'];

            // Redirect to dashboard
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error_message = "Invalid admin credentials!";
        }
    } else {
        $error_message = "Invalid admin credentials or you are not authorized!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
</head>
<body>

    <h2>Admin Login</h2>

    <?php if (!empty($error_message)) { ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php } ?>

    <form method="POST" action="admin_login.php">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Login">
    </form>

</body>
</html>
