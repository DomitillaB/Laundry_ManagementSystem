<?php
// Include the database connection configuration
include ("config.php");

// Check if the connection is successful
if ($conn) {
    echo "Database connection successful!";
} else {
    echo "Failed to connect to database.";
}

// Close the connection
$conn->close();
?>