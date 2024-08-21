<?php
// Database connection credentials
$host = 'sql7.freemysqlhosting.net';  // Your database host
$dbname = 'sql7725987';                // Your database name
$username = 'sql7725987';              // Your database username
$password = 'iumvRQVX3V';              // Your database password

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
