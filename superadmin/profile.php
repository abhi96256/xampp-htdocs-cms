<?php
error_reporting(0);

include('./function/function.php');
check_session();

$umessage = '';
if (isset($_POST['adduser'])) {
    // Initialize id as empty because it will be auto-incremented by the database
    $id = '';
    extract($_POST);

    // Database connection
    $pdo = getPDOObject();
    $posted_data = $_POST;
    
    // Initialize $num for checking existing user (unused here but kept for reference)
    $num = 0;
    
    // Handle file upload for photo
    $photos = $_FILES['photo']['name'];
    $Filename = '';

    if (!$num) {
        if ($photos) {
            // Generate a unique filename to avoid overwriting
            $Filename = date('dmyhis') . basename($_FILES['photo']['name']);
            $posted_data['photo'] = $Filename;
            
            // Specify the target directory for uploaded photos
            $target = "../upload/" . $Filename;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
                // File uploaded successfully
            } else {
                // File upload failed
                $umessage = '<div class="alert alert-danger" role="alert">Failed to upload photo.</div>';
            }
        }
    }

    // Prepare the insert query without the 'id' field
    $q = $pdo->prepare("INSERT INTO `admin` (name, photo, username, password, role, phone, actstat) 
                        VALUES (:name, :photo, :username, :password, :role, :phone, :actstat)");

    try {
        // Execute the query with user data
        $q->execute([ 
            ':name' => $name,
            ':photo' => $Filename,  // Use the generated filename
            ':username' => $username,
            ':password' => $password,
            ':role' => $role,
            ':phone' => $phone,
            ':actstat' => $actstat
        ]);

        // Check if the insert was successful
        $affected_rows = $q->rowCount();
        if ($affected_rows) {
            $umessage = '<div class="alert alert-success" role="alert">
                            <strong>Success:</strong> Added Successfully
                          </div>';
        } else {
            $umessage = '<div class="alert alert-danger" role="alert">Failed to add user</div>';
        }
    } catch (PDOException $e) {
        // Log the error to a file and optionally display it to the user
        error_log("SQL Error: " . $e->getMessage());
        $umessage = '<div class="alert alert-danger" role="alert">
                        <strong>Error:</strong> ' . $e->getMessage() . '
                     </div>';
    }
}

if (isset($_POST['editdone'])) {
    extract($_POST);
    $pdo = getPDOObject();
    
    // Handle file upload for photo during edit
    $photos = $_FILES['photo']['name'];
    $Filename = $old_photo; // Use the existing photo by default

    if ($photos) {
        // Generate a unique filename to avoid overwriting
        $Filename = date('dmyhis') . basename($_FILES['photo']['name']);
        $target = "../upload/" . $Filename;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
            // File uploaded successfully
        } else {
            // If file upload fails, revert to the old photo
            $Filename = $old_photo;
            $umessage = '<div class="alert alert-danger" role="alert">Failed to upload new photo, keeping old photo.</div>';
        }
    }

    // Check if any field has been updated (excluding photo)
    $updateFields = [];
    $updateValues = [];

    if ($username != $old_username) {
        $updateFields[] = 'username';
        $updateValues[] = $username;
    }
    if ($password != $old_password) {
        $updateFields[] = 'password';
        $updateValues[] = $password;
    }
    if ($name != $old_name) {
        $updateFields[] = 'name';
        $updateValues[] = $name;
    }
    if ($phone != $old_phone) {
        $updateFields[] = 'phone';
        $updateValues[] = $phone;
    }
    if ($role != $old_role) {
        $updateFields[] = 'role';
        $updateValues[] = $role;
    }
    if ($actstat != $old_actstat) {
        $updateFields[] = 'actstat';
        $updateValues[] = $actstat;
    }
    if ($Filename != $old_photo) {
        $updateFields[] = 'photo';
        $updateValues[] = $Filename;
    }

    // Only proceed with the update if any fields are changed
    if (!empty($updateFields)) {
        // Prepare the SET part of the SQL query dynamically based on changed fields
        $setString = "";
        foreach ($updateFields as $key => $field) {
            $setString .= $field . " = ?, ";
        }
        $setString = rtrim($setString, ", "); // Remove the last comma

        // Add the ID at the end of the values
        $updateValues[] = $id;

        // Prepare the update query
        $q = $pdo->prepare("UPDATE admin SET $setString WHERE id = ?");
        try {
            // Execute the query with the updated data
            $q->execute($updateValues);
            $affected_rows = $q->rowCount();
            if ($affected_rows) {
                $umessage = '<div class="alert alert-success" role="alert">
                                <strong>Success:</strong> Updated Successfully
                              </div>';
            } else {
                $umessage = '<div class="alert alert-warning" role="alert">
                                <strong>Notice:</strong> No changes made
                              </div>';
            }
        } catch (PDOException $e) {
            // Log and display error message
            error_log("SQL Error: " . $e->getMessage());
            $umessage = '<div class="alert alert-danger" role="alert">
                            <strong>Error:</strong> ' . $e->getMessage() . '
                         </div>';
        }
    } else {
        // No fields to update
        $umessage = '<div class="alert alert-warning" role="alert">
                        <strong>Notice:</strong> No fields were changed.
                      </div>';
    }
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $pdo = getPDOObject();
    
    // Prepare the delete query
    try {
        $q = $pdo->prepare("DELETE FROM `admin` WHERE id IN ($id)");
        $q->execute();
        $umessage = '<div class="alert alert-success" role="alert">
                        <strong></strong>Deleted Successfully
                     </div>';
    } catch (PDOException $e) {
        // Log and display error message
        error_log("SQL Error: " . $e->getMessage());
        $umessage = '<div class="alert alert-danger" role="alert">
                        <strong>Error:</strong> ' . $e->getMessage() . '
                     </div>';
    }
}
?>

