<?php
session_start(); // Start the session

// Check if admin_id exists in session
$admin_id = isset($_SESSION['admin_id']) ? intval($_SESSION['admin_id']) : null;

if ($admin_id === null) {
    // Optionally, you can redirect the user or show a generic message
    // For example, redirecting to login page
    header("Location: login.php");
    exit; // Make sure the script doesn't continue
}

try {
    // Database connection parameters
    $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '';
    if (strpos($host, 'a2prealtech.com') !== false) {
        $dsn = "mysql:host=localhost;dbname=u615712904_a2p;charset=utf8mb4";
        $username = "u615712904_a2p";
        $password = "VermaA2p@#9717";
    } else {
        $dsn = "mysql:host=localhost;dbname=u435351083_cms;charset=utf8mb4";
        $username = "u435351083_jms";
        $password = "Maydivjms1@3";
    }

    // Create a new PDO instance
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Fetch admin name from subadmin table using session admin_id
    $stmt = $pdo->prepare("SELECT name FROM `subadmin` WHERE id = :admin_id");
    $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
    $stmt->execute();

    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        // Handle the case when no admin is found
        $_SESSION['admin_name'] = "Admin";
    } else {
        // Set the admin name in the session to use later in the HTML
        $_SESSION['admin_name'] = $admin['name'];
    }

} catch (Exception $e) {
    // Handle exceptions silently or with a generic error message
    $_SESSION['admin_name'] = "Admin"; // Default to a generic name
}
?>



<?php
$umessage = '';
include('./function/function.php');
check_session();

// Function to process form data and insert into database
if (isset($_POST['addclient'])) {
    $id = 0;
    extract($_POST);
    $pdo = getPDOObject();

    // Check if the offer already exists
    $sql = $pdo->prepare("SELECT * FROM `offer` WHERE name LIKE :name");
    $sql->execute([':name' => $name]);
    $num = $sql->rowCount();

    $photos = $_FILES['photo']['name'];
    $Filename = '';

    if (!$num) {
        if ($photos) {
            $Filename = date('dmyhis') . basename($_FILES['photo']['name']);
            $target = "../upload/" . $Filename;
            move_uploaded_file($_FILES['photo']['tmp_name'], $target);
        }

        // Insert data into database
        $q = $pdo->prepare("INSERT INTO `offer` VALUES(:id,:name, :photo,:des,:des1,:meta_title,:meta_keyword,:meta_description,  :by_blog,  :fld_order, :actstat)");
        $q->execute([
            ':id' => $id,
            ':name' => $name,
            ':photo' => $Filename,
            ':des' => $des,
            ':des1' => $des1,
            ':meta_title' => $meta_title,
            ':meta_keyword' => $meta_keyword,
            ':meta_description' => $meta_description,
            ':by_blog' => $by_blog,
            ':fld_order' => $fld_order,
            ':actstat' => $actstat
        ]);

        $affected_rows = $q->rowCount();
        if ($affected_rows)
            $umessage = '<div class="alert alert-success" role="alert">
							<strong></strong>Added Successfully
					   </div>';
    } else {
        $umessage = '<div class="alert alert-danger" role="alert">Duplicate Entry!!! Code Already Exists </div>';
    }
}

// Deleting a single item
if ($_GET['action'] == 'delete') {
    $pdo = getPDOObject();
    $q = $pdo->prepare("DELETE FROM offer WHERE id = :id");
    $q->execute([':id' => $_GET['id']]);
    echo "<script>window.open('offer.php','_self')</script>";
}

// Deleting multiple items
if (isset($_POST['deleteall'])) {
    $arr = $_POST['ids'];
    if (count($arr)) {
        $str_rest_refs = implode(",", $arr);
        $data = sqlfetch("SELECT * FROM `offer` WHERE id IN ($str_rest_refs)");
        foreach ($data as $client) {
            $img_path = '../upload/' . $client['photo'];
            if (file_exists($img_path)) {
                @unlink($img_path);
            }
        }
        $pdo = getPDOObject();
        $q = $pdo->query("DELETE FROM `offer` WHERE id IN ($str_rest_refs)");
        if ($q)
            $umessage = '<div class="alert alert-success" role="alert">
							<strong></strong>Deleted Successfully
					   </div>';
    } else {
        $umessage = '<div class="alert alert-danger" role="alert">
							<strong></strong>Please Select Items to perform this action
					   </div>';
    }
}

// Activating multiple items
if (isset($_POST['activate'])) {
    $arr = $_POST['ids'];
    if (count($arr)) {
        $str_rest_refs = implode(",", $arr);
        $pdo = getPDOObject();
        $q = $pdo->query("UPDATE `offer` SET actstat='1' WHERE id IN ($str_rest_refs)");
        if ($q)
            $umessage = '<div class="alert alert-success" role="alert">
							<strong></strong>Activated Successfully
					   </div>';
    } else {
        $umessage = '<div class="alert alert-danger" role="alert">
							<strong></strong>Please Select Items to perform this action
					   </div>';
    }
}

