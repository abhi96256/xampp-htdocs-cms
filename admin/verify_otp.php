<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('db.php'); // Include your database connection file

// Initialize variables
$message = "";
$redirect = false;

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate the OTP input
    $otp = filter_var($_POST['otp'], FILTER_SANITIZE_NUMBER_INT);

    if ($otp && is_numeric($otp)) {
        try {
            // Prepare the SQL statement to search for the user with the given OTP
            $stmt = $pdo->prepare("SELECT * FROM subadmin WHERE otp = :otp");
            $stmt->execute(['otp' => $otp]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Calculate time difference to check OTP expiration (5 minutes = 300 seconds)
                $otp_time = strtotime($user['otp_created_at']);
                $current_time = time();

                if (($current_time - $otp_time) > 300) {
                    $message = "OTP has expired. Please request a new one.";
                } else {
                    // OTP is valid and not expired; set session variables
                    $_SESSION['admin_id'] = $user['id']; // Store user ID for session
                    $_SESSION['user_email'] = $user['username'];
                    $_SESSION['logged_in'] = true;

                    // Redirect with a success message
                    echo "<script>
                        alert('OTP verified successfully!');
                        window.location.href = 'index.php';
                    </script>";
                    exit;
                }
            } else {
                // Invalid OTP message
                $message = "The OTP you entered is invalid. Please try again.";
            }
        } catch (PDOException $e) {
            // Handle database error
            $message = "Database error: " . htmlspecialchars($e->getMessage());
        } catch (Exception $e) {
            // Handle general errors
            $message = "An error occurred: " . htmlspecialchars($e->getMessage());
        }
    } else {
        // If OTP is invalid or not provided correctly
        $message = "Please enter a valid OTP.";
    }
}

// Include necessary files and setup
include('./function/function.php');
$umessage = '<div class="alert alert-info">Please login with your Username and Password.</div>';
check_login();  // Check if already logged in
if (isset($_POST['login_me'])) {
    $umessage = login_me();
}
$no_visible_elements = true;
include('include/header.php');
?>

<!-- OTP Verification Form -->
<div class="authentication-wrapper authentication-cover">
    <a href="#" class="auth-cover-brand d-flex align-items-center gap-2">
        <span class="app-brand-logo demo">
            <span style="color:var(--bs-primary);">
                <!-- SVG Logo -->
                <svg width="268" height="150" viewBox="0 0 38 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M30.0944 2.22569C29.0511 0.444187 26.7508 -0.172113 24.9566 0.849138C23.1623 1.87039 22.5536 4.14247 23.5969 5.92397L30.5368 17.7743C31.5801 19.5558 33.8804 20.1721 35.6746 19.1509C37.4689 18.1296 38.0776 15.8575 37.0343 14.076L30.0944 2.22569Z" fill="currentColor" />
                </svg>
            </span>
        </span>
        <span class="app-brand-text demo text-heading fw-bold"><?php echo htmlspecialchars($siteTitle); ?></span>
    </a>

    <div class="authentication-inner row m-0">
        <div class="d-none d-lg-flex col-lg-6 col-xl-6 align-items-center p-5 pb-2">
            <img src="assets/img/illustrations/auth-login-illustration-light.png" class="auth-cover-illustration w-100" alt="auth-illustration">
        </div>

        <div class="d-flex col-12 col-lg-6 col-xl-6 align-items-center authentication-bg position-relative py-sm-5 px-4 py-4">
            <div class="w-px-400 mx-auto pt-5 pt-lg-0">
                <h4 class="mb-2">Verify Your OTP</h4>
                <p class="mb-4">Please enter the OTP sent to your email.</p>

                <?php if (!empty($message)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <form id="formAuthentication" class="mb-3" method="post" action="verify_otp.php">
                    <div class="form-floating form-floating-outline mb-3">
                        <input class="form-control" type="text" name="otp" placeholder="Enter OTP" required>
                        <label for="otp">Enter OTP</label>
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-primary d-grid w-100" name="login_me" type="submit">Verify OTP</button>
                    </div>
                </form>

                <div class="divider my-4">
                    <div class="divider-text">or</div>
                </div>

                <!-- Social Media Links (optional) -->
                <div class="d-flex justify-content-center gap-2">
                    <a href="javascript:;" class="btn btn-icon btn-lg rounded-pill btn-text-facebook">
                        <i class="tf-icons mdi mdi-24px mdi-facebook"></i>
                    </a>
                    <a href="javascript:;" class="btn btn-icon btn-lg rounded-pill btn-text-twitter">
                        <i class="tf-icons mdi mdi-24px mdi-twitter"></i>
                    </a>
                    <a href="javascript:;" class="btn btn-icon btn-lg rounded-pill btn-text-github">
                        <i class="tf-icons mdi mdi-24px mdi-github"></i>
                    </a>
                    <a href="javascript:;" class="btn btn-icon btn-lg rounded-pill btn-text-google-plus">
                        <i class="tf-icons mdi mdi-24px mdi-google"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('include/footer.php'); ?>