<?php require('include/header.php'); ?>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Home/</span>Add Admin</h4>

        <?php echo $umessage; ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body demo-vertical-spacing demo-only-element z4">
                        <div class="row">
                            <?php
                            if (isset($_POST['Edit'])) {
                                echo '<div class="panel-body1">
                                        <h3 style="padding-top:20px;">Editing</h3>';

                                $id = $_POST['id'];
                                $query = "SELECT * FROM admin WHERE id='$id'";
                                $admindata = sqlfetch($query);
                                foreach ($admindata as $data) {
                                    extract($data);
                            ?>
                                <form method="post" action="" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Username</label>
                                                    <input class="form-control" name="username" value="<?php echo $username; ?>" required type="text" placeholder="Username" />
                                                </div>
                                            </div>
                                            
                                             <div class="col-lg-6 mt-4">
                        
                                                    <div class="input-group input-group-merge">
                        
                                                        <span class="input-group-text">@</span>
                        
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="file" class="form-control" name="photo" aria-label="Upload" value="<?php echo $photo; ?>">
                                                            <label for="basic-addon11">Add Photo</label>
                        
                                                        </div>
                        
                                                    </div>
                                                </div>

                                            <div class="col-sm-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Password</label>
                                                    <input class="form-control" name="password" value="<?php echo $password; ?>" required type="text" placeholder="Password" />
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Name</label>
                                                    <input class="form-control" name="name" value="<?php echo $name; ?>" required type="text" placeholder="Name" />
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Role</label>
                                                    <input class="form-control" name="role" value="<?php echo $role; ?>" required type="text" placeholder="Role" />
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select class="form-control btn-square" name="actstat" id="selectError" data-rel="chosen">
                                                        <option <?php if ($actstat == '1') echo 'selected'; ?> value="1">Active</option>
                                                        <option <?php if ($actstat == '0') echo 'selected'; ?> value="0">Deactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Phone</label>
                                                    <input class="form-control" name="phone" value="<?php echo $phone; ?>" required type="text" placeholder="Phone" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-end">
                                        <button class="btn btn-primary" name="editdone" type="submit">Submit</button>
                                    </div>
                                </form>
                            <?php
                                }
                                echo '</div>';
                            } else {
                            ?>
                                <form method="post" action="" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">Add New</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Username</label>
                                                    <input class="form-control" name="username" required type="email" placeholder="Email-ID" />
                                                </div>
                                            </div>
                                             <div class="col-lg-6 mt-4">
                        
                                                    <div class="input-group input-group-merge">
                        
                                                        <span class="input-group-text">@</span>
                        
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="file" class="form-control" name="photo" aria-label="Upload">
                                                            <label for="basic-addon11">Add Photo</label>
                        
                                                        </div>
                        
                                                    </div>
                                                </div>
                                                 
                                            <div class="col-sm-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Password</label>
                                                    <input class="form-control" name="password" required type="text" placeholder="Password" />
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Name</label>
                                                    <input class="form-control" name="name" required type="text" placeholder="Name" />
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Role</label>
                                                    <input class="form-control" name="role" required type="text" placeholder="Role" />
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select class="form-control btn-square" name="actstat" id="selectError" data-rel="chosen">
                                                        <option value="1">Active</option>
                                                        <option value="0">Deactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Phone</label>
                                                    <input class="form-control" name="phone" required type="text" placeholder="Phone" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-end">
                                        <button class="btn btn-primary" name="adduser" type="submit">Submit</button>
                                    </div>
                                </form>
                            <?php
                            }
                            ?>

                    </div>
                </div>
            </div>
        </div>


        <div class="card">
            <div class="card-datatable dataTable_select text-nowrap table-responsive">
                <table id="tableID" class="display dt-select-table table table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>UserName</th>
                            <th>Profile Photo</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Phone</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Get all admin users
                        $query = "SELECT * FROM admin ORDER BY id DESC";
                        if (isset($_GET['id'])) {
                            $id = $_GET['id'];
                            $query = "SELECT * FROM admin WHERE id='$id'";
                        }
                        $admindata = sqlfetch($query);
                        foreach ($admindata as $data) {
                            echo '<tr>';
                            echo '<td><a class="text-inherit" href="detail.php?cid=' . $data['id'] . '">' . $data['id'] . '</a></td>';
                            echo '<td><a class="text-inherit" href="detail.php?cid=' . $data['id'] . '">' . $data['username'] . '</a></td>';
                            echo '<td><img style="height: 32px; width: 32px;" src="../upload/' . $data['photo'] . '" class="img-responsive"></td>';
                            echo '<td>' . $data['name'] . '</td>';
                            echo '<td>' . $data['role'] . '</td>';
                            echo '<td>' . get_active_status_text($data['actstat']) . '</td>';
                            echo '<td>' . $data['phone'] . '</td>';
                            echo '<td>
                                    <form action="" method="post">
                                    <input type="hidden" name="id" value="' . $data['id'] . '" >
                                    <a class="btn btn-primary btn-sm" href="">
                                        <i class="fa fa-pencil"></i> 
                                        <input type="submit" class="btn btn-xs btn-primary" name="Edit" value="Edit">
                                    </a>
                                    </form>
                                  </td>';
                            echo '<td>
                                    <form action="" method="post">
                                    <input type="hidden" name="id" value="' . $data['id'] . '" >
                                    <a class="btn btn-danger btn-sm" href="javascript:void(0)">
                                        <i class="fa fa-trash"></i> 
                                        <input type="submit" class="btn btn-xs btn-danger" name="delete" value="Delete">
                                    </a>
                                    </form>
                                  </td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<?php require('include/footer.php'); ?>
