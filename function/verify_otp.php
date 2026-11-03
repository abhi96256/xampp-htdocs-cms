<?php
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = isset($_POST['otp']) ? trim($_POST['otp']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';

    if (empty($otp) || empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'OTP and Email are required.']);
        exit;
    }

    if (!isset($_SESSION['contact_otp']) || !isset($_SESSION['contact_email'])) {
        echo json_encode(['status' => 'error', 'message' => 'OTP expired or not initiated.']);
        exit;
    }

    // Check expiration (10 minutes)
    if (time() - $_SESSION['otp_time'] > 600) {
        unset($_SESSION['contact_otp']);
        unset($_SESSION['contact_email']);
        unset($_SESSION['otp_time']);
        echo json_encode(['status' => 'error', 'message' => 'OTP has expired. Please send a new one.']);
        exit;
    }

    if ($otp == $_SESSION['contact_otp'] && $email == $_SESSION['contact_email']) {
        $_SESSION['otp_verified'] = true;
        // Keep it for a bit just in case, or we can clear it. 
        // We'll clear it after successful form submission in the backend if needed, 
        // but since form submission is AJAX, we'll use this session flag.
        echo json_encode(['status' => 'success', 'message' => 'OTP verified successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid OTP. Please try again.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
