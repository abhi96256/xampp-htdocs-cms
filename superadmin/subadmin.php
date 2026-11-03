<?php
$umessage = '';
include('./function/function.php');
check_session();

if (isset($_POST['addsubcategory'])) {
    extract($_POST);
    $pdo = getPDOObject();
    $posted_data = $_POST;

    // Handle file upload for photo
    $photos = $_FILES['photo']['name'];
    $Filename = '';

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $file_type = $_FILES['photo']['type'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

        if (in_array($file_type, $allowed_types)) {
            $Filename = date('dmyhis') . basename($photos);
            $target = "../upload/" . $Filename;

            // Ensure upload directory exists
            if (!is_dir('../upload/')) {
                mkdir('../upload/', 0777, true);
            }

            // Move uploaded file to target directory
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
                $posted_data['photo'] = $Filename;
                $umessage = '<div class="alert alert-success" role="alert">Photo uploaded successfully!</div>';
            } else {
                $umessage = '<div class="alert alert-danger" role="alert">Failed to move uploaded file!</div>';
            }
        } else {
            $umessage = '<div class="alert alert-danger" role="alert">Invalid file type. Only JPEG, PNG, and GIF are allowed.</div>';
        }
    }

    // Store permissions as JSON
    $posted_data['permissions'] = isset($posted_data['permissions']) ? json_encode($posted_data['permissions']) : json_encode([]);

    // Insert the new subadmin
    $affected_rows = insert('subadmin', $posted_data);
    if ($affected_rows) {
        $umessage = '<div class="alert alert-success" role="alert">Added Successfully</div>';
    }
}

if ($_GET['action'] == 'delete') {
    sqlfetch("DELETE FROM subadmin WHERE id='" . $_GET['id'] . "'");
    echo "<script>window.open('subadmin.php','_self')</script>";
}

if (isset($_POST['deleteall'])) {
    $arr = $_POST['ids'];
    if (count($arr)) {
        $str_rest_refs = implode(",", $arr);
        $pdo = getPDOObject();
        $pdo->query("DELETE FROM `subadmin` WHERE id in ($str_rest_refs)");
        $umessage = '<div class="alert alert-success" role="alert">Deleted Successfully</div>';
    } else {
        $umessage = '<div class="alert alert-danger" role="alert">Please select items to perform this action</div>';
    }
}

if (isset($_POST['editdone'])) {
    extract($_POST);
    $pdo = getPDOObject();
    $photos = $_FILES['photo']['name'];
    $Filename = $prevphoto;

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $file_type = $_FILES['photo']['type'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

        if (in_array($file_type, $allowed_types)) {
            $Filename = date('dmyhis') . basename($photos);
            $target = "../upload/" . $Filename;

            // Ensure upload directory exists
            if (!is_dir('../upload/')) {
                mkdir('../upload/', 0777, true);
            }

            // Move uploaded file to target directory
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
                $umessage = '<div class="alert alert-success" role="alert">Photo uploaded successfully!</div>';
            } else {
                $umessage = '<div class="alert alert-danger" role="alert">Failed to move uploaded file!</div>';
            }
        } else {
            $umessage = '<div class="alert alert-danger" role="alert">Invalid file type. Only JPEG, PNG, and GIF are allowed.</div>';
        }
    }

    $posted_data = $_POST;
    $posted_data['photo'] = $Filename;
    $posted_data['permissions'] = isset($posted_data['permissions']) ? json_encode($posted_data['permissions']) : json_encode([]);

    $affected_rows = update('subadmin', $posted_data, ['id' => $pid]);
    if ($affected_rows) {
        $umessage = '<div class="alert alert-success" role="alert">Updated Successfully</div>';
    }
}

