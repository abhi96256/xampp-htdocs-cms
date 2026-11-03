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
                <li>Search </li>
            </ul>
            <h2>Search </h2>
        </div>
    </div>
</section>
<style>
/* Futuristic Premium Search Layout */
.search-container-wrap {
    position: relative;
    padding: 80px 0;
    background: radial-gradient(circle at top right, rgba(192, 4, 21, 0.05) 0%, transparent 40%),
                radial-gradient(circle at bottom left, rgba(16, 42, 131, 0.05) 0%, transparent 40%);
    overflow: hidden;
}

.search-container-wrap::before {
    content: '';
    position: absolute;
    top: -50px;
    right: -50px;
    width: 200px;
    height: 200px;
    background: rgba(192, 4, 21, 0.03);
    border-radius: 50%;
    filter: blur(50px);
}

.search-glass-card {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.4);
    border-radius: 30px;
    padding: 50px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08);
    max-width: 900px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
}

.welcome-one {
    padding: 0;
    background: transparent;
}

.search-bar-premium {
    position: relative;
    display: flex;
    align-items: center;
    background: #fff;
    border-radius: 60px;
    padding: 8px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    border: 2px solid transparent;
}

.search-bar-premium:focus-within {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(192, 4, 21, 0.12);
    border-color: rgba(192, 4, 21, 0.3);
}

.search-input-premium {
    flex: 1;
    border: none;
    background: transparent;
    padding: 15px 30px;
    font-size: 18px;
    color: #333;
    font-weight: 500;
}

.search-input-premium:focus {
    outline: none;
}

.search-input-premium::placeholder {
    color: #aaa;
    font-weight: 400;
}

.btn-search-glow {
    background: #c00415;
    color: #fff;
    border: none;
    padding: 15px 40px;
    border-radius: 50px;
    font-size: 18px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 12px;
    position: relative;
    overflow: hidden;
    transition: all 0.4s ease;
    box-shadow: 0 8px 20px rgba(192, 4, 21, 0.3);
}

.btn-search-glow::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: 0.5s;
}

.btn-search-glow:hover {
    background: #900010;
    transform: scale(1.02);
    box-shadow: 0 12px 25px rgba(192, 4, 21, 0.4);
}

.btn-search-glow:hover::before {
    left: 100%;
}

.btn-search-glow i {
    font-size: 20px;
    transition: all 0.3s ease;
}

.btn-search-glow:hover i {
    transform: rotate(20deg) scale(1.2);
}

.search-tagline {
    text-align: center;
    margin-bottom: 30px;
}

.search-tagline h2 {
    font-size: 42px;
    font-weight: 900;
    color: #222;
    margin-bottom: 10px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.05);
}

.search-tagline p {
    color: #666;
    font-size: 16px;
    letter-spacing: 1px;
    text-transform: uppercase;
    font-weight: 600;
}

.accent-line {
    width: 60px;
    height: 4px;
    background: #c00415;
    margin: 15px auto;
    border-radius: 2px;
}

@media (max-width: 767px) {
    .search-container-wrap {
        padding: 40px 0;
    }
    .search-glass-card {
        padding: 30px 15px;
        margin: 0 10px;
        border-radius: 20px;
    }
    .search-bar-premium {
        background: transparent;
        box-shadow: none;
        padding: 0;
        border: none;
    }
    .search-bar-premium form {
        flex-direction: column !important;
        gap: 15px;
    }
    .search-bar-premium form i {
        display: none; /* Hide magnifying glass on mobile to save space */
    }
    .search-input-premium {
        width: 100% !important;
        background: #fff !important;
        border-radius: 12px !important;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05) !important;
        text-align: center;
        padding: 15px !important;
        font-size: 15px !important;
    }
    .btn-search-glow {
        width: 100% !important;
        justify-content: center;
        border-radius: 12px !important;
        padding: 15px !important;
        font-size: 16px !important;
    }
    .search-tagline h2 {
        font-size: 24px !important;
    }
    .search-tagline p {
        font-size: 13px !important;
    }
    /* Fix for the actual search bar */
    .search-bar-wrapper {
        margin: 0 10px !important; /* Force some side margin */
        border-radius: 30px !important;
    }
    .search-bar-wrapper input {
        padding: 15px 20px !important;
        font-size: 16px !important;
    }
    .search-bar-wrapper button {
        width: 60px !important; /* Smaller button on mobile */
        height: 55px !important;
        font-size: 18px !important;
    }
}
</style>

<section class="search-container-wrap">
    <div class="container text-center">
        <!-- Search Here Title -->
        <h1 style="color: #c00415; font-size: 48px; font-weight: 800; margin-bottom: 30px; text-transform: none;">Search Here</h1>
        
        <div class="search-bar-wrapper" style="max-width: 900px; margin: 0 auto; background: #fff; border: 1px solid #ddd; border-radius: 50px; display: flex; align-items: center; overflow: hidden; box-shadow: 0 5px 25px rgba(0,0,0,0.05);">
            <form action="<?= SITE_URL; ?>search_result.php" method="GET" class="d-flex w-100 align-items-center" onsubmit="return checkSearchRedirect(this)">
                <input type="text" 
                       style="flex: 1; border: none; padding: 20px 35px; font-size: 18px; outline: none;" 
                       placeholder="Search here..." 
                       name="query" 
                       autocomplete="off">
                <button type="submit" 
                        style="background: #2196F3; color: #fff; border: none; height: 65px; width: 80px; font-size: 22px; cursor: pointer; transition: background 0.3s; display: flex; align-items: center; justify-content: center; border-radius: 0 50px 50px 0;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>
    </div>
</section>

<script>
function checkSearchRedirect(form) {
    var queryInput = form.querySelector('[name="query"]');
    var query = queryInput ? queryInput.value.trim().toLowerCase() : '';
    if (!query) return true;

    // 1. Blog redirection
    var blogKeywords = ['blog', 'news', 'article', 'update', 'blogs'];
    var isBlogMatch = blogKeywords.some(function(kw) {
        return query.indexOf(kw) !== -1;
    });
    if (isBlogMatch) {
        window.location.href = '<?= SITE_URL; ?>blog.php';
        return false;
    }

    // 2. Contact related keywords
    var contactKeywords = [
        'address', 'location', 'phone', 'mobile', 'call', 'email', 'contact',
        'office', 'address?', 'pincode', 'corporate', 'connect', 'reach',
        'enquiry', 'help', 'support', 'number', 'whatsapp'
    ];

    // Check if query contains any contact keyword
    var isKeywordMatch = contactKeywords.some(function(kw) {
        return query.indexOf(kw) !== -1;
    });

    // Check for phone number pattern or email pattern
    var digitsOnly = query.replace(/[^\d]/g, '');
    var isPhone = digitsOnly.length >= 7; // Basic phone check
    var isEmail = /[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}/.test(query);

    if (isKeywordMatch || isPhone || isEmail) {
        window.location.href = '<?= SITE_URL; ?>contact.php';
        return false;
    }
    return true;
}
</script>





<?php include 'include/footer.php' ?>