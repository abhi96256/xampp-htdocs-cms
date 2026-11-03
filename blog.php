<?php include "function/function.php"; ?>
<?php
$sql_add = sqlfetch("select * from address where id=1");
if (count($sql_add))
    foreach ($sql_add as $pr_add) {
    }
?>

<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns#">

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
    
    <script async type="application/javascript"
        src="https://news.google.com/swg/js/v1/swg-basic.js"></script>
<script>
  (self.SWG_BASIC = self.SWG_BASIC || []).push( basicSubscriptions => {
    basicSubscriptions.init({
      type: "NewsArticle",
      isPartOfType: ["Product"],
      isPartOfProductId: "CAowpuveCw:openaccess",
      clientOptions: { theme: "light", lang: "en" },
    });
  });
</script>


<style>
/* Force all property cards to be rectangular with sharp corners */
.blog-one__single, 
.project-three__single, 
.project-card-v2, 
.blog-one__img, 
.project-three__img, 
.project-three__img-box,
.blog-one__content {
    border-radius: 0 !important;
    -webkit-border-radius: 0 !important;
    -moz-border-radius: 0 !important;
}

/* Restore the rest of the blog card layout */
.blog-one__single {
    height: calc(100% - 30px) !important;
    display: flex !important;
    flex-direction: column !important;
    margin: 15px !important; 
    background-color: #fff;
    transition: all 500ms ease;
    border: 1px solid #eee;
    overflow: hidden;
    box-shadow: 0px 5px 15px rgba(0,0,0,0.05);
}

.blog-one__img {
    height: 220px !important;
    width: 100% !important;
    overflow: hidden;
    position: relative;
    display: flex !important;
    background-color: #000;
}

.blog-one__img img {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
    object-position: center;
    transition: all 500ms ease;
}

.blog-one__content {
    flex-grow: 1 !important;
    display: flex !important;
    flex-direction: column !important;
    background-color: var(--ambed-primary, #f2eeeb);
    padding: 30px 25px 20px !important;
    transition: all 500ms ease !important;
    position: relative;
}

.blog-one__single:hover .blog-one__content {
    background-color: white !important;
    box-shadow: 0px 10px 60px 0px rgba(0, 0, 0, 0.07) !important;
}

.blog-one__date {
    position: absolute !important;
    top: -20px !important;
    left: 20px !important;
    right: auto !important;
    background-color: #102a83 !important;
    padding: 10px 20px !important;
    z-index: 10;
}

.blog-one__date p {
    color: #fff !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    margin: 0 !important;
}

.blog-one__title {
    margin-top: 15px !important;
    margin-bottom: 20px !important;
    min-height: 64px;
    display: flex;
    align-items: center;
}

.blog-one__title a {
    font-size: 20px !important;
    line-height: 1.4 !important;
    color: #c00415 !important;
}

.blog-one__meta {
    margin-top: auto !important;
    margin-bottom: 0 !important;
    padding-top: 15px !important;
    border-top: 1px solid rgba(0,0,0,0.05) !important;
}

@media (max-width: 767px) {
    .blog-one__img {
        height: 200px !important;
    }
    .blog-one__title {
        min-height: auto !important;
    }
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
                <li>Blogs</li>
            </ul>
            <h2>Blogs</h2>
        </div>
    </div>
</section>
<!--Page Header End-->






<!--Blog Two Start-->
<section class="blog-two">
    <div class="blog-two-bg" style="background-image: url(<?= SITE_URL; ?>assets/images/backgrounds/blog-two-bg.jpg);"></div>
    <div class="container">
        <div class="blog-two__top">
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="blog-two__top-left">
                        <div class="section-title text-left">
                            <span class="section-title__tagline">News & Updates</span>
                            <h2 class="section-title__title">Check Latest News & Articles</h2>
                            <div class="section-title__line"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="blog-two__bottom">
            <div class="row">

                <?php $result = sqlfetch("select * from offer ORDER BY id DESC");
                if (count($result)) {
                    foreach ($result as $offer) {
                ?>
                        <div class="col-xl-4 col-lg-4 wow fadeInUp" data-wow-delay="100ms">
                            <!--Blog One Start-->
                            <a href="<?= SITE_URL; ?>blog_detail/<?php echo makeurlnamebynameCategory($offer['name']); ?>.php">
                            <div class="blog-one__single">
                                <?php 
                                    $imagePath = "upload/" . trim($offer['photo']);
                                    if (!empty($offer['photo']) && file_exists($imagePath)) { 
                                        $displayImg = SITE_URL . $imagePath; 
                                    } else { 
                                        $displayImg = SITE_URL . "upload/" . ($pr_add['photo'] ?? '080325100432logo.png'); 
                                    }
                                ?>
                                <div class="blog-one__img">
                                    <img src="<?php echo $displayImg; ?>" alt="<?php echo $offer['name']; ?>" style="width: 100%; height: 250px; object-fit: fill !important;">
                                </div>
                                <div class="blog-one__content">
                                    <div class="blog-one__date">
                                        <p><?php echo $offer['des1']; ?></p>
                                    </div>
                                    <ul class="list-unstyled blog-one__meta">
                                        <li><a href="<?= SITE_URL; ?>blog_detail/<?php echo makeurlnamebynameCategory($offer['name']); ?>.php"><i class="far fa-user-circle"></i> <?php echo $offer['by_blog']; ?></a></li>
                                        <li><span>/</span></li>
                                    </ul>
                                    <h3 class="blog-one__title"><a href="<?= SITE_URL; ?>blog_detail/<?php echo makeurlnamebynameCategory($offer['name']); ?>.php"><?php custom_echo($offer['name'], 40); ?></a></h3>
                                </div>
                            </div>
                             </a>
                        </div>
                <?php }
                } ?>



            </div>
        </div>
    </div>
</section>
<!--Blog Two End-->





<?php include 'include/footer.php' ?>