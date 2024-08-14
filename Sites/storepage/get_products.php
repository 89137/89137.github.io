<?php
// Database connection credentials
$host = 'sql7.freemysqlhosting.net';  // Usually localhost
$dbname = 'sql7725987'; // Your database name
$username = 'sql7725987';   // Your database username
$password = 'iumvRQVX3V';       // Your database password

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
} else {
    echo "No products found!";
}

// Close the connection
$conn->close();

// Output the products as JSON
header('Content-Type: application/json');
echo json_encode($products);
?>
