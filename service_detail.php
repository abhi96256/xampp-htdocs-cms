<?php include "function/function.php"; ?>
<?php
$sql_add = sqlfetch("select * from address where id=1");
if (count($sql_add))
    foreach ($sql_add as $pr_add) {
    }
?>
<?php
$pid = $_GET['id'];
$sql_ser = findRecordBySlug('subproduct', $pid);

// Redirect if no property found to avoid blank page
if (count($sql_ser) == 0) {
    echo "<script>window.location.href='".SITE_URL."index.php';</script>";
    exit;
}

if (count($sql_ser)) {
    foreach ($sql_ser as $subproductss) {

?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <title><?php echo $subproductss['meta_title']; ?></title>
            <meta name="description" content="<?php echo $subproductss['meta_description']; ?>">
            <meta name="keywords" content="<?php echo $subproductss['meta_keyword']; ?>">
            <meta name="facebook-domain-verification" content="m7nn0fxbw83tklfswjvxxzpz42u09w" />
            <?php include 'include/favicon.php'; ?>
            
            <meta property="og:title" content="<?php echo htmlspecialchars($subproductss['meta_title']); ?>">
            <meta property="og:description" content="<?php echo htmlspecialchars($subproductss['meta_description']); ?>">
            <?php 
            $_og_photo = !empty($subproductss['photo']) ? trim($subproductss['photo']) : '080325100432logo.png';
            $_og_img = SITE_URL . 'upload/' . str_replace(' ', '%20', $_og_photo);
            ?>
            <meta property="og:image" content="<?php echo $_og_img; ?>?v=<?php echo date('Ymd'); ?>">
            <meta property="og:image:secure_url" content="<?php echo $_og_img; ?>?v=<?php echo date('Ymd'); ?>">
            <meta property="og:image:width" content="1200">
            <meta property="og:image:height" content="630">
            <meta property="og:url" content="<?php echo SITE_URL . ltrim($_SERVER['REQUEST_URI'], '/'); ?>">
            <meta property="og:type" content="website">
            <meta property="og:site_name" content="A2P Realtech">
            <!-- Twitter/X Card -->
            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:title" content="<?php echo htmlspecialchars($subproductss['meta_title']); ?>">
            <meta name="twitter:description" content="<?php echo htmlspecialchars($subproductss['meta_description']); ?>">
            <meta name="twitter:image" content="<?php echo $_og_img_resized; ?>">

            
            
            
            <link rel="preconnect" href="https://fonts.googleapis.com/">
            <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
                rel="stylesheet">
            <link rel="stylesheet" href="<?= SITE_URL; ?>assets/vendors/bootstrap/css/bootstrap.min.css" />
            <link rel="stylesheet" href="<?= SITE_URL; ?>assets/vendors/animate/animate.min.css" />
            <link rel="stylesheet" href="<?= SITE_URL; ?>assets/vendors/animate/custom-animate.css" />
            <link rel="stylesheet" href="<?= SITE_URL; ?>assets/vendors/fontawesome/css/all.min.css" />
            <link rel="stylesheet" href="<?= SITE_URL; ?>assets/vendors/jarallax/jarallax.css" />
            <link rel="stylesheet" href="<?= SITE_URL; ?>assets/vendors/jquery-magnific-popup/jquery.magnific-popup.css" />
            <link rel="stylesheet" href="<?= SITE_URL; ?>assets/vendors/nouislider/nouislider.min.css" />
            <link rel="stylesheet" href="<?= SITE_URL; ?>assets/vendors/nouislider/nouislider.pips.css" />
            <link rel="stylesheet" href="<?= SITE_URL; ?>assets/vendors/odometer/odometer.min.css" />
            <link rel="stylesheet" href="<?= SITE_URL; ?>assets/vendors/swiper/swiper.min.css" />
            <link rel="stylesheet" href="<?= SITE_URL; ?>assets/vendors/ambed-icons/style.css">
            <link rel="stylesheet" href="<?= SITE_URL; ?>assets/vendors/tiny-slider/tiny-slider.min.css" />
            <link rel="stylesheet" href="<?= SITE_URL; ?>assets/vendors/reey-font/stylesheet.css" />
            <link rel="stylesheet" href="<?= SITE_URL; ?>assets/vendors/owl-carousel/owl.carousel.min.css" />
            <link rel="stylesheet" href="<?= SITE_URL; ?>assets/vendors/owl-carousel/owl.theme.default.min.css" />
            <link rel="stylesheet" href="<?= SITE_URL; ?>assets/vendors/bxslider/jquery.bxslider.css" />
            <link rel="stylesheet" href="<?= SITE_URL; ?>assets/vendors/bootstrap-select/css/bootstrap-select.min.css" />
            <link rel="stylesheet" href="<?= SITE_URL; ?>assets/vendors/vegas/vegas.min.css" />
            <link rel="stylesheet" href="<?= SITE_URL; ?>assets/vendors/jquery-ui/jquery-ui.css" />
            <link rel="stylesheet" href="<?= SITE_URL; ?>assets/vendors/timepicker/timePicker.css" />
            <link rel="stylesheet" href="<?= SITE_URL; ?>assets/css/ambed.css" />
            <link rel="stylesheet" href="<?= SITE_URL; ?>assets/css/ambed-responsive.css" />
            <link rel="stylesheet" id="jssMode" href="<?= SITE_URL; ?>assets/css/ambed-light.css">
            <link rel="stylesheet" href="<?= SITE_URL; ?>assets/vendors/toolbar/css/toolbar.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        </head>

        <style>

            /* FORCE TABLES TO FIT IN MOBILE VIEW (NO SCROLL) */
            .table-responsive-wrapper {
                width: 100% !important;
                overflow-x: hidden !important;
                margin-top: 20px;
                margin-bottom: 20px;
            }

            .tab-content table {
                width: 100% !important;
                table-layout: fixed !important; /* Force columns to stay within 100% */
                border-collapse: collapse;
                border-spacing: 0;
                font-family: Arial, sans-serif;
                font-size: 13px; /* Slightly smaller to fit better */
            }

            .tab-content table th, .tab-content table td {
                word-break: break-all !important; /* Force break long text */
                overflow-wrap: anywhere !important;
                padding: 5px !important;
            }

            .tab-content th {
                background-color: #1a5fa8;
                color: #fff;
                text-align: left;
                padding: 12px;
                font-weight: bold;
                white-space: nowrap;
            }

            .tab-content td {
                padding: 10px;
                border-bottom: 1px solid #ddd;
                vertical-align: top;
            }

            .tab-content tr:nth-child(even) {
                background-color: #f0f6ff;
            }

            .tab-content tr:hover {
                background-color: #dce9f9;
            }

            .tab-content table,
            .tab-content th,
            .tab-content td {
                border: 1px solid #ccd;
            }

            /* On mobile: Force table to fit screen width exactly */
            @media screen and (max-width: 991px) {
                .tab-content table {
                    font-size: 11px;
                    width: 100% !important;
                    min-width: 0 !important;
                    table-layout: fixed !important;
                }
                .tab-content th,
                .tab-content td {
                    padding: 5px 2px !important;
                    word-break: break-all !important;
                }
            }



        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
            /* Alignment for Product Images - Ultra Aggressive Mobile Fix */
            .blog-details__img img,
            .blog-details__left img,
            .tab-content img {
                width: 100% !important; /* Forces responsive width */
                max-width: 100% !important;
                height: auto !important;
                max-height: none !important;
                object-fit: contain !important;
                display: block !important;
                margin: 10px auto !important;
                box-shadow: none !important;
            }

            @media (max-width: 991px) {
                .blog-details__img img,
                .blog-details__left img,
                .tab-content img {
                    width: 100% !important;
                    max-width: 100% !important;
                    height: auto !important;
                    object-fit: contain !important;
                    display: block !important;
                    margin: 10px auto !important;
                }
                .blog-details {
                    padding: 20px 0 !important;
                    overflow-x: hidden !important;
                }
                .blog-details__left {
                    width: 100% !important;
                    overflow-x: hidden !important;
                }
            }

            .project-three__imgs img {
                width: 100%;
                height: 250px;
                object-fit: cover;
                border-radius: 8px;
            }

            /* Gallery items alignment */
            .project-three__single {
                margin-bottom: 30px;
            }

            /* Video container */
            .video-container {
                position: relative;
                padding-bottom: 56.25%; /* 16:9 aspect ratio */
                height: 0;
                overflow: hidden;
                max-width: 100%;
                background: #000;
                border-radius: 12px;
            }
            .video-container iframe {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                border: 0;
            }

            .tab-content li {
                color: black;
            }

            /* Tab Nav Styles */
            .con_tabs .nav-tabs .nav-link {
                border: none;
                font-size: 1.1rem;
                padding: 12px 10px;
                transition: background-color 0.3s ease, box-shadow 0.3s ease;
                margin: 0 5px;
                color: #333;
                background: #f8f9fa;
            }

            .con_tabs .nav-tabs .nav-link.active {
                background-color: #c00415;
                color: #fff;
                box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            }

            .con_tabs .nav-tabs .nav-link:hover {
                background-color: #c00415;
                color: #fff;
                box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            }

            /* Tab Content Animation */
            .con_tabs .tab-pane {
                opacity: 0;
                transform: translateY(20px);
                transition: opacity 0.5s ease, transform 0.5s ease;
            }

            .con_tabs .tab-pane.show.active {
                opacity: 1;
                transform: translateY(0);
            }

            /* Card-style shadow for tab content */
            .con_tabs .tab-content {
                margin-top: 20px;
                padding: 20px;
                background: #fff;
                border-radius: 8px;
                box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
            }

            /* Extra styling for headers inside tabs */
            .con_tabs h3 {
                font-size: 1.5rem;
                color: #c00415;
                margin-bottom: 15px;
            }

            /* Link styles in the brochure tab */
            .con_tabs a {
                text-decoration: none;
                color: #c00415;
                transition: color 0.3s ease;
            }

            .con_tabs a:hover {
                color: #c00415;
            }
        </style>



        <?php include 'include/header.php' ?>



<style>
.btn-primary {
    padding: 8px 19px;
    color: #fff;
    background-color: #60020a;
    border-color: #000000;
}


.form-label {
    color: black;
    margin-bottom: .5rem;
}


       #enquiryBtn {
    position: fixed;
    right: -44px;
    top: 50%;
    transform: rotate(4.7137rad);
    z-index: 1050;
    padding: 10px 20px;
    font-size: 18px;
    background-color: #c00415;
    color: white;
    border: none;
    border-radius: 5px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

#enquiryBtn a
{
    text-decoration: none;
    color: white;
}
    </style>





<!-- Enquiry Now Button -->
    <button id="enquiryBtn" data-bs-toggle="modal">
        <a href="https://a2prealtech.com/contact.php">Enquiry Now</a>
    </button>
    
    
    

    <!-- Enquiry Modal -->
    <div class="modal fade" id="enquiryModal11" tabindex="-1" aria-labelledby="enquiryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="enquiryModalLabel">Enquiry Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                   <form id="enquiryForm" action="<?= SITE_URL; ?>mail.php" method="post">
    <div class="row">
        <!-- Name input in col-lg-6 -->
        <div class="col-lg-6 mb-3">
            <label for="name" class="form-label">Your Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <!-- Phone input in col-lg-6 -->
        <div class="col-lg-6 mb-3">
            <label for="phone" class="form-label">Your Phone</label>
            <input type="number" class="form-control" id="phone" name="phone" required>
        </div>
        <!-- Email input in col-lg-6 -->
        <div class="col-lg-6 mb-3">
            <label for="email" class="form-label">Your Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <!-- Subject input in col-lg-6 -->
        <div class="col-lg-6 mb-3">
            <label for="subject" class="form-label">Your Subject</label>
            <input type="text" class="form-control" id="subject" name="subject" required>
        </div>
        <!-- Message input in col-lg-12 -->
        <div class="col-lg-12 mb-3">
            <label for="message" class="form-label">Your Message</label>
            <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Submit Enquiry</button>
</form>

                   
                </div>
            </div>
        </div>
    </div>






      <style>
            .social-share-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
}

.social-share-buttons a {
    display: inline-block;
    width: 45px;
    height: 45px;
    background-color: #f1f1f1;
    border-radius: 50%;
    text-align: center;
    line-height: 45px;
    transition: all 0.4s ease;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.social-share-buttons a i {
    font-size: 18px;
    color: #fff;
    transition: all 0.3s ease;
}

/* Branded Backgrounds */
.social-share-buttons a.facebook { background: #1877F2; }
.social-share-buttons a.twitter { background: #000000; }
.social-share-buttons a.linkedin { background: #0077B5; }
.social-share-buttons a.whatsapp { background: #25D366; }
.social-share-buttons a.share { background: #6c757d; }

/* Hover effects */
.social-share-buttons a:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.social-share-buttons a:hover i {
    transform: scale(1.1);
}






        </style>




<?php
$rawBlogName = $subproductss['name'];
$encodedBlogName = urlencode($rawBlogName);
$rawPageUrl = SITE_URL . ltrim($_SERVER['REQUEST_URI'], '/');
$encodedPageUrl = urlencode($rawPageUrl);
?>





        <section class="page-header">
            <div class="page-header-bg" style="background-image: url(<?= SITE_URL; ?>assets/images/backgrounds/page-header-bg.jpg)">
            </div>
            <div class="container">
                <div class="page-header__inner">
                    <ul class="thm-breadcrumb list-unstyled">
                        <li><a href="<?= SITE_URL; ?>">Home</a></li>
                        <li><span>/</span></li>
                        <li> <?php custom_echo($subproductss['name'], 30); ?></li>
                    </ul>
                    <h2><?php custom_echo($subproductss['name'], 70); ?></h2>
                </div>
            </div>
        </section>


        <section class="blog-details">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-8 col-md-12">
                        <div class="blog-details__left">
                            <div class="blog-details__img">
                                <img src="<?= SITE_URL; ?>upload/<?php echo trim($subproductss['photo']); ?>" alt=" <?php echo $subproductss['name']; ?>">
                            </div>
                            <div class="blog-details__content">

                                <h3 class="blog-details__title"><?php echo $subproductss['name']; ?></h3>

                                 <div class="mob_share d-block d-lg-none mt-4 mb-4">
                                    <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 10px; color: #333;">Social Media Share</h3>
                                    <div class="social-share-buttons">
                                        <a href="#" onclick="shareFacebook(event)" class="facebook" title="Share on Facebook">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                        <a href="https://twitter.com/intent/tweet?text=<?php echo $encodedBlogName; ?>&amp;url=<?php echo $encodedPageUrl; ?>" target="_blank" class="twitter" title="Share on Twitter">
                                            <i class="fab fa-x-twitter"></i>
                                        </a>
                                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $encodedPageUrl; ?>" target="_blank" class="linkedin" title="Share on LinkedIn">
                                            <i class="fab fa-linkedin-in"></i>
                                        </a>
                                        <a href="https://api.whatsapp.com/send?phone=918130525001&text=Hello! I am interested in: <?php echo $encodedBlogName; ?> (<?php echo $encodedPageUrl; ?>)" target="_blank" class="whatsapp" title="Enquire on WhatsApp">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                        <a href="#" class="share" title="Share" onclick="shareContent(event)">
                                            <i class="fas fa-share-alt"></i>
                                        </a>
                                    </div>
                                </div>


                            </div>



                         <br><br>
                           </div>
                        



                            <div class="con_tabs mt-5">
                                <!-- Tabs navigation -->
                                <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Description</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="amenities-tab" data-bs-toggle="tab" data-bs-target="#amenities" type="button" role="tab" aria-controls="amenities" aria-selected="false">Amenities</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="floorplan-tab" data-bs-toggle="tab" data-bs-target="#floorplan" type="button" role="tab" aria-controls="floorplan" aria-selected="false">Floor Plan</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="gallery-tab" data-bs-toggle="tab" data-bs-target="#gallery" type="button" role="tab" aria-controls="gallery" aria-selected="false">Gallery</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="map-tab" data-bs-toggle="tab" data-bs-target="#map" type="button" role="tab" aria-controls="map" aria-selected="false">Map</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="video-tab" data-bs-toggle="tab" data-bs-target="#video" type="button" role="tab" aria-controls="video" aria-selected="false">Video</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="download-link" ><?php echo !empty($subproductss['photo4']) ? 'Download Brochure' : 'Request Brochure'; ?></button>
                                    </li>
                                </ul>

                                <!-- Tabs content -->
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                                        <h3>Description</h3>
                                        <?php echo $subproductss['pro_additionalinfo']; ?>
                                    </div>
                                    <div class="tab-pane fade" id="amenities" role="tabpanel" aria-labelledby="amenities-tab">
                                        <h3>Amenities</h3>
                                        <?php echo $subproductss['des']; ?>
                                    </div>
                                    <div class="tab-pane fade" id="floorplan" role="tabpanel" aria-labelledby="floorplan-tab">
                                        <h3>Floor Plan</h3>



                                        <div class="thm-owl__carousel owl-theme owl-carousel owl-with-shadow owl-dot-one owl-dot-one--md owl-nav-one owl-nav-one--md"
                                            data-owl-options='{
                                                        "items": 3,
                                                        "margin": 30,
                                                        "smartSpeed": 700,
                                                        "loop":true,
                                                        "autoplay": 6000,
                                                        "nav":true,
                                                        "dots":true,
                                                        "navText": ["<span class=\"fa fa-angle-left\"></span>","<span class=\"fa fa-angle-right\"></span>"],
                                                        "responsive":{
                                                            "0":{
                                                                "items":1
                                                            },
                                                            "575":{
                                                                "items":1
                                                            },
                                                            "992":{
                                                                "items": 2
                                                            }
                                                        }
                                                    }'>


                                            <!--<div class="item">-->

                                            <!--    <div class="project-three__single">-->
                                            <!--        <div class="project-three__img-box">-->
                                            <!--            <div class="project-three__imgs">-->
                                            <!--                <img src="<?= SITE_URL; ?>upload/<?php echo $subproductss['photo2']; ?>" alt="" style="height:250px;">-->

                                            <!--            </div>-->
                                            <!--        </div>-->
                                            <!--    </div>-->
                                            <!--</div>-->

                                            <!--<div class="item">-->

                                            <!--    <div class="project-three__single">-->
                                            <!--        <div class="project-three__img-box">-->
                                            <!--            <div class="project-three__imgs"> -->
                                            <!--                <img src="<?= SITE_URL; ?>upload/<?php echo $subproductss['photo3']; ?>" alt="" style="height:250px;">-->

                                            <!--            </div>-->
                                            <!--        </div>-->
                                            <!--    </div>-->
                                            <!--</div>-->
                                            
                                            
                                      
                                            <?php
                                            $sub_cat = sqlfetch("SELECT * FROM subcategory2 where subcat='" . $subproductss['id'] . "' ");
                                            if (count($sub_cat)) {
                                                foreach ($sub_cat as $subcategory2ee) {
                                            ?>

                                            
                                            
                                            
                                            
                                             <div class="item">

                                                <div class="project-three__single">
                                                    <div class="project-three__img-box">
                                                        <div class="project-three__img">
                                                            <img src="<?= SITE_URL; ?>upload/<?php echo trim($subcategory2ee['photo']); ?>" alt="">
                                                            
                                                            <h3 class="mt-4"><?php echo $subcategory2ee['name']; ?></h3>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php }
                                            } ?>


                                        </div>



                                    </div>
                                    <div class="tab-pane fade" id="gallery" role="tabpanel" aria-labelledby="gallery-tab">
                                        <h3>Gallery</h3>


                                        <div class="thm-owl__carousel owl-theme owl-carousel owl-with-shadow owl-dot-one owl-dot-one--md owl-nav-one owl-nav-one--md"
                                            data-owl-options='{
                                                        "items": 3,
                                                        "margin": 30,
                                                        "smartSpeed": 700,
                                                        "loop":true,
                                                        "autoplay": 6000,
                                                        "nav":true,
                                                        "dots":true,
                                                        "navText": ["<span class=\"fa fa-angle-left\"></span>","<span class=\"fa fa-angle-right\"></span>"],
                                                        "responsive":{
                                                            "0":{
                                                                "items":1
                                                            },
                                                            "575":{
                                                                "items":1
                                                            },
                                                            "992":{
                                                                "items": 2
                                                            }
                                                        }
                                                    }'>


                                            <?php
                                            $sub_cat = sqlfetch("SELECT * FROM sub_image where subcat='" . $subproductss['id'] . "' ");
                                            if (count($sub_cat)) {
                                                foreach ($sub_cat as $sub_image) {
                                            ?>


                                                    <div class="item">

                                                        <div class="project-three__single">
                                                            <div class="project-three__img-box">
                                                                <div class="project-three__imgs">
                                                                    <img src="<?= SITE_URL; ?>upload/<?php echo trim($sub_image['photo']); ?>" alt=" <?php echo $subproductss['name']; ?>">

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                            <?php }
                                            } ?>




                                        </div>

                                    </div>
                                    <div class="tab-pane fade" id="map" role="tabpanel" aria-labelledby="map-tab">
                                        <h3>Map</h3>


                                        <iframe src="<?php echo $subproductss['pro_shortdes']; ?>" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

                                    </div>
                                    <div class="tab-pane fade" id="video" role="tabpanel" aria-labelledby="video-tab">
                                        <h3>Property Video</h3>
                                        <?php 
                                        $video_found = false;
                                        // Robust Regex to extract 11-character YouTube ID from various formats (watch, youtu.be, shorts, embed)
                                        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts)/|.*[?&]v=)|youtu\.be/)([^"&?/ <>]{11})%i', $subproductss['pro_additionalinfo'], $matches)) {
                                            $video_id = $matches[1];
                                            echo '<div class="video-container"><iframe src="https://www.youtube.com/embed/'.$video_id.'" allowfullscreen></iframe></div>';
                                            $video_found = true;
                                        } else {
                                            echo '<p>Property video will be uploaded soon.</p>';
                                        }
                                        ?>
                                    </div>
                                    <div class="tab-pane fade" id="brochure" role="tabpanel" aria-labelledby="brochure-tab">
                                        <!--<h3>Download Brochure</h3>-->
                                      
                                        
                                                       <?php if (empty($subproductss['photo4'])): ?>
                                                          <h3>Brochure Not Available</h3>
                                                          <p style="color: #666; font-size: 16px;">The brochure is not available right now but our team will reach out to you.</p>
                                                          <button class="btn btn-danger mt-3" id="download-link" style="background-color: #c00415; border: none; padding: 10px 25px;">Request Brochure</button>
                                                      <?php else: ?>
                                                          <p style="color: #666; font-size: 16px;">Click the button below to download the project brochure.</p>
                                                          <button class="btn btn-danger mt-3" id="download-link" style="background-color: #c00415; border: none; padding: 10px 25px;">Download Brochure</button>
                                                      <?php endif; ?>
  
                                    </div>
                                </div>
                            </div>












                        </div>
                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <div class="sidebar">
                            <div class="sidebar__single sharing-sidebar d-none d-lg-block">
                                <h3 class="sidebar__title">Social Media Share</h3>
                                     <div class="social-share-buttons">
                                         <!-- Facebook Share -->
                                         <a href="#" onclick="shareFacebook(event)" class="facebook" title="Share on Facebook">
                                            <i class="fab fa-facebook-f"></i>
                                         </a>
                                         
                                         <!-- Twitter Share -->
                                         <a href="https://twitter.com/intent/tweet?text=<?php echo $encodedBlogName; ?>&amp;url=<?php echo $encodedPageUrl; ?>" target="_blank" class="twitter" title="Share on Twitter">
                                             <i class="fab fa-x-twitter"></i>
                                         </a>
                                         
                                         <!-- LinkedIn Share -->
                                         <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $encodedPageUrl; ?>" target="_blank" class="linkedin" title="Share on LinkedIn">
                                             <i class="fab fa-linkedin-in"></i>
                                         </a>
                                     
                                         <!-- WhatsApp Share -->
                                         <a href="https://api.whatsapp.com/send?phone=918130525001&text=Hello! I am interested in: <?php echo $encodedBlogName; ?> (<?php echo $encodedPageUrl; ?>)" target="_blank" class="whatsapp" title="Enquire on WhatsApp">
                                             <i class="fab fa-whatsapp"></i>
                                         </a>
            
                                         <!-- General Share Button -->
                                         <a href="#" class="share" title="Share" onclick="shareContent(event)">
                                             <i class="fas fa-share-alt"></i>
                                         </a>
                                     </div>

                                     <script>
                                        function shareFacebook(e) {
                                            e.preventDefault();
                                            var shareUrl = <?php echo json_encode($rawPageUrl); ?>;
                                            var shareTitle = <?php echo json_encode($rawBlogName); ?>;
                                            var sharerUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(shareUrl);
                                            var isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);

                                            if (isMobile && navigator.share) {
                                                navigator.share({
                                                    title: shareTitle,
                                                    url: shareUrl
                                                }).catch(function(err) {
                                                    if (err.name !== 'AbortError') { window.location.href = sharerUrl; }
                                                });
                                            } else {
                                                window.open(sharerUrl, '_blank', 'width=600,height=400');
                                            }
                                        }

                                        function shareContent(event) {
                                            event.preventDefault();
                                            var shareUrl = <?php echo json_encode($rawPageUrl); ?>;
                                            var shareTitle = <?php echo json_encode($rawBlogName); ?>;
                                            if (navigator.share) {
                                                navigator.share({
                                                    title: shareTitle,
                                                    url: shareUrl
                                                }).catch(function(err) { console.log(err); });
                                            } else {
                                                window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(shareUrl), '_blank');
                                            }
                                        }
                                     </script>
                           </div>
                        



                            <div class="sidebar__single sidebar__post">
                                <h3 class="sidebar__title">Related Blogs</h3>
                                <ul class="sidebar__post-list list-unstyled">
                                    <?php 
                                    $blog_query = "SELECT * FROM offer WHERE actstat=1";
                                    if (!empty($subproductss['related_blogs'])) {
                                        $related_ids = $subproductss['related_blogs'];
                                        $blog_query .= " AND id IN ($related_ids)";
                                    }
                                    $blog_query .= " ORDER BY id DESC LIMIT 5";
                                    
                                    $result = sqlfetch($blog_query);
                                    if (count($result)) {
                                        $placeholders = [
                                            "060225101609Luxury_Homes_on_Dwarka_Expressway_A2P_Realtech.webp",
                                            "060225101329Dream_House_With_A2P_Realtech.webp",
                                            "060225100954M3M_Mansion_113_A2P_Realtech.webp",
                                            "060225100526Dwarka_Expressway_Luxury_Projects_with_A2P_Realtech.webp",
                                            "060225100348Hero_Homes_Top_Choice_A2P_Realtech.webp",
                                            "060225101913Dwarka_Expressway_Projects_A2P_Realtech_Gurgaon.webp"
                                        ];
                                        foreach ($result as $offer) {
                                            $imagePath = "upload/" . $offer['photo'];
                                            if (file_exists($imagePath) && !empty($offer['photo'])) {
                                                $displayImg = SITE_URL . $imagePath;
                                            } else {
                                                $placeholderIndex = $offer['id'] % count($placeholders);
                                                $displayImg = SITE_URL . "upload/" . $placeholders[$placeholderIndex];
                                            }
                                    ?>
                                            <li>
                                                <div class="sidebar__post-image">
                                                    <img src="<?php echo $displayImg; ?>" alt="<?php echo htmlspecialchars($offer['name']); ?>" style="height: 80px;">
                                                </div>
                                                <div class="sidebar__post-content">
                                                    <h3>
                                                        <span class="sidebar__post-content-meta"><i
                                                                class="far fa-user-circle"></i> by <?php echo !empty($offer['by_blog']) ? htmlspecialchars($offer['by_blog']) : 'A2P Realtech'; ?></span>
                                                        <a href="<?= SITE_URL; ?>blog_detail/<?php echo makeurlnamebynameCategory($offer['name']); ?>.php"><?php custom_echo($offer['name'], 30); ?></a>
                                                    </h3>
                                                </div>
                                            </li>
                                    <?php }
                                    } else {
                                        echo "<li>No related blogs found.</li>";
                                    } ?>
                                </ul>
                            </div>

                            <?php
                            // ── RELATED PRODUCTS ───────────────────────────────────────
                            $rel_prod_raw = $subproductss['related_products'] ?? '';
                            $rel_prod_ids = array_filter(array_map('trim', explode(',', $rel_prod_raw)));

                            if (!empty($rel_prod_ids)):
                                $rp_ids_safe   = implode(',', array_map('intval', $rel_prod_ids));
                                $related_prods = sqlfetch("SELECT id, name, photo FROM `subproduct` WHERE id IN ($rp_ids_safe) AND actstat=1 ORDER BY fld_order ASC");
                            else:
                                $related_prods = [];
                            endif;

                            if (!empty($related_prods)): ?>
                            <div class="sidebar__single sidebar__post" style="margin-top: 30px;">
                                <h3 class="sidebar__title">Related Products</h3>
                                <ul class="sidebar__post-list list-unstyled">
                                    <?php foreach ($related_prods as $rp): ?>
                                    <li>
                                        <div class="sidebar__post-image">
                                            <img src="<?= SITE_URL; ?>upload/<?php echo htmlspecialchars($rp['photo']); ?>" alt="<?php echo htmlspecialchars($rp['name']); ?>" style="height:80px;width:80px;object-fit:cover;">
                                        </div>
                                        <div class="sidebar__post-content">
                                            <h3>
                                                <span class="sidebar__post-content-meta">
                                                    <i class="fas fa-building"></i> Property
                                                </span>
                                                <a href="<?= SITE_URL; ?>service_detail/<?php echo makeurlnamebynameCategory($rp['name']); ?>.php">
                                                    <?php custom_echo($rp['name'], 30); ?>
                                                </a>
                                            </h3>
                                        </div>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </section>






<!-- Redesigned Brochure Modal -->
<style>
  #enquiryModal .modal-content {
    border-radius: 15px;
    border: none;
    overflow: hidden;
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
  }
  #enquiryModal .modal-body {
    padding: 0;
  }
  .brochure-form-wrapper {
    display: flex;
    flex-wrap: wrap;
    min-height: 500px;
  }
  .brochure-image {
    flex: 1;
    background: url('<?= SITE_URL; ?>enquirymodel.jpg') center/cover no-repeat;
    min-height: 300px;
    position: relative;
    display: flex;
    align-items: flex-end;
  }
  .brochure-image-overlay {
    background: rgba(192, 4, 21, 0.85);
    width: 100%;
    padding: 20px;
    color: #fff;
  }
  .brochure-image-overlay h4 {
    color: #ffd700;
    margin-bottom: 5px;
    font-weight: 700;
  }
  .brochure-form-content {
    flex: 1.2;
    padding: 30px;
    background: #fff;
  }
  .brochure-form-content h3 {
    color: #c00415;
    font-weight: 800;
    margin-bottom: 5px;
  }
  .brochure-form-content p {
    font-size: 14px;
    color: #666;
    margin-bottom: 20px;
  }
  .form-group-custom {
    margin-bottom: 15px;
  }
  .form-group-custom label {
    font-weight: 600;
    font-size: 13px;
    color: #333;
    margin-bottom: 5px;
    display: block;
  }
  .form-control-custom {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.3s;
  }
  .form-control-custom:focus {
    border-color: #c00415;
    box-shadow: 0 0 8px rgba(192, 4, 21, 0.1);
    outline: none;
  }
  .btn-submit-custom {
    background: #c00415;
    color: #fff;
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 6px;
    font-weight: 700;
    font-size: 16px;
    text-transform: uppercase;
    transition: all 0.3s;
    margin-top: 10px;
  }
  .btn-submit-custom:hover {
    background: #c00415;
    transform: translateY(-2px);
  }
  @media (max-width: 767px) {
    .brochure-form-wrapper {
      flex-direction: column;
    }
    .brochure-image {
      min-height: 200px;
    }
  }
