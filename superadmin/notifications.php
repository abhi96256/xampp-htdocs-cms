<?php
// Enable Debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ── IST Timezone (Indian Standard Time) ─────────────────────────
date_default_timezone_set('Asia/Kolkata');

$umessage = '';
include('./function/function.php');
include('./function/push_helper.php'); // Include helper for consistency
check_session();

$pdo = getPDOObject();

// Ensure click tracking table exists
$pdo->exec("CREATE TABLE IF NOT EXISTS notification_clicks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    notification_id INT NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    clicked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_notification_id (notification_id)
)");

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<!-- Debug: POST Request Detected -->";
    extract($_POST);

    $final_image_url = !empty($image_url) ? $image_url : '';

    // Handle Image Upload
    if (isset($_FILES['notif_img']) && $_FILES['notif_img']['error'] == 0) {
        $target_dir = "../upload/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_ext = pathinfo($_FILES["notif_img"]["name"], PATHINFO_EXTENSION);
        $file_name = "notif_" . time() . "_" . rand(1000, 9999) . "." . $file_ext;
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES["notif_img"]["tmp_name"], $target_file)) {
            $final_image_url = SITE_URL . "upload/" . $file_name;
            echo "<!-- Debug: Image Uploaded: $final_image_url -->";
        }
    }

    // Handle scheduled or instant
    $is_scheduled = !empty($scheduled_at);
    
    if ($is_scheduled) {
        // Normalize datetime-local format (browser sends "2026-03-07T09:03", MySQL needs "2026-03-07 09:03:00")
        $scheduled_at_clean = date('Y-m-d H:i:s', strtotime($scheduled_at));
        
        // Save as scheduled in DB (IST time as entered by admin)
        $q = $pdo->prepare("INSERT INTO notifications (title, message, link, image_url, scheduled_at, status) VALUES (:title, :message, :link, :image_url, :scheduled_at, 'scheduled')");
        $q->execute([
            ':title'      => $title,
            ':message'    => $message,
            ':link'       => $link,
            ':image_url'  => $final_image_url ?: null,
            ':scheduled_at' => $scheduled_at_clean
        ]);
        
        $umessage = '<div class="alert alert-info" role="alert">
            <strong><i class="mdi mdi-clock-outline me-1"></i> Scheduled!</strong> Notification saved for <strong>' . date('d M Y, h:i A', strtotime($scheduled_at_clean)) . '</strong> (IST).
        </div>';
    } else {
        try {
            echo "<!-- Debug: Sending Global Push... -->";
            // Send Instantly using push_helper logic
            $sent_count = sendGlobalPushNotification($title, $message, $link, $final_image_url);
            
            if ($sent_count !== false) {
                $umessage = '<div class="alert alert-success" role="alert">
                    <strong><i class="mdi mdi-check-circle me-1"></i> Success!</strong> Notification sent to ' . $sent_count . ' subscribers.
                </div>';
            } else {
                 $umessage = '<div class="alert alert-danger" role="alert">
                    <strong><i class="mdi mdi-alert-circle me-1"></i> Error!</strong> Push helper failed to send. Check PHP error logs.
                </div>';
            }
        } catch (Exception $e) {
            $umessage = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="mdi mdi-alert me-2"></i>
                Error sending via FCM: <strong>' . htmlspecialchars($e->getMessage()) . '</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>';
        }
    }
}

