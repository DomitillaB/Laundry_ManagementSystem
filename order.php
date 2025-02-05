<?php
// Include database connection
include('config.php');

// Check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "Please login to place an order.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Initialize variables
$service_prices = [
    'Wash & Fold' => 500,
    'Dry Cleaning' => 1000
];

$order_id = "";
$total_price = 0;
$success_message = "";

// Handle order submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service_type = $_POST['service_type'];
    $quantity = $_POST['quantity'];

    // Calculate total price
    $price_per_kg = $service_prices[$service_type];
    $total_price = $price_per_kg * $quantity;
    $status = 'Pending';

    // Insert order into orders table
    $sql = "INSERT INTO orders (user_id, total_price, status) 
            VALUES ('$user_id', '$total_price', '$status')";

    if ($conn->query($sql) === TRUE) {
        $order_id = $conn->insert_id;

        // Insert into order_items table
        $sql_item = "INSERT INTO order_items (order_id, service_type, quantity, price)
                     VALUES ('$order_id', '$service_type', '$quantity', '$price_per_kg')";

        if ($conn->query($sql_item) === TRUE) {
            $success_message = "Order placed successfully!";

            // Redirect to payment page with order_id and total_price as query parameters
            header("Location: payments.php?order_id=$order_id&amount=$total_price");
            exit;
        } else {
            echo "Error placing order items: " . $conn->error;
        }
    } else {
        echo "Error placing order: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Place Order</title>
    <script>
        function calculateTotalPrice() {
            var serviceType = document.getElementById('service_type').value;
            var quantity = document.getElementById('quantity').value;
            var price = 0;

            if (serviceType === 'Wash & Fold') {
                price = 500;
            } else if (serviceType === 'Dry Cleaning') {
                price = 1000;
            }

            var totalPrice = price * quantity;
            document.getElementById('total_price').value = totalPrice;
        }
    </script>
</head>
<body>
    <h2>Place Order</h2>
    <form method="POST" action="order.php">
        <label for="service_type">Service Type:</label><br>
        <select id="service_type" name="service_type" required onchange="calculateTotalPrice()">
            <option value="Wash & Fold">Wash & Fold</option>
            <option value="Dry Cleaning">Dry Cleaning</option>
        </select><br><br>

        <label for="quantity">Quantity (kg):</label><br>
        <input type="number" id="quantity" name="quantity" required min="1" onchange="calculateTotalPrice()"><br><br>

        <label for="total_price">Total Price (Ksh):</label><br>
        <input type="number" id="total_price" name="total_price" required readonly><br><br>

        <input type="submit" value="Place Order">
    </form>

    <?php if (!empty($success_message)) { ?>
        <h3><?php echo $success_message; ?></h3>
        <p><strong>Order ID:</strong> <?php echo $order_id; ?></p>
        <p><strong>Total Price:</strong> Ksh. <?php echo number_format($total_price, 2); ?></p>
        <!-- Automatically redirects to payments.php after successful order -->
    <?php } ?>
</body>
</html>


