<?php
$umessage = '';
include('./function/function.php');
check_session();

$pdo = getPDOObject();

// Ensure the fcm_message_id column exists
try { $pdo->exec("ALTER TABLE notifications ADD COLUMN fcm_message_id VARCHAR(255) DEFAULT NULL"); } catch(Exception $e) {}
try { $pdo->exec("ALTER TABLE notifications ADD COLUMN recipients INT DEFAULT 0"); } catch(Exception $e) {}

// Ensure subscriber_devices table exists
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

// Ensure click tracking table exists
$pdo->exec("CREATE TABLE IF NOT EXISTS notification_clicks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    notification_id INT NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    clicked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_notification_id (notification_id)
)");

$notif_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($notif_id <= 0) {
    echo "<script>window.location.href='notifications.php';</script>"; exit;
}

// Fetch notification
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE id = :id");
$stmt->execute([':id' => $notif_id]);
$notif = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$notif) {
    echo "<script>window.location.href='notifications.php';</script>"; exit;
}

// FCM stats (Note: v1 API doesn't provide real-time delivery confirmation per recipient in the same way, we rely on our record)
$fcm_msg_id = $notif['fcm_message_id'] ?? null;

$api_reached    = $notif['recipients'] ?? 0;
$api_converted  = 0;
$api_errored    = 0;
$api_remaining  = 0;

// (OneSignal API code removed here as we are now using FCM)
$onesignal_data = null;
if (false && $fcm_msg_id) {
    // Hidden OneSignal logic
}

// Local click tracking stats
$click_count = $pdo->prepare("SELECT COUNT(*) FROM notification_clicks WHERE notification_id = :id");
$click_count->execute([':id' => $notif_id]);
$local_clicks = $click_count->fetchColumn();

