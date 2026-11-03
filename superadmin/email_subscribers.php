<?php
$umessage = '';
include('./function/function.php');
check_session();

$pdo = getPDOObject();

// Ensure table exists
$pdo->exec("CREATE TABLE IF NOT EXISTS email_subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    source VARCHAR(100),
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Handle Delete
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $pdo->query("DELETE FROM email_subscriptions WHERE id='$id'");
    echo "<script>window.location.href='email_subscribers.php';</script>";
}

require('include/header.php');
?>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Admin /</span> iOS & Email Subscribers</h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Email Subscriptions</h5>
                <div class="badge bg-label-primary"><?php echo $pdo->query("SELECT COUNT(*) FROM email_subscriptions")->fetchColumn(); ?> Total</div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Date</th>
                            <th>Email Address</th>
                            <th>Source</th>
                            <th>IP Address</th>
                            <th>Device Info</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 1;
                        $data = sqlfetch("SELECT * FROM `email_subscriptions` ORDER BY id DESC");
                        foreach ($data as $e) { 
                            $ua = $e['user_agent'];
                            $device = "Unknown";
                            if (stripos($ua, 'iPhone') !== false) $device = "iPhone";
                            elseif (stripos($ua, 'iPad') !== false) $device = "iPad";
                            elseif (stripos($ua, 'Android') !== false) $device = "Android";
                            elseif (stripos($ua, 'Windows') !== false) $device = "Windows";
                            elseif (stripos($ua, 'Macintosh') !== false) $device = "Mac";
                            ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td><?php echo date('d M, Y', strtotime($e['created_at'])); ?></td>
                                <td><strong><?php echo htmlspecialchars($e['email']); ?></strong></td>
                                <td><span class="badge bg-label-info"><?php echo htmlspecialchars($e['source']); ?></span></td>
                                <td><code><?php echo htmlspecialchars($e['ip_address']); ?></code></td>
                                <td>
                                    <small class="text-muted"><?php echo $device; ?></small>
                                </td>
                                <td>
                                    <a href="email_subscribers.php?id=<?php echo $e['id']; ?>&action=delete" 
                                       onclick="return confirm('Delete this subscriber?')" 
                                       class="btn btn-sm btn-icon btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if (empty($data)) { echo "<tr><td colspan='7' class='text-center'>No subscribers found.</td></tr>"; } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require('include/footer.php'); ?>
