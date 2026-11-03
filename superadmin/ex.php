<?php
error_reporting(E_ALL);
include("function/function.php");
include("include/header.php");

// $con = mysqli_connect('localhost', 'shri2595_corprate', 'shri2595_corprate', 'shri2595_corprate');
$con = mysqli_connect('localhost', 'root', '', 'corprate');
if (isset($_POST['submit'])) {
    $file = $_FILES['doc']['tmp_name'];

    $ext = pathinfo($_FILES['doc']['name'], PATHINFO_EXTENSION);
    if ($ext == 'xlsx') {
        require('PHPExcel/PHPExcel.php');
        require('PHPExcel/PHPExcel/IOFactory.php');
        $obj = PHPExcel_IOFactory::load($file);
        foreach ($obj->getWorksheetIterator() as $sheet) {
            $getHighestRow = $sheet->getHighestRow();
            for ($i = 0; $i <= $getHighestRow; $i++) {
                $name = $sheet->getCellByColumnAndRow(0, $i)->getValue();
                $des = $sheet->getCellByColumnAndRow(1, $i)->getValue();
                $pro_lable = $sheet->getCellByColumnAndRow(2, $i)->getValue();
                $pro_specification = $sheet->getCellByColumnAndRow(3, $i)->getValue();
                $pro_mainprice = $sheet->getCellByColumnAndRow(4, $i)->getValue();
                $pro_discountprice = $sheet->getCellByColumnAndRow(5, $i)->getValue();
                $pro_shortdes = $sheet->getCellByColumnAndRow(6, $i)->getValue();
                $pro_additionalinfo = $sheet->getCellByColumnAndRow(7, $i)->getValue();
                $actstat = $sheet->getCellByColumnAndRow(8, $i)->getValue();
                if ($name != '') {
                    mysqli_query($con, "insert into subproduct(name,des,pro_lable,pro_specification,pro_mainprice,pro_discountprice,pro_shortdes,pro_additionalinfo,actstat) 
                    values('$name',' $des', '$pro_lable','$pro_specification','$pro_mainprice','$pro_discountprice','$pro_shortdes','$pro_additionalinfo','$actstat')");
                }
            }
        }
    } else {

        echo '<script>alert("Invalid file format")</script>';
    }
}
?>

<?php
if ($ext == 'xlsx') {
    echo '<script>alert("Added Succesfully")</script>';
}
?>


<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">


        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Home/</span>Content</h4>




        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header  text-danger">Only Excel File Supported* </h5>
                    <div class="card-body demo-vertical-spacing demo-only-element z4">
                        <div class="row">

                            <form method="post" enctype="multipart/form-data">
                                <div class="form theme-form">

                                    <div class="row">
                                        <div class="col-lg-12  mt-3">
                                            <div class="row">
                                                <div class="col-lg-6 mt-4">

                                                    <div class="input-group input-group-merge">

                                                        <span class="input-group-text">@</span>

                                                        <div class="form-floating form-floating-outline">
                                                            <input type="file" class="form-control" type="file" name="doc">
                                                            <label for="basic-addon11">Upload Bulk Pincode</label>

                                                        </div>

                                                    </div>

                                                </div>


                                                <div class="col-lg-12  mt-5">
                                                    <div class="input-group input-group-merge">
                                                        <button class="btn btn-primary waves-effect  waves-light" type="submit" name="submit" value="submit">
                                                            <span class=" align-middle">Submit</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>








                                    </div>



                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>





    </div>


    <?php include("include/footer.php"); ?>