// ── AUTO-PROCESS SCHEDULED NOTIFICATIONS (runs on every page load) ──────────
// This eliminates the need for a cron job on shared hosting
try {
    $now_ist = date('Y-m-d H:i:s'); // IST time (timezone set above)
    $due_stmt = $pdo->prepare("SELECT * FROM notifications WHERE status = 'scheduled' AND scheduled_at <= :now");
    $due_stmt->execute([':now' => $now_ist]);
    $due_notifs = $due_stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($due_notifs as $due) {
        // Build tracking link
        $tracking_link = $due['link'];
        if ($due['link'] && $due['id']) {
            $tracking_link = SITE_URL . 'superadmin/track_click.php?notif_id=' . $due['id'] . '&redirect=' . urlencode($due['link']);
        }

        // Send via push_helper
        $sent = sendGlobalPushNotification($due['title'], $due['message'], $tracking_link ?: $due['link'], $due['image_url']);
        $sent_count = is_int($sent) ? $sent : 0;

        // Mark as sent
        $pdo->prepare("UPDATE notifications SET status='sent', fcm_message_id=:fid, recipients=:rcpt WHERE id=:id")
            ->execute([
                ':fid'  => 'AUTO_' . time(),
                ':rcpt' => $sent_count,
                ':id'   => $due['id']
            ]);
    }

    if (!empty($due_notifs) && empty($umessage)) {
        $cnt = count($due_notifs);
        $umessage = '<div class="alert alert-success" role="alert">
            <strong><i class="mdi mdi-send-clock me-1"></i> Auto-Sent!</strong> ' . $cnt . ' scheduled notification(s) were due and have been sent.
        </div>';
    }
} catch (Exception $e) {
    // silent — don't break page if auto-send fails
}

// Handle Delete
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = (int)$_GET['id'];
    $pdo->query("DELETE FROM notifications WHERE id='$id'");
    $pdo->query("DELETE FROM notification_clicks WHERE notification_id='$id'");
    echo "<script>window.location.href='notifications.php';</script>";
}

