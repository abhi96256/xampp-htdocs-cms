<?php
include('function/function.php');

$pdo = getPDOObject();

// Create table if not exists
$pdo->exec("CREATE TABLE IF NOT EXISTS subscriber_devices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fcm_token VARCHAR(255) UNIQUE,
    device_type VARCHAR(50),
    browser VARCHAR(50),
    os VARCHAR(50),
    device_model VARCHAR(100),
    user_agent TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['fcm_token'])) {
        $fcm_token = $data['fcm_token'];
        $device_type = isset($data['device_type']) ? $data['device_type'] : 'unknown';
        $browser = isset($data['browser']) ? $data['browser'] : 'unknown';
        $os = isset($data['os']) ? $data['os'] : 'unknown';
        $device_model = isset($data['device_model']) ? $data['device_model'] : 'unknown';
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $ip_address = $_SERVER['REMOTE_ADDR'];
        file_put_contents('save_log.txt', "Token Save Attempt: " . date('Y-m-d H:i:s') . " - Token: " . substr($fcm_token, 0, 20) . " - IP: " . $ip_address . "\n", FILE_APPEND);
        
        $q = $pdo->prepare("INSERT INTO subscriber_devices 
            (fcm_token, device_type, browser, os, device_model, user_agent, ip_address) 
            VALUES (:fcm_token, :device_type, :browser, :os, :device_model, :user_agent, :ip_address)
            ON DUPLICATE KEY UPDATE 
            device_type = VALUES(device_type),
            browser = VALUES(browser),
            os = VALUES(os),
            device_model = VALUES(device_model),
            user_agent = VALUES(user_agent),
            ip_address = VALUES(ip_address)");
            
        $q->execute(array(
            ':fcm_token' => $fcm_token,
            ':device_type' => $device_type,
            ':browser' => $browser,
            ':os' => $os,
            ':device_model' => $device_model,
            ':user_agent' => $user_agent,
            ':ip_address' => $ip_address
        ));
        
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Missing token']);
    }
}
?>
