<!--Site Footer Start-->

<style>
    .footer-widget__title {
        color: black;
        position: relative;
        padding-bottom: 15px;
        margin-bottom: 25px;
    }

    .footer-widget__title:before {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 35px;
        height: 3px;
        background: #c00415;
    }

    .footer-widget__explore-list li a {
        color: black;
        transition: all 0.3s ease;
    }
    
    .footer-widget__explore-list li a:hover {
        color: #c00415;
        padding-left: 5px;
    }

    .footer-widget__services-list li a {
        color: #000000;
        transition: all 0.3s ease;
    }
    
    .footer-widget__services-list li a:hover {
        color: #c00415;
        padding-left: 5px;
    }

    .footer-widget__contact-list li .text h5 {
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #666;
        margin-bottom: 4px;
    }

    .footer-widget__contact-list li .text p {
        color: #000000;
        font-weight: 600;
        line-height: 1.5;
    }

    .footer-widget__contact-list li .text p a {
        color: #000000;
        transition: color 0.3s;
    }
    
    .footer-widget__contact-list li .text p a:hover {
        color: #c00415;
    }

    .footer-widget__about-text {
        color: #444;
        line-height: 1.8;
        margin-bottom: 25px;
    }

    .site-footer-two .site-footer__top {
        box-shadow: 0 -5px 20px rgba(0,0,0,0.05);
        padding: 80px 0 50px;
    }

    .footer-widget__logo img {
        max-height: 80px;
        width: auto !important;
        object-fit: contain;
    }

    .footer-widget__contact-list li {
        display: flex;
        align-items: flex-start;
        margin-bottom: 20px;
        gap: 15px;
    }

    .footer-widget__contact-list li .icon {
        width: 45px;
        height: 45px;
        background: #c00415;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff !important;
        font-size: 18px;
        flex-shrink: 0;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(192, 4, 21, 0.2);
    }

    .footer-widget__contact-list li .icon span,
    .footer-widget__contact-list li .icon i {
        color: #fff !important;
    }

    .footer-widget__contact-list li:hover .icon {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 6px 15px rgba(192, 4, 21, 0.3);
    }

    .footer-widget__contact {
        margin-left: 0 !important;
    }

    .logo-box img {
        width: 155px !important;
        height: auto !important;
        object-fit: contain !important;
    }

    /* Colorful Footer Social Icons */
    .site-footer__social a {
        background: #333 !important; /* Default */
        color: #fff !important;
        transition: all 0.3s ease !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 44px !important;
        height: 44px !important;
        border-radius: 50% !important;
        margin-right: 8px !important;
        font-size: 20px !important;
        text-decoration: none !important;
    }

    .site-footer__social a.facebook { background-color: #1877F2 !important; }
    .site-footer__social a.twitter { background-color: #000 !important; }
    .site-footer__social a.instagram { 
        background: #f09433 !important;
        background: linear-gradient(45deg, #405de6, #5851db, #833ab4, #c13584, #e1306c, #fd1d1d) !important;
        background-image: linear-gradient(45deg, #405de6, #5851db, #833ab4, #c13584, #e1306c, #fd1d1d) !important;
    }
    .site-footer__social a.linkedin { background-color: #0077B5 !important; }
    .site-footer__social a.youtube { background-color: #FF0000 !important; }
    .site-footer__social a.whatsapp { background-color: #25D366 !important; }

    .site-footer__social a:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2) !important;
        opacity: 0.9 !important;
    }

    /* Global Modals Base Styles */
    #location-modal {
        position: fixed; top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.85);
        display: none; align-items: center; justify-content: center;
        z-index: 2000001; backdrop-filter: blur(10px);
        font-family: 'Poppins', sans-serif;
    }

    /* Shared Modal Cards Style */
    .loc-modal-card {
        background: #fff; padding: 40px 30px;
        border-radius: 24px; text-align: center;
        max-width: 420px; width: 90%;
        box-shadow: 0 20px 60px rgba(0,0,0,0.4);
        animation: modalPop 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
    }

    @keyframes modalPop {
        from { opacity: 0; transform: scale(0.8) translateY(30px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }

    /* Icon Pulse Styles */
    .loc-icon-pulse {
        width: 90px; height: 90px;
        background: #fdf2f2; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        margin-bottom: 25px; position: relative;
        color: #c00415; font-size: 35px;
    }

    .loc-icon-pulse::after {
        content: ''; position: absolute;
        top: -5px; left: -5px; right: -5px; bottom: -5px;
        border: 2px solid #c00415; border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        100% { transform: scale(1.3); opacity: 0; }
    }

    .loc-modal-card h3 { color: #222; font-weight: 800; font-size: 24px; margin-bottom: 12px; }
    .loc-modal-card p { color: #666; font-size: 15px; line-height: 1.6; margin-bottom: 30px; }

    .loc-btn-primary {
        background: #c00415; color: #fff; border: none;
        padding: 16px 30px; border-radius: 50px;
        font-weight: 700; cursor: pointer; width: 100%;
        font-size: 16px; transition: all 0.3s;
        box-shadow: 0 8px 20px rgba(192,4,21,0.3);
    }
    .loc-btn-primary:hover { background: #000; transform: translateY(-3px); }
</style>


<footer class="site-footer site-footer-two">
    <div class="site-footer-bg" style="background:white;">
    </div>
    <div class="site-footer__top">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="100ms">
                    <div class="footer-widget__column footer-widget__about">
                        <div class="footer-widget__logo">
                            <a href="<?= SITE_URL; ?>"><img src="<?= SITE_URL; ?>upload/<?php echo $pr_add['photo']; ?>" alt="Logo"></a>
                        </div>
                        <div class="footer-widget__about-text-box">
                            <p class="footer-widget__about-text">A2P Realtech is setting new standards for excellence and transforming the way real estate services are delivered.</p>
                        </div>
                        <div class="site-footer__social">
                            <a href="<?php echo $pr_add['facebook']; ?>" class="facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="<?php echo $pr_add['twitter']; ?>" class="twitter"><i class="fab fa-x-twitter"></i></a>
                            <a href="<?php echo $pr_add['youtube']; ?>" class="instagram"><i class="fab fa-instagram"></i></a>
                            <a href="<?php echo $pr_add['linkedin']; ?>" class="youtube"><i class="fab fa-youtube"></i></a>
                            <a href="<?php echo $pr_add['linkedin2']; ?>" class="linkedin"><i class="fab fa-linkedin-in"></i></a>
                            <a href="https://api.whatsapp.com/send?phone=918130525001" class="whatsapp" target="_blank"><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </div>
                </div>
                <!--<div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="200ms">-->
                <!--    <div class="footer-widget__column footer-widget__explore clearfix">-->
                <!--        <h3 class="footer-widget__title">Projects</h3>-->
                <!--        <ul class="footer-widget__explore-list list-unstyled clearfix">-->
                <!--            <li><a href="<?= SITE_URL; ?>residential.php">Residential</a></li>-->
                <!--            <li><a href="<?= SITE_URL; ?>commercial.php">Commercial</a></li>-->
                            

                <!--        </ul>-->
                <!--    </div>-->
                <!--</div>-->
                <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="300ms">
                    <div class="footer-widget__column footer-widget__services clearfix">
                        <h3 class="footer-widget__title">Quicklinks</h3>
                        <ul class="footer-widget__services-list list-unstyled clearfix">


                            
                                    <li><a href="<?= SITE_URL; ?>sitemap.html">Site Map </a></li>
                                    <li><a href="<?= SITE_URL; ?>terms_conditions.php">Terms And Conditions </a></li>
                                    <li><a href="<?= SITE_URL; ?>privacy_policy.php">Privacy Policy </a></li>
                                    <li><a href="<?= SITE_URL; ?>blog.php">Blog </a></li>
                                    <li><a href="<?= SITE_URL; ?>contact.php">Contact Us </a></li>
                                    <li><a href="<?= SITE_URL; ?>career.php">Career </a></li>

                            

                        </ul>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="400ms">
                    <div class="footer-widget__column footer-widget__contact clearfix">
                        <h3 class="footer-widget__title">Contact</h3>
                        <ul class="footer-widget__contact-list list-unstyled clearfix">
                            <li>
                                <div class="icon">
                                    <span class="icon-phone-call"></span>
                                </div>
                                <div class="text">
                                    <h5>Call anytime</h5>
                                    <p><a href="tel:+91-8130525001">+91-8130525001</a></p>
                                    <p><a href="tel:+91-8130510678">+91-8130510678</a></p>
                                </div>
                            </li>
                            <li>
                                <div class="icon">
                                    <span class="icon-message"></span>
                                </div>
                                <div class="text">
                                    <h5>Send email</h5>
                                    <p><a href="mailto:<?php echo $pr_add['email']; ?>"><?php echo $pr_add['email']; ?></a></p>
                                </div>
                            </li>
                            <li>
                                <div class="icon">
                                    <span class="icon-location"></span>
                                </div>
                                <div class="text">
                                    <h5>Address</h5>
                                    <p><?php echo $pr_add['addr']; ?></p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="site-footer__bottom">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="site-footer__bottom-inner">
                        <p class="site-footer__bottom-text">© Copyright  by <a href="<?= SITE_URL; ?>">A2P Realtech</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!--Site Footer End-->


</div>


<div class="mobile-nav__wrapper">
    <div class="mobile-nav__overlay mobile-nav__toggler"></div>
    <!-- /.mobile-nav__overlay -->
    <div class="mobile-nav__content">
        <span class="mobile-nav__close mobile-nav__toggler"><i class="fa fa-times"></i></span>

        <div class="logo-box">
            <a href="<?= SITE_URL; ?>" aria-label="logo image"><img src="<?= SITE_URL; ?>upload/<?php echo $pr_add['photo']; ?>"
                    width="155" alt="" /></a>
        </div>
        <!-- /.logo-box -->
        <style>
            .mobile-nav__search-box {
                padding: 20px 20px;
                border-bottom: 1px solid rgba(255,255,255,0.08);
            }
            .mobile-nav__search-box form {
                position: relative;
                display: flex;
                align-items: center;
                background: rgba(255,255,255,0.1) !important;
                border-radius: 12px;
                padding: 2px;
                border: 1px solid rgba(255,255,255,0.1) !important;
                box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
                overflow: hidden;
            }
            .mobile-nav__search-box button {
                position: absolute;
                left: 5px; /* Moved to left */
                top: 5px;
                bottom: 5px;
                width: 40px;
                background: #fff !important;
                border-radius: 8px;
                color: #c00415 !important;
                border: none;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.3s;
                z-index: 2;
            }
            .mobile-nav__search-box input {
                flex: 1;
                background: transparent;
                border: none;
                color: #fff !important;
                padding: 12px 15px 12px 50px; /* Space on left for button */
                font-size: 14px;
                outline: none !important;
                width: 100%;
                z-index: 1;
            }
            .mobile-nav__search-box form:focus-within button {
                color: #c00415;
            }
            .mobile-nav__search-box input::placeholder {
                color: rgba(255,255,255,0.5);
                transition: all 0.3s;
            }
            .mobile-nav__search-box form:focus-within input::placeholder {
                color: #999;
            }
        </style>

        <div class="mobile-nav__search-box">
            <form action="<?= SITE_URL; ?>search_result.php" method="GET">
                <input type="text" name="query" placeholder="Search for property..." required>
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>

        <div class="mobile-nav__container"></div>
        <!-- /.mobile-nav__container -->

        <!-- Attractive Phone Card -->
        <div class="mobile-phone-card">
            <div class="mobile-phone-card__icon">
                <i class="fa fa-phone-alt"></i>
            </div>
            <div class="mobile-phone-card__text">
                <span>Call Us Anytime</span>
                <?php
                    $phone_raw = $pr_add['phone'];
                    // Split numbers if they are comma-separated
                    $phone_arr = explode(',', $phone_raw);
                    foreach($phone_arr as $p) {
                        $p = trim($p);
                        if(empty($p)) continue;
                        
                        // Remove +91 prefix for display
                        $display = preg_replace('/^\+91[-\s]?/', '', $p);
                        // Keep only digits from the prefix-removed string
                        $digits = preg_replace('/[^0-9]/', '', $display);
                        
                        echo '<a href="tel:+91'.$digits.'" style="display:block; margin-top:2px; letter-spacing:1px; color:#fff !important; position:relative; z-index:5;">'.$display.'</a>';
                    }
                ?>
            </div>
        </div>
        <style>
        .mobile-phone-card {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 0 15px 5px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 14px;
            padding: 12px 16px;
            backdrop-filter: blur(5px);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .mobile-phone-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at center, rgba(255,255,255,0.05) 0%, transparent 60%);
            animation: phonePulse 3s ease-in-out infinite;
            pointer-events: none;
        }
        @keyframes phonePulse {
            0%, 100% { opacity: 0.5; transform: scale(0.8); }
            50% { opacity: 1; transform: scale(1); }
        }
        .mobile-phone-card__icon {
            width: 44px;
            height: 44px;
            background: #fff !important;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #c00415 !important;
            font-size: 18px;
            flex-shrink: 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2) !important;
            animation: phoneRing 2.5s ease-in-out infinite;
        }
        @keyframes phoneRing {
            0%, 100% { transform: rotate(0deg); }
            10% { transform: rotate(-15deg); }
            20% { transform: rotate(15deg); }
            30% { transform: rotate(-10deg); }
            40% { transform: rotate(10deg); }
            50% { transform: rotate(0deg); }
        }
        .mobile-phone-card__text {
            display: flex;
            flex-direction: column;
            line-height: 1.3;
        }
        .mobile-phone-card__text span {
            font-size: 10px;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }
        .mobile-phone-card__text a {
            font-size: 15px;
            color: #fff !important;
            font-weight: 700;
            text-decoration: none;
            letter-spacing: 0.5px;
        }
        .mobile-phone-card__text a:hover {
            color: #ffcdd2 !important;
        }
        </style>
        <div class="mobile-nav__top">
            <div class="mobile-social-wrapper">
                <div class="mobile-nav__social">
                    <a href="<?php echo $pr_add['facebook']; ?>" class="facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="<?php echo $pr_add['youtube']; ?>" class="instagram"><i class="fab fa-instagram"></i></a>
                    <a href="<?php echo $pr_add['linkedin']; ?>" class="youtube"><i class="fab fa-youtube"></i></a>
                    <a href="<?php echo $pr_add['linkedin2']; ?>" class="linkedin"><i class="fab fa-linkedin-in"></i></a>
                    <a href="https://api.whatsapp.com/send?phone=918130525001" class="whatsapp" target="_blank"><i class="fab fa-whatsapp"></i></a>
                </div><!-- /.mobile-nav__social -->
            </div>
        </div><!-- /.mobile-nav__top -->



    </div>
    <!-- /.mobile-nav__content -->
</div>


<style>
/* Premium Search Popup Redesign */
.search-popup {
    background: rgba(0, 0, 0, 0.9);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.search-popup__content {
    max-width: 800px !important;
    width: 90%;
    margin: 0 auto;
    position: relative;
}

.search-popup form {
    position: relative;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 60px;
    padding: 10px 15px;
    display: flex;
    align-items: center;
    transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
}

.search-popup form:focus-within {
    background: rgba(255, 255, 255, 1);
    border-color: #c00415;
    box-shadow: 0 0 50px rgba(192, 4, 21, 0.3);
    transform: scale(1.02);
}

.search-popup input[type="text"] {
    background: transparent !important;
    color: #fff !important;
    font-size: 26px !important;
    font-weight: 600 !important;
    padding: 15px 30px !important;
    border: none !important;
    flex: 1;
    letter-spacing: 0.5px;
}

.search-popup form:focus-within input[type="text"] {
    color: #222 !important;
}

.search-popup .thm-btn {
    width: 60px !important;
    height: 60px !important;
    border-radius: 50% !important;
    background: #c00415 !important;
    color: #fff !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: all 0.4s ease !important;
    padding: 0 !important;
    box-shadow: 0 8px 15px rgba(192, 4, 21, 0.3) !important;
}

.search-popup .thm-btn:hover {
    transform: rotate(90deg) scale(1.1) !important;
    background: #900010 !important;
}

.search-popup .thm-btn i {
    font-size: 22px !important;
}

/* Close button enhancement */
.mobile-nav__close.search-toggler {
    font-size: 35px;
    color: #fff;
    cursor: pointer;
    transition: all 0.3s ease;
    top: 30px;
    right: 30px;
}

.mobile-nav__close.search-toggler:hover {
    color: #c00415;
    transform: rotate(90deg);
}

.popup-tagline {
    color: #c00415;
    text-align: center;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 3px;
    margin-bottom: 20px;
    font-size: 14px;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.8s ease 0.2s;
}

.search-popup.active .popup-tagline {
    opacity: 1;
    transform: translateY(0);
}
</style>

<div class="search-popup">
    <div class="search-popup__overlay search-toggler"></div>
    <div class="search-popup__content">
        <div class="popup-tagline">Experience the future of property search</div>
        <form action="<?= SITE_URL; ?>search_result.php" method="GET" onsubmit="return checkSearchRedirect(this)">
            <input type="text" id="search" name="query" placeholder="Tell us what you're looking for..." autocomplete="off" />
            <button type="submit" aria-label="search submit" class="thm-btn">
                <i class="icon-magnifying-glass"></i>
            </button>
        </form>
    </div>
</div>

<!-- Location Permission Modal -->
<div id="location-modal" style="display: none;">
    <div class="loc-modal-card">
        <div class="loc-icon-pulse">
            <i class="fas fa-map-marker-alt"></i>
        </div>
        <h3>Location Permission</h3>
        <p>To provide you with the best real estate service in your city, please <strong>Allow Location Permission</strong> in the next step.</p>
        <button id="give-loc-permission" class="loc-btn-primary">Allow Permission & Continue</button>
    </div>
</div>

<script>
/* ══════════════════════════════════════════════════════════════
   GLOBAL TABLE RESPONSIVE FIX (v2)
   1. Sabhi tables ko .table-scroll-wrapper div mein wrap karo
   2. Mobile par inline width styles remove karo (CMS tables)
   3. table-layout: fixed + equal columns = bina scroll fit
   ══════════════════════════════════════════════════════════════ */
(function() {
    var isMobile = window.innerWidth <= 767;

    function fixTables() {
        var tables = document.querySelectorAll('table');
        tables.forEach(function(table) {

            // ── Step 1: Wrapper div mein wrap karo ──
            if (table.parentNode && !table.parentNode.classList.contains('table-scroll-wrapper')) {
                var wrapper = document.createElement('div');
                wrapper.className = 'table-scroll-wrapper';
                table.parentNode.insertBefore(wrapper, table);
                wrapper.appendChild(table);
            }

            // ── Step 2: Mobile par inline widths remove karo ──
            if (isMobile) {
                // Table ka inline width hata do
                table.style.removeProperty('width');
                table.style.removeProperty('min-width');

                // Har TD aur TH ka inline width reset karo
                var cells = table.querySelectorAll('td, th');
                cells.forEach(function(cell) {
                    cell.style.removeProperty('width');
                    cell.style.removeProperty('min-width');
                    cell.style.removeProperty('max-width');
                    cell.style.removeProperty('white-space');
                    // Font chhota karo taaki content fit ho
                    cell.style.fontSize = '11px';
                    cell.style.padding = '4px 3px';
                    cell.style.wordBreak = 'break-word';
                    cell.style.overflowWrap = 'break-word';
                });

                // Table ko force fixed layout
                table.style.tableLayout = 'fixed';
                table.style.width = '100%';
            }
        });
    }

    // DOM ready par chalao
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', fixTables);
    } else {
        fixTables();
    }

    // Screen resize par bhi chalao
    window.addEventListener('resize', function() {
        isMobile = window.innerWidth <= 767;
        fixTables();
    });
})();
</script>
<script>
function checkSearchRedirect(form) {
    var queryField = form.querySelector('[name="query"]');
    var query = queryField ? queryField.value.trim() : '';
    if (!query) return true;

    // Phone: 5+ consecutive digits
    var digitsOnly = query.replace(/[\s\-\(\)\+]/g, '');
    var isPhone = /\d{5,}/.test(digitsOnly);

    // Email pattern
    var isEmail = /[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}/.test(query);

    if (isPhone || isEmail) {
        window.location.href = '<?= SITE_URL; ?>contact.php';
        return false;
    }
    return true;
}
</script>
<script>
    document.getElementById('search-button').addEventListener('click', function() {
        var searchBar = document.getElementById('search-bar');

        if (searchBar.style.display === "none" || searchBar.style.display === "") {
            searchBar.style.display = "block";
        } else {
            searchBar.style.display = "none";
        }
    });
</script>




<!-- Premium AI Chatbot Widget -->
<div id="a2p-chatbot">
    <!-- Chat Button -->
    <div id="chat-button" onclick="toggleChat()">
        <i class="fas fa-robot"></i>
        <span class="chat-badge">1</span>
    </div>

    <!-- Chat Window -->
    <div id="chat-window">
        <div class="chat-header">
            <div class="bot-info">
                <div class="bot-avatar">
                   <img src="<?= SITE_URL; ?>assets/images/favicons/favicon-48x48.png?v=3" alt="A2P Bot">
                </div>
                <div class="bot-details">
                    <h6>A2P Assistant</h6>
                    <small>Online | Instant Response</small>
                </div>
            </div>
            <button class="close-chat" onclick="toggleChat()">&times;</button>
        </div>
        
        <div class="chat-body" id="chat-messages">
            <div class="bot-msg">
                <p>Hello! Welcome to <strong>A2P Realtech</strong>. How can I help you find your dream property today?</p>
            </div>
            <div class="bot-options">
                <button onclick="handleBotOption('buy')"><i class="fas fa-building"></i> Buy Property</button>
                <button onclick="handleBotOption('offers')"><i class="fas fa-fire"></i> Latest Offers</button>
                <button onclick="handleBotOption('location')"><i class="fas fa-map-marker-alt"></i> Search by Location</button>
                <button onclick="handleBotOption('contact')"><i class="fas fa-headset"></i> Talk to Expert</button>
            </div>
        </div>

        <div class="chat-footer">
            <input type="text" id="user-input" placeholder="Type your query..." onkeypress="handleKeyPress(event)">
            <button onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>
</div>

<style>
/* Chat Bot Styling - Using #a2p-chatbot prefix for specificity */
#a2p-chatbot {
    position: fixed !important;
    bottom: 105px !important;
    right: 20px !important;
    z-index: 10001 !important;
    font-family: 'Poppins', sans-serif !important;
}

#a2p-chatbot * {
    box-sizing: border-box !important;
}

#a2p-chatbot #chat-button {
    width: 60px !important;
    height: 60px !important;
    background: #000 !important;
    border-radius: 50% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    color: #fff !important;
    font-size: 24px !important;
    cursor: pointer !important;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3) !important;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
    position: relative !important;
}

#a2p-chatbot #chat-button:hover {
    transform: scale(1.1) rotate(10deg) !important;
    background: #c00415 !important;
}

#a2p-chatbot .chat-badge {
    position: absolute !important;
    top: -5px !important;
    right: -5px !important;
    background: #c00415 !important;
    color: #fff !important;
    width: 22px !important;
    height: 22px !important;
    border-radius: 50% !important;
    font-size: 12px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    border: 2px solid #fff !important;
}

