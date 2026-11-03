<?php
/**
 * Optimized Mailer Helper — A2P Realtech
 * Uses a single SMTP connection to send both Admin and User emails.
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$phpmailerBase = __DIR__ . '/../admin/vendor/phpmailer/phpmailer/src/';
require_once $phpmailerBase . 'Exception.php';
require_once $phpmailerBase . 'PHPMailer.php';
require_once $phpmailerBase . 'SMTP.php';

define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);

define('SMTP_USER', 'team@a2prealtech.com');
define('SMTP_PASS', 'yvfnyvmpxvhfjpau');
define('SMTP_FROM', 'team@a2prealtech.com');
define('SMTP_FROM_NAME', 'A2P Realtech');
define('ADMIN_EMAIL', 'team@a2prealtech.com');

function writeMailLog($msg)
{
  $logFile = __DIR__ . '/../mail_debug.log';
  $timestamp = date('Y-m-d H:i:s');
  file_put_contents($logFile, "[$timestamp] $msg\n", FILE_APPEND);
}

/**
 * Common function to send both Admin Notification and User Auto-Reply
 */
function sendAllMails($userEmail, $userName, $adminSubject, $adminBody)
{
  $mail = new PHPMailer(true);
  try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = SMTP_PORT;
    $mail->CharSet = 'UTF-8';

    // 1. Send to Admin
    $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
    $mail->addAddress(ADMIN_EMAIL, 'A2P Admin');
    $mail->isHTML(true);
    $mail->Subject = $adminSubject;
    $mail->Body = $adminBody;
    $mail->send();
    writeMailLog("Admin Notification Sent to " . ADMIN_EMAIL);

    // Clear recipients for the next mail
    $mail->clearAddresses();

    // 2. Send Auto-Reply to User (If email is valid)
    if (!empty($userEmail) && filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
      $mail->addAddress($userEmail, $userName);
      $mail->Subject = 'Thank You for Your Luxury Property Inquiry — A2P Realtech';

      // Re-using the same branded template
      $mail->Body = getAutoReplyTemplate($userName);
      $mail->send();
      writeMailLog("Auto-Reply Sent to " . $userEmail);
    }

    return true;
  }
  catch (Exception $e) {
    writeMailLog("MAIL ERROR: " . $mail->ErrorInfo);
    return false;
  }
}

function getAutoReplyTemplate($toName)
{
  return '
    <!DOCTYPE html>
    <html>
    <body style="margin:0;padding:0;background:#f4f7f6;font-family:\'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f7f6;padding:30px 0;">
      <tr>
        <td align="center">
          <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 8px 30px rgba(0,0,0,0.12); border: 1px solid #e0e0e0;">
            <!-- Header Banner -->
            <tr>
              <td style="background: #c00415; padding: 40px 30px; text-align: center; border-bottom: 6px solid #0f3460;">
                <h1 style="color:#ffffff; margin:0; font-size: 30px; letter-spacing: 2.5px; font-weight: 800; text-transform: uppercase;">A2P REALTECH</h1>
                <p style="color:#ffffff; margin:8px 0 0; font-size: 14px; letter-spacing: 4px; font-weight: 500; text-transform: uppercase;">Luxury Property Advisory</p>
              </td>
            </tr>
            
            <!-- Content Area -->
            <tr>
              <td style="padding: 40px 40px 30px;">
                <p style="font-size: 18px; color: #1a1a2e; margin: 0 0 20px; font-weight: 600;">Dear ' . htmlspecialchars($toName) . ',</p>
                <p style="font-size: 15px; color: #444; line-height: 1.8; margin: 0 0 20px;">Thank you for contacting <strong>A2P Realtech</strong> regarding premium real estate opportunities in:</p>
                
                <!-- Locations List -->
                <div style="background: #f9f9f9; border-left: 4px solid #c00415; padding: 15px 25px; margin-bottom: 25px;">
                  <ul style="color: #333; font-size: 15px; line-height: 1.8; margin: 0; padding: 0; list-style: none;">
                    <li>• Dwarka Expressway</li>
                    <li>• Gurgaon</li>
                    <li>• Faridabad</li>
                    <li>• Noida</li>
                  </ul>
                </div>

                <p style="font-size: 15px; color: #444; line-height: 1.8; margin: 0 0 15px;">We specialize in curated luxury offerings including:</p>
                
                <!-- Specialties List -->
                <table width="100%" cellpadding="0" cellspacing="0" style="background: #ffffff; border: 1px solid #0f3460; border-radius: 8px; padding: 15px; margin-bottom: 25px;">
                  <tr>
                    <td style="color: #1a1a2e; font-size: 14px; line-height: 2.2; font-weight: 500;">
                      <span style="color: #c00415; font-weight: bold;">✔</span> Luxury Flats & Apartments<br>
                      <span style="color: #c00415; font-weight: bold;">✔</span> Premium Villas<br>
                      <span style="color: #c00415; font-weight: bold;">✔</span> Modern Duplex Homes<br>
                      <span style="color: #c00415; font-weight: bold;">✔</span> SCO Plots & Commercial Investments
                    </td>
                  </tr>
                </table>

                <p style="font-size: 14px; color: #555; line-height: 1.7; margin: 0 0 25px;">Our senior property advisor will personally review your requirements and connect with you shortly to provide tailored options based on your preferred location, budget, and investment goals.</p>
                
                <!-- Call to Action / Urgent -->
                <div style="background: #0f3460; border-radius: 8px; padding: 25px; text-align: center; margin-bottom: 30px; box-shadow: 0 4px 12px rgba(15, 52, 96, 0.2);">
                  <p style="color: #ffffff; margin: 0 0 12px; font-size: 14px; opacity: 0.9;">If your inquiry is urgent, please feel free to contact us directly:</p>
                  <p style="color: #ffffff; margin: 0; font-size: 17px; font-weight: 600; line-height: 1.6;">
                    📞 +91-8130525001, +91-8130510678<br>
                    📧 <a href="mailto:team@a2prealtech.com" style="color: #ffffff; text-decoration: underline;">team@a2prealtech.com</a>
                  </p>
                </div>

                <p style="font-size: 14px; color: #555; line-height: 1.7; margin: 0;">We look forward to assisting you in securing a premium property in one of NCR’s most sought-after destinations.</p>
              </td>
            </tr>

            <!-- Footer -->
            <tr>
              <td style="padding: 35px 40px; text-align: center; background: #ffffff; border-top: 1px solid #f0f0f0;">
                <p style="margin: 0; color: #1a1a2e; font-size: 16px; font-weight: 700;">Warm regards,</p>
                <p style="margin: 6px 0 0; color: #c00415; font-size: 18px; font-weight: 800;">Luxury Property Advisory Team</p>
                <p style="margin: 4px 0 0; color: #0f3460; font-size: 15px; font-weight: 700; letter-spacing: 1px;">A2P Realtech</p>
                <div style="margin-top: 25px; border-top: 1px solid #f0f0f0; padding-top: 20px;">
                  <p style="color: #bbb; font-size: 10px; margin: 0; text-transform: uppercase; letter-spacing: 1px;">© 2025 A2P Realtech Private Limited. Luxury Lifestyle & Investments.</p>
                </div>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    </body>
    </html>';
}
