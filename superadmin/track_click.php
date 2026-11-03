<?php
/**
 * track_click.php
 * Records a click on a push notification, then redirects to the destination URL.
 * Called via: track_click.php?notif_id=5&redirect=https://...
 */
include('../function/function.php');

$pdo = getPDOObject();

// Create click tracking table if not exists
$pdo->exec("CREATE TABLE IF NOT EXISTS notification_clicks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    notification_id INT NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    clicked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_notification_id (notification_id)
)");

$notif_id = isset($_GET['notif_id']) ? (int)$_GET['notif_id'] : 0;
$redirect  = isset($_GET['redirect'])  ? $_GET['redirect']  : '/';

if ($notif_id > 0) {
    $stmt = $pdo->prepare("INSERT INTO notification_clicks (notification_id, ip_address, user_agent) VALUES (:nid, :ip, :ua)");
    $stmt->execute([
        ':nid' => $notif_id,
        ':ip'  => $_SERVER['REMOTE_ADDR'],
        ':ua'  => $_SERVER['HTTP_USER_AGENT']
    ]);
}

// Sanitize redirect
$redirect = filter_var($redirect, FILTER_SANITIZE_URL);
if (!preg_match('/^https?:\/\//i', $redirect)) {
    // If it's a relative path, prepend SITE_URL (ensure no double-slash)
    $redirect = rtrim(SITE_URL, '/') . '/' . ltrim($redirect, '/');
}
header("Location: $redirect");
exit;