</style>

<div class="modal fade" id="enquiryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; right: 15px; top: 15px; z-index: 10;"></button>
        <div class="brochure-form-wrapper">
          <div class="brochure-image">
            <div class="brochure-image-overlay">
              <h4>Enquire Now</h4>
              <p>Fill in the form below to receive more details about this project.</p>
            </div>
          </div>
          <div class="brochure-form-content">
            <h3>Request Brochure</h3>
            <p>Please fill in your details to get more information.</p>
            
            <form id="enquiryForm" method="post" action="<?= SITE_URL; ?>mail2.php">
              <div class="form-group-custom">
                <label>Your Name *</label>
                <input type="text" id="brochure_name" class="form-control-custom" name="name" placeholder="Enter your full name" required>
              </div>
              <div class="form-group-custom">
                <label>Your Phone *</label>
                <input type="tel" id="brochure_phone" class="form-control-custom" name="phone" placeholder="Enter 10-digit number" pattern="[0-9]{10}" required>
              </div>
              <div class="form-group-custom">
                <label>Your Email *</label>
                <input type="email" id="brochure_email" class="form-control-custom" name="email" placeholder="Enter your email address" required>
              </div>
              <div class="form-group-custom">
                <label>Message</label>
                <textarea class="form-control-custom" name="message" rows="3" placeholder="Any specific requirements?"></textarea>
              </div>

              <!-- OTP Section -->
              <div id="brochure-otp-section" style="display: none; margin-bottom: 15px; border-top: 1px solid #ddd; padding-top: 15px;">
                  <label style="color: #c00415; font-weight: 600; font-size: 13px; margin-bottom: 5px; display: block;">Enter 6-Digit OTP *</label>
                  <div style="display: flex; gap: 10px;">
                      <input type="text" id="brochure_otp_code" class="form-control-custom" placeholder="Enter code" maxlength="6" style="flex: 1;">
                      <button type="button" id="brochure-verify-otp-btn" style="width: auto; background: #28a745; color: #fff; border: none; border-radius: 6px; padding: 5px 15px; font-size: 14px; font-weight: 600;">Verify</button>
                  </div>
                  <p id="brochure-otp-status-msg" style="font-size: 12px; margin-top: 5px; font-weight: 500;"></p>
              </div>

              <input type="hidden" name="page" value="<?php echo $subproductss['name']; ?>">
              <input type="hidden" name="destination" value="Brochure Enquiry">
              <input type="hidden" name="brochure_file" value="<?php echo $subproductss['photo4']; ?>">
              <input type="hidden" name="city" id="brochure_city">
              <input type="hidden" name="lat_long" id="brochure_lat_long">

              <button type="button" id="brochure-send-otp-btn" class="btn-submit-custom">Send Verification Code</button>
              <button type="submit" id="brochure-main-submit-btn" class="btn-submit-custom" style="display: none;">Submit Enquiry</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Coming Soon Modal -->
