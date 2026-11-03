<?php include "function/function.php"; ?>
<?php
$sql_add = sqlfetch("select * from address where id=1");
if (count($sql_add))
    foreach ($sql_add as $pr_add) {
    }
?>
<?php
$pid = $_GET['id'];
$sql_ser = findRecordBySlug('offer', $pid);

// Redirect if no blog found to avoid blank page
if (count($sql_ser) == 0) {
    echo "<script>window.location.href='".SITE_URL."blog.php';</script>";
    exit;
}

if (count($sql_ser)) {
    foreach ($sql_ser as $offer) {

?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <title><?php echo $offer['meta_title']; ?></title>
            <meta name="description" content="<?php echo $offer['meta_description']; ?>">
            <meta name="keywords" content="<?php echo $offer['meta_keyword']; ?>">
            <meta name="facebook-domain-verification" content="m7nn0fxbw83tklfswjvxxzpz42u09w" />
            <?php include 'include/favicon.php'; ?>
            
            <meta property="og:title" content="<?php echo htmlspecialchars($offer['meta_title']); ?>">
            <meta property="og:description" content="<?php echo htmlspecialchars($offer['meta_description']); ?>">
            <?php 
            $_og_photo = !empty($offer['photo']) ? trim($offer['photo']) : ($pr_add['photo'] ?? '080325100432logo.png');
            // Encode spaces properly, but do NOT append ?v= – Facebook rejects images with query strings
            $_og_img = SITE_URL . 'upload/' . rawurlencode($_og_photo);
            ?>
            <meta property="og:image" content="<?php echo $_og_img; ?>">
            <meta property="og:image:secure_url" content="<?php echo $_og_img; ?>">
            <meta property="og:image:width" content="1200">
            <meta property="og:image:height" content="630">
            <meta property="og:url" content="<?php echo SITE_URL . ltrim($_SERVER['REQUEST_URI'], '/'); ?>">
            <meta property="og:type" content="article">
            <meta property="og:site_name" content="A2P Realtech">
            <!-- Twitter/X Card -->
            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:title" content="<?php echo htmlspecialchars($offer['meta_title']); ?>">
            <meta name="twitter:description" content="<?php echo htmlspecialchars($offer['meta_description']); ?>">
            <meta name="twitter:image" content="<?php echo $_og_img; ?>">
            
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
                        <li> <?php custom_echo($offer['name'], 30); ?></li>
                    </ul>
                    <h2>Blogs</h2>
                </div>
            </div>
        </section>
        <!--Page Header End-->

<?php
$rawBlogName = $offer['name'];
$encodedBlogName = urlencode($rawBlogName);
// Build absolute raw URL for navigator.share and encoded version for <a> tags
$rawPageUrl = SITE_URL . ltrim($_SERVER['REQUEST_URI'], '/');
$encodedPageUrl = urlencode($rawPageUrl);
?>





        <!--Blog Details Start-->
        <section class="blog-details">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        <div class="blog-details__left">
                            <div class="blog-details__img">
                                <?php 
                                    $imagePath = "upload/" . trim($offer['photo']);
                                    if (!empty($offer['photo']) && file_exists($imagePath)) { 
                                        $displayImg = SITE_URL . $imagePath; 
                                    } else { 
                                        $displayImg = SITE_URL . "upload/" . ($pr_add['photo'] ?? '080325100432logo.png'); 
                                    }
                                ?>
                                <img src="<?php echo $displayImg; ?>" alt="<?php echo $offer['name']; ?>">
                            </div>
                            <div class="blog-details__content">
                                <ul class="list-unstyled blog-details__meta">
                                    <li><a href="#"><i class="far fa-user-circle"></i><?php echo $offer['by_blog']; ?> </a>
                                    </li>
                                    <li><span>/</span></li>
                                   
                                </ul>
                                
         
                          
                          
                                <h3 class="blog-details__title"><?php echo $offer['name']; ?></h3>
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
                                
                                

                                <?php 
                                // Table mobile scroll fix — PHP level (most reliable)
                                $blog_content = $offer['des'];
                                // Har <table ke aage wrapper div lagao
                                $blog_content = preg_replace(
                                    '/<table([^>]*)>/i',
                                    '<div class="table-scroll-wrapper" style="display:block;width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch;margin:10px 0;"><table$1>',
                                    $blog_content
                                );
                                // Har </table> ke baad wrapper div band karo
                                $blog_content = preg_replace(
                                    '/<\/table>/i',
                                    '</table></div>',
                                    $blog_content
                                );
                                ?>
                                <div class="blog-details__text-2"><?php echo $blog_content; ?></div>
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

                            <?php
                            // ── RELATED BLOGS ──────────────────────────────────────────
                            $related_blogs_raw = $sql_ser[0]['related_blogs'] ?? '';
                            $related_blog_ids  = array_filter(array_map('trim', explode(',', $related_blogs_raw)));

                            if (!empty($related_blog_ids)):
                                $ids_safe          = implode(',', array_map('intval', $related_blog_ids));
                                $related_blog_list = sqlfetch("SELECT id, name, photo, by_blog FROM `offer` WHERE id IN ($ids_safe) AND actstat=1 ORDER BY fld_order ASC");
                            else:
                                $related_blog_list = [];
                            endif;

                            if (!empty($related_blog_list)): ?>
                            <div class="sidebar__single sidebar__post">
                                <h3 class="sidebar__title">Related Blogs</h3>
                                <ul class="sidebar__post-list list-unstyled">
                                    <?php foreach ($related_blog_list as $rb): ?>
                                    <li>
                                        <div class="sidebar__post-image">
                                            <?php 
                                                $rb_imagePath = "upload/" . trim($rb['photo']);
                                                if (!empty($rb['photo']) && file_exists($rb_imagePath)) { 
                                                    $rb_displayImg = SITE_URL . $rb_imagePath; 
                                                } else { 
                                                    $rb_displayImg = SITE_URL . "upload/" . ($pr_add['photo'] ?? '080325100432logo.png'); 
                                                }
                                            ?>
                                            <img src="<?php echo $rb_displayImg; ?>" alt="<?php echo htmlspecialchars($rb['name']); ?>" style="height:80px;width:80px;object-fit:cover;">
                                        </div>
                                        <div class="sidebar__post-content">
                                            <h3>
                                                <span class="sidebar__post-content-meta">
                                                    <i class="far fa-user-circle"></i> by <?php echo htmlspecialchars($rb['by_blog'] ?: 'A2P Realtech'); ?>
                                                </span>
                                                <a href="<?= SITE_URL; ?>blog_detail/<?php echo makeurlnamebynameCategory($rb['name']); ?>.php">
                                                    <?php custom_echo($rb['name'], 30); ?>
                                                </a>
                                            </h3>
                                        </div>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php endif; ?>

                            <?php
                            // ── RELATED PRODUCTS ───────────────────────────────────────
                            $related_prods_raw = $sql_ser[0]['related_products'] ?? '';
                            $related_prod_ids  = array_filter(array_map('trim', explode(',', $related_prods_raw)));

                            if (!empty($related_prod_ids)):
                                $prod_ids_safe     = implode(',', array_map('intval', $related_prod_ids));
                                $related_prod_list = sqlfetch("SELECT id, name, photo FROM `subproduct` WHERE id IN ($prod_ids_safe) AND actstat=1 ORDER BY fld_order ASC");
                            else:
                                $related_prod_list = [];
                            endif;

                            if (!empty($related_prod_list)): ?>
                            <div class="sidebar__single sidebar__post" style="margin-top: 30px;">
                                <h3 class="sidebar__title">Related Products</h3>
                                <ul class="sidebar__post-list list-unstyled">
                                    <?php foreach ($related_prod_list as $rp): ?>
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
        <!--Blog Details End-->







        <?php include 'include/footer.php' ?><?php }
                                        }
                                                ?>