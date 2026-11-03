<?php
/**
 * Cron script to process scheduled push notifications
 * Setup: Run this script via cron job every minute
 * Example: * * * * * php /path/to/superadmin/cron_process_notifications.php
 */

require_once(__DIR__ . '/function/function.php');
require_once(__DIR__ . '/function/fcm_helper.php');

$pdo = getPDOObject();

// 1. Get current time (IST - Indian Standard Time)
date_default_timezone_set('Asia/Kolkata');
$now = date('Y-m-d H:i:s');

// 2. Find pending scheduled notifications
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE status = 'scheduled' AND scheduled_at <= :now");
$stmt->execute([':now' => $now]);
$pending = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($pending)) {
    exit("No pending notifications to send.\n");
}

// 3. Setup Configuration
$service_account_path = __DIR__ . '/firebase-service-account.json';
if (!file_exists($service_account_path)) {
    exit("Error: Service account file not found.\n");
}

$fcm = new FCMHelper($service_account_path);

// Determine Site Base for tracking links
// Since this runs in CLI, we might need a hardcoded or stored base URL
// We'll try to guess or use a fallback. 
// Ideally, SITE_URL should be defined in function.php or config.php
$site_base = defined('SITE_URL') ? SITE_URL . 'superadmin' : 'https://' . ($_SERVER['HTTP_HOST'] ?? 'a2prealtech.com') . '/cms/superadmin';

foreach ($pending as $n) {
    echo "Processing Notification ID: " . $n['id'] . " - " . $n['title'] . "\n";
    
    $notif_db_id = $n['id'];
    $link = $n['link'];
    $image_url = $n['image_url'];
    
    // Build tracking link
    $tracking_link = $link;
    if ($link && $notif_db_id) {
        $tracking_link = $site_base . '/track_click.php?notif_id=' . $notif_db_id . '&redirect=' . urlencode($link);
    }

    // Fetch tokens (Android/Desktop)
    $tokens = $pdo->query("SELECT DISTINCT fcm_token FROM subscriber_devices WHERE fcm_token IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN);
    
    $success_count = 0;
    $fail_count = 0;
    
    foreach ($tokens as $token) {
        if(strlen($token) < 20) continue;
        
        $sender = $fcm->sendNotification($token, $n['title'], $n['message'], $tracking_link ?: $link, $image_url);
        
        if ($sender['success']) {
            $success_count++;
        } else {
            $fail_count++;
            $err = $sender['response']['error']['message'] ?? '';
            if(strpos($err, 'NOT_FOUND') !== false || strpos($err, 'UNREGISTERED') !== false) {
                $pdo->prepare("DELETE FROM subscriber_devices WHERE fcm_token = ?")->execute([$token]);
            }
        }
    }

    // --- NEW: Email Notifications for iOS/Email Subscribers ---
    try {
        $email_subs = $pdo->query("SELECT DISTINCT email FROM email_subscriptions")->fetchAll(PDO::FETCH_COLUMN);
        if (!empty($email_subs)) {
            require_once(__DIR__ . '/../function/mailer.php');
            
            foreach ($email_subs as $email) {
                $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host       = SMTP_HOST;
                    $mail->SMTPAuth   = true;
                    $mail->Username   = SMTP_USER;
                    $mail->Password   = SMTP_PASS;
                    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = SMTP_PORT;
                    $mail->CharSet    = 'UTF-8';

                    $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
                    $mail->addAddress($email);
                    $mail->isHTML(true);
                    $mail->Subject = "New Update: " . $n['title'];
                    
                    $btn_html = $tracking_link ? '<div style="margin-top:20px;"><a href="'.$tracking_link.'" style="background:#c00415; color:#fff; padding:12px 25px; text-decoration:none; border-radius:5px; font-weight:bold;">View Update</a></div>' : '';
                    $img_html = $image_url ? '<div style="margin-top:20px;"><img src="'.$image_url.'" style="max-width:100%; border-radius:10px;"></div>' : '';

                    $mail->Body = "
                    <div style='font-family: Arial, sans-serif; padding: 25px; border: 1px solid #eee; border-radius: 15px; color:#333; max-width:600px; margin:0 auto;'>
                        <h2 style='color:#c00415; margin-bottom:15px;'>".$n['title']."</h2>
                        <p style='font-size:16px; line-height:1.6;'>".$n['message']."</p>
                        $img_html
                        $btn_html
                        <div style='margin-top:30px; padding-top:20px; border-top:1px solid #eee; font-size:12px; color:#999;'>
                            You are receiving this because you subscribed to notifications on A2P Realtech.
                        </div>
                    </div>";
                    $mail->send();
                } catch (Exception $e) { /* Error logged via PHP mailer if needed */ }
            }
        }
    } catch (Exception $e) { /* Error fetching subs */ }

    // Update notification status
    $pdo->prepare("UPDATE notifications SET status = 'sent', fcm_message_id = :fcmid, recipients = :rcpt WHERE id = :id")
        ->execute([
            ':fcmid' => 'FCM_CRON_' . time(),
            ':rcpt' => $success_count + count($email_subs),
            ':id' => $notif_db_id
        ]);
    
    echo "Sent to $success_count devices and " . count($email_subs) . " emails. Failed: $fail_count.\n";
}

echo "Done.\n";
