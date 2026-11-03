<?php
$umessage = '';
include('./function/function.php');
check_session();
if (isset($_POST['addabout'])) {
    $id = 0;
    extract($_POST);
    $type = 1;
    $pdo = getPDOObject();

    $q = $pdo->prepare("INSERT into `about` values(:id,:name,:short, :short2,  :des)");
    $q->execute(array(
        ':id' => $id,
        ':name' => $name,
        ':short' => $short,
        ':short2' => $short2,
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
    $affected_rows = update('about', $posted_data, array('id' => $pid));
    //$affected_rows = $q->rowCount();
    if ($affected_rows)
        $umessage = '<div class="alert alert-success" role="alert">
							<strong></strong>Updated Successfully
						   </div>';
}

function about_form($pid = '0', $name = '',   $short = '', $short2 = '',  $des = '', $photo = '', $formname = 'addabout')
{ ?>

    <form action="about_us.php?&pid=<?php echo $_GET['pid']; ?>&edit=true" method="post" enctype="multipart/form-data">
        <div class="form theme-form">
            <input type="hidden" name="pid" value="<?php echo $pid; ?>" />
            <input type="hidden" name="prevphoto" value="<?php echo $photo; ?>" />
            <div class="row">
                <div class="col-lg-12  mt-3">
                    <div class="row">
                        <div class="col-lg-4 mt-4">

                            <div class="input-group input-group-merge">

                                <span class="input-group-text">@</span>

                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="name" placeholder="Add Name" aria-label="Upload" value="<?php echo $name; ?>">
                                    <label for="basic-addon11">Add Name</label>

                                </div>

                            </div>

                        </div>
                          <div class="col-lg-4 mt-4">

                            <div class="input-group input-group-merge">

                                <span class="input-group-text">@</span>

                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="short" placeholder="Add Years" aria-label="Upload" value="<?php echo $short; ?>">
                                    <label for="basic-addon11">Add Years</label>

                                </div>

                            </div>

                        </div>
                        
                        <div class="col-lg-4 mt-4">

                            <div class="input-group input-group-merge">

                                <span class="input-group-text">@</span>

                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="short2" placeholder="Add Other Text" aria-label="Upload" value="<?php echo $short2; ?>">
                                    <label for="basic-addon11">Add Other Text</label>

                                </div>

                            </div>

                        </div>
                        
                        
                        <div class="col-lg-4 mt-4">

                            <div class="input-group input-group-merge">

                                <span class="input-group-text">@</span>

                                <div class="form-floating form-floating-outline">
                                    <input type="file" class="form-control" name="photo" aria-label="Upload" value="<?php echo $photo; ?>">
                                    <label for="basic-addon11">Client Photo</label>

                                </div>

                            </div>
                            <a href="../upload/<?php echo $photo; ?>" target="_blank">
                                <img src="../upload/<?php echo $photo; ?>" style=" border-radius: 12px;width: 39px;height: 35px;">
                            </a>
                        </div>

                        <div class="col-lg-12  mt-3">
                            <div class="input-group input-group-merge">

                                <div class="form-floating form-floating-outline">
                                    <label for="basic-addon11">Description Text</label>
                                    <br><br>
									

<!-- Hidden textarea to submit content -->
                                        <textarea name="des" class="page_data editor"><?php echo $des;?></textarea>

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








            </div>



        </div>
    </form>

<?php
}


?>

<?php require('include/header.php'); ?>
<!-- Include required files -->


<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">


        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Home/</span>Content</h4>


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
                                $productdata = sqlfetch("SELECT * FROM `about` where id='$pid' ");
                                foreach ($productdata as $product) {
                                    extract($product);
                                    about_form($pid, $name, $short,  $short2,   $des, $photo, $formname = 'editdone');
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        <?php
        } else {
        ?>










        <?php } ?>
<!-- Quill v2 CSS -->
<script>
  ClassicEditor
    .create(document.querySelector('#quill-content'))
    .catch(error => console.error(error));
</script>


        <?php require('include/footer.php'); ?>