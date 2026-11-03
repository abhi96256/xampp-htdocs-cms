<?php
include('./function/function.php');
check_session();

if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = intval($_GET['id']);
    $pdo = getPDOObject();
    
    // Get resume filename to delete file
    $app = sqlfetch("SELECT resume FROM job_applications WHERE id='$id'");
    if (count($app) > 0 && !empty($app[0]['resume'])) {
        $file = '../upload/resumes/' . $app[0]['resume'];
        if (file_exists($file)) {
            unlink($file);
        }
    }
    
    $pdo->query("DELETE FROM job_applications WHERE id='$id'");
    echo "<script>window.open('job_applications.php','_self')</script>";
}

require('include/header.php');
?>
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Manage Career /</span> Job Applications</h4>

        <div class="card">
            <h5 class="card-header d-flex align-items-center gap-2">
                <i class="mdi mdi-briefcase-account me-1"></i> Received Applications
                <span class="badge bg-label-primary ms-2"><?php echo count(sqlfetch("SELECT id FROM job_applications")); ?> Total</span>
            </h5>
            <div class="card-datatable dataTable_select text-nowrap table-responsive">
                <table id="tableJobApp" class="display table table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Applicant Name</th>
                            <th>Contact Details</th>
                            <th>Job ID</th>
                            <th>Location</th>
                            <th>Resume</th>
                            <th>Message</th>
                            <th>Applied Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 1;
                        $data = sqlfetch("SELECT * FROM job_applications ORDER BY applied_at DESC");
                        if (count($data) > 0):
                            foreach ($data as $app): ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td><strong><?php echo htmlspecialchars($app['name']); ?></strong></td>
                                <td>
                                    <small><i class="mdi mdi-email-outline me-1"></i><?php echo htmlspecialchars($app['email']); ?></small><br>
                                    <small><i class="mdi mdi-phone-outline me-1"></i><?php echo htmlspecialchars($app['phone']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($app['job_id']); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($app['city']); ?>
                                    <?php if(!empty($app['lat_long'])): ?>
                                        <br><a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($app['lat_long']); ?>" target="_blank" class="btn btn-xs btn-outline-info mt-1" style="font-size: 10px; padding: 2px 5px;">
                                            <i class="mdi mdi-map-marker"></i> View Map
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($app['resume'])): ?>
                                        <a href="../upload/resumes/<?php echo $app['resume']; ?>" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="mdi mdi-file-pdf-box me-1"></i> View Resume
                                        </a>
                                    <?php else: ?>
                                        <span class="badge bg-label-secondary">No Resume</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#msgModal<?php echo $app['id']; ?>">
                                        <i class="mdi mdi-message-text-outline"></i> Read
                                    </button>
                                    <!-- Message Modal -->
                                    <div class="modal fade" id="msgModal<?php echo $app['id']; ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="mdi mdi-account-circle me-2"></i>
                                                        Message from <?php echo htmlspecialchars($app['name']); ?>
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <?php echo nl2br(htmlspecialchars($app['message'])); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo date('d M Y, h:i A', strtotime($app['applied_at'])); ?></td>
                                <td>
                                    <a href="job_applications.php?id=<?php echo $app['id']; ?>&action=delete"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this application?');">
                                        <i class="mdi mdi-trash-can-outline"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach;
                        else: ?>
                            <tr><td colspan="8" class="text-center text-muted py-4">No applications received yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php require('include/footer.php'); ?>
</div>
