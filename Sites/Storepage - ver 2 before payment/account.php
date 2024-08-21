<?php
include 'src/components/db_connection.php';
include 'src/components/header.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Adjusted to point correctly to the login page
    exit;
}

// Initialize variables
$phone_number_err = $update_msg = "";

// Retrieve user information
$user_id = $_SESSION['id'];
$sql = "SELECT username, email, phone_number, role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();

if ($user_result->num_rows > 0) {
    $user_info = $user_result->fetch_assoc();
} else {
    echo "User information not found.";
    exit;
}

// Update phone number if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["phone_number"]))) {
        $phone_number_err = "Please enter a phone number.";
    } else {
        $phone_number = trim($_POST["phone_number"]);
        // Validate phone number
        if (!preg_match("/^\d{10}$/", $phone_number)) {
            $phone_number_err = "Please enter a valid 10-digit phone number.";
        } else {
            // Update the phone number in the database
            $sql = "UPDATE users SET phone_number = ? WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("si", $phone_number, $user_id);
                if ($stmt->execute()) {
                    $update_msg = "Phone number updated successfully!";
                    // Refresh user info to display updated phone number
                    $user_info['phone_number'] = $phone_number;
                } else {
                    $update_msg = "Failed to update the phone number. Please try again.";
                }
                $stmt->close();
            }
        }
    }
}

// Retrieve orders for user
$sql = "SELECT orders.id, products.title, products.price, orders.order_date, orders.shipping_address 
        FROM orders 
        JOIN products ON orders.product_id = products.id 
        WHERE orders.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$order_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="src/css/mystyle.css"> <!-- Adjusted path for the CSS -->
</head>

<body>
    <h1>My Account</h1>

    <!-- Display user information -->
    <div class="user-info">
        <p><strong>Username:</strong> <?php echo htmlspecialchars($user_info['username']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user_info['email']); ?></p>
        <p><strong>Role:</strong> <?php echo htmlspecialchars($user_info['role']); ?></p>

        <!-- Phone number update form -->
        <form method="post" action="account.php"> <!-- Form action points to account.php -->
            <label><strong>Phone Number:</strong></label>
            <input type="text" name="phone_number" value="<?php echo htmlspecialchars($user_info['phone_number']); ?>" 
                   pattern="\d{10}" title="Please enter a 10-digit phone number" maxlength="10" required>
            <span><?php echo $phone_number_err; ?></span>
            <button type="submit">Update Phone Number</button>
        </form>

        <p><?php echo $update_msg; ?></p>
    </div>

    <h2>My Orders</h2>
    <?php if ($order_result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Date</th>
                    <th>Shipping Address</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $order_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo "$" . number_format($row['price'], 2); ?></td>
                        <td><?php echo $row['order_date']; ?></td>
                        <td><?php echo htmlspecialchars($row['shipping_address']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>

</body>

</html>

<?php include 'src/components/footer.php'; ?> <!-- Adjusted path for the footer -->
