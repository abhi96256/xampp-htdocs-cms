<?php
include('function/function.php');

$pdo = getPDOObject();

// Create table if not exists
$pdo->exec("CREATE TABLE IF NOT EXISTS email_subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    source VARCHAR(100),
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['email']) && filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $email = $data['email'];
        $source = isset($data['source']) ? $data['source'] : 'Unknown';
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        
        try {
            $q = $pdo->prepare("INSERT INTO email_subscriptions (email, source, ip_address, user_agent) VALUES (:email, :source, :ip_address, :user_agent)");
            $q->execute(array(
                ':email' => $email,
                ':source' => $source,
                ':ip_address' => $ip_address,
                ':user_agent' => $user_agent
            ));
            
            echo json_encode(['status' => 'success']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
?>
