<?php
session_start();
require_once 'mailer.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : 'User';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
            exit;
        }

        $otp = rand(100000, 999999);
        $_SESSION['contact_otp'] = $otp;
        $_SESSION['contact_email'] = $email;
        $_SESSION['otp_time'] = time();

        $subject = "OTP for Contact Verification - A2P Realtech";
        $body = "
        <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #ddd; border-radius: 10px;'>
            <h2 style='color: #c00415;'>A2P Realtech Verification</h2>
            <p>Dear $name,</p>
            <p>Your One-Time Password (OTP) for verifying your contact inquiry is:</p>
            <h1 style='color: #c00415; letter-spacing: 5px;'>$otp</h1>
            <p>This OTP is valid for 10 minutes. Please do not share it with anyone.</p>
            <p>Regards,<br>Team A2P Realtech</p>
        </div>";

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mail->addAddress($email, $name);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        if($mail->send()) {
            echo json_encode(['status' => 'success', 'message' => 'OTP sent successfully to ' . $email]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    }
} catch (Throwable $t) {
    echo json_encode(['status' => 'error', 'message' => 'System error: ' . $t->getMessage()]);
}
