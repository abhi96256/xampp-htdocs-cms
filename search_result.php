<?php
include "function/function.php";

if (isset($_GET['query'])) {
    $search_redir = trim($_GET['query']);
    $searchLower_redir = strtolower($search_redir);

    // Check if we are doing a specific filtered search from the wizard/filters
    // If location, category, or subcategory is set, we skip the general redirections
    $isFilteredSearch = isset($_GET['location']) || isset($_GET['category_id']) || isset($_GET['subcategory_id']);

    if (!$isFilteredSearch) {
        // 1. Blog redirection
        $blogKeywords = ['blog', 'news', 'article', 'update', 'blogs'];
        foreach ($blogKeywords as $kw) {
            if (strpos($searchLower_redir, $kw) !== false) {
                header('Location: ' . SITE_URL . 'blog.php');
                exit();
            }
        }

        // 2. Contact redirection
        $contactKeywords_redir = [
            'address', 'location', 'phone', 'mobile', 'call', 'email', 'contact',
            'office', 'address?', 'pincode', 'corporate', 'connect', 'reach',
            'enquiry', 'help', 'support', 'number', 'whatsapp'
        ];

        $isKeywordMatch_redir = false;
        foreach ($contactKeywords_redir as $kw) {
            if (strpos($searchLower_redir, $kw) !== false) {
                $isKeywordMatch_redir = true;
                break;
            }
        }

        $isPhone_redir = preg_match('/\d{7,}/', preg_replace('/[^\d]/', '', $search_redir));
        $isEmail_redir = filter_var($search_redir, FILTER_VALIDATE_EMAIL) ||
            preg_match('/[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}/', $search_redir);

        if ($isKeywordMatch_redir || $isPhone_redir || $isEmail_redir) {
            header('Location: ' . SITE_URL . 'contact.php');
            exit();
        }
    }
}
?>
<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
ini_set('display_errors', 1);
?>



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


<?php
// Include header
include 'include/header.php';

