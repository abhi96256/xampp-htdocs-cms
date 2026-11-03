<?php include "function/function.php"; ?>
<?php
$sql_add = sqlfetch("select * from address where id=1");
if (count($sql_add))
    foreach ($sql_add as $pr_add) {
    }
?>
<?php
$pid = $_GET['id'];
$sql_ser = findRecordBySlug('fixed_delivery_time', $pid);

// Redirect if no gallery found to avoid blank page
if (count($sql_ser) == 0) {
    echo "<script>window.location.href='".SITE_URL."media-gallery.php';</script>";
    exit;
}

if (count($sql_ser)) {
    foreach ($sql_ser as $fixed_delivery_time) {

?>
        <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   
   <title><?php echo $fixed_delivery_time['meta_title']; ?></title>
    <meta name="description" content="<?php echo $fixed_delivery_time['meta_description']; ?>">
    <meta name="keywords" content="<?php echo $fixed_delivery_time['meta_keyword']; ?>">
    <meta name="facebook-domain-verification" content="m7nn0fxbw83tklfswjvxxzpz42u09w" />
    <?php include 'include/favicon.php'; ?>

    <meta property="og:title" content="<?php echo htmlspecialchars($fixed_delivery_time['meta_title']); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($fixed_delivery_time['meta_description']); ?>">
    <?php 
    $rawPhoto = trim($fixed_delivery_time['photo'] ?? '');
    $imagePath = "upload/" . $rawPhoto;
    if (!empty($rawPhoto) && file_exists($imagePath)) { 
        $_og_img = SITE_URL . "upload/" . str_replace(' ', '%20', $rawPhoto); 
    } else { 
        $_og_img = SITE_URL . "upload/" . ($pr_add['photo'] ?? '080325100432logo.png'); 
    }
    ?>
    <meta property="og:image" content="<?php echo $_og_img; ?>?v=1.3">
    <meta property="og:image:secure_url" content="<?php echo $_og_img; ?>?v=1.3">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:url" content="<?php echo SITE_URL . ltrim($_SERVER['REQUEST_URI'], '/'); ?>">
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="A2P Realtech">
    <!-- Twitter/X Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($fixed_delivery_time['meta_title']); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($fixed_delivery_time['meta_description']); ?>">
    <meta name="twitter:image" content="<?php echo $_og_img; ?>?v=1.3">
   
   
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




        <?php include 'include/header.php' ?>


        <section class="page-header">
            <div class="page-header-bg" style="background-image: url(<?= SITE_URL; ?>assets/images/backgrounds/page-header-bg.jpg)">
            </div>
            <div class="container">
                <div class="page-header__inner">
                    <ul class="thm-breadcrumb list-unstyled">
                        <li><a href="<?= SITE_URL; ?>">Home</a></li>
                        <li><span>/</span></li>
                        <li> <?php custom_echo($fixed_delivery_time['name'], 30); ?></li>
                    </ul>
                    <h2>Media Gallery</h2>
                </div>
            </div>
        </section>
        <!--Page Header End-->




<?php
$rawBlogName = $fixed_delivery_time['name'];
$encodedBlogName = urlencode($rawBlogName);
$_protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$rawPageUrl = $_protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$encodedPageUrl = urlencode($rawPageUrl);
?>





       
        <section class="blog-details">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        <div class="blog-details__left">
                            <div class="blog-details__img">
                                <?php 
                                    $rawPhoto = trim($fixed_delivery_time['photo'] ?? '');
                                    $imagePath = "upload/" . $rawPhoto;
                                    if (!empty($rawPhoto) && file_exists($imagePath)) { 
                                        $displayImg = SITE_URL . "upload/" . $rawPhoto; 
                                    } else { 
                                        $displayImg = SITE_URL . "upload/" . ($pr_add['photo'] ?? '080325100432logo.png'); 
                                    }
                                ?>
                                <img src="<?php echo $displayImg; ?>" alt="<?php echo $fixed_delivery_time['name']; ?>" style="width: 100%; height: auto;">
                            </div>
                            <div class="blog-details__content">
                                
                                
         
                          
                          
                                <h3 class="blog-details__title"><?php echo $fixed_delivery_time['name']; ?></h3>
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
                                
                                

                                <p class="blog-details__text-2"><?php echo $fixed_delivery_time['des']; ?></p>
                            </div>

                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-5">
                        <div class="sidebar">
                            <!--<div class="sidebar__single sidebar__search">-->
                            <!--    <form action="#" class="sidebar__search-form">-->
                            <!--        <input type="search" placeholder="Search here">-->
                            <!--        <button type="submit"><i class="fa fa-search"></i></button>-->
                            <!--    </form>-->
                            <!--</div>-->
                            
                            <!-- Social Media Share Buttons -->
                            
                            
                            <div class="sidebar__single sharing-sidebar d-none d-lg-block">
                                <h3 class="sidebar__title">Social Media Share</h3>
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
                                <h3 class="sidebar__title">Latest Posts</h3>
                                
                                
                                <ul class="sidebar__post-list list-unstyled">
                                    <?php $result_side = sqlfetch("select * from fixed_delivery_time order by id DESC");
                                    if (count($result_side)) {
                                        foreach ($result_side as $side_item) {
                                            $sidePhoto = trim($side_item['photo'] ?? '');
                                            $sidePath = "upload/" . $sidePhoto;
                                            if (!empty($sidePhoto) && file_exists($sidePath)) { 
                                                $displaySideImg = SITE_URL . "upload/" . $sidePhoto; 
                                            } else { 
                                                $displaySideImg = SITE_URL . "upload/" . ($pr_add['photo'] ?? '080325100432logo.png'); 
                                            }
                                    ?>
                                            <li>
                                                <div class="sidebar__post-image">
                                                    <img src="<?php echo $displaySideImg; ?>" alt="<?php echo $side_item['name']; ?>" style="height: 80px; width: 80px; object-fit: cover;">
                                                </div>
                                                <div class="sidebar__post-content">
                                                    <h3>
                                                        <a href="<?= SITE_URL; ?>media-gallery-detail/<?php echo makeurlnamebynameCategory($side_item['name']); ?>.php"><?php custom_echo($side_item['name'], 30); ?></a>
                                                    </h3>
                                                </div>
                                            </li>
                                    <?php }
                                    } ?>
                                </ul>
                            </div>
                          
                        </div>
                    </div>
                </div>
            </div>
        </section>
     








        <?php include 'include/footer.php' ?><?php }
                                        }
                                                ?>