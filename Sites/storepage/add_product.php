<?php
// Database connection credentials
$host = 'localhost';  // Usually localhost
$dbname = 'store_db'; // Your database name
$username = 'root';   // Your database username
$password = '';       // Your database password

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables to handle form submission
$title = $description = $image_url = $link = "";
$title_err = $description_err = $image_url_err = $link_err = "";
$success_msg = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs
    if (empty($_POST["title"])) {
        $title_err = "Title is required";
    } else {
        $title = $conn->real_escape_string($_POST["title"]);
    }

    if (empty($_POST["description"])) {
        $description_err = "Description is required";
    } else {
        $description = $conn->real_escape_string($_POST["description"]);
    }

    if (empty($_POST["image_url"])) {
        $image_url_err = "Image URL is required";
    } else {
        $image_url = $conn->real_escape_string($_POST["image_url"]);
    }

    if (empty($_POST["link"])) {
        $link_err = "Product link is required";
    } else {
        $link = $conn->real_escape_string($_POST["link"]);
    }

    // If no errors, insert the product into the database
    if (empty($title_err) && empty($description_err) && empty($image_url_err) && empty($link_err)) {
        $sql = "INSERT INTO products (title, description, image_url, link) VALUES ('$title', '$description', '$image_url', '$link')";
        if ($conn->query($sql) === TRUE) {
            $success_msg = "Product added successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="src/css/formstyle.css">
</head>
<body>
    <h1>Add a New Product</h1>
    
    <!-- Success message -->
    <?php if (!empty($success_msg)) : ?>
        <p class="success"><?php echo $success_msg; ?></p>
    <?php endif; ?>

    <!-- Form to add products -->
    <form action="add_product.php" method="post">
        <div>
            <label for="title">Product Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>">
            <span class="error"><?php echo $title_err; ?></span>
        </div>
        <div>
            <label for="description">Product Description:</label>
            <textarea id="description" name="description"><?php echo htmlspecialchars($description); ?></textarea>
            <span class="error"><?php echo $description_err; ?></span>
        </div>
        <div>
            <label for="image_url">Image URL:</label>
            <input type="text" id="image_url" name="image_url" value="<?php echo htmlspecialchars($image_url); ?>">
            <span class="error"><?php echo $image_url_err; ?></span>
        </div>
        <div>
            <label for="link">Product Link:</label>
            <input type="text" id="link" name="link" value="<?php echo htmlspecialchars($link); ?>">
            <span class="error"><?php echo $link_err; ?></span>
        </div>
        <div>
            <button type="submit">Add Product</button>
        </div>
    </form>
</body>
</html>