#a2p-chatbot #chat-window {
    position: absolute !important;
    bottom: 80px !important;
    right: 0 !important;
    width: 320px !important;
    height: 450px !important;
    background: #fff !important;
    border-radius: 15px !important;
    display: none;
    flex-direction: column !important;
    overflow: hidden !important;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2) !important;
    animation: chatbotSlideIn 0.3s ease-out !important;
}

@keyframes chatbotSlideIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

#a2p-chatbot .chat-header {
    background: #c00415 !important;
    padding: 15px !important;
    color: #fff !important;
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    width: 100% !important;
}

#a2p-chatbot .bot-info {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
}

#a2p-chatbot .bot-avatar img {
    width: 35px !important;
    height: 35px !important;
    border-radius: 50% !important;
    background: #fff !important;
    padding: 2px !important;
}

#a2p-chatbot .bot-details h6 { 
    margin: 0 !important; 
    padding: 0 !important; 
    font-size: 15px !important; 
    font-weight: 700 !important; 
    color: #fff !important; 
    line-height: 1.2 !important; 
    display: block !important;
    text-align: left !important;
    text-transform: none !important;
    letter-spacing: normal !important;
    width: auto !important;
}
#a2p-chatbot .bot-details small { 
    margin: 3px 0 0 0 !important; 
    padding: 0 !important; 
    font-size: 11px !important; 
    opacity: 0.9 !important; 
    color: #fff !important; 
    display: block !important;
    line-height: 1 !important;
    text-align: left !important;
    text-transform: none !important;
    width: auto !important;
    white-space: nowrap !important;
}

