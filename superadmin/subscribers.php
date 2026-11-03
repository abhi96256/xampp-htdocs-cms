<?php
$umessage = '';
include('./function/function.php');
check_session();

$pdo = getPDOObject();

// Create table if it doesn't exist yet (runs before any SELECT)
$pdo->exec("CREATE TABLE IF NOT EXISTS subscriber_devices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fcm_token VARCHAR(255) UNIQUE,
    device_type VARCHAR(50),
    browser VARCHAR(50),
    os VARCHAR(50),
    device_model VARCHAR(100),
    user_agent TEXT,
    ip_address VARCHAR(45),
    city VARCHAR(100) DEFAULT NULL,
    country VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");

// Ensure column rename and extra columns (for existing installations)
try { $pdo->exec("ALTER TABLE subscriber_devices CHANGE COLUMN onesignal_id fcm_token VARCHAR(255)"); } catch(Exception $e) {}
try { $pdo->exec("ALTER TABLE subscriber_devices ADD COLUMN city VARCHAR(100) DEFAULT NULL"); } catch(Exception $e) {}
try { $pdo->exec("ALTER TABLE subscriber_devices ADD COLUMN country VARCHAR(100) DEFAULT NULL"); } catch(Exception $e) {}

// Handle Delete
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $pdo->query("DELETE FROM subscriber_devices WHERE id='$id'");
    echo "<script>window.location.href='subscribers.php';</script>";
}

// --- Stats ---
$total_subscribers = $pdo->query("SELECT COUNT(*) FROM subscriber_devices")->fetchColumn();

// Browser breakdown
$browser_stats = $pdo->query("SELECT browser, COUNT(*) as cnt FROM subscriber_devices GROUP BY browser ORDER BY cnt DESC")->fetchAll(PDO::FETCH_ASSOC);

// Device type breakdown
$device_stats = $pdo->query("SELECT device_type, COUNT(*) as cnt FROM subscriber_devices GROUP BY device_type ORDER BY cnt DESC")->fetchAll(PDO::FETCH_ASSOC);

// City / Region breakdown (from ip_address via geo lookup)
$city_stats = $pdo->query("SELECT ip_address, COUNT(*) as cnt FROM subscriber_devices GROUP BY ip_address ORDER BY cnt DESC LIMIT 20")->fetchAll(PDO::FETCH_ASSOC);

// Visitor count this month
$visitor_this_month = $pdo->query("SELECT COUNT(*) FROM subscriber_devices WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())")->fetchColumn();

// All subscribers list
$devices = sqlfetch("SELECT * FROM subscriber_devices ORDER BY id DESC");

// Prepare chart data as JSON
$browser_labels = json_encode(array_column($browser_stats, 'browser'));
$browser_counts = json_encode(array_column($browser_stats, 'cnt'));
$device_labels  = json_encode(array_column($device_stats,  'device_type'));
$device_counts  = json_encode(array_column($device_stats,  'cnt'));

