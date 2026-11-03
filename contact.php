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
    
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-11490897190"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-11490897190');
</script>

<!-- Event snippet for A2P REALTECH conversion page -->
<script>
  gtag('event', 'conversion', {
      'send_to': 'AW-11490897190/O-YqCL-F0JwaEKbapOcq',
      'value': 1.0,
      'currency': 'INR'
  });
</script>


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
                <li>Contact Us</li>
            </ul>
            <h2>Contact Us</h2>
        </div>
    </div>
</section>
<!--Page Header End-->


<!--Contact Page Start-->
<section class="contact-page">
    <div class="contact-page-shape-1 float-bob-x">
        <img src="assets/images/shapes/contact-page-shape-1.png" alt="">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-7">
                <div class="contact-page__left">
                    <div class="section-title text-left">
                        
                        
                        
<style>
  * {
    box-sizing: border-box;
  }
  body {
    margin: 0;
    font-family: "Poppins", Arial, sans-serif;
    background: #f2f6fa;
  }

  .form-wrapper {
    display: flex;
    flex-wrap: wrap;
    max-width: 1000px;
    margin: 50px auto;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 25px rgba(0,0,0,0.15);
  }

  /* LEFT SIDE IMAGE */
  .form-image {
    flex: 1;
    background: url('enquirymodel.jpg') center/cover no-repeat;
    min-height: 400px;
    position: relative;
  }

  .overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 10px;
    background: rgba(0,0,0,0.4);
    color: #fff;
  }
  .overlay h3 {
    margin: 0;
    font-size: 23px;
    color: yellow;
    text-align: center;
  }
  .overlay p {
    margin-top: 4px;
    font-size: 16px;
    color: white;
    text-align: center;
  }

  /* RIGHT SIDE FORM */
  .form-content {
    flex: 1;
    padding: 20px;
  }

  h2 {
    color: #eb092f;
    text-align: center;
    margin-bottom: 8px;
  }

  p.subtitle {
    text-align: center;
    color: #555;
    margin-bottom: 25px;
  }

  label {
    display: block;
    margin-top: 10px;
    color: #333;
    font-weight: 600;
  }

  input, select, textarea {
    width: 100%;
    padding: 10px;
    margin-top: 6px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 15px;
    background-color: #fff;
  box-sizing: border-box;
  }
  input:focus, select:focus, textarea:focus {
  outline: none;
  border-color: #0078d7;
  box-shadow: 0 0 5px rgba(0,120,215,0.3);
}

  
  
  button {
    
    background: #c00415;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 6px;
    cursor: pointer;
    width: 100%;
    font-size: 22px;
    transition: background 0.3s;
  }

  button:hover {
    background: #c00415;
  }

  /* POPUP */
  .popup {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.6);
    justify-content: center;
    align-items: center;
    z-index: 999;
  }

  .popup-content {
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    text-align: center;
    width: 90%;
    max-width: 400px;
    box-shadow: 0 0 15px rgba(0,0,0,0.3);
    animation: fadeIn 0.3s ease-in-out;
  }

  .popup-content h3 {
    color: #0078d7;
    margin-bottom: 10px;
  }

  .popup-content p {
    color: #333;
  }

  .close-btn {
    margin-top: 15px;
    background: #0078d7;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
  }

  .close-btn:hover {
    background: #005fa3;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
  }

  @media(max-width: 768px) {
    .form-wrapper {
      flex-direction: column;
    }
    .form-image {
      min-height: 250px;
    }
    .overlay h3 {
      font-size: 18px;
    }
  }
</style>
</head>
<body>

