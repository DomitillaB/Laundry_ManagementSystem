<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php
// Include database connection
 include('config.php');

// Start session and check if the user is logged in as admin
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    // Redirect the user to login page if they are not an admin
    header("Location: admin_login.php");  // Direct them to the admin login page
    exit;
}

// Fetch all orders
$sql = "SELECT * FROM orders JOIN users ON orders.user_id = users.user_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Admin Dashboard</h2>
    <h3>Orders</h3>
    <table>
        <tr>
            <th>Order ID</th>
            <th>User</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['order_id']; ?></td>
            <td><?php echo $row['username']; ?></td>
            <td>Ksh.<?php echo $row['total_price']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td>
                <a href="change_status.php?order_id=<?php echo $row['order_id']; ?>">Change Status</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