#a2p-chatbot .close-chat {
    background: none !important;
    border: none !important;
    color: #fff !important;
    font-size: 24px !important;
    cursor: pointer !important;
    padding: 0 !important;
    line-height: 1 !important;
}

#a2p-chatbot .chat-body {
    flex: 1 !important;
    padding: 15px !important;
    overflow-y: auto !important;
    background: #f9f9f9 !important;
    display: flex !important;
    flex-direction: column !important;
}

#a2p-chatbot .bot-msg {
    background: #fff !important;
    padding: 10px 15px !important;
    border-radius: 12px 12px 12px 2px !important;
    margin-bottom: 15px !important;
    font-size: 13px !important;
    line-height: 1.5 !important;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05) !important;
    color: #333 !important;
    width: fit-content !important;
    max-width: 90% !important;
}

#a2p-chatbot .user-msg {
    background: #000 !important;
    color: #fff !important;
    padding: 10px 15px !important;
    border-radius: 12px 12px 2px 12px !important;
    margin-bottom: 15px !important;
    font-size: 13px !important;
    align-self: flex-end !important;
    text-align: right !important;
    margin-left: 20% !important;
    width: fit-content !important;
    max-width: 80% !important;
}

#a2p-chatbot .user-msg p, 
#a2p-chatbot .bot-msg p {
    color: inherit !important;
    margin: 0 !important;
    padding: 0 !important;
}

