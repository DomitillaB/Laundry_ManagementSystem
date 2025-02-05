<?php
// Include database connection
include('config.php');

// Start session and check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];
$message = "";  // Variable to store success/error messages

// Get order details from URL query parameters
if (isset($_GET['order_id']) && isset($_GET['amount'])) {
    $order_id = $_GET['order_id'];
    $amount = $_GET['amount'];
} else {
    $message = "❌ Invalid payment request.";
    $order_id = "";
    $amount = "";
}

// Process the payment when the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($order_id) && !empty($amount)) {
        $payment_method = $_POST['payment_method'];
        $payment_date = date('Y-m-d H:i:s');  // Current date and time

        // Insert payment details into the payments table
        $sql_payment = "INSERT INTO payments (order_id, amount, payment_date, payment_method)
                        VALUES ('$order_id', '$amount', '$payment_date', '$payment_method')";

        if ($conn->query($sql_payment) === TRUE) {
            // After successful payment, update order status to 'Paid'
            $sql_update_order = "UPDATE orders SET status = 'Paid' WHERE order_id = '$order_id'";
            $conn->query($sql_update_order);

            $message = "✅ Payment made successfully!";
        } else {
            $message = "❌ Error processing payment: " . $conn->error;
        }
    } else {
        $message = "❌ No pending orders to process payment.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Process Payment</title>
</head>
<body>
    <h2>Process Payment</h2>

    <?php if (!empty($message)) { ?>
        <h3><?php echo $message; ?></h3>
        <?php if ($message == "✅ Payment made successfully!") { ?>
            <a href="order.php"><button>Back to Orders</button></a>
        <?php } ?>
    <?php } else { ?>
        <form method="POST" action="payments.php?order_id=<?php echo $order_id; ?>&amount=<?php echo $amount; ?>">
            <label for="order_id">Order ID:</label><br>
            <input type="text" id="order_id" name="order_id" value="<?php echo $order_id; ?>" readonly><br><br>

            <label for="amount">Amount (Ksh):</label><br>
            <input type="text" id="amount" name="amount" value="Ksh. <?php echo number_format($amount, 2); ?>" readonly><br><br>

            <label for="payment_method">Payment Method:</label><br>
            <select name="payment_method" id="payment_method" required>
                <option value="Cash">Cash</option>
                <option value="Card">Card</option>
                <option value="M-Pesa">M-Pesa</option>
            </select><br><br>

            <input type="submit" value="Make Payment">
        </form>
    <?php } ?>
</body>
</html>
