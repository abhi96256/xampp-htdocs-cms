<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
$umessage = '';

include('./function/function.php');
check_session();

$pdo = getPDOObject();

// Ensure table exists
$pdo->exec("CREATE TABLE IF NOT EXISTS location_developers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    location VARCHAR(255) NOT NULL,
    category ENUM('Residential', 'Commercial') DEFAULT 'Residential',
    property_type VARCHAR(100) DEFAULT '',
    developer VARCHAR(255) NOT NULL,
    site_name VARCHAR(255) DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Handle Form Submission
if (isset($_POST['save_data'])) {
    $location = trim($_POST['location']);
    $developer_input = $_POST['developer']; // This is now an array
    $id = isset($_POST['id']) ? $_POST['id'] : '';

    if (!empty($id)) {
        // Edit existing (takes first element of array)
        $dev_value = is_array($developer_input) ? $developer_input[0] : $developer_input;
        $q = $pdo->prepare("UPDATE location_developers SET location = :location, developer = :developer WHERE id = :id");
        $q->execute(array(':location' => $location, ':developer' => $dev_value, ':id' => $id));
        $umessage = '<div class="alert alert-success">Mapping Updated Successfully!</div>';
    } else {
        // Add New (loop through array)
        $count = 0;
        foreach ($developer_input as $dev) {
            $dev = trim($dev);
            if (!empty($dev)) {
                $q = $pdo->prepare("INSERT INTO location_developers (location, developer) VALUES (:location, :developer)");
                $q->execute(array(':location' => $location, ':developer' => $dev));
                $count++;
            }
        }
        $umessage = '<div class="alert alert-success">'.$count.' Developer(s) mapped to '.$location.' successfully!</div>';
    }
}

// Handle Delete
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $pdo->query("DELETE FROM location_developers WHERE id='$id'");
    echo "<script>window.location.href='location_developers.php';</script>";
}

// Fetch data for editing
$edit_data = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit') {
    $id = $_GET['id'];
    $edit_results = sqlfetch("SELECT * FROM location_developers WHERE id='$id'");
    if(count($edit_results) > 0) $edit_data = $edit_results[0];
}

require('include/header.php');
?>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Admin /</span> City-Developer Mapping</h4>

        <?php echo $umessage; ?>

        <div class="alert alert-info">
            <strong>Info:</strong> Use this page to link Developers to specific Cities. These links will appear in the Developer dropdown when adding/editing products.
        </div>

        <!-- Add/Edit Form -->
        <div class="card mb-4">
            <h5 class="card-header"><?php echo $edit_data ? 'Edit' : 'Add New'; ?> Mapping</h5>
            <div class="card-body">
                <form action="location_developers.php" method="POST">
                    <?php if ($edit_data) { ?>
                        <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
                    <?php } ?>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">City Name</label>
                            <input type="text" name="location" class="form-control" value="<?php echo $edit_data ? $edit_data['location'] : ''; ?>" placeholder="e.g. Noida, Gurgaon" required>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Developer Name(s)</label>
                            <div id="developer-container">
                                <?php if($edit_data) { ?>
                                    <div class="input-group mb-2">
                                        <input type="text" name="developer[]" class="form-control" value="<?php echo $edit_data['developer']; ?>" placeholder="Enter Developer Name" required>
                                    </div>
                                <?php } else { ?>
                                    <div class="input-group mb-2">
                                        <input type="text" name="developer[]" class="form-control" placeholder="Enter Developer Name" required>
                                        <button type="button" class="btn btn-outline-success" onclick="addDeveloperField()">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <script>
                        function addDeveloperField() {
                            const container = document.getElementById('developer-container');
                            const div = document.createElement('div');
                            div.className = 'input-group mb-2';
                            div.innerHTML = `
                                <input type="text" name="developer[]" class="form-control" placeholder="Enter Developer Name" required>
                                <button type="button" class="btn btn-outline-danger" onclick="removeField(this)">
                                    <i class="fa fa-minus"></i>
                                </button>
                            `;
                            container.appendChild(div);
                        }

                        function removeField(btn) {
                            btn.closest('.input-group').remove();
                        }
                    </script>

                    <div class="mt-2">
                        <button type="submit" name="save_data" class="btn btn-primary">
                            <?php echo $edit_data ? 'Update Mapping' : 'Save Mapping'; ?>
                        </button>
                        <?php if ($edit_data) { ?>
                            <a href="location_developers.php" class="btn btn-secondary">Cancel</a>
                        <?php } ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Saved Mappings List -->
        <?php
        $distinct_cities = sqlfetch("SELECT COUNT(DISTINCT location) as total FROM location_developers");
        $city_count = $distinct_cities[0]['total'];
        ?>
        <div class="card">
            <h5 class="card-header">Existing Mappings (Total Unique Cities: <?php echo $city_count; ?>)</h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>City</th>
                            <th>Developer</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 1;
                        $list = sqlfetch("SELECT * FROM location_developers ORDER BY location ASC, developer ASC");
                        foreach ($list as $row) { ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td><strong><?php echo $row['location']; ?></strong></td>
                                <td><?php echo $row['developer']; ?></td>
                                <td>
                                    <a href="location_developers.php?id=<?php echo $row['id']; ?>&action=edit" class="btn btn-sm btn-info">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="location_developers.php?id=<?php echo $row['id']; ?>&action=delete" 
                                       onclick="return confirm('Are you sure?')" 
                                       class="btn btn-sm btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if(count($list) == 0) echo "<tr><td colspan='4' class='text-center'>No mappings found.</td></tr>"; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require('include/footer.php'); ?>