#a2p-chatbot .bot-options {
    display: flex !important;
    flex-direction: column !important;
    gap: 8px !important;
    margin-bottom: 15px !important;
}

#a2p-chatbot .bot-options button {
    background: #fff !important;
    border: 1px solid #c00415 !important;
    color: #c00415 !important;
    padding: 8px 15px !important;
    border-radius: 20px !important;
    font-size: 13px !important;
    text-align: left !important;
    cursor: pointer !important;
    transition: all 0.2s !important;
    width: 100% !important;
    margin: 0 !important;
    font-family: inherit !important;
    text-transform: none !important;
    font-weight: normal !important;
    height: auto !important;
    min-height: 0 !important;
    line-height: 1.4 !important;
}

#a2p-chatbot .bot-options button:hover {
    background: #c00415 !important;
    color: #fff !important;
}

#a2p-chatbot .permission-btn {
    background: #c00415 !important;
    color: #fff !important;
    border: none !important;
    padding: 10px 20px !important;
    border-radius: 25px !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    width: 100% !important;
    margin-top: 5px !important;
    font-size: 14px !important;
    height: auto !important;
}

#a2p-chatbot .permission-btn:hover {
    background: #000 !important;
    transform: translateY(-2px) !important;
}

#a2p-chatbot .chat-footer {
    padding: 10px !important;
    border-top: 1px solid #eee !important;
    display: flex !important;
    gap: 5px !important;
    background: #fff !important;
    align-items: center !important;
}

