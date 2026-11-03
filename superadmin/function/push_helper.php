<?php
require_once __DIR__ . '/fcm_helper.php';

if (!function_exists('sendGlobalPushNotification')) {
    /**
     * Sends a push notification to all subscribers and logs it in the history.
     */
    function sendGlobalPushNotification($title, $message, $link = '', $image_url = '') {
        $pdo = getPDOObject();
        $service_account_path = __DIR__ . '/../firebase-service-account.json';
        
        // 1. Save to Database for history
        try {
            $q = $pdo->prepare("INSERT INTO notifications (title, message, link, image_url, status) VALUES (:title, :message, :link, :image_url, 'sent')");
            $q->execute([
                ':title'   => $title,
                ':message' => $message,
                ':link'    => $link,
                ':image_url' => $image_url ?: null
            ]);
            $notif_db_id = $pdo->lastInsertId();
        } catch (Exception $e) {
            error_log("DB Insert Error in Push Helper: " . $e->getMessage());
            $notif_db_id = 0;
        }

        // 2. Build tracking link if notification_id is available
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        
        // Try to detect base path (cms or root)
        $base_url = "$protocol://$host";
        $superadmin_url = "$protocol://$host/superadmin";
        
        if (strpos($_SERVER['REQUEST_URI'], '/cms/') !== false) {
            $base_url = "$protocol://$host/cms";
            $superadmin_url = "$protocol://$host/cms/superadmin";
        }
        
        $tracking_link = $link;
        if ($link && $notif_db_id) {
            $tracking_link = $superadmin_url . '/track_click.php?notif_id=' . $notif_db_id . '&redirect=' . urlencode($link);
        }

        // 3. Send via FCM (Android/Desktop)
        try {
            $fcm = new FCMHelper($service_account_path);
            
            // Get all unique tokens
            $tokens = $pdo->query("SELECT DISTINCT fcm_token FROM subscriber_devices WHERE fcm_token IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN);
            
            $success_count = 0;
            $fail_count = 0;

            foreach ($tokens as $token) {
                if (strlen($token) < 20) continue;
                
                $sender = $fcm->sendNotification($token, $title, $message, $tracking_link ?: $link, $image_url);
                
                if ($sender['success']) {
                    $success_count++;
                } else {
                    $fail_count++;
                    // Cleanup invalid tokens
                    $err = $sender['response']['error']['message'] ?? '';
                    if (strpos($err, 'NOT_FOUND') !== false || strpos($err, 'UNREGISTERED') !== false) {
                        $pdo->prepare("DELETE FROM subscriber_devices WHERE fcm_token = ?")->execute([$token]);
                    }
                }
            }

            // 4. Send via Email (Specifically for iOS subscribers)
            try {
                // Fetch all email subscribers
                $email_subs = $pdo->query("SELECT DISTINCT email FROM email_subscriptions")->fetchAll(PDO::FETCH_COLUMN);
                
                if (!empty($email_subs)) {
                    require_once __DIR__ . '/../../function/mailer.php'; // Load SMTP settings
                    
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
                            $mail->Subject = "New Update: " . $title;
                            
                            $btn_html = $tracking_link ? '<div style="margin-top:20px;"><a href="'.$tracking_link.'" style="background:#c00415; color:#fff; padding:12px 25px; text-decoration:none; border-radius:5px; font-weight:bold;">View Update</a></div>' : '';
                            $img_html = $image_url ? '<div style="margin-top:20px;"><img src="'.$image_url.'" style="max-width:100%; border-radius:10px;"></div>' : '';

                            $mail->Body = "
                            <div style='font-family: Arial, sans-serif; padding: 25px; border: 1px solid #eee; border-radius: 15px; color:#333; max-width:600px; margin:0 auto;'>
                                <h2 style='color:#c00415; margin-bottom:15px;'>$title</h2>
                                <p style='font-size:16px; line-height:1.6;'>$message</p>
                                $img_html
                                $btn_html
                                <div style='margin-top:30px; padding-top:20px; border-top:1px solid #eee; font-size:12px; color:#999;'>
                                    You are receiving this because you subscribed to notifications on A2P Realtech.
                                </div>
                            </div>";

                            $mail->send();
                        } catch (Exception $e) {
                            error_log("Email Notification Error for $email: " . $e->getMessage());
                        }
                    }
                }
            } catch (Exception $e) {
                error_log("Email Sending Loop Error: " . $e->getMessage());
            }

            // Update stats in DB (FCM only count for now in stats, or total if preferred)
            if ($notif_db_id) {
                $pdo->prepare("UPDATE notifications SET fcm_message_id = :fcmid, recipients = :rcpt WHERE id = :id")
                    ->execute([
                        ':fcmid' => 'AUTO_' . date('Ymd_His'), 
                        ':rcpt' => $success_count + count($email_subs), 
                        ':id' => $notif_db_id
                    ]);
            }

            return $success_count + count($email_subs);
        } catch (Exception $e) {
            error_log("Push Helper FCM Error: " . $e->getMessage());
            return false;
        }
    }
}
?>
