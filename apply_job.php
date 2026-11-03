<?php
include "function/function.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $message = $_POST['message'] ?? '';
    $city = $_POST['city'] ?? 'Unknown';
    $lat_long = $_POST['lat_long'] ?? '';
    $job_id = $_POST['job_id'] ?? 0;

    if (empty($name) || empty($email) || empty($phone) || !isset($_FILES['resume'])) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill all required fields.']);
        exit;
    }

    $file = $_FILES['resume'];
    $allowed_ext = ['pdf', 'doc', 'docx'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($file_ext, $allowed_ext)) {
        echo json_encode(['status' => 'error', 'message' => 'Only PDF, DOC, and DOCX files are allowed.']);
        exit;
    }

    // Use absolute path for reliability
    $upload_dir = __DIR__ . '/upload/resumes/';
    
    // Auto-create directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        if (!mkdir($upload_dir, 0755, true)) {
            echo json_encode(['status' => 'error', 'message' => 'Could not create upload directory. Please contact admin.']);
            exit;
        }
    }
    
    // Check if writable
    if (!is_writable($upload_dir)) {
        echo json_encode(['status' => 'error', 'message' => 'Upload directory not writable. Please check server permissions.']);
        exit;
    }

    $new_file_name = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $file['name']);
    $upload_path = $upload_dir . $new_file_name;

    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        $pdo = getPDOObject();
        $stmt = $pdo->prepare("INSERT INTO job_applications (name, email, phone, city, lat_long, resume, message, job_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        if ($stmt->execute([$name, $email, $phone, $city, $lat_long, $new_file_name, $message, $job_id])) {
            echo json_encode(['status' => 'success', 'message' => 'Application submitted successfully! Our team will contact you soon.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database error. Please try again later.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to upload resume. Check directory permissions.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
