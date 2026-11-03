<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "function/function.php";
    if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
        echo "error: otp not verified";
        exit;
    }
    include "function/mailer.php";

    $name     = htmlspecialchars($_POST['name'] ?? '');
    $email    = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone    = htmlspecialchars($_POST['phone'] ?? '');
    $interest = htmlspecialchars($_POST['interest'] ?? '');
    $budget   = htmlspecialchars($_POST['budget'] ?? '');
    $message  = htmlspecialchars($_POST['message'] ?? '');
    $city     = htmlspecialchars($_POST['city'] ?? 'Not Shared');
    $lat_long = htmlspecialchars($_POST['lat_long'] ?? '');

    $full_message = "Interested In: $interest | Budget: $budget | Message: $message";
    $page_source  = isset($_POST['source']) ? htmlspecialchars($_POST['source']) : "Contact Us Page";

    $data = [
        'name'        => $name,
        'email'       => $email,
        'phone'       => $phone,
        'city'        => $city,
        'lat_long'    => $lat_long,
        'message'     => $full_message,
        'page'        => $page_source,
        'destination' => "Direct Inquiry",
        'tdate'       => date('Y-m-d'),
    ];
    insert('enquiry', $data);

    // Prepare Admin Body
    $googleMapLink = !empty($lat_long) ? "https://www.google.com/maps/search/?api=1&query=".urlencode($lat_long) : "";

    $adminSubject = "New Property Inquiry from $name";
    $adminBody = "<h3>New Property Inquiry</h3>
                  <p><b>Name:</b> $name<br>
                  <b>Email:</b> $email<br>
                  <b>Phone:</b> $phone<br>
                  <b>Location:</b> $city " . (!empty($googleMapLink) ? "(<a href='$googleMapLink' target='_blank'>View on Map</a>)" : "") . "<br>
                  <b>Coordinates:</b> $lat_long<br>
                  <b>Budget:</b> $budget<br>
                  <b>Source:</b> $page_source</p>";

    // Send BOTH in one go
    sendAllMails($email, $name, $adminSubject, $adminBody);

    unset($_SESSION['otp_verified']);
    echo "success";
}
?>