require('include/header.php');
?>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<style>
.stat-card {
    border-radius: 16px;
    border: none;
    overflow: hidden;
    position: relative;
    transition: transform 0.2s, box-shadow 0.2s;
}
.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.15) !important;
}
.stat-card .card-body {
    padding: 1.6rem;
}
.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}
.stat-number {
    font-size: 2.2rem;
    font-weight: 700;
    line-height: 1;
}
.stat-label {
    font-size: 0.82rem;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    opacity: 0.7;
    margin-top: 4px;
}
.chart-card {
    border-radius: 16px;
    border: none;
    box-shadow: 0 2px 16px rgba(0,0,0,0.07);
}
.chart-card .card-header {
    border-bottom: 1px solid rgba(0,0,0,0.07);
    padding: 1.2rem 1.5rem 0.8rem;
    font-weight: 600;
    font-size: 0.95rem;
}
.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.2rem;
}
.section-sub {
    font-size: 0.88rem;
    color: #8a8d93;
    margin-bottom: 1.5rem;
}
canvas {
    max-height: 240px !important;
}
</style>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-1"><span class="text-muted fw-light">Admin /</span> Subscriber Analytics Dashboard</h4>
        <p class="text-muted mb-4">Push notification subscriber insights: devices, browsers, and monthly trends.</p>

        <!-- Stats Row -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-5 g-4 mb-4">

            <!-- Total Subscribers -->
            <div class="col">
                <div class="card stat-card shadow-sm h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-number"><?php echo number_format($total_subscribers); ?></div>
                                <div class="stat-label">Total Subscribers</div>
                            </div>
                            <div class="stat-icon" style="background:rgba(255,255,255,0.2);">
                                <i class="mdi mdi-bell-ring-outline" style="color:#fff;"></i>
                            </div>
                        </div>
                        <div class="mt-3 d-flex align-items-center gap-1">
                            <i class="mdi mdi-devices" style="font-size:1rem;opacity:0.8;"></i>
                            <small style="opacity:0.85;">All push devices</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visitors This Month -->
            <div class="col">
                <div class="card stat-card shadow-sm h-100" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-number"><?php echo number_format($visitor_this_month); ?></div>
                                <div class="stat-label">New This Month</div>
                            </div>
                            <div class="stat-icon" style="background:rgba(255,255,255,0.2);">
                                <i class="mdi mdi-calendar-month-outline" style="color:#fff;"></i>
                            </div>
                        </div>
                        <div class="mt-3 d-flex align-items-center gap-1">
                            <i class="mdi mdi-trending-up" style="font-size:1rem;opacity:0.8;"></i>
                            <small style="opacity:0.85;">Subscribed in <?php echo date('F'); ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Browser Count -->
            <div class="col">
                <div class="card stat-card shadow-sm h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-number"><?php echo count($browser_stats); ?></div>
                                <div class="stat-label">Browser Types</div>
                            </div>
                            <div class="stat-icon" style="background:rgba(255,255,255,0.2);">
                                <i class="mdi mdi-web" style="color:#fff;"></i>
                            </div>
                        </div>
                        <div class="mt-3 d-flex align-items-center gap-1">
                            <i class="mdi mdi-google-chrome" style="font-size:1rem;opacity:0.8;"></i>
                            <small style="opacity:0.85;">
                                <?php echo !empty($browser_stats) ? $browser_stats[0]['browser'] : 'No data'; ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Device Types -->
            <div class="col">
                <div class="card stat-card shadow-sm h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-number"><?php echo count($device_stats); ?></div>
                                <div class="stat-label">Device Types</div>
                            </div>
                            <div class="stat-icon" style="background:rgba(255,255,255,0.2);">
                                <i class="mdi mdi-devices" style="color:#fff;"></i>
                            </div>
                        </div>
                        <div class="mt-3 d-flex align-items-center gap-1">
                            <i class="mdi mdi-cellphone" style="font-size:1rem;opacity:0.8;"></i>
                            <small style="opacity:0.85;">
                                <?php echo !empty($device_stats) ? $device_stats[0]['device_type'] : 'No data'; ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- City / Regions -->
            <div class="col">
                <div class="card stat-card shadow-sm h-100" style="background: linear-gradient(135deg, #fbbebe 0%, #ff85a2 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-number"><?php echo count($city_stats); ?></div>
                                <div class="stat-label">Top Regions</div>
                            </div>
                            <div class="stat-icon" style="background:rgba(255,255,255,0.2);">
                                <i class="mdi mdi-map-marker-radius-outline" style="color:#fff;"></i>
                            </div>
                        </div>
                        <div class="mt-3 d-flex align-items-center gap-1">
                            <i class="mdi mdi-map-marker-outline" style="font-size:1rem;opacity:0.8;"></i>
                            <small style="opacity:0.85;">Unique IPs/Locations</small>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /Stats Row -->

        <!-- Charts Row -->
        <div class="row g-4 mb-4">

            <!-- Browser Breakdown Chart -->
            <div class="col-lg-4 col-md-6">
                <div class="card chart-card h-100">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="mdi mdi-web text-primary"></i>
                        Browser Breakdown
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <canvas id="browserChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Device Type Chart -->
            <div class="col-lg-4 col-md-6">
                <div class="card chart-card h-100">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="mdi mdi-devices text-success"></i>
                        Device Type Split
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <canvas id="deviceChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Monthly Trend (last 6 months) -->
            <div class="col-lg-4 col-md-12">
                <div class="card chart-card h-100">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="mdi mdi-chart-bar text-warning"></i>
                        Monthly Subscriptions
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>

        </div><!-- /Charts Row -->

        <!-- City / IP Table + Subscribers Table -->
        <div class="row g-4 mb-4">

            <!-- IP / Region Table -->
            <div class="col-lg-4">
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="mdi mdi-map-marker-multiple text-danger"></i>
                        Subscriber IPs / Regions
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>IP Address</th>
                                    <th class="text-end">Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($city_stats as $cs): ?>
                                <tr>
                                    <td><i class="mdi mdi-map-marker-outline text-muted me-1"></i><?php echo htmlspecialchars($cs['ip_address']); ?></td>
                                    <td class="text-end"><span class="badge bg-label-primary"><?php echo $cs['cnt']; ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if(empty($city_stats)): ?>
                                <tr><td colspan="2" class="text-center text-muted py-3">No data found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- All Subscribers Table -->
            <div class="col-lg-8">
                <div class="card chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between gap-2">
                        <span><i class="mdi mdi-table text-info me-1"></i> All Subscribed Devices</span>
                        <span class="badge bg-label-info"><?php echo $total_subscribers; ?> total</span>
                    </div>
                    <div class="table-responsive" style="max-height:420px;overflow-y:auto;">
                        <table class="table table-hover mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>#</th>
                                    <th>Device</th>
                                    <th>OS / Browser</th>
                                    <th>Model</th>
                                    <th>IP</th>
                                    <th>Subscribed</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 1; foreach ($devices as $d): ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td>
                                        <?php if($d['device_type'] == 'Mobile'): ?>
                                            <span class="badge bg-info"><i class="fa fa-mobile-screen me-1"></i> Mobile</span>
                                        <?php else: ?>
                                            <span class="badge bg-primary"><i class="fa fa-laptop me-1"></i> Desktop</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><strong><?php echo htmlspecialchars($d['os'] ?? '—'); ?></strong> / <?php echo htmlspecialchars($d['browser'] ?? '—'); ?></td>
                                    <td><code><?php echo htmlspecialchars($d['device_model'] ?? '—'); ?></code></td>
                                    <td><small><?php echo htmlspecialchars($d['ip_address'] ?? '—'); ?></small></td>
                                    <td><small><?php echo date('d M, Y', strtotime($d['created_at'])); ?></small></td>
                                    <td>
                                        <a href="subscribers.php?id=<?php echo $d['id']; ?>&action=delete"
                                           onclick="return confirm('Remove this device?')"
                                           class="btn btn-sm btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if(empty($devices)): ?>
                                <tr><td colspan="7" class="text-center py-4 text-muted">No devices subscribed yet.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div><!-- /container -->
