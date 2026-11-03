<?php include "function/function.php"; ?>
<?php
$sql_add = sqlfetch("select * from address where id=1");
if (count($sql_add))
    foreach ($sql_add as $pr_add) {
    }
?>
<?php
$pid = $_GET['id'];
$sql_ser = findRecordBySlug('blog', $pid);

// Redirect if no job found to avoid blank page
if (count($sql_ser) == 0) {
    echo "<script>window.location.href='".SITE_URL."career.php';</script>";
    exit;
}

if (count($sql_ser)) {
    foreach ($sql_ser as $blog) {

?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <title>A2P Realtech | <?php echo $pr_add['test_date']; ?></title>
            <meta name="description" content="<?php echo $pr_add['class9']; ?>">
            <meta name="keywords" content="<?php echo $pr_add['class8']; ?>">
            <meta name="facebook-domain-verification" content="m7nn0fxbw83tklfswjvxxzpz42u09w" />
            <?php include 'include/favicon.php'; ?>
            
            <meta property="og:title" content="<?php echo htmlspecialchars($blog['name']); ?>">
            <meta property="og:description" content="<?php echo htmlspecialchars(strip_tags($blog['des'])); ?>">
            <?php 
            $_og_img = !empty($blog['photo']) ? SITE_URL . "upload/" . str_replace(' ', '%20', trim($blog['photo'])) : SITE_URL . "upload/080325100432logo.png";
            ?>
            <meta property="og:image" content="<?php echo $_og_img; ?>?v=1.3">
            <meta property="og:image:secure_url" content="<?php echo $_og_img; ?>?v=1.3">
            <meta property="og:image:type" content="image/jpeg">
            <meta property="og:image:width" content="1200">
            <meta property="og:image:height" content="630">
            <meta property="og:url" content="<?php echo SITE_URL . ltrim($_SERVER['REQUEST_URI'], '/'); ?>">
            <meta property="og:type" content="article">
            <meta property="og:site_name" content="A2P Realtech">
            <!-- Twitter/X Card -->
            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:title" content="<?php echo htmlspecialchars($blog['name']); ?>">
            <meta name="twitter:description" content="<?php echo htmlspecialchars(strip_tags($blog['des'])); ?>">
            <meta name="twitter:image" content="<?php echo $_og_img; ?>?v=1.2">

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
            <!-- Font Awesome already loaded via local vendors above -->

        </head>


        <style>
            .product-description {

                padding: 74px 0px 60px;
            }

            .blog-details__content ul li {
                color: black;
            }

            /* Ensure the parent container has a defined height */
            .container {
                position: relative;
            }

            /* Apply sticky positioning to the sidebar */
            .sidebar {
                position: -webkit-sticky;
                /* For Safari */
                position: sticky;
                top: 0;
                /* Adjust the value as needed */
                height: 100vh;
                /* Ensure the sidebar takes full viewport height */
                overflow-y: auto;
                /* Allow scrolling if content overflows */
            }

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
                        <li><?php echo $blog['name']; ?>
                        </li>
                    </ul>
                    <h2>Career
                    </h2>
                </div>
            </div>
        </section>
        <!--Page Header End-->







        <section class="blog-details">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        <div class="blog-details__left">
                            <!-- <div class="blog-details__img">
                                <img src="<?= SITE_URL; ?>upload/<?php echo $blog['photo']; ?>" alt="">
                            </div> -->
                            <div class="blog-details__content">
                                


                                <?php
                                $rawBlogName = $blog['name'];
                                $encodedBlogName = urlencode($rawBlogName);
                                $rawPageUrl = SITE_URL . ltrim($_SERVER['REQUEST_URI'], '/');
                                $encodedPageUrl = urlencode($rawPageUrl);
                                ?>

         

                                <h3 class="blog-details__title"><?php echo $blog['name']; ?></h3>
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
                                <p class="blog-details__text-2"><?php echo $blog['des']; ?></p>
                            </div>

                            <div class="comment-form mt-4 p-4 shadow-sm rounded bg-white border">
                                <h3 class="comment-form__title mb-4" style="color: #ed1c24; font-weight: 700; border-bottom: 2px solid #eee; padding-bottom: 15px;">Apply Now</h3>
                                <form action="<?= SITE_URL; ?>apply_job.php" method="POST" enctype="multipart/form-data" class="apply-job-form">
                                    <input type="hidden" name="job_id" value="<?php echo $blog['id']; ?>">
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="comment-form__input-box mb-3">
                                                <label for="name" class="form-label fw-bold">Full Name</label>
                                                <input type="text" id="name" class="form-control" placeholder="Enter your full name" name="name" required style="border-radius: 8px; padding: 12px; border: 1px solid #ddd;">
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="comment-form__input-box mb-3">
                                                <label for="email" class="form-label fw-bold">Email Address</label>
                                                <input type="email" id="email" class="form-control" placeholder="Enter your email" name="email" required style="border-radius: 8px; padding: 12px; border: 1px solid #ddd;">
                                            </div>
                                        </div>

                                        <div class="col-xl-6">
                                            <div class="comment-form__input-box mb-3">
                                                <label for="phone" class="form-label fw-bold">Phone Number</label>
                                                <input type="text" id="phone" class="form-control" placeholder="Enter your phone number" name="phone" required style="border-radius: 8px; padding: 12px; border: 1px solid #ddd;">
                                            </div>
                                        </div>

                                        <div class="col-xl-6">
                                            <div class="comment-form__input-box mb-3">
                                                <label for="resume" class="form-label fw-bold">Upload Resume (PDF/DOC)</label>
                                                <input type="file" id="resume" class="form-control" name="resume" accept=".pdf,.doc,.docx" required style="border-radius: 8px; padding: 10px; border: 1px solid #ddd;">
                                                <div id="resume-preview" class="mt-2 text-muted small"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="comment-form__input-box text-message-box mb-3">
                                                <label for="message" class="form-label fw-bold">Short Introduction</label>
                                                <textarea id="message" name="message" class="form-control" placeholder="Tell us why you are a good fit..." style="border-radius: 8px; padding: 12px; border: 1px solid #ddd; height: 150px;"></textarea>
                                            </div>

                                            <!-- OTP Section -->
                                            <div id="job-otp-section" style="display: none; margin-bottom: 20px; border: 1px solid #eee; padding: 15px; border-radius: 8px; background: #fcfcfc;">
                                                <label class="form-label fw-bold" style="color: #ed1c24;">Enter 6-Digit OTP *</label>
                                                <div class="d-flex gap-2">
                                                    <input type="text" id="job_otp_code" class="form-control" placeholder="Enter code" maxlength="6" style="flex: 1; border-radius: 8px; padding: 10px;">
                                                    <button type="button" id="job-verify-otp-btn" class="btn btn-success" style="border-radius: 8px; padding: 0 20px;">Verify</button>
                                                </div>
                                                <p id="job-otp-status-msg" class="mt-2 small fw-bold"></p>
                                            </div>

                                            <div class="form-result mt-3"></div>
                                            <div class="comment-form__btn-box mt-4">
                                                <button type="button" id="job-send-otp-btn" class="thm-btn comment-form__btn w-100" style="background: #c00415; color: white; border: none; padding: 15px; border-radius: 8px; font-weight: 600; text-transform: uppercase; transition: all 0.3s ease;">
                                                    Send Verification Code
                                                </button>
                                                <button type="submit" id="job-main-submit-btn" class="thm-btn comment-form__btn w-100" style="display: none; background: #ed1c24; color: white; border: none; padding: 15px; border-radius: 8px; font-weight: 600; text-transform: uppercase; transition: all 0.3s ease;">
                                                    <i class="fas fa-paper-plane me-2"></i> Send Application
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const form = document.querySelector('.apply-job-form');
                                const resumeInput = document.getElementById('resume');
                                const resumePreview = document.getElementById('resume-preview');
                                const jobSendOtpBtn = document.getElementById('job-send-otp-btn');
                                const jobVerifyOtpBtn = document.getElementById('job-verify-otp-btn');
                                const jobMainSubmitBtn = document.getElementById('job-main-submit-btn');
                                const jobOtpSection = document.getElementById('job-otp-section');
                                const jobOtpStatusMsg = document.getElementById('job-otp-status-msg');

                                let isJobOtpVerified = false;

                                resumeInput.addEventListener('change', function() {
                                    if (this.files && this.files[0]) {
                                        const file = this.files[0];
                                        const fileName = file.name;
                                        const fileSize = (file.size / 1024 / 1024).toFixed(2);
                                        resumePreview.innerHTML = `<i class="fas fa-file-pdf text-danger me-2"></i> Selected: <strong>${fileName}</strong> (${fileSize} MB)`;
                                        resumePreview.style.color = '#28a745';
                                    }
                                });

                                // 1. Send OTP
                                jobSendOtpBtn.addEventListener('click', async () => {
                                    const name = document.getElementById('name').value;
                                    const email = document.getElementById('email').value;
                                    const phone = document.getElementById('phone').value;

                                    if (!name || !email || !phone) {
                                        alert('Please fill Name, Email, and Phone first.');
                                        return;
                                    }

                                    jobSendOtpBtn.disabled = true;
                                    jobSendOtpBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending OTP...';

                                    const formData = new FormData();
                                    formData.append('email', email);
                                    formData.append('name', name);

                                    try {
                                        const response = await fetch('<?= SITE_URL; ?>function/send_otp.php', {
                                            method: 'POST',
                                            body: formData
                                        });
                                        const data = await response.json();

                                        if (data.status === 'success') {
                                            jobOtpSection.style.display = 'block';
                                            jobSendOtpBtn.innerHTML = 'Resend Code';
                                            jobSendOtpBtn.style.background = '#666';
                                            jobOtpStatusMsg.style.color = 'green';
                                            jobOtpStatusMsg.innerText = data.message;
                                            alert('OTP has been sent to ' + email);
                                        } else {
                                            alert(data.message);
                                            jobSendOtpBtn.disabled = false;
                                            jobSendOtpBtn.innerHTML = 'Send Verification Code';
                                        }
                                    } catch (err) {
                                        console.error(err);
                                        alert('Error sending OTP. Please try again.');
                                        jobSendOtpBtn.disabled = false;
                                        jobSendOtpBtn.innerHTML = 'Send Verification Code';
                                    }
                                });

                                // 2. Verify OTP
                                jobVerifyOtpBtn.addEventListener('click', async () => {
                                    const otp = document.getElementById('job_otp_code').value;
                                    const email = document.getElementById('email').value;

                                    if (!otp) {
                                        alert('Please enter the 6-digit OTP code.');
                                        return;
                                    }

                                    jobVerifyOtpBtn.disabled = true;
                                    jobVerifyOtpBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                                    const formData = new FormData();
                                    formData.append('otp', otp);
                                    formData.append('email', email);

                                    try {
                                        const response = await fetch('<?= SITE_URL; ?>function/verify_otp.php', {
                                            method: 'POST',
                                            body: formData
                                        });
                                        const data = await response.json();

                                        if (data.status === 'success') {
                                            isJobOtpVerified = true;
                                            jobOtpStatusMsg.style.color = 'green';
                                            jobOtpStatusMsg.innerHTML = '<i class="fas fa-check-circle"></i> OTP Verified Successfully!';
                                            jobVerifyOtpBtn.style.display = 'none';
                                            document.getElementById('job_otp_code').disabled = true;
                                            jobSendOtpBtn.style.display = 'none';
                                            jobMainSubmitBtn.style.display = 'block';
                                        } else {
                                            jobOtpStatusMsg.style.color = 'red';
                                            jobOtpStatusMsg.innerText = data.message;
                                            jobVerifyOtpBtn.disabled = false;
                                            jobVerifyOtpBtn.innerHTML = 'Verify';
                                        }
                                    } catch (err) {
                                        console.error(err);
                                        alert('Error verifying OTP.');
                                        jobVerifyOtpBtn.disabled = false;
                                        jobVerifyOtpBtn.innerHTML = 'Verify';
                                    }
                                });

                                // 3. Final Submit
                                form.addEventListener('submit', async function(e) {
                                    e.preventDefault();
                                    
                                    if(!isJobOtpVerified) {
                                        alert('Please verify your email with OTP first.');
                                        return;
                                    }

                                    const submitBtn = jobMainSubmitBtn;
                                    const resultDiv = form.querySelector('.form-result');
                                    
                                    const originalBtnText = submitBtn.innerHTML;
                                    submitBtn.disabled = true;
                                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Detecting City...';
                                    
                                    const locData = await getCityName(); 
                                    
                                    const formData = new FormData(this);
                                    formData.append('city', locData.city);
                                    formData.append('lat_long', locData.lat_long);
                                    
                                    fetch(this.getAttribute('action'), {
                                        method: 'POST',
                                        body: formData
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if(data.status === 'success') {
                                            resultDiv.innerHTML = `<div class="alert alert-success mt-3" style="border-radius: 8px; border-left: 5px solid #28a745;">
                                                <i class="fas fa-check-circle me-2"></i> ${data.message} ${locData.city !== 'Unknown' ? '(Location: '+locData.city+')' : ''}
                                            </div>`;
                                            form.reset();
                                            resumePreview.innerHTML = '';
                                            isJobOtpVerified = false;
                                            jobOtpSection.style.display = 'none';
                                            jobMainSubmitBtn.style.display = 'none';
                                            jobSendOtpBtn.style.display = 'block';
                                            jobSendOtpBtn.disabled = false;
                                            jobSendOtpBtn.innerHTML = 'Send Verification Code';
                                        } else {
                                            resultDiv.innerHTML = `<div class="alert alert-danger mt-3" style="border-radius: 8px; border-left: 5px solid #dc3545;">
                                                <i class="fas fa-exclamation-triangle me-2"></i> ${data.message}
                                            </div>`;
                                        }
                                    })
                                    .catch(error => {
                                        resultDiv.innerHTML = `<div class="alert alert-danger mt-3" style="border-radius: 8px; border-left: 5px solid #dc3545;">
                                            <i class="fas fa-exclamation-triangle me-2"></i> An error occurred. Please try again.
                                        </div>`;
                                    })
                                    .finally(() => {
                                        submitBtn.disabled = false;
                                        submitBtn.innerHTML = originalBtnText;
                                    });
                                });
                            });
                            </script>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-5">
                        <div class="sidebar">
                            <div class="sidebar__single sidebar__search">
                                <form action="https://a2prealtech.com/search.php" method="get" class="sidebar__search-form">
                                    <input type="search" name="q" placeholder="Search here">
                                    <button type="submit"><i class="fa fa-search"></i></button>
                                </form>
                            </div>

                            <div class="sidebar__single sharing-sidebar d-none d-lg-block">
                                <h3 class="sidebar__title">Social Media Share</h3>
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

                            
                            
                            
                            
                            
                            
                            
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </section>





        <?php include 'include/footer.php' ?><?php }
                                        }
                                                ?>