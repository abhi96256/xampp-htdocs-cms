<?php
$umessage = '';
include('./function/function.php');
check_session();

$pdo = getPDOObject();

// Handle Delete
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $pdo->query("DELETE FROM chatbot_leads WHERE id='$id'");
    echo "<script>window.location.href='chatbot_leads.php';</script>";
}

require('include/header.php');
?>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Chatbot Leads</h4>

        <?php echo $umessage; ?>

        <div class="card">
            <h5 class="card-header">Chatbot Interactions</h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Date</th>
                            <th>Customer Name</th>
                            <th>Contact Info</th>
                            <th>Interest/Budget</th>
                            <th>Location</th>
                            <th>Message</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 1;
                        $data = sqlfetch("SELECT * FROM `chatbot_leads` ORDER BY id DESC");
                        foreach ($data as $e) { ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td><?php echo date('d M, Y', strtotime($e['tdate'])); ?></td>
                                <td><strong><?php echo $e['name']; ?></strong></td>
                                <td>
                                    <i class="fa fa-envelope text-primary"></i> <?php echo $e['email']; ?><br>
                                    <i class="fa fa-phone text-success"></i> <?php echo $e['phone']; ?>
                                </td>
                                <td>
                                    <span class="badge bg-label-info"><?php echo $e['interest']; ?></span><br>
                                    <span class="badge bg-label-warning"><?php echo $e['budget']; ?></span>
                                </td>
                                <td>
                                    <?php echo $e['city']; ?>
                                    <?php if(!empty($e['lat_long'])): ?>
                                        <br><a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($e['lat_long']); ?>" target="_blank" class="btn btn-xs btn-outline-info mt-1" style="font-size: 10px; padding: 2px 5px;">
                                            <i class="fa fa-map-marker-alt"></i> View Map
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td style="white-space: normal; min-width: 250px;">
                                    <?php echo nl2br($e['message']); ?>
                                </td>
                                <td>
                                    <a href="chatbot_leads.php?id=<?php echo $e['id']; ?>&action=delete" 
                                       onclick="return confirm('Delete this chatbot lead?')" 
                                       class="btn btn-sm btn-icon btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if (empty($data)) { echo "<tr><td colspan='7' class='text-center'>No automated leads found yet.</td></tr>"; } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require('include/footer.php'); ?>
