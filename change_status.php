<?php
// Include the database connection
include('config.php');

// Start session and check if the user is logged in as an admin
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    echo "Access denied. Admins only!";
    exit;
}

// Check if the order_id is passed via GET
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    echo "Order ID: " . $order_id;  // Debug line to print the order ID
    // Check if the form has been submitted to change the order status
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the new status from the form
        $status = $_POST['status'];

        // Update the status in the orders table
        $sql = "UPDATE orders SET status = '$status' WHERE order_id = '$order_id'";

        if ($conn->query($sql) === TRUE) {
            echo "Status updated successfully!";
        } else {
            echo "Error updating status: " . $conn->error;
        }
    }
} else {
    echo "No order ID provided!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Order Status</title>
</head>
<body>
    <h2>Change Order Status</h2>
    
    <form method="POST" action="change_status.php?order_id=<?php echo $_GET['order_id']; ?>">
        <label for="status">Status:</label><br>
        <select name="status" id="status">
            <option value="Pending">Pending</option>
            <option value="In Progress">In Progress</option>
            <option value="Completed">Completed</option>
        </select><br><br>
        <input type="submit" value="Update Status">
    </form>
</body>
</html>

