<?php
/*
 * TinyMCE Upload Handler
 */
header('Content-Type: application/json');

// Folder jahan image save hogi
$imageFolder = "../upload/";

if (!is_dir($imageFolder)) {
    @mkdir($imageFolder, 0775, true);
}

// Check if file was uploaded
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    
    // Extensions check
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($ext, $allowed)) {
        header("HTTP/1.1 400 Invalid extension.");
        echo json_encode(['error' => 'Invalid file type']);
        exit;
    }

    // New safe name
    $fname = date('YmdHis') . "_" . uniqid() . "." . $ext;
    $target = $imageFolder . $fname;

    if (move_uploaded_file($file['tmp_name'], $target)) {
        // Return path relative to the root/site
        // Browser needs to reach this image from 'superadmin/'
        echo json_encode(['location' => '../upload/' . $fname]);
        exit;
    } else {
        header("HTTP/1.1 500 Server Error");
        echo json_encode(['error' => 'Move failed']);
        exit;
    }
} else {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(['error' => 'No file']);
    exit;
}
?>
