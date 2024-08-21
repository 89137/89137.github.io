<?php
include 'db_connection.php';

// Fetch all products from the 'products' table
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Prepare an array to hold the product data
$products = [];

if ($result->num_rows > 0) {
    // Fetch each product as an associative array and store it
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Close the connection
$conn->close();

// Output the products as JSON
header('Content-Type: application/json');
echo json_encode($products);
?>
