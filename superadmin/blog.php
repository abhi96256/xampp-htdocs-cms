<?php
$umessage = '';
include('./function/function.php');
include('./function/push_helper.php');
check_session();
if (isset($_POST['addclient'])) {
    $id = 0;
    extract($_POST);
    $type = 1;
    $pdo = getPDOObject();
    // $sql = $pdo->query("SELECT * FROM `blog` where  name LIKE '$name'");
    // $num = $sql->rowCount();
    $num = 0;
    $photos = $_FILES['photo']['name'];
    $Filename = '';
    if (!$num) {
        if ($photos) {
            $Filename = date('dmyhis') . basename($_FILES['photo']['name']);

            $target = "../upload/" . $Filename;
            move_uploaded_file($_FILES['photo']['tmp_name'], $target);    //Tells you if its all ok	
        }
        $q = $pdo->prepare("INSERT into `blog` values(:id,:name, :photo,:des,:des1, :slug, :fld_order, :actstat)");
        $q->execute(array(
            ':id' => $id,

            ':name' => $name,
            ':photo' => $Filename,
            ':des' => $des,
            ':des1' => $des1,
            ':slug' => $slug,
            ':fld_order' => $fld_order,
            ':actstat' => $actstat
        ));
        $affected_rows = $q->rowCount();
        if ($affected_rows) {
            $umessage = '<div class="alert alert-success" role="alert">
							<strong></strong>Added Successfully
						   </div>';
            
            // Send Push Notification if checked
            if (isset($_POST['send_notif']) && $_POST['send_notif'] == '1') {
                $notif_title = !empty($_POST['notif_title']) ? $_POST['notif_title'] : "New Job Opportunity: " . $name;
                $notif_msg = !empty($_POST['notif_msg']) ? $_POST['notif_msg'] : "We have a new career opening! View details and apply now.";
                $notif_custom_link = !empty($_POST['notif_link']) ? $_POST['notif_link'] : SITE_URL . "career_detail.php?id=" . urlencode($name);

                $notif_img = $Filename ? SITE_URL . "upload/" . $Filename : '';
                sendGlobalPushNotification($notif_title, $notif_msg, $notif_custom_link, $notif_img);
            }
        }
    } else {
        $umessage = '<div class="alert alert-danger" role="alert">Duplicate Entry!!! Code Already Exists </div> ';
    }
}


if ($_GET['action'] == 'delete') {
    $sql_del = sqlfetch("delete from blog where id='" . $_GET['id'] . "'");
    echo "<script>window.open('blog.php','_self')</script>";
}


