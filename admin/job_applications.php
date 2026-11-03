<?php
$umessage = '';
include('./function/function.php');
check_session();

if ($_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $pdo = getPDOObject();
    
    // Get file name to delete it
    $app = sqlfetch("SELECT resume FROM job_applications WHERE id='$id'");
    if(count($app) > 0) {
        $file = '../upload/resumes/' . $app[0]['resume'];
        if(file_exists($file)) {
            unlink($file);
        }
    }
    
    $sql_del = $pdo->query("DELETE FROM job_applications WHERE id='$id'");
    echo "<script>window.open('job_applications.php','_self')</script>";
}

require('include/header.php');
?>
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Manage Career /</span> Job Applications</h4>

        <?php echo $umessage; ?>

        <div class="card">
            <h5 class="card-header">Received Applications</h5>
            <div class="card-datatable dataTable_select text-nowrap table-responsive">
                <table id="tableID" class="display dt-select-table table table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>S. No.</th>
                            <th>Applicant Name</th>
                            <th>Contact Details</th>
                            <th>Applied For (Job ID)</th>
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
                        foreach ($data as $app) { ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td><strong><?php echo htmlspecialchars($app['name']); ?></strong></td>
                                <td>
                                    <i class="fa fa-envelope me-1"></i> <?php echo htmlspecialchars($app['email']); ?><br>
                                    <i class="fa fa-phone me-1"></i> <?php echo htmlspecialchars($app['phone']); ?>
                                </td>
                                <td><?php echo $app['job_id']; ?></td>
                                <td>
                                    <?php if (!empty($app['resume'])): ?>
                                        <a href="../upload/resumes/<?php echo $app['resume']; ?>" target="_blank" class="btn btn-sm btn-info text-white">
                                            <i class="fa fa-file-pdf me-1"></i> View Resume
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">No Resume</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#msgModal<?php echo $app['id']; ?>">
                                        Read Message
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="msgModal<?php echo $app['id']; ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Message from <?php echo htmlspecialchars($app['name']); ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                    <a href="job_applications.php?id=<?php echo $app['id']; ?>&action=delete" class="btn btn-xs btn-danger" onclick="return confirm('Delete this application?');">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php require('include/footer.php'); ?>
</div>
