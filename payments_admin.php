<?php
// Include database connection
include('config.php');

// Start session and check if the user is logged in as admin
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    echo "Access denied. Admins only!";
    exit;
}

// Fetch all payments along with order details
$sql = "SELECT * FROM payments
        JOIN orders ON payments.order_id = orders.order_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payments</title>
</head>
<body>
    <h2>All Payments</h2>
    <table border="1">
        <tr>
            <th>Payment ID</th>
            <th>Order ID</th>
            <th>Amount</th>
            <th>Payment Date</th>
            <th>Payment Method</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['payment_id']; ?></td>
            <td><?php echo $row['order_id']; ?></td>
            <td>Ksh. <?php echo $row['amount']; ?></td>
            <td><?php echo $row['payment_date']; ?></td>
            <td><?php echo $row['payment_method']; ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