#a2p-chatbot .chat-footer input {
    flex: 1 !important;
    border: none !important;
    padding: 8px 12px !important;
    font-size: 13px !important;
    outline: none !important;
    background: #fff !important;
    margin: 0 !important;
    width: auto !important;
    height: auto !important;
    color: #333 !important;
    line-height: normal !important;
    box-shadow: none !important;
}

#a2p-chatbot .chat-footer button {
    background: #c00415 !important;
    color: #fff !important;
    border: none !important;
    width: 35px !important;
    height: 35px !important;
    border-radius: 50% !important;
    cursor: pointer !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 0 !important;
    margin: 0 !important;
}

#a2p-chatbot .chat-footer button i {
    font-size: 14px !important;
}

/* Adjust WhatsApp Button Position */
.float-whatsapp {
    bottom: 150px !important;
}

@media (max-width: 768px) {
    #a2p-chatbot #chat-window {
        width: 280px !important;
        height: 400px !important;
        right: 0 !important;
    }
    #a2p-chatbot {
        bottom: 110px !important;
        right: 15px !important;
    }
}
</style>

<script>
function toggleChat() {
    const window = document.getElementById('chat-window');
    const badge = document.querySelector('.chat-badge');
    if (window.style.display === 'flex') {
        window.style.display = 'none';
    } else {
        window.style.display = 'flex';
        badge.style.display = 'none';
    }
}

