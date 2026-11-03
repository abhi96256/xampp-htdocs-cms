<?php include "function/function.php"; ?>
<?php
$sql_add = sqlfetch("select * from address where id=1");
if (count($sql_add))
    foreach ($sql_add as $pr_add) {
    }

$developer_name = isset($_GET['dev']) ? $_GET['dev'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $developer_name; ?> - Developer Gallery</title>
    <meta name="description" content="Projects by <?php echo $developer_name; ?>">
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
    .services-one__single {
        border-radius: 0 !important;
        border: 1px solid #ddd;
        height: 100%;
        display: flex;
        flex-direction: column;
        background: #fff;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .services-one__img {
        position: relative;
        height: 250px !important;
        overflow: hidden;
    }
    .services-one__img img {
        height: 100% !important;
        width: 100% !important;
        object-fit: fill !important;
    }
    .services-one__content {
        padding: 20px;
        flex-grow: 1;
    }
    .services-one__title {
        font-size: 20px !important;
        font-weight: 800 !important;
        margin-bottom: 15px;
    }
    .services-one__title a {
        color: #c00415 !important;
    }
    .price {
        font-size: 19px;
        font-weight: 800;
        color: #c00415;
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
                    <li>Developer: <?php echo htmlspecialchars($developer_name); ?></li>
                </ul>
                <h2>Projects by <?php echo htmlspecialchars($developer_name); ?></h2>
            </div>
        </div>
    </section>

    <section class="services-page">
        <div class="container">
            <div class="row mt-5">
                <?php
                $pdo = getPDOObject();
                $stmt = $pdo->prepare("SELECT * FROM subproduct WHERE developer = ? AND actstat = 1 ORDER BY id DESC");
                $stmt->execute([$developer_name]);
                $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($projects)) {
                    foreach ($projects as $project) {
                ?>
                        <div class="col-xl-4 col-lg-4 col-md-6 mb-4">
                            <div class="services-one__single wow fadeInUp" data-wow-delay="100ms">
                                <div class="services-one__img">
                                    <a href="<?= SITE_URL; ?>service_detail/<?php echo makeurlnamebynameCategory($project['name']); ?>.php">
                                        <?php 
                                            $res_placeholders = ["060825060302Vatika Sovereign Park Image1.jpg", "100725050048Sobha-City-Sector-108-Dwarka-Expressway-Gurgaon.jpg", "160425091419Sobha Altus image A2P Realtech.jpg", "20260128161604_M3M-GIC-Manesar-Gurgaon.jpg"];
                                            $imagePath = "upload/" . $project['photo'];
                                            if (!empty($project['photo']) && file_exists($imagePath)) { $displayImg = SITE_URL . $imagePath; } 
                                            else { $placeholderIndex = $project['id'] % count($res_placeholders); $displayImg = SITE_URL . "upload/" . $res_placeholders[$placeholderIndex]; }
                                        ?>
                                        <img src="<?php echo $displayImg; ?>" alt="<?php echo $project['name']; ?>">
                                    </a>
                                </div>
                                <div class="services-one__content">
                                    <h3 class="services-one__title">
                                        <a href="<?= SITE_URL; ?>service_detail/<?php echo makeurlnamebynameCategory($project['name']); ?>.php">
                                            <?php echo htmlspecialchars($project['name']); ?>
                                        </a>
                                    </h3>
                                    <p class="services-one__text"><i class="fa-solid fa-map-pin"></i> <?php echo htmlspecialchars($project['pro_lable']); ?></p>
                                    <p class="services-one__text price"><?php echo htmlspecialchars($project['pro_discountprice']); ?></p>
                                </div>
                            </div>
                        </div>
                <?php 
                    }
                } else {
                    echo "<div class='col-12 text-center'><h3>No projects found for this developer.</h3></div>";
                }
                ?>
            </div>
        </div>
    </section>

    <?php include 'include/footer.php' ?>
</body>
</html>