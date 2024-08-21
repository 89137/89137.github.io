<?php
include 'src/components/db_connection.php';
include 'src/components/header.php';

$username = $email = $password = "";
$username_err = $email_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username or email is provided
    if (empty(trim($_POST["username"]))) {
        if (empty(trim($_POST["email"]))) {
            $email_err = "Please enter your username or email.";
        } else {
            $email = trim($_POST["email"]);
        }
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($username_err) && empty($email_err) && empty($password_err)) {
        $sql = "SELECT id, username, email, password, verified, role FROM users WHERE username = ? OR email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $param_username, $param_email);

            // Set parameters
            $param_username = $username;
            $param_email = $email;

            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $db_username, $db_email, $hashed_password, $verified, $role);
                if ($stmt->fetch()) {
                    // Check if the password is correct
                    if (password_verify($password, $hashed_password)) {
                        if ($verified) {
                            // Start session and store user data
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $db_username;
                            $_SESSION["email"] = $db_email; // Store email in session
                            $_SESSION["role"] = $role; // Store user role in session

                            header("location: account.php");
                            exit;
                        } else {
                            $password_err = "Please verify your email before logging in.";
                        }
                    } else {
                        $password_err = "The password you entered is incorrect.";
                    }
                }
            } else {
                $username_err = "No account found with that username or email.";
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="src/css/formstyle.css">
</head>

<body>
    <h1>Login</h1>
    <form action="login.php" method="post">
        <div>
            <label>Username</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>">
            <span><?php echo $username_err; ?></span>
        </div>
        <div>
            <label>Email (optional)</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <span><?php echo $email_err; ?></span>
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password">
            <span><?php echo $password_err; ?></span>
        </div>
        <button type="submit">Login</button>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </form>
</body>

</html>

<?php include 'src/components/footer.php'; ?>
