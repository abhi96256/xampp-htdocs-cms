<?php
$umessage = '';
include('./function/function.php');
check_session();
if (isset($_POST['addproduct'])) {
    $id = 0;
    extract($_POST);
    $type = 1;
    $pdo = getPDOObject();
    $posted_data = $_POST;
    $sql = $pdo->query("SELECT * FROM `xtse` where  name LIKE '$name'");
    $num = $sql->rowCount();
    $photos = $_FILES['photo']['name'];
    $Filename = '';
    $banner = $_FILES['banner']['name'];
    $Filebanner = '';
    if (!$num) {
        if ($photos) {
            $Filename = date('dmyhis') . basename($_FILES['photo']['name']);
            $posted_data['photo'] = $Filename;
            $target = "../upload/" . $Filename;
            move_uploaded_file($_FILES['photo']['tmp_name'], $target);    //Tells you if its all ok	
        }
        if ($banner) {
            $Filebanner = date('dmyhis') . basename($_FILES['banner']['name']);
            $posted_data['banner'] = $Filebanner;
            $target = "../upload/" . $Filebanner;
            move_uploaded_file($_FILES['banner']['tmp_name'], $target);    //Tells you if its all ok	
        }
        $affected_rows = insert('xtse', $posted_data);
        if ($affected_rows)
            $umessage = '<div class="alert alert-success" role="alert">
							<strong></strong>Added Successfully
						   </div>';
    } else {
        $umessage = '<div class="alert alert-danger" role="alert">Duplicate Entry!!! Code Already Exists </div> ';
    }
}

if ($_GET['action'] == 'delete') {
    $sql_del = sqlfetch("delete from xtse where id='" . $_GET['id'] . "'");
    echo "<script>window.open('client.php','_self')</script>";
}

