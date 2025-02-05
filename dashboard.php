<?php
// Start session to check if the user is logged in
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome to your Dashboard, <?php echo $_SESSION['username']; ?>!</h1>
    <p>Here you can manage your orders and profile.</p>
    <a href="order.php">Place a New Order</a><br>
    <a href="logout.php">Logout</a>
</body>
</html>