</div><!-- /content-wrapper -->

<?php
// Fetch monthly data for last 6 months
$monthly_data = [];
$monthly_labels = [];
for ($i = 5; $i >= 0; $i--) {
    $month_label = date('M Y', strtotime("-$i months"));
    $monthly_labels[] = $month_label;
    $cnt = $pdo->query("SELECT COUNT(*) FROM subscriber_devices WHERE YEAR(created_at)=YEAR(DATE_SUB(NOW(), INTERVAL $i MONTH)) AND MONTH(created_at)=MONTH(DATE_SUB(NOW(), INTERVAL $i MONTH))")->fetchColumn();
    $monthly_data[] = (int)$cnt;
}
$monthly_labels_json = json_encode($monthly_labels);
$monthly_data_json   = json_encode($monthly_data);
?>

<script>
const paletteDonut = ['#667eea','#f093fb','#4facfe','#11998e','#f5576c','#fca61f','#a18cd1','#00b09b'];
const palettePie   = ['#764ba2','#4facfe','#38ef7d','#f5576c','#fca61f','#667eea','#11998e'];

// Browser Chart (Doughnut)
const bCtx = document.getElementById('browserChart').getContext('2d');
new Chart(bCtx, {
    type: 'doughnut',
    data: {
        labels: <?php echo $browser_labels; ?>,
        datasets: [{
            data: <?php echo $browser_counts; ?>,
            backgroundColor: paletteDonut,
            borderWidth: 3,
            borderColor: '#fff',
            hoverOffset: 10
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { padding: 12, font: { size: 12 } } },
            tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed} devices` } }
        },
        cutout: '62%'
    }
});

// Device Chart (Pie)
const dCtx = document.getElementById('deviceChart').getContext('2d');
new Chart(dCtx, {
    type: 'pie',
    data: {
        labels: <?php echo $device_labels; ?>,
        datasets: [{
            data: <?php echo $device_counts; ?>,
            backgroundColor: palettePie,
            borderWidth: 3,
            borderColor: '#fff',
            hoverOffset: 10
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { padding: 12, font: { size: 12 } } }
        }
    }
});

// Monthly Bar Chart
const mCtx = document.getElementById('monthlyChart').getContext('2d');
new Chart(mCtx, {
    type: 'bar',
    data: {
        labels: <?php echo $monthly_labels_json; ?>,
        datasets: [{
            label: 'New Subscribers',
            data: <?php echo $monthly_data_json; ?>,
            backgroundColor: 'rgba(102, 126, 234, 0.8)',
            borderRadius: 8,
            borderSkipped: false
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,0.05)' } },
            x: { grid: { display: false } }
        }
    }
});
</script>

<?php require('include/footer.php'); ?>
