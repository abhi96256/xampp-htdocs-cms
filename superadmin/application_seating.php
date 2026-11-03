<?php
$umessage = '';
include('./function/function.php');
check_session();
if (isset($_POST['addabout'])) {
    $id = 0;
    extract($_POST);
    $type = 1;
    $pdo = getPDOObject();

    $q = $pdo->prepare("INSERT into `about` values(:id,:des)");
    $q->execute(array(
        ':id' => $id,
        ':des' => $des
    ));
    $affected_rows = $q->rowCount();
    if ($affected_rows)
        $umessage = '<div class="alert alert-success" role="alert">
							<strong></strong>Added Successfully
						   </div>';
}
if (isset($_POST['deleteall'])) {
    $arr = $_POST['ids'];
    if (count($arr)) {
        $str_rest_refs = implode(",", $arr);

        $data = sqlfetch("select * from `about` where id in ($str_rest_refs)");
        foreach ($data as $category) {
            $img_path = '../upload/' . $category['photo'];
            if (file_exists($img_path)) {
                unlink($img_path);
            }
        }

        $pdo = getPDOObject();
        $q = $pdo->query("DELETE FROM `about` WHERE id in ($str_rest_refs)");
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
        $q = $pdo->query("UPDATE `about` SET actstat='1' WHERE id in ($str_rest_refs)");
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
        $q = $pdo->query("UPDATE `about` SET actstat='0' WHERE id in ($str_rest_refs)");
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
    $pdo = getPDOObject();
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
    /*$q=$pdo->prepare("UPDATE `about` SET 
		des=?
		
		WHERE id=?");
				$q->execute(array($des, $pid));	*/
    $affected_rows = update('address', $posted_data, array('id' => $pid));
    //$affected_rows = $q->rowCount();
    if ($affected_rows)
        $umessage = ' <div class="alert alert-primary alert-dismissible" role="alert">
        Updated Successfully!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
        </button>
      </div>';
}

function about_form($pid = '0', $addr = '', $email = '', $phone = '', $des = '', $photo = '', $link = '', $facebook = '', $twitter = '', $youtube = '', $linkedin = '', $test_date = '', $class8 = '', $class9 = '', $class10 = '', $class11 = '', $all_tag = '', $linkedin2 = '', $ticker_speed = '40', $formname = 'addabout')
{ ?>


    <form action="application_seating.php?&pid=<?php echo $_GET['pid']; ?>&edit=true" method="post" enctype="multipart/form-data">
        <div class="form theme-form">
            <input type="hidden" name="pid" value="<?php echo $pid; ?>" />
            <input type="hidden" name="prevphoto" value="<?php echo $photo; ?>" />
            <div class="row">

                <div class="col-lg-4 mt-3">

                    <div class="input-group input-group-merge">

                        <span class="input-group-text">@</span>

                        <div class="form-floating form-floating-outline">
                            <input type="file" class="form-control" name="photo" aria-label="Upload" value="<?php echo $photo; ?>">
                            <label for="basic-addon11">Company Logo</label>

                        </div>

                    </div>
                    <img src="../upload/<?php echo $photo; ?>" style=" border-radius: 12px;width: 39px;height: 35px;">
                </div>
                <div class="col-lg-4  mt-3">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">@</span>
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" name="addr" placeholder="Address" aria-label="Address" value="<?php echo $addr; ?>" />
                            <label for="basic-addon11">Address</label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4  mt-3">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">@</span>
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" name="phone" placeholder="Phone" aria-label="Phone" value="<?php echo $phone; ?>" />
                            <label for="basic-addon11">Phone</label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4  mt-3">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">@</span>
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" name="email" placeholder="Email" aria-label="Email" value="<?php echo $email; ?>" />
                            <label for="basic-addon11">Email</label>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4  mt-3">
                    <div class="form-floating form-floating-outline">
                        <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                            <option value="Active" <?php if ($link == 'Active') { ?>selected<?php } ?>>Active</option>
                            <option value="Inactive" <?php if ($link == 'Inactive') { ?>selected<?php } ?>>Inactive</option>
                        </select>
                        <label for="floatingSelect">Status</label>
                    </div>
                </div>
                <div class="col-lg-4  mt-3">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">@</span>
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" name="test_date" placeholder="Meta Title" aria-label="Phone" value="<?php echo $test_date; ?>" />
                            <label for="basic-addon11">Meta Title</label>
                        </div>
                    </div>
                </div>
                
                
                
                <div class="col-lg-4  mt-5">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">@</span>
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" name="class8" placeholder="Meta Keyword" aria-label="Phone" value="<?php echo $class8; ?>" />
                            <label for="basic-addon11">Meta Keyword</label>
                        </div>
                    </div>
                </div>
                
                
                
                <div class="col-lg-4  mt-5">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">@</span>
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" name="class9" placeholder="Meta Description" aria-label="Phone" value="<?php echo $class9; ?>" />
                            <label for="basic-addon11">Meta Description</label>
                        </div>
                    </div>
                </div>
                
                
                
                <div class="col-lg-4  mt-5">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">@</span>
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" name="facebook" placeholder="Facebook" aria-label="Phone" value="<?php echo $facebook; ?>" />
                            <label for="basic-addon11">Facebook</label>
                        </div>
                    </div>
                </div>
                
                
                <div class="col-lg-4  mt-5">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">@</span>
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" name="youtube" placeholder="Instagram" aria-label="Phone" value="<?php echo $youtube; ?>" />
                            <label for="basic-addon11">Instagram</label>
                        </div>
                    </div>
                </div>
                
                
                
                <div class="col-lg-4  mt-5">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">@</span>
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" name="linkedin" placeholder="Linkedin" aria-label="Phone" value="<?php echo $linkedin; ?>" />
                            <label for="basic-addon11">Youtube</label>
                        </div>
                    </div>
                </div>
                
                
                
                <div class="col-lg-4  mt-5">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">@</span>
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" name="twitter" placeholder="Twitter" aria-label="Phone" value="<?php echo $twitter; ?>" />
                            <label for="basic-addon11">Twitter</label>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-12  mt-5">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">@</span>
                        <div class="form-floating form-floating-outline">
                            <input type="number" class="form-control" name="ticker_speed" placeholder="Trending Speed (Seconds)" aria-label="Trending Speed" value="<?php echo $ticker_speed; ?>" />
                            <label for="basic-addon11">Trending Speed (Smaller is Faster, default 40)</label>
                        </div>
                    </div>
                </div>
                
                 <div class="col-lg-12  mt-5">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text">@</span>
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" name="linkedin2" placeholder="Twitter" aria-label="Phone" value="<?php echo $linkedin2; ?>" />
                            <label for="basic-addon11">Linkedin</label>
                        </div>
                    </div>
                </div>




               <div class="col-lg-12 mt-5">
    <div class="input-group input-group-merge">
        <span class="input-group-text">@</span>
        <div class="form-floating form-floating-outline">
            <textarea class="form-control rrff no-tinymce" name="all_tag" placeholder="All Seo Tags" aria-label="All Seo Tags"><?php echo htmlspecialchars($all_tag); ?></textarea>
            <label for="basic-addon11">All Seo Tags</label>
        </div>
    </div>
</div>

                <!--<br> -->
                <!--   <a href="view_code.php?&pid=<?php echo $_GET['pid']; ?>&edit=true" target="_blank" class="text-danger"> View Added Code</a>-->





                <div class="col-lg-2  mt-5">
                    <div class="input-group input-group-merge">
                        <button class="btn btn-primary waves-effect w-100 waves-light" type="submit" value="Submit" name="<?php echo $formname; ?>">
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


<style>
    .rrff{
        height:400px !important;
    }
    
</style>


<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">


        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Home/</span>Common Settings</h4>

        <!-- <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Manage Here</h5>
                    <div class="card-body demo-vertical-spacing demo-only-element z4">
                        <div class="row">
                            <div class="col-lg-4 mt-3">
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">@</span>
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="basic-addon11" placeholder="John Doe" aria-label="Username" aria-describedby="basic-addon11" />
                                        <label for="basic-addon11">Username</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4  mt-3">
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">@</span>
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="basic-addon11" placeholder="John Doe" aria-label="Username" aria-describedby="basic-addon11" />
                                        <label for="basic-addon11">Username</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4  mt-3">
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">@</span>
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="basic-addon11" placeholder="John Doe" aria-label="Username" aria-describedby="basic-addon11" />
                                        <label for="basic-addon11">Username</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->






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
                                $productdata = sqlfetch("SELECT * FROM `address` where id='$pid' ");
                                foreach ($productdata as $product) {
                                    extract($product);
                                    about_form($pid, $addr, $email, $phone, $des, $photo, $link, $facebook, $twitter, $youtube, $linkedin, $test_date, $class8, $class9, $class10, $class11,  $all_tag, $linkedin2, $ticker_speed, $formname = 'editdone');
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        <?php
        } else {
        ?>


            <!-- <div class="row">
            <div class="box col-md-12">
                <div class="box-inner">
                    <div class="box-header well" data-original-title="">
                        <h2><i class="glyphicon glyphicon-edit"></i>Address</h2>

                        <div class="box-icon">

                            <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                            <a href="#" class="btn btn-close btn-round btn-default"><i class="glyphicon glyphicon-remove"></i></a>
                        </div>
                    </div>
                    <div class="box-content">
                        <table class="table table-bordered table-striped responsive">
                            <tbody>


                                <tr>

                                    <th>Address</th>


                                </tr>
                                <?php
                                $count = 1;
                                $data = sqlfetch("SELECT * FROM `address` where id='1' order by id");
                                foreach ($data as $menu) { ?>
                                    <tr>

                                        <td><?php echo $menu['des']; ?></td>



                                        <td>

                                            <a class="ajax-link" href="application_seating.php?&pid=<?php echo $menu['id']; ?>&edit=true">
                                                <button type="button" class="btn btn-xs btn-danger pull-right" name="editcategory">Edit</button>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> -->


        <?php } ?>


        <?php require('include/footer.php'); ?>