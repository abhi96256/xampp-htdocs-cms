<?php
$umessage = '';
include('./function/function.php');
check_session();
if (isset($_POST['addclient'])) {
    $id = 0;
    extract($_POST);
    $type = 1;
    $pdo = getPDOObject();
    // $sql = $pdo->query("SELECT * FROM `standard_delivery_price` where  name LIKE '$name'");
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
        $q = $pdo->prepare("INSERT into `standard_delivery_price` values(:id,:name, :photo,:des,:des1, :fld_order, :actstat)");
        $q->execute(array(
            ':id' => $id,

            ':name' => $name,
            ':photo' => $Filename,
            ':des' => $des,
            ':des1' => $des1,
            ':fld_order' => $fld_order,
            ':actstat' => $actstat
        ));
        $affected_rows = $q->rowCount();
        if ($affected_rows)
            $umessage = '<div class="alert alert-success" role="alert">
							<strong></strong>Added Successfully
						   </div>';
    } else {
        $umessage = '<div class="alert alert-danger" role="alert">Duplicate Entry!!! Code Already Exists </div> ';
    }
}


if ($_GET['action'] == 'delete') {
    $sql_del = sqlfetch("delete from standard_delivery_price where id='" . $_GET['id'] . "'");
    echo "<script>window.open('standard_delivery_price.php','_self')</script>";
}



if (isset($_POST['deleteall'])) {
    $arr = $_POST['ids'];
    if (count($arr)) {
        $str_rest_refs = implode(",", $arr);

        $data = sqlfetch("select * from `standard_delivery_price` where id in ($str_rest_refs)");
        foreach ($data as $client) {
            $img_path = '../upload/' . $client['photo'];
            if (file_exists($img_path)) {
                @unlink($img_path);
            }
        }

        $pdo = getPDOObject();
        $q = $pdo->query("DELETE FROM `standard_delivery_price` WHERE id in ($str_rest_refs)");
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
    $q = $pdo->prepare("UPDATE `standard_delivery_price` SET 
		
		
		name=?,
		photo=?,
		des=?,
        des1=?,
		fld_order=?,
		actstat=?
		
		WHERE id=?");
    $q->execute(array($name, $Filename, $des,  $des1, $fld_order, $actstat, $pid));
    $affected_rows = $q->rowCount();
    if ($affected_rows)
        $umessage = '<div class="alert alert-primary alert-dismissible" role="alert">
                           Updated Successfully!
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
          </button>
        </div>';
}

function client_form($pid = '0', $name = '', $photo = '', $des = '', $des1 = '', $fld_order = '0', $actstat = '', $formname = 'addclient')
{ ?>
    <form action="standard_delivery_price.php?&pid=<?php echo $_GET['pid']; ?>&edit=true" method="post" enctype="multipart/form-data">
        <div class="form theme-form">
            <input type="hidden" name="pid" value="<?php echo $pid; ?>" />
            <input type="hidden" name="prevphoto" value="<?php echo $photo; ?>" />
            <div class="row">

                <div class="col-lg-4  mt-3">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">@</span>
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" name="name" placeholder="Name" aria-label="Name" value="<?php echo $name; ?>" />
                            <label for="basic-addon11">Add Price </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mt-3"  style="display: none;">

                    <div class="input-group input-group-merge">

                        <span class="input-group-text">@</span>

                        <div class="form-floating form-floating-outline">
                            <input type="file" class="form-control" name="photo" aria-label="Upload" value="<?php echo $photo; ?>">
                            <label for="basic-addon11">Add Photo</label>

                        </div>

                    </div>
                    <!-- <img src="../upload/<?php echo $photo; ?>" style=" border-radius: 12px;width: 39px;height: 35px;"> -->
                </div>
                <div class="col-lg-4  mt-3"  style="display: none;">
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
                <div class="col-lg-12  mt-3"  style="display: none;">
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


        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Home/</span>Prices</h4>


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
                                $productdata = sqlfetch("SELECT * FROM `standard_delivery_price` where id='$pid' ");
                                foreach ($productdata as $product) {
                                    extract($product);
                                    client_form($pid, $name, $photo, $des, $des1, $fld_order, $actstat, $formname = 'editdone');
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
                                <!-- <th>Photo</th> -->
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
                            $data = sqlfetch("SELECT * FROM `standard_delivery_price`  order by fld_order");
                            foreach ($data as $menu) { ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>

                                    <td><?php echo $menu['name']; ?></td>
                                    <!-- <td><img style="height: 32px;width: 32px;" src="../upload/<?php echo $menu['photo']; ?>" class="img-responsive"></td> -->
                                    <td><?php echo $menu['fld_order']; ?></td>

                                    <td> <?php echo get_active_status_text($menu['actstat']); ?></td>
                                    <td>
                                        <!-- <input class="checkhour" name="ids[]" value="<?php echo $menu['id']; ?>" type="checkbox" /> -->

                                        <a class="ajax-link" href="standard_delivery_price.php?&pid=<?php echo $menu['id']; ?>&edit=true">
                                            <button type="button" class="btn btn-xs btn-danger pull-right" name="editclient"><i class="fa fa-pencil"></i> </button>
                                        </a>
                                    </td>
                                    <td>


                                        <a href="standard_delivery_price.php?id=<?php echo $menu['id']; ?>&action=delete" onclick="return confirm('Are you sure you want to delete this item?');">
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