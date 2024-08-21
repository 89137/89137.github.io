<?php
include 'db_connection.php';

// Define the token expiry time in seconds (6 hours)
$token_expiry_time = 21600; // 6 hours * 60 minutes * 60 seconds

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Prepare the SQL statement to select user based on the token
    $sql = "SELECT id, created_at, verified FROM users WHERE verification_token = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $user_id = $row['id'];
            $created_at = $row['created_at'];
            $verified = $row['verified'];

            // Convert created_at to DateTime
            $token_time = new DateTime($created_at, new DateTimeZone('Europe/Amsterdam'));
            $current_time = new DateTime("now", new DateTimeZone('Europe/Amsterdam'));
            $interval = $current_time->getTimestamp() - $token_time->getTimestamp();

            if ($interval > $token_expiry_time) {
                // Token has expired, delete the user
                $sql_delete = "DELETE FROM users WHERE id = ?";
                if ($stmt_delete = $conn->prepare($sql_delete)) {
                    $stmt_delete->bind_param("i", $user_id);
                    $stmt_delete->execute();
                    $stmt_delete->close();
                }

                echo "The verification link has expired and your account has been removed. Redirecting to the registration page...";
                header("refresh:2;url=/Storepage/register.php"); // Redirect after 2 seconds
            } elseif ($verified) {
                // Token is valid but the user is already verified
                echo "Your email is already verified. Redirecting to the login page...";
                header("refresh:2;url=/Storepage/login.php"); // Redirect after 2 seconds
            } else {
                // Update the user's status to verified
                $sql_update = "UPDATE users SET verified = 1, verification_token = NULL WHERE id = ?";
                if ($stmt_update = $conn->prepare($sql_update)) {
                    $stmt_update->bind_param("i", $user_id);
                    $stmt_update->execute();
                    $stmt_update->close();
                    echo "Your email has been verified successfully! Redirecting to the login page...";
                    header("refresh:2;url=/Storepage/login.php"); // Redirect after 2 seconds
                } else {
                    echo "An error occurred while verifying your email. Please try again later.";
                }
            }
        } else {
            // Token is invalid
            echo "Invalid verification token. Redirecting to the registration page...";
            header("refresh:2;url=/Storepage/register.php"); // Redirect after 2 seconds
        }
        $stmt->close();
    } else {
        echo "An error occurred while processing your request. Please try again later.";
    }
} else {
    echo "No token provided. Redirecting to the registration page...";
    header("refresh:2;url=/Storepage/register.php"); // Redirect after 2 seconds
}

// Close the database connection
$conn->close();
?>