if (isset($_POST['deleteall'])) {
    $arr = $_POST['ids'];
    if (count($arr)) {
        $str_rest_refs = implode(",", $arr);

        $data = sqlfetch("select * from `xtse` where id in ($str_rest_refs)");
        foreach ($data as $product) {
            $img_path = '../upload/' . $product['photo'];
            if (file_exists($img_path)) {
                @unlink($img_path);
            }
        }

        $pdo = getPDOObject();
        $q = $pdo->query("DELETE FROM `xtse` WHERE id in ($str_rest_refs)");
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
        $q = $pdo->query("UPDATE `xtse` SET actstat='1' WHERE id in ($str_rest_refs)");
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
        $q = $pdo->query("UPDATE `xtse` SET actstat='0' WHERE id in ($str_rest_refs)");
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
    $Filename2 = $prevphoto2;
    $photos2 = $_FILES['photo2']['name'];
    if ($photos2 != '') {
        $Filename2 = '';

        $Filename2 = date('dmyhis') . basename($_FILES['photo2']['name']);
        $posted_data['photo2'] = $Filename2;
        $target = "../upload/" . $Filename2;
        move_uploaded_file($_FILES['photo2']['tmp_name'], $target);    //Tells you if its all ok	
        $img_path = '../upload/' . $prevphoto2;
        if (file_exists($img_path)) {
            @unlink($img_path);
        }
    }
    //	 echo "<script>alert('$class')</script>";
    $affected_rows = update('xtse', $posted_data, array('id' => $pid));
    if ($affected_rows)
        $umessage = '<div class="alert alert-success" role="alert">
							<strong></strong>Updated Successfully
						   </div>';
}

function product_form($pid = '0', $name = '', $photo = '', $dob = '', $gender = '', $actstat = '', $fname = '', $mname = '', $email = '', $foccupation = '', $moccupation = '', $address = '', $fphone = '', $mphone = '', $school = '', $class = '', $stream = '', $place = '', $photo2 = '', $date = '', $mode = '', $code = '', $concession = '', $selectOption1 = '', $selectOption2 = '', $selectOption3 = '',    $formname = 'addproduct')
{ ?>
    <form action="client.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="pid" value="<?php echo $pid; ?>" />
        <input type="hidden" name="prevphoto" value="<?php echo $photo; ?>" />
        <input type="hidden" name="prevphoto2" value="<?php echo $photo2; ?>" />

        <br><br>
        <span class="row">

            <span class="col-md-6">
                <label>Name</label>
                <input type="text" readonly name="name" class="form-control" value="<?php echo $name; ?>" required><br /><br />
            </span>


            <span class="col-md-6">
                <label>Email Address</label>
                <input type="text" name="email" readonly value="<?php echo $email; ?>" required class="form-control" /><br /><br />
            </span>


            <span class="col-md-6">
                <label>Phone Number</label>
                <input type="text" name="date" readonly value="<?php echo $date; ?>" required class="form-control" /><br /><br />
            </span>




            <span class="col-md-3" style="display:none;">
                <label>Photo</label>
                <input type="file" name="photo">
                <img class="grayscale img-responsive" alt="" src="../upload/<?php echo $photo; ?>" style="width:50px;">
            </span>
            <span class="col-md-3" style="display:none;">
                <label>Sign</label>
                <input type="file" name="photo2">
                <img class="grayscale img-responsive" alt="" src="../upload/<?php echo $photo2; ?>" style="width:50px;">
            </span>
            <span class="col-md-6">
                <label>Order Staus</label>
                <select name="gender" class="form-control">
                    <option>Choose Here</option>
                    <option <?php if (($gender) == 'Checked') echo 'selected'; ?> value="Checked">Checked</option>
                    <option <?php if (($gender) == 'Inprocess') echo 'selected'; ?> value="Inprocess">Inprocess</option>
                    <option <?php if (($gender) == 'Deliverd') echo 'selected'; ?> value="Deliverd">Deliverd</option>
                    <option <?php if (($gender) == 'Rejected') echo 'selected'; ?> value="Rejected">Rejected</option>
                </select>
            </span>


            <span class="col-md-6"> 
                <label>Product Name</label>
                <input type="text" name="mname" value="<?php echo $mname; ?>" readonly class="form-control" /><br /><br />
            </span>

            <span class="col-md-6" >
                <label>Address</label>
                <input type="text" class="form-control" name="foccupation" readonly value="<?php echo $foccupation; ?>">
            </span>
            <span class="col-md-6" style="display:none;">
                <label>Address line2</label>
                <input type="text" class="form-control" name="moccupation" readonly value="<?php echo $moccupation; ?>">
            </span>


            <span class="col-md-6 mt-3">
                <label>Status</label>
                <div class="controls">
                    <select name="actstat" class="form-control">
                        <option <?php if (($actstat) == '1') echo 'selected'; ?> value="1">Active</option>
                        <option <?php if (($actstat) == '0') echo 'selected'; ?> value="0">Inactive</option>
                    </select>
                </div>
            </span>


            <span class="col-md-6 mt-3" style="display:none;">
                <label>City / Town </label>
                <input type="text" class="form-control" name="dob" readonly value="<?php echo $dob; ?>">
            </span>


            <span class="col-md-6 mt-4" style="display:none;">
                <label>State / County </label>
                <input type="text" class="form-control" name="mode" readonly value="<?php echo $mode; ?>">

            </span>


            <span class="col-md-6 mt-4" style="display:none;">
                <label>Postcode / ZIP</label>
                <input type="text" class="form-control" name="class" readonly value="<?php echo $class; ?>">

            </span>


            <span class="col-md-6 mt-4" style="display:none;">
                <label>Course Name *</label>
                <input type="text" class="form-control" name="place" readonly value="<?php echo $place; ?>">
            </span>

            <span class="col-md-6 mt-4" style="display:none;">
                <label> Totel Price </label>
                <input type="text" class="form-control" name="selectOption1" readonly value="₹ <?php echo $selectOption1; ?>">
            </span>


            <span class="col-md-6 mt-4" style="display:none;">
                <label>Product Quantity </label>
                <input type="text" class="form-control" name="selectOption2" readonly value="<?php echo $selectOption2; ?>">
            </span>




            <span class="col-md-6 mt-4" style="display:none;">
                <label>Payment Method </label>
                <input type="text" class="form-control" name="address" readonly value="<?php echo $address; ?>">

            </span>

            <span class="col-md-6 mt-4" style="display:none;">
                <label>Additional information </label>

                <input type="text" class="form-control" name="school" readonly value="<?php echo $school; ?>">
            </span>

            <span class="col-md-6 mt-4" style="display: none;">
                <label>Delivery Time</label>
                <input type="text" class="form-control" name="concession" value="<?php echo $concession; ?>">
            </span>









            <span class="col-md-4 mt-4" style="display:none;">
                <label>User Name</label>
                <input type="text" class="form-control" name="selectOption3" value="<?php echo $selectOption3; ?>">
            </span>

            <span class="col-md-4 mt-4" style="display:none;">
                <label>User Order Id</label>
                <input type="text" name="fname" value="<?php echo $fname; ?>" class="form-control" /><br /><br />
            </span>

            <span class="col-md-6 mt-4"  style="display:none;">
                <label>Delivery Date</label>
                <input type="text" class="form-control" name="concession" value="<?php echo $concession; ?>">
            </span>

            <span class="col-md-12  mt-4" style="display:none;">

                <div style="  box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;padding: 27px; border: 1px solid black; border-radius: 10px;">
                    <label>Products All Details</label>

                    <br /><br />



                    <div><?php echo $mphone; ?></div>
                </div>

                <br /><br />

            </span>



        </span>




        <span class="row">
            <span class="col-md-4 mt-4">
                <button value="Submit" type="submit" name="<?php echo $formname; ?>" class="btn btn-primary">Submit</button>

            </span>
        </span>
    </form>

<?php
}


?>







<?php
require('include/header.php'); ?>


<div class="content-wrapper">


    <div class="container-xxl flex-grow-1 container-p-y">


        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Deahborad /</span>Order List
        </h4>

        <?php echo $umessage; ?>

        <?php
        if (isset($_GET['edit']) and ($_GET['edit'] == 'true')) { ?>


            <div class="row">

                <div class="col-12">
                    <div class="card">

                        <div class="card-body">
                            <?php
                            $pid = $_GET['pid'];
                            $productdata = sqlfetch("SELECT * FROM `xtse` where id='$pid'");
                            foreach ($productdata as $product) {
                                extract($product);
                                product_form($pid, $name, $photo, $dob, $gender, $actstat, $fname, $mname, $email, $foccupation, $moccupation, $address, $fphone, $mphone, $school, $class, $stream, $place, $photo2, $date, $mode, $code, $concession,  $selectOption1, $selectOption2, $selectOption3, $formname = 'editdone');
                            } ?>

                        </div>
                    </div>
                </div>

            </div>

        <?php
        } else {
        ?>



            <!--<div class="row">-->
            <!--    <h4 class="p-2">Add-Details</h4>-->
            <!--    <div class="col-12">-->
            <!--        <div class="card">-->

            <!--            <div class="card-body">-->
            <!--                <?php product_form(); ?>-->

            <!--            </div>-->
            <!--        </div>-->
            <!--    </div>-->

            <!--</div>-->

            <div class="row mt-5">

                <div class="col-12">
                    <div class="card">

                        <div class="card-body ">

                            <div class="card-datatable dataTable_select text-nowrap table-responsive">
                                <table id="tableID" class="display dt-select-table table table-bordered" style="width:100%">


                                    <thead>
                                        <tr>
                                            <th>S NO</th>
                                            <th>Customer Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                
                                            <th>Edit</th>
                                            <th>Delet</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $count = 1;
                                        $data = sqlfetch("SELECT * FROM `xtse`");
                                        foreach ($data as $menu) { ?>
                                            <tr>
                                                <td><?php echo $count++; ?></td>

                                                <td><?php echo $menu['name']; ?></td>
                                                <td><?php echo $menu['email']; ?></td>
                                                <td><?php echo $menu['date']; ?></td>
                                             
                                                <td>
                                                    <a class="ajax-link" href="client.php?&pid=<?php echo $menu['id']; ?>&edit=true">
                                                        <button type="button" class="btn btn-xs btn-danger pull-right" name="editclient"><i class="fa fa-pencil"></i> </button>
                                                    </a>
                                                </td>
                                                <td>


                                                    <a href="client.php?id=<?php echo $menu['id']; ?>&action=delete" onclick="return confirm('Are you sure you want to delete this item?');">
                                                        <button type="submit" class="btn btn-xs btn-danger pull-right" style="margin:0px 10px;" name="delete"><i class="fa-solid fa-trash"></i></button>
                                                    </a>

                                                </td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>

                                </table>
                            </div>

                        </div>
                    </div>
                </div>

            </div>









        <?php } ?>
    </div>
































</div>
</div>





</div>






<?php include 'include/footer.php' ?>