<div class="modal fade" id="comingSoonModal" tabindex="-1" aria-labelledby="comingSoonLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="comingSoonLabel">Coming Soon</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        The brochure is not available yet. Please check back later.
      </div>
    </div>
  </div>
</div>

<script>
  const enquiryForm = document.getElementById('enquiryForm');
  const brochureSendOtpBtn = document.getElementById('brochure-send-otp-btn');
  const brochureVerifyOtpBtn = document.getElementById('brochure-verify-otp-btn');
  const brochureMainSubmitBtn = document.getElementById('brochure-main-submit-btn');
  const brochureOtpSection = document.getElementById('brochure-otp-section');
  const brochureOtpStatusMsg = document.getElementById('brochure-otp-status-msg');

  let isBrochureOtpVerified = false;

  // 1. Send OTP
  brochureSendOtpBtn.addEventListener('click', async () => {
    const name = document.getElementById('brochure_name').value;
    const email = document.getElementById('brochure_email').value;
    const phone = document.getElementById('brochure_phone').value;

    if (!name || !email || !phone) {
      alert('Please fill Name, Email, and Phone first.');
      return;
    }

    brochureSendOtpBtn.disabled = true;
    brochureSendOtpBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending OTP...';

    const formData = new FormData();
    formData.append('email', email);
    formData.append('name', name);

    try {
      const response = await fetch('<?= SITE_URL; ?>function/send_otp.php', {
        method: 'POST',
        body: formData
      });
      const data = await response.json();

      if (data.status === 'success') {
        brochureOtpSection.style.display = 'block';
        brochureSendOtpBtn.innerHTML = 'Resend OTP';
        brochureSendOtpBtn.style.background = '#666'; // muted color
        brochureOtpStatusMsg.style.color = 'green';
        brochureOtpStatusMsg.innerText = data.message;
        alert('OTP has been sent to ' + email);
      } else {
        alert(data.message);
        brochureSendOtpBtn.disabled = false;
        brochureSendOtpBtn.innerHTML = 'Send Verification Code';
      }
    } catch (err) {
      console.error(err);
      alert('Error sending OTP. Please try again.');
      brochureSendOtpBtn.disabled = false;
      brochureSendOtpBtn.innerHTML = 'Send Verification Code';
    }
  });

  // 2. Verify OTP
  brochureVerifyOtpBtn.addEventListener('click', async () => {
    const otp = document.getElementById('brochure_otp_code').value;
    const email = document.getElementById('brochure_email').value;

    if (!otp) {
      alert('Please enter the 6-digit OTP code.');
      return;
    }

    brochureVerifyOtpBtn.disabled = true;
    brochureVerifyOtpBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    const formData = new FormData();
    formData.append('otp', otp);
    formData.append('email', email);

    try {
      const response = await fetch('<?= SITE_URL; ?>function/verify_otp.php', {
        method: 'POST',
        body: formData
      });
      const data = await response.json();

      if (data.status === 'success') {
        isBrochureOtpVerified = true;
        brochureOtpStatusMsg.style.color = 'green';
        brochureOtpStatusMsg.innerHTML = '<i class="fas fa-check-circle"></i> OTP Verified Successfully!';
        brochureVerifyOtpBtn.style.display = 'none';
        document.getElementById('brochure_otp_code').disabled = true;
        brochureSendOtpBtn.style.display = 'none';
        brochureMainSubmitBtn.style.display = 'block';
      } else {
        brochureOtpStatusMsg.style.color = 'red';
        brochureOtpStatusMsg.innerText = data.message;
        brochureVerifyOtpBtn.disabled = false;
        brochureVerifyOtpBtn.innerHTML = 'Verify';
      }
    } catch (err) {
      console.error(err);
      alert('Error verifying OTP.');
      brochureVerifyOtpBtn.disabled = false;
      brochureVerifyOtpBtn.innerHTML = 'Verify';
    }
  });

  // 3. Final Form Submission
  enquiryForm.addEventListener('submit', async (e) => {
    if (!isBrochureOtpVerified) {
      e.preventDefault();
      alert('Please verify your email with the OTP first.');
      return;
    }

    brochureMainSubmitBtn.disabled = true;
    brochureMainSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

    const locData = await getCityName(); 
    document.getElementById('brochure_city').value = locData.city || 'Not Shared';
    document.getElementById('brochure_lat_long').value = locData.lat_long || '';

    // Let the form submit normally after setting location
    return true; 
  });

  // Define brochure availability for JS
  const isBrochureAvailable = <?php echo !empty($subproductss['photo4']) ? 'true' : 'false'; ?>;

  // Open the enquiry modal when any link with id 'download-link' is clicked
  document.querySelectorAll('[id="download-link"]').forEach(element => {
    element.addEventListener('click', function (event) {
        event.preventDefault(); // Prevent default link behavior
        
        const modalTitle = document.querySelector('#enquiryModal h3');
        const modalDesc = document.querySelector('#enquiryModal .brochure-form-content p');
        
        if (!isBrochureAvailable) {
            if (modalTitle) modalTitle.innerText = "Brochure Not Available";
            if (modalDesc) modalDesc.innerText = "Brochure not available right now but team will reach out to you. Please fill in your details.";
        } else {
            if (modalTitle) modalTitle.innerText = "Download Brochure";
            if (modalDesc) modalDesc.innerText = "Please fill in your details to get the brochure.";
        }

        var myModal = new bootstrap.Modal(document.getElementById('enquiryModal'));
        myModal.show();
    });
  });

  // Auto-wrap all tables inside tab-content with a horizontal scrollable container
  document.querySelectorAll('.tab-content table').forEach(function(table) {
    if (!table.parentElement.classList.contains('table-responsive-wrapper')) {
      var wrapper = document.createElement('div');
      wrapper.classList.add('table-responsive-wrapper');
      table.parentNode.insertBefore(wrapper, table);
      wrapper.appendChild(table);
    }
  });
</script>






        <?php include 'include/footer.php' ?><?php }
                                        }
                                                ?> 





                                  