if (isset($_POST['deleteall'])) {
    $arr = $_POST['ids'];
    if (count($arr)) {
        $str_rest_refs = implode(",", $arr);

        $data = sqlfetch("select * from `blog` where id in ($str_rest_refs)");
        foreach ($data as $client) {
            $img_path = '../upload/' . $client['photo'];
            if (file_exists($img_path)) {
                @unlink($img_path);
            }
        }

        $pdo = getPDOObject();
        $q = $pdo->query("DELETE FROM `blog` WHERE id in ($str_rest_refs)");
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

if (isset($_POST['activate'])) {
    $arr = $_POST['ids'];
    if (count($arr)) {
        $str_rest_refs = implode(",", $arr);
        $pdo = getPDOObject();
        $q = $pdo->query("UPDATE `client` SET actstat='1' WHERE id in ($str_rest_refs)");
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

if (isset($_POST['deactivate'])) {
    $arr = $_POST['ids'];
    if (count($arr)) {
        $str_rest_refs = implode(",", $arr);
        $pdo = getPDOObject();
        $q = $pdo->query("UPDATE `client` SET actstat='0' WHERE id in ($str_rest_refs)");
        if ($q)
            $umessage = '<div class="alert alert-success" role="alert">
							<strong></strong>DeActivated Successfully
						   </div>';
    } else {
        $umessage = '<div class="alert alert-danger" role="alert">
							<strong></strong>Please Select Items to perform this action
						   </div>';
    }
}

if (isset($_POST['editdone'])) {
    extract($_POST);
    $Filename = $prevphoto;
    $photos = $_FILES['photo']['name'];
    if ($photos != '') {
        $Filename = '';

        $Filename = date('dmyhis') . basename($_FILES['photo']['name']);
        $target = "../upload/" . $Filename;
        move_uploaded_file($_FILES['photo']['tmp_name'], $target);    //Tells you if its all ok	
        $img_path = '../upload/' . $prevphoto;
        if (file_exists($img_path)) {
            @unlink($img_path);
        }
    }

    $pdo = getPDOObject();
    $q = $pdo->prepare("UPDATE `blog` SET 
		
		
		name=?,
		photo=?,
		des=?,
        des1=?,
        slug=?,
		fld_order=?,
		actstat=?
		
		WHERE id=?");
    $q->execute(array($name, $Filename, $des,  $des1, $slug, $fld_order, $actstat, $pid));
    $affected_rows = $q->rowCount();
    if ($affected_rows) {
        $umessage = '<div class="alert alert-primary alert-dismissible" role="alert">
                           Updated Successfully!
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
          </button>
        </div>';

        // Send Push Notification if checked
        if (isset($_POST['send_notif']) && $_POST['send_notif'] == '1') {
            $notif_title = !empty($_POST['notif_title']) ? $_POST['notif_title'] : "Job Opening Updated: " . $name;
            $notif_msg = !empty($_POST['notif_msg']) ? $_POST['notif_msg'] : "We've updated the details for the " . $name . " position. Check it out!";
            $notif_custom_link = !empty($_POST['notif_link']) ? $_POST['notif_link'] : SITE_URL . "career_detail.php?id=" . urlencode($name);

            $notif_img = $Filename ? SITE_URL . "upload/" . $Filename : '';
            sendGlobalPushNotification($notif_title, $notif_msg, $notif_custom_link, $notif_img);
        }
    }
}

function client_form($pid = '0', $name = '', $photo = '', $des = '', $des1 = '', $slug = '', $fld_order = '0', $actstat = '', $formname = 'addclient')
{ ?>
    <form action="blog.php" method="post" enctype="multipart/form-data">
        <div class="form theme-form">
            <input type="hidden" name="pid" value="<?php echo $pid; ?>" />
            <input type="hidden" name="prevphoto" value="<?php echo $photo; ?>" />
            <div class="row">
                <div class="col-lg-12  mt-3">
                    <div class="row">
                        <div class="col-lg-4  mt-4 ">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">@</span>
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Name" aria-label="Name" value="<?php echo $name; ?>" />
                                    <label for="basic-addon11">Name</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4  mt-4 ">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">@</span>
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="slug" name="slug" placeholder="Page Slug" aria-label="Page Slug" value="<?php echo $slug; ?>" />
                                    <label for="basic-addon11">Page Slug</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4  mt-4">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">@</span>
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="name" placeholder="Name " aria-label="Name" value="<?php echo $name; ?>" />
                                    <label for="basic-addon11">Name </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6  mt-4 ">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select" name="actstat" id="selectError" data-rel="chosen">
                                    <option <?php if (($actstat) == '1') echo 'selected'; ?> value="1">Active</option>
                                    <option <?php if (($actstat) == '0') echo 'selected'; ?> value="0">Inactive</option>
                                </select>
                                <label for="floatingSelect">Status</label>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-4">
                            <div class="card p-4 border shadow-none" style="background: #fdf5ff; border-radius: 12px; border: 1px dashed #666cff !important;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-bell-ring-outline text-primary fs-3 me-2"></i>
                                        <div>
                                            <h6 class="mb-0 text-primary">Push Notification Alert</h6>
                                            <p class="mb-0 text-muted small">Notify all job seekers about this opening</p>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input ms-0" type="checkbox" name="send_notif" id="sendNotifCareer" value="1" style="cursor: pointer; width: 3em; height: 1.5em; margin-top: 0;">
                                    </div>
                                </div>

                                <!-- Extra Notification Fields -->
                                <div id="notifFields" style="display:none; border-top: 1px dashed #666cff44; padding-top: 20px; margin-top: 15px;">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" class="form-control border-primary-subtle" name="notif_title" id="notif_title" placeholder="Notification Title">
                                                <label>Notification Title</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" class="form-control border-primary-subtle" name="notif_link" id="notif_link" placeholder="Custom Link (Optional)">
                                                <label>Custom Link (Optional)</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-floating form-floating-outline">
                                                <textarea class="form-control border-primary-subtle" name="notif_msg" id="notif_msg" placeholder="Message Body" style="height: 80px"></textarea>
                                                <label>Notification Message / Body</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mt-4">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">@</span>
                                <div class="form-floating form-floating-outline">
                                    <input type="number" class="form-control" placeholder="Sort Order" aria-label="Description" name="fld_order" value="<?php echo $fld_order; ?>" />
                                    <label for="basic-addon11">Sort Order</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-3">
                            <div class="input-group input-group-merge">

                                <div class="form-floating form-floating-outline">
                                    <label for="basic-addon11">Description Text</label>
                                    <br><br>
                                    <textarea  class="page_data editor" id="mytextarea" name="des" cols="60" rows="10"><?php echo $des; ?></textarea>
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

                <div class="col-lg-6  mt-3" style="display: none;">
                    <div class="input-group input-group-merge">

                        <div class="form-floating form-floating-outline">
                            <label for="basic-addon11">Description Text</label>
                            <br><br>
                            <textarea class="page_data editor" name="des1" cols="60" rows="10"><?php echo $des1; ?></textarea>
                        </div>
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


        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Home/</span> </h4>


        <?php echo $umessage; ?>


        <?php
        if (isset($_GET['edit']) and ($_GET['edit'] == 'true')) { ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <h5 class="card-header">Manage Here / Career</h5>
                        <div class="card-body demo-vertical-spacing demo-only-element z4">
                            <div class="row">
                                <?php
                                $pid = $_GET['pid'];
                                $productdata = sqlfetch("SELECT * FROM `blog` where id='$pid' ");
                                foreach ($productdata as $product) {
                                    extract($product);
                                    client_form($pid, $name, $photo, $des, $des1,  $slug, $fld_order, $actstat, $formname = 'editdone');
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
                        <h5 class="card-header">Manage Here / Career</h5>
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
                            $data = sqlfetch("SELECT * FROM `blog`  order by fld_order");
                            foreach ($data as $menu) { ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td><?php echo $menu['name']; ?></td>
                                    <td><img style="height: 32px;width: 32px;" src="../upload/<?php echo $menu['photo']; ?>" class="img-responsive"></td>

                                    <td><?php echo $menu['fld_order']; ?></td>

                                    <td> <?php echo get_active_status_text($menu['actstat']); ?></td>
                                    <td>
                                        <!-- <input class="checkhour" name="ids[]" value="<?php echo $menu['id']; ?>" type="checkbox" /> -->

                                        <a class="ajax-link" href="blog.php?&pid=<?php echo $menu['id']; ?>&edit=true">
                                            <button type="button" class="btn btn-xs btn-danger pull-right" name="editclient"><i class="fa fa-pencil"></i> </button>
                                        </a>
                                    </td>
                                    <td>


                                        <a href="blog.php?id=<?php echo $menu['id']; ?>&action=delete" onclick="return confirm('Are you sure you want to delete this item?');">
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
        <script>
            document.getElementById('name').addEventListener('input', function() {
                var nameInput = this.value;
                var slugInput = nameInput.toLowerCase().replace(/[^a-z0-9]/g, ''); // Removes special characters and spaces
                document.getElementById('slug').value = slugInput;
            });

            // Push Notification Field Handling
            const sendNotifCheck = document.getElementById('sendNotifCareer');
            const notifFields = document.getElementById('notifFields');
            if (sendNotifCheck) {
                sendNotifCheck.addEventListener('change', function() {
                    notifFields.style.display = this.checked ? 'block' : 'none';
                    if (this.checked) {
                        const itemName = document.getElementById('name').value;
                        document.getElementById('notif_title').value = itemName || "New Job Opening";
                        if (!document.getElementById('notif_msg').value) {
                            document.getElementById('notif_msg').value = itemName ? "New Hiring: " + itemName + ". Apply now!" : "We have a new career opening!";
                        }
                    }
                });
            }
        </script>


        <?php require('include/footer.php'); ?>