<div class="form-wrapper">
  <div class="form-image">
    <!-- You can replace the image below with one that shows a girl on a call with a laptop -->
    <div class="overlay">
      <h3>Talk to Our Real Estate Expert</h3>
      <p>Explore premium properties & investment opportunities with A2P Realtech.</p>
    </div>
  </div>

  <div class="form-content">
    <strong> <h2>Contact A2P Realtech</h2></strong>
   <strong> <p class="subtitle">Get personalized assistance for your property needs.</p></strong>

   <form id="leadForm" action="lead-submit.php" method="POST">


      <label for="name">Full Name *</label>
      <input type="text" id="name" name="name" placeholder="Enter your name" required>

      <label for="email">Email *</label>
      <input type="email" id="email" name="email" placeholder="Enter your email" required>

      <label for="phone">Phone *</label>
      <input type="tel" id="phone" name="phone" placeholder="10-digit number" pattern="[0-9]{10}" required>

      <label for="interest">Interested In</label>
      <select id="interest" name="interest">
        <option value="Residential">Residential Property</option>
        <option value="Commercial">Commercial Property</option>
        <option value="Plot">Sco Plot</option>
        <option value="New">New Launch</option>
        <option value="Construction">Under Construction</option>
        <option value="Ready">Ready to Move</option>
        <option value="Investment">Investment Options</option>
        <option value="Shop">Shop</option>
        <option value="Office">Office Space</option>
      </select>
       <!-- ✅ Add Budget box here -->
  <label for="budget">Budget</label>
  <select id="budget" name="budget" required>
    <option value="">-- Select Your Budget --</option>
    <option value="2-3 CR">2–3 CR</option>
    <option value="3-4 CR">3–4 CR</option>
    <option value="4-5 CR">4–5 CR</option>
    <option value="5-6 CR">5–6 CR</option>
    <option value="More Than 6 CR">More Than 6 CR</option>
         
      </select>

      <label for="message">Message</label>
      <textarea id="message" name="message" rows="4" placeholder="Tell us about your requirements..."></textarea>

      <!-- OTP Section -->
      <div id="otp-section" style="display: none; margin-bottom: 15px; border-top: 1px solid #ddd; padding-top: 15px;">
          <label for="otp" style="color: #c00415;">Enter 6-Digit OTP *</label>
          <div style="display: flex; gap: 10px;">
              <input type="text" id="otp_code" placeholder="Enter code" maxlength="6" style="flex: 1;">
              <button type="button" id="verify-otp-btn" style="width: auto; background: #28a745; font-size: 16px; padding: 10px 20px;">Verify</button>
          </div>
          <p id="otp-status-msg" style="font-size: 14px; margin-top: 5px; font-weight: 500;"></p>
      </div>

      <button type="button" id="send-otp-btn">Send Verification Code</button>
      <button type="submit" id="main-submit-btn" style="display: none;">Submit Inquiry</button>
    </form>
    <p id="form-error" style="color: red; font-size: 14px; margin-top: 10px; display: none; text-align: center; font-weight: bold;"></p>
  </div>
</div>

<!-- Popup -->
<div class="popup" id="popup">
  <div class="popup-content">
    <h3>Thank You!</h3>
    <p>Your inquiry has been sent successfully.<br>Our team will contact you soon.</p>
    <button class="close-btn" onclick="closePopup()">Close</button>
  </div>
</div>

