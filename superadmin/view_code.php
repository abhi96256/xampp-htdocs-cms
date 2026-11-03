<?php
$umessage = '';
include('./function/function.php'); ?>


<?php require('include/header.php'); ?>


<style>
    .rrff{
        height:400px !important;
    }
    .address-box {
    background-color: #f9f9f9; /* Light gray background */
    border: 1px solid #ccc; /* Gray border */
    padding: 15px; /* Padding inside the box */
    margin: 10px 0; /* Space between boxes */
    border-radius: 5px; /* Rounded corners */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    font-family: Arial, sans-serif; /* Font style */
    color: #333; /* Text color */
}

</style>


<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">


        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Home/</span>Common Settings</h4>

         <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Manage Here</h5>
                    <div class="card-body demo-vertical-spacing demo-only-element z4">
                        <div class="row">
                            <div class="col-lg-12 mt-3">
                                <div class="input-group input-group-merge">
                                  
                                 
                                 <?php
                            $sql_gal = sqlfetch("SELECT * FROM address WHERE id = 1"); 
                            if (count($sql_gal)) {
                                foreach ($sql_gal as $address) {
                                    echo '<div class="address-box">' . htmlspecialchars($address['all_tag']) . '</div>';
                                }
                            }
                            ?>

                                  
                                  
                                  
                                  
                                  
                                  
                                  
                                  
                                  
                                  
                                </div>
                            </div>
                          
                        </div>
                    </div>
                </div>
            </div>
        </div> 









        <?php require('include/footer.php'); ?>