function subcategory_form($pid = '0', $name = '', $photo = '', $bio = '', $username = '', $password = '', $permissions = [], $formname = 'addsubcategory')
{
    $permissions = empty($permissions) ? [] : json_decode($permissions, true);
?>

    <form action="subadmin.php" method="post" enctype="multipart/form-data">
        <div class="form theme-form">
            <input type="hidden" name="pid" value="<?php echo $pid; ?>" />
            <input type="hidden" name="prevphoto" value="<?php echo $photo; ?>" />
            <div class="row">
                <div class="col-lg-12 mt-3">
                    <div class="row">
                        <div class="col-lg-4 mt-4">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">@</span>
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="name" placeholder="Name" value="<?php echo $name; ?>" />
                                    <label for="name">Name</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mt-4">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">@</span>
                                <div class="form-floating form-floating-outline">
                                    <input type="file" class="form-control" name="photo" />
                                    <label for="photo">Photo</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mt-4">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">@</span>
                                <div class="form-floating form-floating-outline">
                                    <input type="email" class="form-control" name="username" placeholder="Username" value="<?php echo $username; ?>" />
                                    <label for="username">Username</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mt-4">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">@</span>
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="password" placeholder="Password" value="<?php echo $password; ?>" />
                                    <label for="password">Password</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mt-4">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">@</span>
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="bio" placeholder="Bio" value="<?php echo $bio; ?>" />
                                    <label for="bio">Bio</label>
                                </div>
                            </div>
                        </div>

                        <?php if ($pid > 0) { ?>
                            <div class="col-md-12 mt-4">
                                <div class="form-floating form-floating-outline">
                                    <select id="select2Multiple" name="permissions[]" class="select2 form-select" multiple>
                                        <optgroup label="Select Menu Bars">
                                            <option value="Dashboards" <?php echo in_array('Dashboards', $permissions) ? 'selected' : ''; ?>>Dashboards</option>
                                          <option value="Manage Front Page" <?php echo in_array('Manage Front Page', $permissions) ? 'selected' : ''; ?>>Manage Front Page</option>
                                            <option value="Manage About Us" <?php echo in_array('Manage About Us', $permissions) ? 'selected' : ''; ?>>Manage About Us</option>
                                            <option value="Manage Blogs" <?php echo in_array('Manage Blogs', $permissions) ? 'selected' : ''; ?>>Manage Blogs</option>
                                            <option value="Manage Other Pages" <?php echo in_array('Manage Other Pages', $permissions) ? 'selected' : ''; ?>>Manage Other Pages</option>
                                            <option value="Manage Projects" <?php echo in_array('Manage Projects', $permissions) ? 'selected' : ''; ?>>Manage Projects</option>
                                            <option value="Manage Products" <?php echo in_array('Manage Products', $permissions) ? 'selected' : ''; ?>>Manage Products</option>
                                            <option value="Manage Career" <?php echo in_array('Manage Career', $permissions) ? 'selected' : ''; ?>>Manage Career</option>
                                            <!--<option value="Manage Sub Admins" <?php echo in_array('Manage Sub Admins', $permissions) ? 'selected' : ''; ?>>Manage Sub Admins</option>-->
                                            <option value="Manage Query" <?php echo in_array('Manage Query', $permissions) ? 'selected' : ''; ?>>Manage Query</option>

                                          
                                          
                                        </optgroup>
                                    </select>
                                    <label for="select2Multiple">Permissions</label>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-lg-12 mt-5">
                    <div class="input-group input-group-merge">
                        <button class="btn btn-primary" type="submit" name="<?php echo $formname; ?>">Submit</button>
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


        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Home/</span> Categories </h4>


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
                                $productdata = sqlfetch("SELECT * FROM `subadmin` where id='$pid' ");
                                foreach ($productdata as $product) {
                                    extract($product);
                                    subcategory_form($pid, $name, $photo, $bio,  $username, $password, $permissions,  $formname = 'editdone');
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
                                <?php subcategory_form(); ?>
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
                                <th>Username</th>
                                <th>Password</th>
                                <th>Edit / Give Permissions</th>
                                <!--<th>Edit</th>-->
                                <th>Delet</th>
                            </tr>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 1;
                            $data = sqlfetch("SELECT * FROM `subadmin` ");
                            foreach ($data as $menu) { ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td><?php echo $menu['name']; ?></td>
                                      <td><img style="height: 32px;width: 32px;" src="../upload/<?php echo $menu['photo']; ?>" class="img-responsive"></td>
                                    <td><?php echo $menu['username']; ?></td>
                                    <td><?php echo $menu['password']; ?></td>
                                       <td>


                                        <a class="ajax-link text-dark" href="subadmin.php?&pid=<?php echo $menu['id']; ?>&edit=true" style="float: left;"> 
                                            <button type="button" class="btn btn-xs btn-danger pull-right" name="editclient"> Click Here         <i class="fa fa-lock" style="margin-left:5px;"></i> </button>
                                        </a>
                                      
                                    </td>
                                    

                                    <!--<td>-->


                                    <!--    <a class="ajax-link" href="subadmin.php?&pid=<?php echo $menu['id']; ?>&edit=true"> -->
                                    <!--        <button type="button" class="btn btn-xs btn-danger pull-right" name="editclient"><i class="fa fa-pencil"></i> </button>-->
                                    <!--    </a>-->
                                    <!--</td>-->
                                    <td>


                                        <a href="subadmin.php?id=<?php echo $menu['id']; ?>&action=delete" onclick="return confirm('Are you sure you want to delete this item?');">
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