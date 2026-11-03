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
    <title>Developer Gallery</title>
    <?php include __DIR__ . '/../include/favicon.php'; ?>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

</head>

<style>
    .developer-card {
        background: #fff;
        padding: 30px;
        text-align: center;
        border: 1px solid #eee;
        transition: all 0.3s ease;
        margin-bottom: 30px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .developer-card:hover {
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-color: #c00415;
    }
    .developer-card i {
        font-size: 50px;
        color: #c00415;
        margin-bottom: 20px;
    }
    .developer-card h3 {
        font-size: 22px;
        font-weight: 700;
        margin: 0;
        color: #333;
    }
    .developer-card a {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1;
    }
</style>

<body>
    <?php include 'include/header.php' ?>

    <section class="page-header">
        <div class="page-header-bg" style="background-image: url(<?= SITE_URL; ?>assets/images/backgrounds/page-header-bg.jpg)"></div>
        <div class="container">
            <div class="page-header__inner">
                <ul class="thm-breadcrumb list-unstyled">
                    <li><a href="<?= SITE_URL; ?>">Home</a></li>
                    <li><span>/</span></li>
                    <li>Developer Gallery</li>
                </ul>
                <h2>All Developers</h2>
            </div>
        </div>
    </section>

    <section class="services-page">
        <div class="container">
            <div class="row mt-5">
                <?php
                $dev_list = sqlfetch("SELECT DISTINCT developer FROM subproduct WHERE developer != '' AND actstat = 1 ORDER BY developer ASC");
                if (count($dev_list)) {
                    foreach ($dev_list as $dev) {
                ?>
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                            <div class="developer-card wow fadeInUp" data-wow-delay="100ms" style="position: relative;">
                                <i class="fa fa-building"></i>
                                <h3><?php echo htmlspecialchars($dev['developer']); ?></h3>
                                <a href="<?= SITE_URL; ?>developer_projects.php?dev=<?php echo urlencode($dev['developer']); ?>"></a>
                            </div>
                        </div>
                <?php 
                    }
                } else {
                    echo "<div class='col-12 text-center'><h3>No developers found in our gallery.</h3></div>";
                }
                ?>
            </div>
        </div>
    </section>

    <?php include 'include/footer.php' ?>
</body>
</html>