<script>
  const leadForm = document.getElementById('leadForm');
  const sendOtpBtn = document.getElementById('send-otp-btn');
  const verifyOtpBtn = document.getElementById('verify-otp-btn');
  const mainSubmitBtn = document.getElementById('main-submit-btn');
  const otpSection = document.getElementById('otp-section');
  const otpStatusMsg = document.getElementById('otp-status-msg');
  const formError = document.getElementById('form-error');
  const popup = document.getElementById('popup');

  let isOtpVerified = false;

  // 1. Send OTP
  sendOtpBtn.addEventListener('click', async () => {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;

    if (!name || !email || !phone) {
      alert('Please fill Name, Email, and Phone first.');
      return;
    }

    sendOtpBtn.disabled = true;
    sendOtpBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending OTP...';

    const formData = new FormData();
    formData.append('email', email);
    formData.append('name', name);

    try {
      const response = await fetch('function/send_otp.php', {
        method: 'POST',
        body: formData
      });
      const data = await response.json();

      if (data.status === 'success') {
        otpSection.style.display = 'block';
        sendOtpBtn.innerHTML = 'Resend OTP';
        sendOtpBtn.style.background = '#666'; // muted color
        otpStatusMsg.style.color = 'green';
        otpStatusMsg.innerText = data.message;
        alert('OTP has been sent to ' + email);
      } else {
        alert(data.message);
        sendOtpBtn.disabled = false;
        sendOtpBtn.innerHTML = 'Send Verification Code';
      }
    } catch (err) {
      console.error(err);
      alert('Error sending OTP. Please try again.');
      sendOtpBtn.disabled = false;
      sendOtpBtn.innerHTML = 'Send Verification Code';
    }
  });

  // 2. Verify OTP
  verifyOtpBtn.addEventListener('click', async () => {
    const otp = document.getElementById('otp_code').value;
    const email = document.getElementById('email').value;

    if (!otp) {
      alert('Please enter the 6-digit OTP code.');
      return;
    }

    verifyOtpBtn.disabled = true;
    verifyOtpBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    const formData = new FormData();
    formData.append('otp', otp);
    formData.append('email', email);

    try {
      const response = await fetch('function/verify_otp.php', {
        method: 'POST',
        body: formData
      });
      const data = await response.json();

      if (data.status === 'success') {
        isOtpVerified = true;
        otpStatusMsg.style.color = 'green';
        otpStatusMsg.innerHTML = '<i class="fas fa-check-circle"></i> OTP Verified Successfully!';
        verifyOtpBtn.style.display = 'none';
        document.getElementById('otp_code').disabled = true;
        sendOtpBtn.style.display = 'none';
        mainSubmitBtn.style.display = 'block';
      } else {
        otpStatusMsg.style.color = 'red';
        otpStatusMsg.innerText = data.message;
        verifyOtpBtn.disabled = false;
        verifyOtpBtn.innerHTML = 'Verify';
      }
    } catch (err) {
      console.error(err);
      alert('Error verifying OTP.');
      verifyOtpBtn.disabled = false;
      verifyOtpBtn.innerHTML = 'Verify';
    }
  });

  // 3. Final Form Submission
  leadForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    if (!isOtpVerified) {
      alert('Please verify your email with the OTP first.');
      return;
    }

    mainSubmitBtn.disabled = true;
    mainSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

    const locData = await getCityName(); // defined in include/footer.php or globally
    
    const formData = new FormData(leadForm);
    formData.append('city', locData.city || 'Not Shared');
    formData.append('lat_long', locData.lat_long || '');

    try {
      // Send to local DB
      await fetch('lead-submit.php', {
        method: 'POST',
        body: formData
      });

      // Redirect to Thank You page (contact version)
      window.location.href = 'thank-you.php?source=contact';
      leadForm.reset();
      isOtpVerified = false;
      otpSection.style.display = 'none';
      mainSubmitBtn.style.display = 'none';
      sendOtpBtn.style.display = 'block';
      sendOtpBtn.disabled = false;
      sendOtpBtn.innerHTML = 'Send Verification Code';
    } catch (err) {
      console.error(err);
      alert('Submission failed, but we have your contact info. Our team will contact you.');
      mainSubmitBtn.disabled = false;
      mainSubmitBtn.innerHTML = 'Submit Inquiry';
    }
  });

  function closePopup() {
    popup.style.display = 'none';
  }
</script>

</body>
</html>


  </body>
</html>


                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-5">
                <div class="contact-page__right">
                    <div class="contact-page__details">
                        <ul class="list-unstyled contact-page__details-list">
                           

  <style>
    
    /* Contact Section */
    .contact-section {
      max-width: 400px;
      margin: 50px auto;
      background-color: #fff;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      border-radius: 10px;
      overflow: hidden;
      text-align: center;
    }

    /* Image Section with Overlay */
    .contact-image {
      position: relative;
      width: 100%;
      height: 400px;
      background-image: url('https://images.unsplash.com/photo-1507089947368-19c1da9775ae?auto=format&fit=crop&w=1000&q=80');
      background-size: cover;
      background-position: center;
    }

    .contact-image::after {
      content: "Contact Us";
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 36px;
      font-weight: bold;
      color: #fff;
      text-shadow: 0 2px 6px rgba(0,0,0,0.7);
    }

    .contact-details {
      padding: 20px 10px;
    }

    .contact-details h2 {
      color: #2a2a2a;
      margin-bottom: 10px;
      font-size: 28px;
    }

    .contact-details ul {
      list-style: none;
      padding: 0;
      line-height: 1.8;
    }

    .contact-details li {
      margin-bottom: 15px;
    }

    .contact-details span {
      font-weight: bold;
      color: #1e90ff;
      display: block;
      margin-bottom: 5px;
      font-size: 15px;
    }

    .contact-details a {
      color: #333;
      text-decoration: none;
    }

    .contact-details a:hover {
      text-decoration: underline;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
      .contact-image {
        height: 250px;
      }

      .contact-image::after {
        font-size: 25px;
      }
    }

    @media (max-width: 480px) {
      .contact-image {
        height: 200px;
      }

      .contact-image::after {
        font-size: 22px;
      }

      .contact-details h2 {
        font-size: 24px;
      }

      .contact-details li {
        margin-bottom: 20px;
      }
    }
  </style>
</head>
<body>

