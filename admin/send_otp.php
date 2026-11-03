<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include('db.php'); // Ensure PDO is properly initialized here

function sendOTP($email, $otp)
{
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'team@a2prealtech.com';
        $mail->Password = 'yvfnyvmpxvhfjpau';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('team@a2prealtech.com', 'A2P OTP System');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = 'Your OTP code is: ' . $otp;

        $mail->send();
        return true;
    }
    catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}

function generateOTP()
{
    return rand(100000, 999999);
}

$message = '';
$otpSent = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; // Get password from POST

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            // Check if the email exists in the subadmin table
            $stmt = $pdo->prepare("SELECT * FROM subadmin WHERE username = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user) {
                // Directly compare plain text passwords
                if ($password === $user['password']) {
                    $otp = generateOTP();

                    // Update OTP
                    $stmt = $pdo->prepare("UPDATE subadmin SET otp = :otp, otp_created_at = NOW() WHERE username = :email");
                    $stmt->execute(['otp' => $otp, 'email' => $email]);

                    if (sendOTP($email, $otp)) {
                        // Redirect to OTP verification page with email as query parameter
                        header("Location: verify_otp.php?email=" . urlencode($email));
                        exit();
                    }
                    else {
                        $message = 'Failed to send OTP. Please try again later.';
                    }
                }
                else {
                    $message = 'Email and password do not match.';
                }
            }
            else {
                $message = 'Your email is not registered with us.';
            }
        }
        catch (Exception $e) {
            error_log("Database Error: " . $e->getMessage());
            $message = 'Database error. Please try again later.';
        }
    }
    else {
        $message = 'Invalid email format.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background-color: #4cae4c;
        }
        p {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <form method="post" action="">
        <input type="email" name="email" placeholder="Enter your email" required>
        <input type="password" name="password" placeholder="Enter your password" required>
        <button type="submit">Send OTP</button>
    </form>

    <?php if (!empty($message)): ?>
        <p><?php echo $message; ?></p>
    <?php
endif; ?>
</body>
</html>