// Get all notifications with click counts
$notifs = $pdo->query("
    SELECT n.*, 
           COALESCE(nc.clicks, 0) AS click_count
    FROM notifications n
    LEFT JOIN (
        SELECT notification_id, COUNT(*) AS clicks 
        FROM notification_clicks 
        GROUP BY notification_id
    ) nc ON nc.notification_id = n.id
    ORDER BY n.id DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Total subscribers
$total_subs = $pdo->query("SELECT COUNT(*) FROM subscriber_devices")->fetchColumn();

require('include/header.php');
?>

<style>
.notif-form-card {
    border-radius: 16px;
    border: none;
    box-shadow: 0 4px 24px rgba(102, 126, 234, 0.12);
}
.notif-form-card .card-header {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 16px 16px 0 0;
    padding: 1.2rem 1.5rem;
    font-weight: 600;
    font-size: 1rem;
}
.history-card {
    border-radius: 16px;
    border: none;
    box-shadow: 0 2px 16px rgba(0,0,0,0.07);
}
.history-card .card-header {
    padding: 1.2rem 1.5rem;
    font-weight: 600;
    border-bottom: 1px solid rgba(0,0,0,0.07);
}
.subs-banner {
    background: linear-gradient(135deg, #1a1a2e, #16213e);
    border-radius: 16px;
    padding: 1.2rem 1.5rem;
    color: #fff;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}
.subs-banner .subs-count {
    font-size: 2.5rem;
    font-weight: 800;
    color: #38ef7d;
    line-height: 1;
}
.metric-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    border-radius: 30px;
    padding: 3px 10px;
    font-size: 0.75rem;
    font-weight: 500;
}
</style>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-1"><span class="text-muted fw-light">Admin /</span> Push Notifications</h4>
        <p class="text-muted mb-4">Create and manage push notifications. Click "Analytics" on any notification to see delivery & click stats.</p>

        <?php echo $umessage; ?>

        <!-- Subscriber Banner -->
        <div class="subs-banner">
            <div>
                <div class="subs-count"><?php echo number_format($total_subs); ?></div>
                <div style="opacity:0.7;font-size:0.9rem;margin-top:4px;">Total active push subscribers</div>
            </div>
            <a href="subscribers.php" class="btn btn-sm" style="background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.25);">
                <i class="mdi mdi-chart-bar me-1"></i> View Subscriber Analytics
            </a>
        </div>

        <!-- Send Notification Form -->
        <div class="card notif-form-card mb-4">
            <div class="card-header">
                <i class="mdi mdi-bell-plus-outline me-2"></i> Create New Push Notification
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" onsubmit="this.send_notif.disabled=true; this.send_notif.innerHTML='<i class=\'mdi mdi-loading mdi-spin me-2\'></i> SENDING...'; return true;">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Notification Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. New Property Launch!" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Target Link <small class="text-muted">(optional — click tracking auto-applied)</small></label>
                            <input type="url" name="link" class="form-control" placeholder="https://a2prealtech.com/product.php">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Upload Image <small class="text-muted">(Device se upload karein)</small></label>
                            <input type="file" name="notif_img" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">OR Image URL <small class="text-muted">(Agar link hai to)</small></label>
                            <input type="url" name="image_url" class="form-control" placeholder="https://example.com/image.jpg">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Schedule Sending <small class="text-muted">(optional - leave empty for instant)</small></label>
                            <input type="datetime-local" name="scheduled_at" class="form-control">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Message Content <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control" rows="3" placeholder="Enter your notification message..." required></textarea>
                        </div>
                    </div>
                    <button type="submit" name="send_notif" class="btn btn-primary d-flex align-items-center">
                        <i class="mdi mdi-send me-2"></i> SEND NOTIFICATION
                    </button>
                    <div class="form-text mt-2">
                        <i class="mdi mdi-information-outline"></i> Click tracking is automatically enabled for individual notifications.
                    </div>
                </form>
            </div>
        </div>

        <!-- Notification History -->
        <div class="card history-card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="mdi mdi-history me-2"></i>Notification History</span>
                <span class="badge bg-label-secondary"><?php echo count($notifs); ?> notifications</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Message</th>
                            <th>Recipients</th>
                            <th>Clicks</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $count = 1; foreach ($notifs as $n): ?>
                        <tr>
                            <td><?php echo $count++; ?></td>
                            <td><strong><?php echo htmlspecialchars($n['title']); ?></strong></td>
                            <td style="max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                <?php echo htmlspecialchars($n['message']); ?>
                            </td>
                            <td>
                                <?php if ($n['status'] == 'scheduled'): ?>
                                <span class="badge bg-label-warning">
                                    <i class="mdi mdi-clock-fast"></i> Scheduled
                                </span>
                                <?php elseif ($n['fcm_message_id']): ?>
                                <span class="metric-pill" style="background:#e8f5e9;color:#2e7d32;">
                                    <i class="mdi mdi-send-check-outline"></i>
                                    <?php echo number_format($n['recipients'] ?? 0); ?>
                                </span>
                                <?php else: ?>
                                <span class="text-muted small">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="metric-pill" style="background:#e3f2fd;color:#1565c0;">
                                    <i class="mdi mdi-cursor-default-click-outline"></i>
                                    <?php echo number_format($n['click_count']); ?>
                                </span>
                            </td>
                            <td><small><?php
                                // MySQL TIMESTAMP is UTC — convert to IST for display
                                $dt = new DateTime($n['created_at'], new DateTimeZone('UTC'));
                                $dt->setTimezone(new DateTimeZone('Asia/Kolkata'));
                                echo $dt->format('d M, Y h:i A');
                            ?></small></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="notification_analytics.php?id=<?php echo $n['id']; ?>"
                                       class="btn btn-sm btn-outline-primary" title="View Analytics">
                                        <i class="mdi mdi-chart-areaspline"></i> Analytics
                                    </a>
                                    <a href="notifications.php?id=<?php echo $n['id']; ?>&action=delete"
                                       onclick="return confirm('Delete this notification and all its analytics?')"
                                       class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($notifs)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="mdi mdi-bell-off-outline" style="font-size:2.5rem;display:block;margin-bottom:10px;opacity:0.3;"></i>
                                No notifications sent yet.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div><!-- /container -->
</div><!-- /content-wrapper -->

<?php require('include/footer.php'); ?>