<style>
  .git-contact-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    margin-bottom: 20px;
  }
  .git-icon-box {
    width: 46px;
    height: 46px;
    min-width: 46px;
    background: #c00415;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
    box-shadow: 0 4px 10px rgba(192,4,21,0.25);
  }
  .git-contact-item .git-text {
    display: flex;
    flex-direction: column;
    justify-content: center;
  }
  .git-contact-item .git-text span {
    font-weight: 700;
    color: #c00415;
    font-size: 14px;
    display: block;
    margin-bottom: 3px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .git-contact-item .git-text a,
  .git-contact-item .git-text p {
    color: #333;
    text-decoration: none;
    font-size: 14px;
    margin: 0;
    line-height: 1.6;
  }
  .git-contact-item .git-text a:hover {
    color: #c00415;
    text-decoration: underline;
  }
  .git-contact-section { padding: 10px 5px; }
  .git-contact-section h2 { color: #2a2a2a; margin-bottom: 20px; font-size: 24px; font-weight: 700; }

  /* Social icons - footer style */
  .contact-social-icons {
    display: flex;
    align-items: center;
    gap: 8px; /* Slightly reduced gap */
    margin-top: 20px;
    flex-wrap: nowrap; /* Force one row */
    justify-content: flex-start;
  }
  .contact-social-icons a {
    width: 32px; /* Slightly smaller to ensure fit on mobile */
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 14px; /* Slightly smaller font */
    border-radius: 50%;
    transition: all 400ms ease;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    text-decoration: none;
    flex-shrink: 0; /* Don't shrink the icons */
  }
  .contact-social-icons a:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.25);
  }
  .contact-social-icons a.facebook  { background: #1877F2; }
  .contact-social-icons a.twitter   { background: #000000; }
  .contact-social-icons a.instagram { background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%); }
  .contact-social-icons a.youtube   { background: #FF0000; }
  .contact-social-icons a.linkedin  { background: #0077B5; }
  .contact-social-icons a.whatsapp  { background: #25D366; }
</style>

<div class="git-contact-section">
  <h2>Get in Touch</h2>

  <div class="git-contact-item">
    <div class="git-icon-box"><i class="fa fa-phone-alt"></i></div>
    <div class="git-text">
      <span>Call - Team A2P</span>
      <a href="tel:+918130525001">+91-8130525001</a>
      <a href="tel:+918130510678">+91-8130510678</a>
    </div>
  </div>

  <div class="git-contact-item">
    <div class="git-icon-box"><i class="fa fa-envelope"></i></div>
    <div class="git-text">
      <span>Send Email</span>
      <a href="mailto:team@a2prealtech.com">team@a2prealtech.com</a>
    </div>
  </div>

  <div class="git-contact-item">
    <div class="git-icon-box"><i class="fa fa-building"></i></div>
    <div class="git-text">
      <span>A2P REALTECH PVT LTD</span>
      <p>S-3 2nd Floor Malik Plaza Plot No -5 Sector 4 Dwarka New Delhi 110078</p>
    </div>
  </div>

  <!-- Social Media Icons - Footer Style -->
  <div class="contact-social-icons">
    <a href="<?php echo $pr_add['facebook']; ?>" class="facebook" target="_blank"><i class="fab fa-facebook-f"></i></a>
    <a href="<?php echo $pr_add['twitter']; ?>" class="twitter" target="_blank"><i class="fab fa-x-twitter"></i></a>
    <a href="<?php echo $pr_add['youtube']; ?>" class="instagram" target="_blank"><i class="fab fa-instagram"></i></a>
    <a href="<?php echo $pr_add['linkedin']; ?>" class="youtube" target="_blank"><i class="fab fa-youtube"></i></a>
    <a href="<?php echo $pr_add['linkedin2']; ?>" class="linkedin" target="_blank"><i class="fab fa-linkedin-in"></i></a>
    <a href="https://api.whatsapp.com/send?phone=918130525001" class="whatsapp" target="_blank"><i class="fab fa-whatsapp"></i></a>
  </div>

</div>

</body>
</html>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--Contact Page End-->

<!--Google Map Start-->
<section class="google-map-two">

   <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3503.0808414848975!2d77.0498477!3d28.597351500000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390d1b8d7ee000e5%3A0x5a6f1f8f031ff9fb!2sA2P%20Realtech%20Private%20Limited!5e0!3m2!1sen!2sin!4v1745565088950!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>


</section>
<!--Google Map End-->

<script>
  // Handled above in the head/body section
</script>



<?php include 'include/footer.php' ?>