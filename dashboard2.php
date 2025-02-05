<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php
// Start session to check if the user is logged in
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include('config.php');

// Fetch user orders
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM orders WHERE user_id = '$user_id'";
$result = $conn->query($sql);

// Avoid undefined session variable error
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "User";  // 🔹 Fix applied
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
</head>
<body>
    <h1>Welcome to your Dashboard, <?php echo htmlspecialchars($username); ?>!</h1>  <!-- 🔹 Secure Output -->
    <p>Here you can manage your orders and profile.</p>
    <a href="order.php">Place a New Order</a><br>
    <a href="logout.php">Logout</a>

    <h3>Your Orders</h3>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Total Price</th>
            <th>Status</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['order_id']; ?></td>
            <td>Ksh.<?php echo $row['total_price']; ?></td>
            <td><?php echo $row['status']; ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
