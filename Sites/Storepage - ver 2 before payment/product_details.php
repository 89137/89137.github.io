<?php
include 'src/components/db_connection.php';
include 'src/components/header.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Get the product ID from the URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id > 0) {
    // Fetch product details from the database
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        die("Product not found");
    }
} else {
    die("Invalid product ID");
}

// Handle form submission for "buying" the product
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $address = $conn->real_escape_string($_POST['address']); // Added address

    // Insert the order into the "orders" table
    $sql = "INSERT INTO orders (product_id, user_id, order_date, customer_name, customer_email, shipping_address) 
            VALUES ($product_id, ?, NOW(), '$name', '$email', '$address')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['id']); // Bind user_id
    if ($stmt->execute()) {
        $order_success = true; // Set success flag
    } else {
        $order_error = "Error: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['title']); ?></title>
    <link rel="stylesheet" href="src/css/product_details.css">
    <style>
        /* Modal Styles */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <h1><?php echo htmlspecialchars($product['title']); ?></h1>
    <p><?php echo htmlspecialchars($product['description']); ?></p>
    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Product Image">
    <p>Price: $<?php echo number_format($product['price'], 2); ?></p>

    <!-- Form to "buy" the product -->
    <form method="post" action="">
        <div>
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $_SESSION['username']; ?>" <?php echo isset($_SESSION['loggedin']) ? 'readonly' : 'required'; ?>>
        </div>
        <div>
            <label for="email">Your Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $_SESSION['email']; ?>" <?php echo isset($_SESSION['loggedin']) ? 'readonly' : 'required'; ?>>
        </div>
        <div>
            <label for="address">Shipping Address:</label>
            <input type="text" id="address" name="address" required>
        </div>
        <button type="submit">Buy Now</button>
    </form>

    <!-- The Modal -->
    <div id="orderModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="modalMessage"></p>
        </div>
    </div>

    <script>
        // Get the modal
        var modal = document.getElementById("orderModal");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // Show the modal if the order was successful
        <?php if (isset($order_success) && $order_success): ?>
            window.onload = function() {
                document.getElementById("modalMessage").innerText = "Order placed successfully!";
                modal.style.display = "block";

                // Redirect after 2 seconds
                setTimeout(function() {
                    window.location.href = "index.php"; // Redirect to store page
                }, 2000);
            }
        <?php elseif (isset($order_error)): ?>
            window.onload = function() {
                document.getElementById("modalMessage").innerText = "<?php echo htmlspecialchars($order_error); ?>";
                modal.style.display = "block";
            }
        <?php endif; ?>

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>

</html>

<?php include 'src/components/footer.php'; ?>