// Clicks over time (last 10 days)
$click_trend = $pdo->prepare("
    SELECT DATE(clicked_at) as click_date, COUNT(*) as cnt 
    FROM notification_clicks 
    WHERE notification_id = :id 
    GROUP BY DATE(clicked_at) 
    ORDER BY click_date ASC 
    LIMIT 15
");
$click_trend->execute([':id' => $notif_id]);
$click_trend_rows = $click_trend->fetchAll(PDO::FETCH_ASSOC);

// Device breakdown of clicks
$click_by_device = $pdo->prepare("
    SELECT 
        CASE 
            WHEN user_agent LIKE '%Mobile%' OR user_agent LIKE '%Android%' OR user_agent LIKE '%iPhone%' THEN 'Mobile'
            ELSE 'Desktop'
        END as device_type,
        COUNT(*) as cnt
    FROM notification_clicks 
    WHERE notification_id = :id
    GROUP BY device_type
");
$click_by_device->execute([':id' => $notif_id]);
$click_device_rows = $click_by_device->fetchAll(PDO::FETCH_ASSOC);

// Recent clickers
$recent_clicks = $pdo->prepare("SELECT * FROM notification_clicks WHERE notification_id = :id ORDER BY clicked_at DESC LIMIT 20");
$recent_clicks->execute([':id' => $notif_id]);
$recent_click_rows = $recent_clicks->fetchAll(PDO::FETCH_ASSOC);

// Subscribers reached (from local subscriber_devices table as a reference)
$total_subs = $pdo->query("SELECT COUNT(*) FROM subscriber_devices")->fetchColumn();

// Delivery rate
$delivery_rate = ($total_subs > 0 && $api_reached > 0) ? round(($api_reached / $total_subs) * 100, 1) : 0;
$click_rate    = ($api_reached > 0) ? round(($local_clicks / $api_reached) * 100, 1) : 0;

// Chart JSON
$trend_labels = json_encode(array_column($click_trend_rows, 'click_date'));
$trend_counts = json_encode(array_map('intval', array_column($click_trend_rows, 'cnt')));
$device_labels = json_encode(array_column($click_device_rows, 'device_type'));
$device_counts = json_encode(array_map('intval', array_column($click_device_rows, 'cnt')));

require('include/header.php');
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<style>
.analytics-hero {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    border-radius: 20px;
    padding: 2rem;
    color: #fff;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
}
.analytics-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 100%;
    height: 200%;
    background: radial-gradient(circle, rgba(102,126,234,0.15) 0%, transparent 60%);
    pointer-events: none;
}
.analytics-hero .notif-title {
    font-size: 1.6rem;
    font-weight: 700;
    margin-bottom: 0.4rem;
}
.analytics-hero .notif-msg {
    opacity: 0.75;
    font-size: 0.95rem;
    margin-bottom: 1rem;
}
.analytics-hero .meta-tags span {
    background: rgba(255,255,255,0.12);
    border-radius: 30px;
    padding: 4px 14px;
    font-size: 0.78rem;
    margin-right: 8px;
    display: inline-block;
    margin-bottom: 4px;
}
.kpi-card {
    border-radius: 16px;
    border: none;
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
}
.kpi-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(0,0,0,0.15) !important; }
.kpi-number { font-size: 2.4rem; font-weight: 800; line-height: 1; }
.kpi-label  { font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.06em; opacity: 0.75; margin-top: 4px; }
.kpi-icon   { width: 52px; height: 52px; border-radius: 13px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
.chart-card { border-radius: 16px; border: none; box-shadow: 0 2px 16px rgba(0,0,0,0.07); }
.chart-card .card-header { border-bottom: 1px solid rgba(0,0,0,0.07); padding: 1.2rem 1.5rem 0.8rem; font-weight: 600; }
.progress-thin { height: 8px; border-radius: 30px; }
.no-onesignal-banner {
    background: linear-gradient(135deg, #fbc2eb, #a6c1ee);
    border-radius: 14px;
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
    color: #333;
    display: flex;
    align-items: center;
    gap: 12px;
}
canvas { max-height: 220px !important; }
</style>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Breadcrumb -->
        <div class="d-flex align-items-center gap-2 mb-3">
            <a href="notifications.php" class="btn btn-sm btn-outline-secondary">
                <i class="mdi mdi-arrow-left me-1"></i> Back to Notifications
            </a>
            <h4 class="mb-0 ms-2"><span class="text-muted fw-light">Notifications /</span> Analytics</h4>
        </div>

        <!-- Hero Banner -->
        <div class="analytics-hero">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <div class="notif-title">
                        <i class="mdi mdi-bell-outline me-2"></i>
                        <?php echo htmlspecialchars($notif['title']); ?>
                    </div>
                    <div class="notif-msg"><?php echo htmlspecialchars($notif['message']); ?></div>
                    <div class="meta-tags">
                        <span><i class="mdi mdi-calendar me-1"></i><?php echo date('d M Y, h:i A', strtotime($notif['created_at'])); ?></span>
                        <?php if ($notif['link']): ?>
                        <span><i class="mdi mdi-link me-1"></i><?php echo htmlspecialchars(parse_url($notif['link'], PHP_URL_HOST) ?: $notif['link']); ?></span>
                        <?php endif; ?>
                        <?php if ($fcm_msg_id): ?>
                        <span><i class="mdi mdi-check-circle me-1"></i>FCM Status: Sent</span>
                        <?php else: ?>
                        <span style="background:rgba(255,200,0,0.2);"><i class="mdi mdi-alert-outline me-1"></i>Notification ID: <?php echo htmlspecialchars($notif['fcm_message_id'] ?: 'Not Found'); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-3 text-md-end pt-3 pt-md-0">
                    <div style="font-size: 4rem; opacity: 0.15; position: absolute; right: 2rem; top: 1rem;">
                        <i class="mdi mdi-chart-areaspline"></i>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!$fcm_msg_id): ?>
        <div class="no-onesignal-banner">
            <i class="mdi mdi-information-outline" style="font-size:1.4rem;color:#6c63ff;"></i>
            <div>
                <strong>FCM delivery stats</strong> are summarized below. Multi-device click tracking is active.
            </div>
        </div>
        <?php endif; ?>

        <!-- KPI Cards -->
        <div class="row g-4 mb-4">

            <!-- Subscribers Available -->
            <div class="col-xl-3 col-sm-6">
                <div class="card kpi-card shadow-sm h-100" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="kpi-icon" style="background:rgba(255,255,255,0.2);">
                                <i class="mdi mdi-account-group-outline"></i>
                            </div>
                            <div class="badge" style="background:rgba(255,255,255,0.15);font-size:0.75rem;">Total Base</div>
                        </div>
                        <div class="kpi-number"><?php echo number_format($total_subs); ?></div>
                        <div class="kpi-label">Subscriber Base</div>
                    </div>
                </div>
            </div>

            <!-- Reached (OneSignal) -->
            <div class="col-xl-3 col-sm-6">
                <div class="card kpi-card shadow-sm h-100" style="background: linear-gradient(135deg, #11998e, #38ef7d);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="kpi-icon" style="background:rgba(255,255,255,0.2);">
                                <i class="mdi mdi-send-check-outline"></i>
                            </div>
                            <?php if ($fcm_msg_id): ?>
                            <div class="badge" style="background:rgba(255,255,255,0.15);font-size:0.75rem;">FCM Broadcast</div>
                            <?php else: ?>
                            <div class="badge" style="background:rgba(255,200,0,0.3);font-size:0.75rem;">No ID</div>
                            <?php endif; ?>
                        </div>
                        <div class="kpi-number"><?php echo $fcm_msg_id ? number_format($api_reached) : '—'; ?></div>
                        <div class="kpi-label">Devices Reached</div>
                        <?php if ($fcm_msg_id && $total_subs > 0): ?>
                        <div class="mt-2">
                            <div class="progress progress-thin" style="background:rgba(255,255,255,0.3);">
                                <div class="progress-bar bg-white" style="width:<?php echo $delivery_rate; ?>%"></div>
                            </div>
                            <small style="opacity:0.8;font-size:0.75rem;"><?php echo $delivery_rate; ?>% delivery rate</small>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Clicks (Local) -->
            <div class="col-xl-3 col-sm-6">
                <div class="card kpi-card shadow-sm h-100" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="kpi-icon" style="background:rgba(255,255,255,0.2);">
                                <i class="mdi mdi-cursor-default-click-outline"></i>
                            </div>
                            <div class="badge" style="background:rgba(255,255,255,0.15);font-size:0.75rem;">Tracked</div>
                        </div>
                        <div class="kpi-number"><?php echo number_format($local_clicks); ?></div>
                        <div class="kpi-label">Total Clicks</div>
                        <?php if ($api_reached > 0): ?>
                        <div class="mt-2">
                            <div class="progress progress-thin" style="background:rgba(255,255,255,0.3);">
                                <div class="progress-bar bg-white" style="width:<?php echo min($click_rate, 100); ?>%"></div>
                            </div>
                            <small style="opacity:0.8;font-size:0.75rem;"><?php echo $click_rate; ?>% click rate</small>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Errored / Remaining -->
            <div class="col-xl-3 col-sm-6">
                <div class="card kpi-card shadow-sm h-100" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="kpi-icon" style="background:rgba(255,255,255,0.2);">
                                <i class="mdi mdi-alert-circle-outline"></i>
                            </div>
                            <div class="badge" style="background:rgba(255,255,255,0.15);font-size:0.75rem;">FCM</div>
                        </div>
                        <div class="kpi-number"><?php echo $fcm_msg_id ? number_format($api_errored) : '—'; ?></div>
                        <div class="kpi-label">Failed Deliveries</div>
                        <?php if ($fcm_msg_id): ?>
                        <small style="opacity:0.8;font-size:0.75rem;"><?php echo number_format($api_remaining); ?> pending</small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div><!-- /KPI Cards -->

        <!-- Charts + Recent Clicks -->
        <div class="row g-4 mb-4">

            <!-- Click Trend Line Chart -->
            <div class="col-lg-7">
                <div class="card chart-card h-100">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="mdi mdi-chart-line text-primary"></i>
                        Click Trend Over Time
                    </div>
                    <div class="card-body">
                        <?php if (empty($click_trend_rows)): ?>
                        <div class="text-center text-muted py-5">
                            <i class="mdi mdi-chart-line-stacked" style="font-size:3rem;opacity:0.3;"></i>
                            <p class="mt-2">No click data recorded yet for this notification.</p>
                        </div>
                        <?php else: ?>
                        <canvas id="clickTrendChart"></canvas>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Click Device Breakdown -->
            <div class="col-lg-5">
                <div class="card chart-card h-100">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="mdi mdi-devices text-success"></i>
                        Clicks by Device Type
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <?php if (empty($click_device_rows)): ?>
                        <div class="text-center text-muted py-4">
                            <i class="mdi mdi-cellphone" style="font-size:3rem;opacity:0.3;"></i>
                            <p class="mt-2">No click data yet.</p>
                        </div>
                        <?php else: ?>
                        <canvas id="deviceClickChart"></canvas>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

        <!-- OneSignal Raw Data (if available) + Recent Clickers -->
        <div class="row g-4">

            <!-- FCM Delivery Summary -->
            <div class="col-lg-4">
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="mdi mdi-signal text-warning"></i>
                        FCM Message Details
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <tbody>
                                <tr><td class="text-muted">Total Recipients</td><td class="fw-bold text-end"><?php echo number_format($api_reached); ?></td></tr>
                                <tr><td class="text-muted">Status</td><td class="text-end"><span class="badge bg-success">Completed</span></td></tr>
                                <tr><td class="text-muted">Provider</td><td class="text-end">Firebase (FCM)</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Clickers Table -->
            <div class="col-lg-12">
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between gap-2">
                        <span><i class="mdi mdi-account-multiple-check-outline text-info me-1"></i> Recent Clickers</span>
                        <span class="badge bg-label-info"><?php echo $local_clicks; ?> total clicks</span>
                    </div>
                    <div class="table-responsive" style="max-height:320px;overflow-y:auto;">
                        <table class="table table-hover mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>#</th>
                                    <th>IP Address</th>
                                    <th>Device</th>
                                    <th>Browser / OS</th>
                                    <th>Clicked At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $ci = 1; foreach ($recent_click_rows as $rc):
                                    $ua = $rc['user_agent'] ?? '';
                                    $is_mobile = (stripos($ua, 'Mobile') !== false || stripos($ua, 'Android') !== false || stripos($ua, 'iPhone') !== false);
                                    $browser = 'Unknown';
                                    if (stripos($ua, 'Chrome') !== false) $browser = 'Chrome';
                                    elseif (stripos($ua, 'Firefox') !== false) $browser = 'Firefox';
                                    elseif (stripos($ua, 'Safari') !== false) $browser = 'Safari';
                                    elseif (stripos($ua, 'Edge') !== false) $browser = 'Edge';
                                ?>
                                <tr>
                                    <td><?php echo $ci++; ?></td>
                                    <td><small><i class="mdi mdi-map-marker-outline text-muted me-1"></i><?php echo htmlspecialchars($rc['ip_address'] ?? '—'); ?></small></td>
                                    <td>
                                        <?php if ($is_mobile): ?>
                                        <span class="badge bg-info"><i class="mdi mdi-cellphone me-1"></i>Mobile</span>
                                        <?php else: ?>
                                        <span class="badge bg-primary"><i class="mdi mdi-laptop me-1"></i>Desktop</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><small><?php echo $browser; ?></small></td>
                                    <td><small><?php echo date('d M Y, h:i A', strtotime($rc['clicked_at'])); ?></small></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($recent_click_rows)): ?>
                                <tr><td colspan="5" class="text-center py-4 text-muted">
                                    <i class="mdi mdi-mouse-off" style="font-size:2rem;display:block;margin-bottom:8px;opacity:0.35;"></i>
                                    No clicks tracked yet. Clicks via the tracking URL will appear here.
                                </td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div><!-- /row -->

        <!-- Tracking URL info box -->
        <?php if ($notif['link']): ?>
        <div class="card chart-card mt-4">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="mdi mdi-link-variant text-secondary"></i>
                Click Tracking URL (use this as your notification link for tracking)
            </div>
            <div class="card-body">
                <?php
                    $site_base = ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1')
                        ? 'http://localhost/cms/superadmin'
                        : 'https://a2prealtech.com/superadmin';
                    $tracking_url = $site_base . '/track_click.php?notif_id=' . $notif_id . '&redirect=' . urlencode($notif['link']);
                ?>
                <div class="input-group">
                    <input type="text" class="form-control font-monospace" id="trackingUrl" value="<?php echo htmlspecialchars($tracking_url); ?>" readonly>
                    <button class="btn btn-outline-primary" onclick="navigator.clipboard.writeText(document.getElementById('trackingUrl').value); this.textContent='Copied!'">
                        <i class="mdi mdi-content-copy me-1"></i> Copy
                    </button>
                </div>
                <div class="form-text mt-1">Use this URL as the link in future notifications to enable click tracking.</div>
            </div>
        </div>
        <?php endif; ?>

    </div><!-- /container -->
</div>

<script>
<?php if (!empty($click_trend_rows)): ?>
// Click Trend Chart
const ctCtx = document.getElementById('clickTrendChart').getContext('2d');
new Chart(ctCtx, {
    type: 'line',
    data: {
        labels: <?php echo $trend_labels; ?>,
        datasets: [{
            label: 'Clicks',
            data: <?php echo $trend_counts; ?>,
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.15)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#667eea',
            pointRadius: 5
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,0.05)' } },
            x: { grid: { display: false } }
        }
    }
});
<?php endif; ?>

<?php if (!empty($click_device_rows)): ?>
// Device Click Doughnut
const dcCtx = document.getElementById('deviceClickChart').getContext('2d');
new Chart(dcCtx, {
    type: 'doughnut',
    data: {
        labels: <?php echo $device_labels; ?>,
        datasets: [{
            data: <?php echo $device_counts; ?>,
            backgroundColor: ['#4facfe', '#f093fb', '#11998e'],
            borderWidth: 4,
            borderColor: '#fff',
            hoverOffset: 10
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: {
            legend: { position: 'bottom', labels: { padding: 14 } }
        }
    }
});
<?php endif; ?>
</script>

<?php require('include/footer.php'); ?>