// Deactivating multiple items
if (isset($_POST['deactivate'])) {
    $arr = $_POST['ids'];
    if (count($arr)) {
        $str_rest_refs = implode(",", $arr);
        $pdo = getPDOObject();
        $q = $pdo->query("UPDATE `offer` SET actstat='0' WHERE id IN ($str_rest_refs)");
        if ($q)
            $umessage = '<div class="alert alert-success" role="alert">
							<strong></strong>Deactivated Successfully
					   </div>';
    } else {
        $umessage = '<div class="alert alert-danger" role="alert">
							<strong></strong>Please Select Items to perform this action
					   </div>';
    }
}

// Processing edited data
if (isset($_POST['editdone'])) {
    extract($_POST);
    $Filename = $prevphoto;
    $photos = $_FILES['photo']['name'];
    if ($photos != '') {
        $Filename = date('dmyhis') . basename($_FILES['photo']['name']);
        $target = "../upload/" . $Filename;
        move_uploaded_file($_FILES['photo']['tmp_name'], $target);
        $img_path = '../upload/' . $prevphoto;
        if (file_exists($img_path)) {
            @unlink($img_path);
        }
    }

    $pdo = getPDOObject();
    $q = $pdo->prepare("UPDATE `offer` SET 
            name=?,
            photo=?,
            des=?,
            des1=?,
            meta_title=?,
            meta_keyword=?,
            meta_description=?,
            by_blog=?,
            fld_order=?,
            actstat=?
            WHERE id=?");
    $q->execute([$name, $Filename, $des, $des1, $meta_title, $meta_keyword, $meta_description, $by_blog,  $fld_order, $actstat, $pid]);
    $affected_rows = $q->rowCount();
    if ($affected_rows)
        $umessage = '<div class="alert alert-primary alert-dismissible" role="alert">
                       Updated Successfully!
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
      </button>
    </div>';
}

// Function to display client form
function client_form($pid = '0', $name = '', $photo = '', $des = '', $des1 = '', $meta_title = '', $meta_keyword = '', $meta_description = '',   $by_blog = '',   $fld_order = '0', $actstat = '', $formname = 'addclient')
{ ?>


    <form action="offer.php" method="post" enctype="multipart/form-data">
        
        
        
        <div class="form theme-form">
            <input type="hidden" name="pid" value="<?php echo $pid; ?>" />
            <input type="hidden" name="prevphoto" value="<?php echo $photo; ?>" />
            <div class="row">

                <div class="col-lg-4  mt-3">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">@</span>
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" name="name" placeholder="Name" aria-label="Name" value="<?php echo $name; ?>" />
                            <label for="basic-addon11">Name </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mt-3">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">@</span>
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" name="meta_title" placeholder="Meta Title" aria-label="Meta Title" value="<?php echo $meta_title; ?>" />
                            <label for="basic-addon11">Meta Title</label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mt-3">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">@</span>
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" name="meta_keyword" placeholder="Meta Keyword" aria-label="Meta Keyword" value="<?php echo $meta_keyword; ?>" />
                            <label for="basic-addon11">Meta Keyword</label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mt-3">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">@</span>
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" name="meta_description" placeholder="Meta Description" aria-label="Meta Description" value="<?php echo $meta_description; ?>" />
                            <label for="basic-addon11">Meta Description</label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mt-3">

                    <?php 
                        $preview_photo = $photo;
                        if (!$preview_photo) {
                            $logo_info = sqlfetch("SELECT photo FROM address WHERE id=1");
                            $preview_photo = count($logo_info) ? $logo_info[0]['photo'] : '';
                        }
                    ?>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">@</span>
                        <div class="form-floating form-floating-outline">
                            <input type="file" class="form-control" name="photo" aria-label="Upload">
                            <label for="basic-addon11">Add Photo</label>
                        </div>
                    </div>
                    <?php if ($preview_photo): ?>
                    <div class="mt-2 text-center">
                        <img src="../upload/<?php echo $preview_photo; ?>" style="border-radius: 8px; width: 50px; height: 50px; object-fit: cover;" title="<?php echo $photo ? 'Current main photo' : 'Default Logo'; ?>">
                    </div>
                    <?php endif; ?>
                </div>

                <div class="col-lg-4  mt-3">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">@</span>
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" name="des1" placeholder="Name" aria-label="Name" value="<?php echo $des1; ?>" />
                            <label for="basic-addon11">Additinol Text</label>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4  mt-3">
                    <div class="form-floating form-floating-outline">
                        <select class="form-select" name="actstat" id="selectError" data-rel="chosen">
                            <option <?php if (($actstat) == '1') echo 'selected'; ?> value="1">Active</option>
                            <option <?php if (($actstat) == '0') echo 'selected'; ?> value="0">Inactive</option>
                        </select>
                        <label for="floatingSelect">Status</label>
                    </div>
                </div>
                <div class="col-lg-4 mt-3">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">@</span>
                        <div class="form-floating form-floating-outline">
                            <input type="number" class="form-control" placeholder="Sort Order" aria-label="Description" name="fld_order" value="<?php echo $fld_order; ?>" />
                            <label for="basic-addon11">Sort Order</label>
                        </div>
                    </div>
                </div>
                
                        <div class="col-lg-4 mt-3">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select" name="by_blog" id="selectError" data-rel="chosen">
                                    <option <?php if ($by_blog == '0') echo 'selected'; ?> value="0">Select Here</option>
                                    <option <?php if ($by_blog == htmlspecialchars($_SESSION['admin_name'])) echo 'selected'; ?>
                                        value="<?php echo htmlspecialchars($_SESSION['admin_name']); ?>">
                                        <?php echo htmlspecialchars($_SESSION['admin_name']); ?>
                                    </option>
                                </select>

                                <label for="selectError">Select Author</label>
                            </div>
                        </div>
                
                
                
                <div class="col-lg-12  mt-3">
                    <div class="input-group input-group-merge">

                        <div class="form-floating form-floating-outline">
                            <label for="basic-addon11">Description Text</label>
                            <br><br>
                            <textarea id="mytextarea" name="des" cols="60" rows="10"><?php echo $des; ?></textarea>
                        </div>
                    </div>
                </div>



                <div class="col-lg-12  mt-5">
                    <div class="input-group input-group-merge">
                        <button class="btn btn-primary waves-effect  waves-light" type="submit" value="Submit" name="<?php echo $formname; ?>">
                            <span class=" align-middle">Submit</span>
                        </button>
                    </div>
                </div>

            </div>



        </div>
    </form>
<?php
}
?>


<?php require('include/header.php'); ?>
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">


        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Home/</span>Banner Settings  </h4>


        <?php echo $umessage; ?>


        <?php
        if (isset($_GET['edit']) and ($_GET['edit'] == 'true')) { ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <h5 class="card-header">Manage Here</h5>
                        <div class="card-body demo-vertical-spacing demo-only-element z4">
                            <div class="row">
                               <?php
                                $pid = $_GET['pid'];
                                $productdata = sqlfetch("SELECT * FROM `offer` where id='$pid' ");
                                foreach ($productdata as $product) {
                                    extract($product);
                                    client_form($pid, $name, $photo, $des, $des1, $meta_title, $meta_keyword, $meta_description, $by_blog, $fld_order, $actstat, $formname = 'editdone');
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        <?php
        } else {
        ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <h5 class="card-header">Manage Here</h5>
                        <div class="card-body demo-vertical-spacing demo-only-element z4">
                            <div class="row">
                                <?php client_form(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>









            <div class="card">
                <div class="card-datatable dataTable_select text-nowrap table-responsive">
                    <table id="tableID" class="display dt-select-table table table-bordered" style="width:100%">
                        <thead>
                            <tr>
                            <tr>
                                <th>S. No.</th>
                                <th>Name</th>
                                <th>Photo</th>
                                <th>Sort Order</th>
                                <th>Status</th>
                                <th>Edit</th>
                                <th>Delet</th>
                            </tr>
                            </tr>
                        </thead>
                        <tbody>
                                    <?php
                                  $count = 1;
                                        $data = sqlfetch("SELECT * FROM `offer` ORDER BY id DESC");
                                        
                                        foreach ($data as $menu) { ?>
                                            <tr>
                                                <td><?php echo $count++; ?></td>
                                                <td><?php echo $menu['name']; ?></td>
                                                <td>
                                                    <?php 
                                                    $disp_photo = $menu['photo'];
                                                    if (empty($disp_photo)) {
                                                        $logo_data = sqlfetch("SELECT photo FROM address WHERE id=1");
                                                        $disp_photo = count($logo_data) ? $logo_data[0]['photo'] : '';
                                                    }
                                                    ?>
                                                    <img style="height: 32px;width: 32px;object-fit: cover;border-radius: 4px;" src="../upload/<?php echo $disp_photo; ?>" class="img-responsive">
                                                </td>
                                                <td><?php echo $menu['fld_order']; ?></td>
                                                <td> <?php echo get_active_status_text($menu['actstat']); ?></td>
                                                <td>
                                                    <a class="ajax-link" href="offer.php?&pid=<?php echo $menu['id']; ?>&edit=true">
                                                        <button type="button" class="btn btn-xs btn-danger pull-right" name="editclient"><i class="fa fa-pencil"></i> </button>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="offer.php?id=<?php echo $menu['id']; ?>&action=delete" onclick="return confirm('Are you sure you want to delete this item?');">
                                                        <button type="submit" class="btn btn-xs btn-danger pull-right" style="margin:0px 10px;" name="delete"><i class="fa-solid fa-trash"></i></button>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>



                        </tbody>
                    </table>
                </div>
            </div>










        <?php } ?>


        <?php require('include/footer.php'); ?>
