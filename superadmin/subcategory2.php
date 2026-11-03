<?php
$umessage = '';
include('./function/function.php');
check_session();
if (isset($_POST['addsubcategory'])) {
    $id = 0;
    extract($_POST);
    $type = 1;
    $pdo = getPDOObject();
    $posted_data = $_POST;
    // $sql = $pdo->query("SELECT * FROM `client4` where  name LIKE '$name'");
    // $num = $sql->rowCount();
    $num = 0;
    $photos = $_FILES['photo']['name'];
    $Filename = '';
    if (!$num) {
        if ($photos) {
            $Filename = date('dmyhis') . basename($_FILES['photo']['name']);
            $posted_data['photo'] = $Filename;
            $target = "../upload/" . $Filename;
            move_uploaded_file($_FILES['photo']['tmp_name'], $target);    //Tells you if its all ok	
        }
        $affected_rows = insert('subcategory2', $posted_data);
        if ($affected_rows)
            if ($affected_rows)
                $umessage = '<div class="alert alert-success" role="alert">
							<strong></strong>Added Successfully
						   </div>';
    } else {
        $umessage = '<div class="alert alert-danger" role="alert">Duplicate Entry!!! Code Already Exists </div> ';
    }
}
if ($_GET['action'] == 'delete') {
    $sql_del = sqlfetch("delete from subcategory2 where id='" . $_GET['id'] . "'");
    echo "<script>window.open('subcategory2.php','_self')</script>";
}


