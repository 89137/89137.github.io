<?php
include 'src/components/db_connection.php';
include 'src/components/header.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Handle product deletion
if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
}

// Handle product addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $image_url = trim($_POST['image_url']);
    $link = trim($_POST['link']);
    $price = trim($_POST['price']);
    $category = trim($_POST['category']);
    $stock = trim($_POST['stock']);

    // Validate input
    if (!empty($title) && is_numeric($price)) {
        $sql = "INSERT INTO products (title, description, image_url, link, price, category, stock) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssdis", $title, $description, $image_url, $link, $price, $category, $stock);
        $stmt->execute();
    }
}

// Fetch all products to display for deletion
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="src/css/mystyle.css">
</head>

<body>
    <h1>Add/Remove Products</h1>

    <!-- Form to add new product -->
    <form action="add_product.php" method="POST">
        <h2>Add New Product</h2>
        <div>
            <label for="title">Product Title:</label>
            <input type="text" name="title" id="title" required>
        </div>
        <div>
            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>
        </div>
        <div>
            <label for="image_url">Image URL:</label>
            <input type="text" name="image_url" id="image_url">
        </div>
        <div>
            <label for="link">Product Link:</label>
            <input type="text" name="link" id="link">
        </div>
        <div>
            <label for="price">Price:</label>
            <input type="number" step="0.01" name="price" id="price" required>
        </div>
        <div>
            <label for="category">Category:</label>
            <input type="text" name="category" id="category">
        </div>
        <div>
            <label for="stock">Stock:</label>
            <input type="number" name="stock" id="stock" value="0" min="0">
        </div>
        <button type="submit" name="add_product">Add Product</button>
    </form>

    <!-- List of existing products -->
    <h2>Existing Products</h2>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Category</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo "$" . number_format($row['price'], 2); ?></td>
                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                    <td><?php echo htmlspecialchars($row['stock']); ?></td>
                    <td>
                        <a href="add_product.php?delete=<?php echo $row['id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>

<?php include 'src/components/footer.php'; ?>
