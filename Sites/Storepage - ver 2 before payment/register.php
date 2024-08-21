<?php
include 'src/components/db_connection.php';
include 'vendor/autoload.php';
include 'src/components/header.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Initialize variables
$username = $email = $password = $phone_number = "";
$username_err = $email_err = $password_err = $phone_err = $registration_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST["username"]);
        // Check if username already exists
        $sql = "SELECT id FROM users WHERE username = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $username_err = "This username is already taken.";
            }
            $stmt->close();
        }
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else {
        $email = trim($_POST["email"]);
        // Check if email already exists
        $sql = "SELECT id FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $email_err = "An account with this email already exists.";
            }
            $stmt->close();
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate phone number
    if (empty(trim($_POST["phone_number"]))) {
        $phone_err = "Please enter a phone number.";
    } else {
        $phone_number = trim($_POST["phone_number"]);
    }

    // Check input errors before inserting into the database
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($phone_err)) {
        // Generate verification token
        $verification_token = bin2hex(random_bytes(16));

        // Insert user details into the database
        $sql = "INSERT INTO users (username, email, password, phone_number, verification_token, created_at, verified) VALUES (?, ?, ?, ?, ?, NOW(), 0)";
        if ($stmt = $conn->prepare($sql)) {
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
            $stmt->bind_param("sssss", $username, $email, $param_password, $phone_number, $verification_token);

            if ($stmt->execute()) {
                // Send verification email using PHPMailer
                $mail = new PHPMailer(true);

                try {
                    // Set up PHPMailer server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';  // Use Gmail's SMTP server
                    $mail->SMTPAuth = true;
                    $mail->Username = 'wompw0575@gmail.com'; // Your Gmail address
                    $mail->Password = 'bwtcohnzlvokwojc'; // Your Gmail App Password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587; // Port for TLS/STARTTLS

                    // Recipients
                    $mail->setFrom('no-reply@yourdomain.com', 'Your Store');
                    $mail->addAddress($email); // Add recipient

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Verify Your Email Address';
                    $verify_link = "http://localhost/Storepage/src/components/verify.php?token=$verification_token";
                    $mail->Body = "Click this link to verify your email: <a href='$verify_link'>$verify_link</a><br><br>You have 30 seconds to verify your account.";
                    // $mail->SMTPDebug = 2; // Show detailed SMTP communication

                    $mail->send();
                    echo "Registration successful. Please check your email to verify your account.";
                } catch (Exception $e) {
                    echo "Verification email could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
                exit;
            } else {
                $registration_err = "Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="src/css/formstyle.css">
</head>

<body>
    <h1>Register</h1>
    <form action="register.php" method="post">
        <div>
            <label>Username</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>">
            <span><?php echo $username_err; ?></span>
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <span><?php echo $email_err; ?></span>
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password">
            <span><?php echo $password_err; ?></span>
        </div>
        <div>
            <label>Phone Number</label>
            <input type="text" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>"
                pattern="\d{10}" title="Please enter a 10-digit phone number" maxlength="10" required>
            <span><?php echo $phone_err; ?></span>
        </div>
        <button type="submit">Register</button>
        <p>Already have an account? <a href="login.php">Login here</a></p>
        <span><?php echo $registration_err; ?></span>
    </form>
</body>

</html>

<?php include 'src/components/footer.php'; ?>