if (isset($_POST['deleteall'])) {
    $arr = $_POST['ids'];
    if (count($arr)) {
        $str_rest_refs = implode(",", $arr);

        $data = sqlfetch("select * from `subcategory2` where id in ($str_rest_refs)");
        foreach ($data as $subcategory) {
            $img_path = '../upload/' . $subcategory['photo'];
            if (file_exists($img_path)) {
                @unlink($img_path);
            }
        }

        $pdo = getPDOObject();
        $q = $pdo->query("DELETE FROM `subcategory2` WHERE id in ($str_rest_refs)");
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
        $q = $pdo->query("UPDATE `subcategory2` SET actstat='1' WHERE id in ($str_rest_refs)");
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
        $q = $pdo->query("UPDATE `subcategory2` SET actstat='0' WHERE id in ($str_rest_refs)");
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
    $posted_data = $_POST;
    $Filename = $prevphoto;
    $photos = $_FILES['photo']['name'];
    if ($photos != '') {
        $Filename = '';

        $Filename = date('dmyhis') . basename($_FILES['photo']['name']);
        $posted_data['photo'] = $Filename;
        $target = "../upload/" . $Filename;
        move_uploaded_file($_FILES['photo']['tmp_name'], $target);    //Tells you if its all ok	
        $img_path = '../upload/' . $prevphoto;
        if (file_exists($img_path)) {
            @unlink($img_path);
        }
    }

    $affected_rows = update('subcategory2', $posted_data, array('id' => $pid));
    if ($affected_rows)
        $umessage = '<div class="alert alert-success" role="alert">
							<strong></strong>Updated Successfully
						   </div>';
}

function subcategory_form($pid = '0', $name = '', $photo = '', $des = '', $des1 = '', $meta_title = '', $meta_keyword = '', $meta_description = '', $slug = '',  $fld_order = '0', $link = '', $subcat = 0, $actstat = '', $formname = 'addsubcategory')
{ ?>





    <form action="subcategory2.php" method="post" enctype="multipart/form-data">
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
                                    <label for="basic-addon11"> Floor Name</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4  mt-4 d-none ">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">@</span>
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="slug" name="slug" placeholder="Page Slug" aria-label="Page Slug" value="<?php echo $slug; ?>" />
                                    <label for="basic-addon11">Page Slug</label>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-4  mt-4 d-none ">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">@</span>
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="des1" placeholder="Icon Add " aria-label="Name" value="<?php echo $des1; ?>" />
                                    <label for="basic-addon11"> Add Short Description</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4  mt-4  d-none ">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">@</span>
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="meta_title" placeholder="Meta Title" aria-label="Meta Title" value="<?php echo $meta_title; ?>" />
                                    <label for="basic-addon11">Meta Title</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4  mt-4  d-none">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">@</span>
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="meta_keyword" placeholder="Meta Keyword" aria-label="Meta Keyword" value="<?php echo $meta_keyword; ?>" />
                                    <label for="basic-addon11">Meta Keyword</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4  mt-4  d-none">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text">@</span>
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="meta_description" placeholder="Meta Description" aria-label="Meta Description" value="<?php echo $meta_description; ?>" />
                                    <label for="basic-addon11">Meta Description</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mt-4">

                            <div class="input-group input-group-merge">

                                <span class="input-group-text">@</span>

                                <div class="form-floating form-floating-outline">
                                    <input type="file" class="form-control" name="photo" aria-label="Upload" value="<?php echo $photo; ?>">
                                    <label for="basic-addon11">Add Photo</label>

                                </div>

                            </div>
                            <!-- <img src="../upload/<?php echo $photo; ?>" style=" border-radius: 12px;width: 39px;height: 35px;"> -->
                        </div>

                        <div class="col-lg-4  mt-4">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select" name="subcat" id="selectError" data-rel="chosen">
                                    <option>SELECT Product</option>
                                    <?php
                                    $categories = sqlfetch("SELECT * FROM `subproduct` order by fld_order");
                                    foreach ($categories as $category) {
                                        $select = '';
                                        if (($subcat == ($category['id'])))
                                            $select = 'selected';
                                        echo '<option ' . $select . ' value="' . $category['id'] . '">' . $category['name'] . '</option>';
                                    }
                                    ?>
                                </select>
                                <label for="floatingSelect">SELECT Category</label>
                            </div>
                        </div>
                        <div class="col-lg-6  mt-4">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select" name="actstat" id="selectError" data-rel="chosen">
                                    <option <?php if (($actstat) == '1') echo 'selected'; ?> value="1">Active</option>
                                    <option <?php if (($actstat) == '0') echo 'selected'; ?> value="0">Inactive</option>
                                </select>
                                <label for="floatingSelect">Status</label>
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

                    </div>
                </div>
 
                <div class="col-lg-12  mt-3 d-none">
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
                                $productdata = sqlfetch("SELECT * FROM `subcategory2` where id='$pid' ");
                                foreach ($productdata as $product) {
                                    extract($product);
                                    subcategory_form($pid, $name, $photo, $des, $des1, $meta_title, $meta_keyword, $meta_description, $slug, $fld_order, $link, $subcat, $actstat, $formname = 'editdone');
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
                                <th>S. No.</th>
                                <th>Floor Name</th>
                                <th>Product Name</th>
                                <th>Photo</th>
                                <th>Sort Order</th>
                                <th>Status</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch subcategories from the `accessories` table
                            $subcategories = sqlfetch("SELECT * FROM `subproduct` ORDER BY fld_order");
                            $subcategoryLookup = [];
                            foreach ($subcategories as $subcategory) {
                                $subcategoryLookup[$subcategory['id']] = $subcategory['name'];
                            }

                            // Initialize counter
                            $count = 1;

                            // Fetch the subcategory data
                            $data = sqlfetch("SELECT * FROM `subcategory2` ORDER BY fld_order");

                            // Generate the table rows
                            foreach ($data as $menu) {
                                // Get the name for subcat
                                $subcatName = isset($subcategoryLookup[$menu['subcat']]) ? $subcategoryLookup[$menu['subcat']] : '';
                            ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td><?php echo $menu['name']; ?></td> <!-- Corrected name -->
                                    <td><?php echo $subcatName; ?></td>
                                    <td><img style="height: 32px; width: 32px;" src="../upload/<?php echo $menu['photo']; ?>" class="img-responsive"></td>
                                    <td><?php echo $menu['fld_order']; ?></td>
                                    <td><?php echo get_active_status_text($menu['actstat']); ?></td>

                                    <td>
                                        <a class="ajax-link" href="subcategory2.php?pid=<?php echo $menu['id']; ?>&edit=true">
                                            <button type="button" class="btn btn-xs btn-danger pull-right" name="editclient"><i class="fa fa-pencil"></i></button>
                                        </a>
                                    </td>

                                    <td>
                                        <a href="subcategory2.php?id=<?php echo $menu['id']; ?>&action=delete" onclick="return confirm('Are you sure you want to delete this item?');">
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
        </script>

        <?php require('include/footer.php'); ?>