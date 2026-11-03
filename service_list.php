<?php include "function/function.php"; ?>
<?php
$sql_add = sqlfetch("select * from address where id=1");
if (count($sql_add))
    foreach ($sql_add as $pr_add) {
    }
?>


<?php
$pid = makeurlnormal($_GET['id']);
$sql_ser = sqlfetch("select * from subcategory where slug='$pid' and actstat=1");
if (count($sql_ser)) {
    foreach ($sql_ser as $subcategorydd) {

?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <title><?php echo $subcategorydd['meta_title']; ?></title>
            <meta name="description" content="<?php echo $subcategorydd['meta_description']; ?>">
            <meta name="keywords" content="<?php echo $subcategorydd['meta_keyword']; ?>">
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



<style>
    /* Force all property cards to be rectangular with sharp corners */
    .services-one__single, 
    .blog-one__single, 
    .project-three__single, 
    .project-card-v2, 
    .services-one__img, 
    .blog-one__img, 
    .project-three__img, 
    .project-three__img-box,
    .blog-one__content,
    .services-one__content {
        border-radius: 0 !important;
        -webkit-border-radius: 0 !important;
        -moz-border-radius: 0 !important;
    }

    .services-one__single {
        margin-bottom: 30px;
        background: #fff;
        border-radius: 0px !important; 
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid #ddd;
        height: 100%;
        display: flex;
        flex-direction: column;
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
        display: block;
    }

    .services-one__content {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .services-one__title {
        font-size: 20px !important;
        font-weight: 800 !important;
        margin-bottom: 15px;
        line-height: 1.2;
    }

    .services-one__title a {
        color: #c00415 !important;
        text-decoration: none !important;
        display: block;
    }

    .price {
        font-size: 19px;
        font-weight: 800;
        color: #c00415;
        margin-top: auto;
        display: block;
    }

    @media (max-width: 767px) {
        .services-one__img {
            height: 200px !important;
        }
        .services-one__title {
            font-size: 18px !important;
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
                        <li><?php echo $subcategorydd['name']; ?></li>
                    </ul>
                    <h2><?php echo $subcategorydd['name']; ?></h2>
                </div>
            </div>
        </section>
        <!--Page Header End-->



       
        <section class="services-page">
            <div class="container">
                
                
                
                
<!--                          <div class="block-filter">-->
    <!--<h6 class="item-collapse">Price Filter</h6>-->
<!--    <div class="box-collapse scrollFilter">-->
<!--        <div class="filter-group">-->
<!--            <label for="minPrice">Min Price:</label>-->
<!--            <input class="ghd" type="number" id="minPrice" name="minPrice" min="0" value="0">-->
<!--        </div>-->
<!--        <div class="filter-group">-->
<!--            <label for="maxPrice">Max Price:</label>-->
<!--            <input class="ghd" type="number" id="maxPrice" name="maxPrice" min="0" value="50000">-->
<!--        </div>-->
<!--        <button id="applyFilter" class="bud mt-4">Apply Filter</button>-->
<!--    </div>-->
<!--</div>-->



                        
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                <div class="row mt-5">

                    <?php
                    $sub_cat = sqlfetch("SELECT * FROM subproduct where subcat='" . $subcategorydd['id'] . "'  ORDER BY id DESC");
                    if (count($sub_cat)) {
                        foreach ($sub_cat as $subproductwww) {
                    ?>

                            <div class="col-xl-4 col-lg-4 col-md-6 productr">
                                <!--Services One Single-->
                                <div class="services-one__single wow fadeInUp" data-wow-delay="100ms">
                                    <div class="services-one__img">
                                          <a href="<?= SITE_URL; ?>service_detail/<?php echo makeurlnamebynameCategory($subproductwww['name']); ?>.php">
                                        <?php 
                                            $res_placeholders = ["060825060302Vatika Sovereign Park Image1.jpg", "100725050048Sobha-City-Sector-108-Dwarka-Expressway-Gurgaon.jpg", "160425091419Sobha Altus image A2P Realtech.jpg", "20260128161604_M3M-GIC-Manesar-Gurgaon.jpg"];
                                            $imagePath = "upload/" . $subproductwww['photo'];
                                            if (!empty($subproductwww['photo']) && file_exists($imagePath)) { $displayImg = SITE_URL . $imagePath; } 
                                            else { $placeholderIndex = $subproductwww['id'] % count($res_placeholders); $displayImg = SITE_URL . "upload/" . $res_placeholders[$placeholderIndex]; }
                                        ?>
                                        <img src="<?php echo $displayImg; ?>" alt="<?php echo $subproductwww['name']; ?>" style="width: 100%; height: 250px; object-fit: fill !important;">
                                       
                                       
                                    </a>
                                       
                                    </div>
                                    <div class="services-one__content">
                                        <h3 class="services-one__title"><a href="<?= SITE_URL; ?>service_detail/<?php echo makeurlnamebynameCategory($subproductwww['name']); ?>.php"><?php echo $subproductwww['name']; ?></a></h3>
                                        <p class="services-one__text"><i class="fa-solid fa-map-pin"></i> <?php echo $subproductwww['pro_lable']; ?></p>
                                        <p class="services-one__text price"><?php echo $subproductwww['pro_discountprice']; ?></p>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    } ?>
                </div>
                 <div id="noProductsMessage" style="display: none; color: #ff0000; margin-top: 10px;box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px; padding:20px; border-radius:10px;font-size: 24px;font-weight: 500; ">

                            <center><span> No Properties  Match The Selected Filter.
                                    <button class="bud" onclick="refreshPage()">Back To Page</button>
                                </span></center>
                                  </div>
            </div>
        </section>
       


  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

        <script>
            $(document).ready(function() {
                // Apply the price filter
                $('#applyFilter').on('click', function() {
                    var minPrice = parseInt($('#minPrice').val()) || 0;
                    var maxPrice = parseInt($('#maxPrice').val()) || 50000;
                    filterProductsByPrice(minPrice, maxPrice);
                });

                // Function to filter products by price
                function filterProductsByPrice(min, max) {
                    var productsToShow = 0;

                    $('.productr').each(function() {
                        var productPrice = parseInt($(this).find('.price').text().replace('₹', ''));
                        if (productPrice >= min && productPrice <= max) {
                            $(this).show();
                            productsToShow++;
                        } else {
                            $(this).hide();
                        }
                    });

                    // Show a message if no products are available
                    if (productsToShow === 0) {
                        $('#noProductsMessage').show();
                    } else {
                        $('#noProductsMessage').hide();
                    }

                    // Set display to "none" for products not in the price range
                    $('.productr:not(:visible)').css('display', 'none');
                }
            });
        </script>


        <script>
            // JavaScript function to refresh the page
            function refreshPage() {
                // Use the location.reload() method to reload the page
                location.reload();
            }
        </script>




        <?php include 'include/footer.php' ?><?php
                                                break; // Stop after the first record
                                            }
                                        }
                                                ?>