// Function to highlight search terms
function highlightTerms($text, $term)
{
    if (!$term || $text === null)
        return (string)$text;

    // Create a regex pattern that matches the term with optional spaces/hyphens
    $words = preg_split('/\s+/', trim($term), -1, PREG_SPLIT_NO_EMPTY);
    $pattern = implode('[[:space:]-]*', array_map('preg_quote', $words));

    return preg_replace('/(' . $pattern . ')/i', '<strong>$1</strong>', (string)$text);
}
?>

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

    /* Highlight - Match Image 2 (just bold and red) */
    strong {
        background-color: transparent;
        color: inherit;
        font-weight: 900;
        text-decoration: none;
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
        width: 100%;
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

    h3.cool {
        margin-bottom: 32px;
        font-weight: 800;
        font-size: 24px;
        color: #007bff;
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

<section class="page-header">
    <div class="page-header-bg" style="background-image: url(assets/images/backgrounds/page-header-bg.jpg)"></div>
    <div class="container">
        <div class="page-header__inner">
            <ul class="thm-breadcrumb list-unstyled">
                <li><a href="<?= SITE_URL; ?>">Home</a></li>
                <li><span>/</span></li>
                <li>
                    <?php
if (isset($_GET['query']) && $_GET['query'] != '') {
    echo highlightTerms('Search', $_GET['query']);
}
else {
    echo 'Search';
}
?>
                </li>
            </ul>
            <h2>
                <span style="color: #007bff;">Search Results for:</span> 
                <?php
if (isset($_GET['query']) && $_GET['query'] != '') {
    echo '"' . highlightTerms(htmlspecialchars($_GET['query']), $_GET['query']) . '"';
}
else {
    echo 'Search';
}
?>
            </h2>
        </div>
    </div>
</section>

<section class="services-page">
    <div class="container">
        <?php
$pdo = getPDOObject();

if (isset($_GET['query'])) {
    $search = trim($_GET['query']);
    $location = isset($_GET['location']) ? trim($_GET['location']) : '';
    $category_id = isset($_GET['category_id']) ? trim($_GET['category_id']) : '';
    $subcategory_id = isset($_GET['subcategory_id']) ? trim($_GET['subcategory_id']) : '';

    // 1. IMPROVED SEARCH LOGIC
    // To exclude 'whitelanland' when searching for 'elan', we use word boundaries [[:<:]] and [[:>:]]
    // To handle 'smart world' vs 'smartworld', we create a regex that matches optional spaces/hyphens

    $words = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);
    $cleanTerm = implode('[[:space:]-]*', array_map('preg_quote', $words));

    // Regex for exact word/phrase match with flexible spaces
    $searchRegex = '[[:<:]]' . $cleanTerm . '[[:>:]]';

    // Also handle cases where spaces are completely removed in DB (like 'smartworld')
    $compactSearch = str_replace([' ', '-'], '', $search);
    $compactRegex = '[[:<:]]' . preg_quote($compactSearch) . '[[:>:]]';

    $searchSafe = "%$search%";
    $locSafe = "%$location%";

    $query = "SELECT DISTINCT * FROM subproduct 
                      WHERE (name LIKE ? 
                      OR pro_lable LIKE ?
                      OR city LIKE ?
                      OR meta_title LIKE ?
                      OR meta_keyword LIKE ?
                      OR (developer REGEXP ? OR REPLACE(developer, ' ', '') REGEXP ?))";

    $params = [
        $searchSafe, $searchSafe, $searchSafe,
        $searchSafe, $searchSafe,
        $searchRegex, $compactRegex
    ];

    if (!empty($location)) {
        $query .= " AND (pro_lable LIKE ? OR city LIKE ?)";
        $params[] = $locSafe;
        $params[] = $locSafe;
    }

    if (!empty($category_id)) {
        $query .= " AND subcat2 = ?";
        $params[] = $category_id;
    }

    if (!empty($subcategory_id)) {
        $query .= " AND subcat = ?";
        $params[] = $subcategory_id;
    }

    $query .= " AND actstat = 1";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // If no products found, try searching in subcategory names with same improved logic
    if (empty($products)) {
        $stmt2 = $pdo->prepare("SELECT id FROM subcategory WHERE (name REGEXP ? OR REPLACE(name, ' ', '') REGEXP ?) AND actstat = 1");
        $stmt2->execute([$searchRegex, $compactRegex]);
        $subcat = $stmt2->fetch(PDO::FETCH_ASSOC);

        if ($subcat) {
            $subcatId = $subcat['id'];
            $stmt3 = $pdo->prepare("SELECT DISTINCT * FROM subproduct WHERE subcat = ? AND actstat = 1");
            $stmt3->execute([$subcatId]);
            $products = $stmt3->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    // --- Fetch Offers (Blogs) ---
    $offers = [];
    if (!$isFilteredSearch) {
        $stmt4 = $pdo->prepare("SELECT * FROM offer WHERE name LIKE ? OR des1 LIKE ?");
        $stmt4->execute([$searchSafe, $searchSafe]);
        $offers = $stmt4->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- Fetch Media Gallery (fixed_delivery_time) ---
    $mediaItems = [];
    if (!$isFilteredSearch) {
        $stmt5 = $pdo->prepare("SELECT * FROM fixed_delivery_time WHERE name LIKE ? OR meta_title LIKE ?");
        $stmt5->execute([$searchSafe, $searchSafe]);
        $mediaItems = $stmt5->fetchAll(PDO::FETCH_ASSOC);
    }

    echo '<div class="row"><div class="col-md-12">';
    echo '<h3 class="cool"><span style="color: #007bff;">Search Results for:</span> "' . htmlspecialchars($search) . '"</h3>';

    // Show products
    if (!empty($products)) {
        echo '<h4 class="mb-4 mt-5">Projects & Properties</h4>';
        echo '<div class="row">';
        foreach ($products as $subproductwww) {
?>
                    <div class="col-xl-4 col-lg-4 col-md-6 productr mb-4">
                        <div class="services-one__single wow fadeInUp" data-wow-delay="100ms">
                            <div class="services-one__img">
                                <a href="<?= SITE_URL; ?>service_detail/<?php echo makeurlnamebynameCategory($subproductwww['name']); ?>.php">
                                    <?php
            $res_placeholders = ["060825060302Vatika Sovereign Park Image1.jpg", "100725050048Sobha-City-Sector-108-Dwarka-Expressway-Gurgaon.jpg", "160425091419Sobha Altus image A2P Realtech.jpg", "20260128161604_M3M-GIC-Manesar-Gurgaon.jpg"];
            $imagePath = "upload/" . $subproductwww['photo'];
            if (!empty($subproductwww['photo']) && file_exists($imagePath)) {
                $displayImg = SITE_URL . $imagePath;
            }
            else {
                $placeholderIndex = $subproductwww['id'] % count($res_placeholders);
                $displayImg = SITE_URL . "upload/" . $res_placeholders[$placeholderIndex];
            }
?>
                                    <img src="<?php echo $displayImg; ?>" alt="" style="width: 100%; height: 250px; object-fit: fill !important;">
                                </a>
                            </div>
                            <div class="services-one__content">
                                <h3 class="services-one__title">
                                    <a href="<?= SITE_URL; ?>service_detail/<?php echo makeurlnamebynameCategory($subproductwww['name']); ?>.php">
                                        <?php echo highlightTerms(htmlspecialchars($subproductwww['name']), $search); ?>
                                    </a>
                                </h3>
                                <p class="services-one__text"><i class="fa-solid fa-map-pin"></i> <?php echo htmlspecialchars($subproductwww['pro_lable']); ?></p>
                                <p class="services-one__text price"><?php echo htmlspecialchars($subproductwww['pro_discountprice']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
        }
        echo '</div>';
    }

    // Show Media Gallery Results
    if (!empty($mediaItems)) {
        echo '<hr class="my-5">';
        echo '<h4 class="mb-4">Media Gallery Results</h4>';
        echo '<div class="row">';
        foreach ($mediaItems as $media) {
?>
                    <div class="col-xl-4 col-lg-4 col-md-6 mb-4">
                        <div class="services-one__single wow fadeInUp" data-wow-delay="100ms">
                            <div class="services-one__img">
                                <a href="<?= SITE_URL; ?>media-gallery-detail/<?php echo makeurlnamebynameCategory($media['name']); ?>.php">
                                    <?php
            $media_placeholders = ["060825060302Vatika Sovereign Park Image1.jpg", "100725050048Sobha-City-Sector-108-Dwarka-Expressway-Gurgaon.jpg", "160425091419Sobha Altus image A2P Realtech.jpg"];
            $imagePath = "upload/" . $media['photo'];
            if (!empty($media['photo']) && file_exists($imagePath)) {
                $displayImg = SITE_URL . $imagePath;
            }
            else {
                $placeholderIndex = $media['id'] % count($media_placeholders);
                $displayImg = SITE_URL . "upload/" . $media_placeholders[$placeholderIndex];
            }
?>
                                    <img src="<?php echo $displayImg; ?>" alt="" style="height:250px; width:100%; object-fit:fill !important;">
                                </a>
                            </div>
                            <div class="services-one__content">
                                <h3 class="services-one__title text-center">
                                    <a href="<?= SITE_URL; ?>media-gallery-detail/<?php echo makeurlnamebynameCategory($media['name']); ?>.php">
                                        <?php echo highlightTerms(htmlspecialchars($media['name']), $search); ?>
                                    </a>
                                </h3>
                                <div class="text-center">
                                    <a href="<?= SITE_URL; ?>media-gallery-detail/<?php echo makeurlnamebynameCategory($media['name']); ?>.php" class="btn btn-sm btn-danger mt-3">View Gallery</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
        }
        echo '</div>';
    }

    // Show offers
    if (!empty($offers)) {
        echo '<hr class="my-5">';
        echo '<h4 class="mb-4">News & Blogs</h4>';
        echo '<div class="row">';
        foreach ($offers as $offer) {
?>
                    <div class="col-xl-4 col-lg-4 wow fadeInUp" data-wow-delay="100ms">
                        <a href="<?= SITE_URL; ?>blog_detail/<?php echo makeurlnamebynameCategory($offer['name']); ?>.php">
                            <div class="blog-one__single">
                                <div class="blog-one__img">
                                    <?php
            $imagePath = "upload/" . trim($offer['photo']);
            if (!empty($offer['photo']) && file_exists($imagePath)) {
                $displayImg = SITE_URL . $imagePath;
            }
            else {
                $displayImg = SITE_URL . "upload/" . ($pr_add['photo'] ?? '080325100432logo.png');
            }
?>
                                    <img src="<?php echo $displayImg; ?>" alt="<?php echo htmlspecialchars($offer['name']); ?>" style="height: 250px; width: 100%; object-fit: fill !important;">
                                </div>
                                <div class="blog-one__content">
                                    <div class="blog-one__date">
                                        <p><?php echo htmlspecialchars($offer['des1']); ?></p>
                                    </div>
                                    <ul class="list-unstyled blog-one__meta">
                                        <li><a href="<?= SITE_URL; ?>blog_detail/<?php echo makeurlnamebynameCategory($offer['name']); ?>.php"><i class="far fa-user-circle"></i> <?php echo htmlspecialchars($offer['by_blog']); ?></a></li>
                                        <li><span>/</span></li>
                                    </ul>
                                    <h3 class="blog-one__title">
                                        <a href="<?= SITE_URL; ?>blog_detail/<?php echo makeurlnamebynameCategory($offer['name']); ?>.php">
                                            <?php echo highlightTerms(custom_echo(htmlspecialchars($offer['name']), 40), $search); ?>
                                        </a>
                                    </h3>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php
        }
        echo '</div>';
    }

    if (empty($products) && empty($offers) && empty($mediaItems)) {
        echo '<div class="alert alert-info text-center py-5">
                    <i class="fa fa-frown fa-3x mb-3 text-muted"></i>
                    <h4>No direct results found for: "' . htmlspecialchars($search) . '"</h4>
                    <p>Try searching for a different keyword or check our categories.</p>
                </div>';
    }

    echo '</div></div>';
}
else {
    echo '<div class="alert alert-warning">Please enter a search term</div>';
}
?>
    </div>
</section>
    </div>
</section>

<?php include 'include/footer.php'; ?>

