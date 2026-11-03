<?php include "function/function.php"; ?>
<?php
$sql_add = sqlfetch("select * from address where id=1");
if (count($sql_add))
    foreach ($sql_add as $pr_add) {
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>A2P Realtech | <?php echo $pr_add['test_date']; ?></title>
    <meta name="description" content="<?php echo $pr_add['class9']; ?>">
    <meta name="keywords" content="<?php echo $pr_add['class8']; ?>">
    <?php include 'include/og_meta.php'; ?>
    <?php include 'include/favicon.php'; ?>
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




<?php include 'include/header.php' ?>


<section class="page-header">
    <div class="page-header-bg" style="background-image: url(assets/images/backgrounds/page-header-bg.jpg)">
    </div>
    <div class="container">
        <div class="page-header__inner">
            <ul class="thm-breadcrumb list-unstyled">
                <li><a href="<?= SITE_URL; ?>">Home</a></li>
                <li><span>/</span></li>
                <li>Media Gallery</li>
            </ul>
            <h2>Media Gallery</h2>
        </div>
    </div>
</section>
<!--Page Header End-->



<style>
.blog-one__img {
    padding: 20px;
   
}
    
    .project-three__img  img{
        height: 350px;
    }
    
    @media (max-width: 600px) {
  .project-three__img  img{
        height: 250px !important;
    }
}

</style>




<!--Projects Page Start-->
<section class="projects-page">
    <div class="container">
        <div class="row">
          
              <?php $result = sqlfetch("select * from fixed_delivery_time  ");
            if (count($result)) {
                foreach ($result as $fixed_delivery_time) {
            ?>
            
                        <div class="col-xl-4 col-lg-4 wow fadeInUp" data-wow-delay="100ms">
                            <!--Blog One Start-->
                            <a href="<?= SITE_URL; ?>media-gallery-detail/<?php echo makeurlnamebynameCategory($fixed_delivery_time['name']); ?>.php">
                            <div class="blog-one__single">
                                <div class="blog-one__img">
                                    <?php 
                                        $imagePath = "upload/" . trim($fixed_delivery_time['photo']);
                                        if (!empty($fixed_delivery_time['photo']) && file_exists($imagePath)) { 
                                            $displayImg = SITE_URL . $imagePath; 
                                        } else { 
                                            // Fallback to address photo or default logo
                                            $displayImg = SITE_URL . "upload/" . ($pr_add['photo'] ?? '080325100432logo.png'); 
                                        }
                                    ?>
                                    <img src="<?php echo $displayImg; ?>" alt="<?php echo $fixed_delivery_time['name']; ?>" style="width: 100%; height: 250px; object-fit: fill !important;">
                                </div>
                                <div class="blog-one__content">
                                    
                                    
                                    <h3 class="blog-one__title"><a href="<?= SITE_URL; ?>media-gallery-detail/<?php echo makeurlnamebynameCategory($fixed_delivery_time['name']); ?>.php"><?php echo $fixed_delivery_time['name']; ?></a></h3>
                                    
                                    <a href="<?= SITE_URL; ?>media-gallery-detail/<?php echo makeurlnamebynameCategory($fixed_delivery_time['name']); ?>.php">
                                     <button class="btn btn-danger text-white  mt-4"> Read More</button>
                                     </a>
                                </div>
                            </div>
                             </a>
                        </div>
                              <?php }
                        } ?>
            
            
            
            
        </div>
    </div>
</section>
<!--Projects Page End-->









<?php include 'include/footer.php' ?>