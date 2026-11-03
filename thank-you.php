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



 <style>
  

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .card {
      padding: 40px;
      border: none;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      text-align: center;
      background: #ffffff;
      animation: fadeInCard 1.5s ease-in-out;
    }

    @keyframes fadeInCard {
      0% { opacity: 0; transform: scale(0.95); }
      100% { opacity: 1; transform: scale(1); }
    }

    .checkmark {
      font-size: 60px;
      color: #4CAF50;
      margin-bottom: 20px;
    }

    h1 {
      font-weight: 700;
      color: #2e7d32;
    }

    p {
      color: #555;
    }

    .contact-info a {
      display: inline-block;
      color: #1565c0;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .contact-info a:hover {
      color: #0d47a1;
      text-decoration: underline;
      transform: scale(1.05);
    }
  </style>


  <div class="card col-md-12 col-lg-12">
    <div class="checkmark">✔️</div>
    <h1>THANK YOU!</h1>
    <p class="mt-3">Thanks for showing interest in <strong>A2P Realtech</strong>. 
      Our representative will contact you soon.</p>

    <div class="contact-info mt-4">
      <p><strong><i class="fa fa-phone-alt" style="color: #c00415;"></i> Call Anytime:</strong>
        <a href="tel:+918130525001">+91-8130525001</a>
        <a href="tel:+918130510678">+91-8130510678</a></p>

      <p><strong><i class="fa fa-envelope" style="color: #c00415;"></i> Send Email:</strong>
        <a href="mailto:team@a2prealtech.com">team@a2prealtech.com</a></p>
    </div>

    <?php if (isset($_GET['source']) && $_GET['source'] === 'contact'): ?>
        <!-- Contact Form Thank You Content -->
        <p class="mt-3" style="color:#555; font-size:1.05rem;">
            Our real estate expert will reach out to you shortly.<br>
            In the meantime, feel free to connect with us:
        </p>

        <div style="display:flex; flex-wrap:wrap; gap:16px; justify-content:center; margin-top:28px;">

            <!-- Call -->
            <a href="tel:+918130525001" style="text-decoration:none;">
                <div style="background:#fff; border:2px solid #c00415; border-radius:14px; padding:18px 24px; min-width:160px; text-align:center; transition:all 0.3s; box-shadow:0 4px 14px rgba(192,4,21,0.1);">
                    <div style="font-size:2rem; color:#c00415;">📞</div>
                    <div style="font-weight:700; color:#1a1a2e; margin-top:8px; font-size:0.9rem;">Call Us</div>
                    <div style="color:#c00415; font-size:0.85rem; margin-top:4px;">+91-8130525001</div>
                    <div style="color:#c00415; font-size:0.85rem;">+91-8130510678</div>
                </div>
            </a>

            <!-- WhatsApp -->
            <a href="https://api.whatsapp.com/send?phone=918130525001&text=Hello! I just submitted an enquiry on your website." target="_blank" style="text-decoration:none;">
                <div style="background:#fff; border:2px solid #25D366; border-radius:14px; padding:18px 24px; min-width:160px; text-align:center; box-shadow:0 4px 14px rgba(37,211,102,0.1);">
                    <div style="font-size:2rem; color:#25D366;">💬</div>
                    <div style="font-weight:700; color:#1a1a2e; margin-top:8px; font-size:0.9rem;">WhatsApp</div>
                    <div style="color:#25D366; font-size:0.85rem; margin-top:4px;">Chat Instantly</div>
                </div>
            </a>

            <!-- Email -->
            <a href="mailto:team@a2prealtech.com" style="text-decoration:none;">
                <div style="background:#fff; border:2px solid #1565c0; border-radius:14px; padding:18px 24px; min-width:160px; text-align:center; box-shadow:0 4px 14px rgba(21,101,192,0.1);">
                    <div style="font-size:2rem; color:#1565c0;">✉️</div>
                    <div style="font-weight:700; color:#1a1a2e; margin-top:8px; font-size:0.9rem;">Email Us</div>
                    <div style="color:#1565c0; font-size:0.82rem; margin-top:4px;">team@a2prealtech.com</div>
                </div>
            </a>

            <!-- Office -->
            <div style="background:#fff; border:2px solid #555; border-radius:14px; padding:18px 24px; min-width:160px; text-align:center; box-shadow:0 4px 14px rgba(0,0,0,0.07);">
                <div style="font-size:2rem;">🏢</div>
                <div style="font-weight:700; color:#1a1a2e; margin-top:8px; font-size:0.9rem;">Visit Us</div>
                <div style="color:#555; font-size:0.78rem; margin-top:4px; line-height:1.5;">S-3, 2nd Floor, Malik Plaza,<br>Sector 4, Dwarka,<br>New Delhi – 110078</div>
            </div>

        </div>

        <div style="margin-top:28px;">
            <a href="<?= SITE_URL; ?>" style="display:inline-block; background:#c00415; color:#fff; padding:12px 32px; border-radius:8px; text-decoration:none; font-weight:700; font-size:1rem; letter-spacing:0.3px;">
                🏠 Explore Properties
            </a>
        </div>

    <?php elseif (isset($_GET['brochure'])): ?>
        <div class="mt-4">
            <a href="<?= SITE_URL; ?>upload/<?php echo htmlspecialchars($_GET['brochure']); ?>" class="btn btn-danger btn-lg" style="background-color: #c00415; border: none; padding: 15px 30px; border-radius: 10px;" download>
                <i class="fas fa-file-download"></i> Download Brochure Again
            </a>
            <p class="mt-2 text-muted">Your brochure should have opened in a new tab. If not, click the button above.</p>
        </div>

        <script>
            window.addEventListener('load', function() {
                var brochureUrl = '<?= SITE_URL; ?>upload/<?php echo htmlspecialchars($_GET['brochure']); ?>';
                window.open(brochureUrl, '_blank');
            });
        </script>
    <?php else: ?>
        <div class="mt-4 alert alert-info" style="background-color: #f8f9fa; border-left: 5px solid #c00415; color: #333; padding: 20px; border-radius: 10px;">
            <p style="margin-bottom: 0; font-weight: 600;">Brochure not available right now but team will reach out to you.</p>
        </div>
    <?php endif; ?>
  </div>






<?php include 'include/footer.php' ?>