let chatbotState = {
    step: 0,
    needDetails: false,
    data: { name: '', email: 'N/A', phone: '', interest: 'General Setup', budget: 'Not Specified', message: 'Chatbot User Inquiry', city: 'Not Shared', lat_long: '' }
};

async function getCityName(forceDetect = false) {
    return new Promise((resolve) => {
        const modal = document.getElementById('location-modal');
        const btn = document.getElementById('give-loc-permission');
        
        const detectLocation = async () => {
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(async (position) => {
                    if(modal) modal.style.display = 'none';
                    try {
                        const lat = position.coords.latitude;
                        const lon = position.coords.longitude;
                        const coordsText = `${lat}, ${lon}`;
                        const resp = await fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lon}&localityLanguage=en`);
                        const data = await resp.json();
                        resolve({ 
                            city: data.city || data.locality || "Unknown",
                            lat_long: coordsText
                        });
                    } catch (e) {
                        resolve({ city: "Unknown", lat_long: "" });
                    }
                }, async (error) => {
                    if(modal) modal.style.display = 'none';
                    resolve({ city: "Denied/Error", lat_long: "" });
                }, { timeout: 10000 });
            } else {
                if(modal) modal.style.display = 'none';
                resolve({ city: "Not Supported", lat_long: "" });
            }
        };

        if(forceDetect) {
            detectLocation();
        } else {
            // Always show our custom modal first to force user interaction
            if(modal) {
                modal.style.display = 'flex';
                if(btn) {
                    btn.innerHTML = 'Allow Permission & Continue';
                    btn.onclick = () => {
                        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Initializing...';
                        detectLocation();
                    };
                }
            } else {
                detectLocation();
            }
        }
    });
}

function handleBotOption(option) {
    let msg = "";
    
    if(option === 'buy') {
        msg = "I'm looking to buy a property.";
        chatbotState.data.interest = "Residential/Commercial";
    } else if(option === 'offers') {
        msg = "Show me the latest offers.";
        chatbotState.data.interest = "Latest Offers";
    } else if(option === 'location') {
        msg = "I want to search by location.";
        chatbotState.data.interest = "Search Location";
    } else if(option === 'contact') {
        msg = "I want to talk to an expert.";
        chatbotState.data.interest = "Talk to Expert";
    }

    addMessage(msg, 'user');
    
    setTimeout(() => {
        addMessage("To assist you better, could you please tell me your <strong>Full Name</strong>?", 'bot');
        chatbotState.step = 1;
        chatbotState.needDetails = true;
    }, 1000);
}

function processChatInput(text) {
    if(!chatbotState.needDetails) {
        chatbotState.data.message = text;
        chatbotState.needDetails = true;
        chatbotState.step = 1;
        setTimeout(() => {
            addMessage("Thanks! May I know your <strong>Full Name</strong> to assist you better?", 'bot');
        }, 800);
        return;
    }

    if(chatbotState.step === 1) {
        chatbotState.data.name = text;
        chatbotState.step = 2;
        setTimeout(() => {
            addMessage("Nice to meet you, " + text + "! Please provide your <strong>Phone Number</strong> so our expert can connect with you.", 'bot');
        }, 800);
    } else if(chatbotState.step === 2) {
        chatbotState.data.phone = text;
        chatbotState.step = 3;
        setTimeout(() => {
            addMessage("Got it! Lastly, what is your <strong>Email ID</strong>?", 'bot');
        }, 800);
    } else if(chatbotState.step === 3) {
        chatbotState.data.email = text;
        chatbotState.step = 4;
        
        // Send OTP
        sendChatbotOtp(chatbotState.data.email, chatbotState.data.name);

        setTimeout(() => {
            addMessage("To verify your email, I've sent a <strong>6-digit OTP</strong> to " + text + ". Please enter the code below to continue.", 'bot');
        }, 800);
    } else if(chatbotState.step === 4) {
        // Verify OTP
        verifyChatbotOtp(text);
    }
}

async function sendChatbotOtp(email, name) {
    const formData = new FormData();
    formData.append('email', email);
    formData.append('name', name);
    try {
        await fetch('<?= SITE_URL; ?>function/send_otp.php', { method: 'POST', body: formData });
    } catch (e) { console.error("OTP Send Error:", e); }
}

async function verifyChatbotOtp(otp) {
    const formData = new FormData();
    formData.append('otp', otp);
    formData.append('email', chatbotState.data.email);
    
    try {
        const resp = await fetch('<?= SITE_URL; ?>function/verify_otp.php', { method: 'POST', body: formData });
        const data = await resp.json();
        
        if(data.status === 'success') {
            addMessage('<i class="fas fa-check-circle"></i> OTP Verified Successfully!', 'bot');
            chatbotState.step = 5;
            setTimeout(() => {
                addMessage("Perfect! One last thing! Please <strong>Allow Location Permission</strong> to help us tailor the best properties for your area.", 'bot');
                
                // Show custom permission button in chatbot ui
                const chatBody = document.getElementById('chat-messages');
                const div = document.createElement('div');
                div.className = 'bot-options';
                div.style.marginTop = '10px';
                div.id = 'location-permission-btn';
                div.innerHTML = `<button onclick="handleLocationPermission()" class="permission-btn"><i class="fas fa-map-marker-alt"></i> Share My City & Finish</button>`;
                chatBody.appendChild(div);
                chatBody.scrollTop = chatBody.scrollHeight;
            }, 800);
        } else {
            addMessage('<i class="fas fa-exclamation-triangle"></i> Invalid OTP. Please enter the correct 6-digit code sent to your email.', 'bot');
        }
    } catch (e) { 
        console.error("OTP Verify Error:", e);
        addMessage("Error verifying OTP. Please try again.", 'bot');
    }
}

async function handleLocationPermission() {
    const btn = document.querySelector('.permission-btn');
    if(btn) btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Fetching City...';
    
    const locData = await getCityName(true); // Force detect immediately
    chatbotState.data.city = locData.city;
    chatbotState.data.lat_long = locData.lat_long;

    
    // Resume flow
    submitChatbotLead();
    
    setTimeout(() => {
        addMessage(`Thank you! Detected City: <strong>${locData.city}</strong>. Your details have been securely saved.`, 'bot');
        chatbotState.step = 6;
        document.querySelector('.chat-footer').style.display = 'none';
        const btnDiv = document.getElementById('location-permission-btn');
        if(btnDiv) btnDiv.remove();
    }, 500);
}

function submitChatbotLead() {
    let formData = new FormData();
    formData.append('name', chatbotState.data.name);
    formData.append('email', chatbotState.data.email);
    formData.append('phone', chatbotState.data.phone);
    formData.append('interest', chatbotState.data.interest);
    formData.append('budget', chatbotState.data.budget);
    formData.append('message', chatbotState.data.message);
    formData.append('city', chatbotState.data.city);
    formData.append('lat_long', chatbotState.data.lat_long);
    formData.append('source', 'Chatbot Widget');

    fetch('<?= SITE_URL; ?>chatbot-submit.php', {
        method: 'POST',
        body: formData
    })
    .catch(err => console.error(err));
}

function addMessage(text, type) {
    const chatBody = document.getElementById('chat-messages');
    const div = document.createElement('div');
    div.className = type === 'user' ? 'user-msg' : 'bot-msg';
    div.innerHTML = `<p>${text}</p>`;
    chatBody.appendChild(div);
    chatBody.scrollTop = chatBody.scrollHeight;
}

function sendMessage() {
    const input = document.getElementById('user-input');
    const text = input.value.trim();
    if (text) {
        addMessage(text, 'user');
        input.value = '';
        processChatInput(text);
    }
}

function handleKeyPress(e) {
    if (e.key === 'Enter') sendMessage();
}

</script>


<a href="#" data-target="html" class="scroll-to-target scroll-to-top"><i class="fa fa-angle-up"></i></a>




<script src="<?= SITE_URL; ?>assets/vendors/jquery/jquery-3.6.0.min.js"></script>
<script src="<?= SITE_URL; ?>assets/vendors/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= SITE_URL; ?>assets/vendors/jarallax/jarallax.min.js"></script>
<script src="<?= SITE_URL; ?>assets/vendors/jquery-ajaxchimp/jquery.ajaxchimp.min.js"></script>
<script src="<?= SITE_URL; ?>assets/vendors/jquery-appear/jquery.appear.min.js"></script>
<script src="<?= SITE_URL; ?>assets/vendors/jquery-circle-progress/jquery.circle-progress.min.js"></script>
<script src="<?= SITE_URL; ?>assets/vendors/jquery-magnific-popup/jquery.magnific-popup.min.js"></script>
<script src="<?= SITE_URL; ?>assets/vendors/jquery-validate/jquery.validate.min.js"></script>
<script src="<?= SITE_URL; ?>assets/vendors/nouislider/nouislider.min.js"></script>
<script src="<?= SITE_URL; ?>assets/vendors/odometer/odometer.min.js"></script>
<script src="<?= SITE_URL; ?>assets/vendors/swiper/swiper.min.js"></script>
<script src="<?= SITE_URL; ?>assets/vendors/tiny-slider/tiny-slider.min.js"></script>
<script src="<?= SITE_URL; ?>assets/vendors/wnumb/wNumb.min.js"></script>
<script src="<?= SITE_URL; ?>assets/vendors/wow/wow.js"></script>
<script src="<?= SITE_URL; ?>assets/vendors/isotope/isotope.js"></script>
<script src="<?= SITE_URL; ?>assets/vendors/countdown/countdown.min.js"></script>
<script src="<?= SITE_URL; ?>assets/vendors/owl-carousel/owl.carousel.min.js"></script>
<script src="<?= SITE_URL; ?>assets/vendors/bxslider/jquery.bxslider.min.js"></script>
<script src="<?= SITE_URL; ?>assets/vendors/bootstrap-select/js/bootstrap-select.min.js"></script>
<script src="<?= SITE_URL; ?>assets/vendors/vegas/vegas.min.js"></script>
<script src="<?= SITE_URL; ?>assets/vendors/jquery-ui/jquery-ui.js"></script>
<script src="<?= SITE_URL; ?>assets/vendors/timepicker/timePicker.js"></script>
<script src="<?= SITE_URL; ?>assets/vendors/circleType/jquery.circleType.js"></script>
<script src="<?= SITE_URL; ?>assets/vendors/circleType/jquery.lettering.min.js"></script>


<script src="<?= SITE_URL; ?>assets/js/ambed.js"></script>
<script src="<?= SITE_URL; ?>assets/js/analytics-tracker.js"></script>

<script src="<?= SITE_URL; ?>assets/vendors/toolbar/js/js.cookie.min.js"></script>
<script src="<?= SITE_URL; ?>assets/vendors/toolbar/js/jQuery.style.switcher.min.js"></script>
<script src="<?= SITE_URL; ?>assets/vendors/toolbar/js/toolbar.js"></script>

    <!-- ===== STYLISH NOTIFICATION PERMISSION MODAL (iOS Style Center) ===== -->
    <div id="notif-permission-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 9999999; align-items: center; justify-content: center; backdrop-filter: blur(10px); font-family: 'Poppins', sans-serif;">
        <div class="loc-modal-card">
            <div class="loc-icon-pulse">
                <i class="fa fa-bell"></i>
            </div>
            <h3 id="notif-banner-title">Stay Updated!</h3>
            <p id="notif-banner-text">Get instant alerts on new properties, exclusive offers & real estate news directly on your device.</p>
            
            <div style="display: flex; flex-direction: column; gap: 12px; margin-top: 10px;">
                <button class="loc-btn-primary" id="notif-banner-btn-main" onclick="acceptNotifBanner()">
                    <i class="fa fa-bell"></i> Allow Notifications
                </button>
                <button onclick="dismissNotifBanner()" style="background: none; border: none; color: #aaa; font-size: 14px; font-weight: 600; cursor: pointer; transition: color 0.3s;" onmouseover="this.style.color='#666'" onmouseout="this.style.color='#aaa'">
                    Maybe Later
                </button>
            </div>
            <button onclick="dismissNotifBanner()" style="position: absolute; top: 15px; right: 20px; background: none; border: none; font-size: 24px; color: #ccc; cursor: pointer;">&times;</button>
        </div>
    </div>

    <!-- Email Subscription Modal (Specifically for iOS) -->
    <div id="email-sub-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.85); z-index:99999999; align-items:center; justify-content:center; backdrop-filter:blur(10px); font-family: 'Poppins', sans-serif;">
        <div class="loc-modal-card">
            <button onclick="closeEmailSubModal()" style="position:absolute; top:15px; right:20px; background:none; border:none; font-size:24px; color:#ccc; cursor:pointer;">&times;</button>
            <div class="loc-icon-pulse">
                <i class="fa fa-envelope-open-text"></i>
            </div>
            <h3 style="font-size:24px; font-weight:800; color:#1a1a1a; margin-bottom:8px;">Super Excited! 🚀</h3>
            <p style="font-size:14px; color:#666; margin-bottom:25px; line-height:1.6;">Subscribe to our notification for updates and offers.</p>
            
            <div id="email-error-msg" style="display:none; color:#c00415; font-size:12px; margin-bottom:10px; font-weight:600; text-align: center;">Please enter a valid email.</div>
            
            <form onsubmit="handleEmailSub(event)">
                <input type="email" id="sub-email-input" placeholder="Your Email Address" required style="width:100%; padding:15px 20px; border-radius:15px; border:2px solid #eee; margin-bottom:15px; font-size:15px; outline:none; transition:border-color 0.3s;" onfocus="this.style.borderColor='#c00415'">
                <button type="submit" id="email-sub-submit-btn" class="loc-btn-primary">SUBSCRIBE NOW</button>
            </form>
            <div id="email-success-msg" style="display:none; margin-top:20px;">
                <div style="color:#34c759; font-weight:700; font-size:15px; text-align: center;">Awesome! You're subscribed. 🚀</div>
                <p style="font-size:13px; color:#888; margin-top:5px; text-align: center;">We'll keep you updated with the latest offers.</p>
            </div>
        </div>
    </div>

    <script>
    // ===== iOS NOTIFICATION FALLBACK LOGIC =====

    window.showNotifPermissionModal = function() {
        const modal = document.getElementById('notif-permission-modal');
        if (modal && !localStorage.getItem('a2p_notif_dismissed')) {
            setTimeout(() => {
                modal.style.display = 'flex';
            }, 3000); // Show after 3 seconds
        }
    }

    window.dismissNotifBanner = function() {
        console.log("Dismissing Notif Banner");
        const modal = document.getElementById('notif-permission-modal');
        if (modal) modal.style.display = 'none';
        localStorage.setItem('a2p_notif_dismissed', 'true');
    }

    window.acceptNotifBanner = function() {
        console.log("Accepting Notif Banner");
        const modal = document.getElementById('notif-permission-modal');
        if (modal) modal.style.display = 'none';

        // Check for iOS (iPhone/iPad)
        const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        
        if (isIOS) {
            // Show email fallback for iOS
            setTimeout(() => {
                const subModal = document.getElementById('email-sub-modal');
                if (subModal) subModal.style.display = 'flex';
            }, 500);
        } else {
            // Native push logic for Chrome/Android
            if ('Notification' in window) {
                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        localStorage.setItem('a2p_notif_granted', 'true');
                        // Call native Firebase logic defined in header
                        if (typeof window.triggerFCMRequest === 'function') {
                            window.triggerFCMRequest();
                        }
                        alert('Thank you! You will now receive notifications.');
                    }
                });
            } else {
                // Fallback for non-supported browsers
                const subModal = document.getElementById('email-sub-modal');
                if (subModal) subModal.style.display = 'flex';
            }
        }
        localStorage.setItem('a2p_notif_dismissed', 'true');
    }

    window.closeEmailSubModal = function() {
        console.log("Closing Email Modal");
        const modal = document.getElementById('email-sub-modal');
        if (modal) modal.style.display = 'none';
    }

    window.handleEmailSub = async function(event) {
        event.preventDefault();
        const email = document.getElementById('sub-email-input').value;
        const submitBtn = document.getElementById('email-sub-submit-btn');
        const successMsg = document.getElementById('email-success-msg');
        const errorMsg = document.getElementById('email-error-msg');

        if (!email || !email.includes('@')) {
            if(errorMsg) errorMsg.style.display = 'block';
            return;
        }
        if(errorMsg) errorMsg.style.display = 'none';

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Subscribing...';

        const formData = new FormData();
        formData.append('email', email);
        formData.append('source', 'iOS Notification Fallback');

        try {
            await fetch('<?= SITE_URL; ?>chatbot-submit.php', {
                method: 'POST',
                body: formData
            });

            submitBtn.style.display = 'none';
            document.getElementById('sub-email-input').style.display = 'none';
            successMsg.style.display = 'block';
            
            // Mark as finished so it doesn't pop up again
            localStorage.setItem('a2p_notif_dismissed', 'true');
            
            setTimeout(() => {
                closeEmailSubModal();
            }, 3000);
        } catch (err) {
            console.error(err);
            alert('Something went wrong. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'SUBSCRIBE NOW';
        }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        showNotifPermissionModal();
    });
    </script>

    <!-- ===== END NOTIFICATION BANNER ===== -->
    <script src="<?= SITE_URL; ?>assets/js/protection.js"></script>
</body>


</html>