<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "function/function.php";
    include "function/mailer.php";

    $name     = isset($_POST['name'])     ? htmlspecialchars($_POST['name'])                           : '';
    $email    = isset($_POST['email'])    ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)         : '';
    $phone    = isset($_POST['phone'])    ? htmlspecialchars($_POST['phone'])                          : '';
    $interest = isset($_POST['interest']) ? htmlspecialchars($_POST['interest'])                       : '';
    $budget   = isset($_POST['budget'])   ? htmlspecialchars($_POST['budget'])                         : '';
    $message  = isset($_POST['message'])  ? htmlspecialchars($_POST['message'])                        : '';
    $city     = isset($_POST['city'])     ? htmlspecialchars($_POST['city'])                           : 'Not Provided';
    $lat_long = isset($_POST['lat_long']) ? htmlspecialchars($_POST['lat_long'])                       : '';

    $data = [
        'name'     => $name,
        'email'    => $email,
        'phone'    => $phone,
        'interest' => $interest,
        'budget'   => $budget,
        'message'  => $message,
        'city'     => $city,
        'lat_long' => $lat_long,
        'tdate'    => date('Y-m-d'),
    ];
    insert('chatbot_leads', $data);

    // Prepare Admin Body
    $googleMapLink = !empty($lat_long) ? "https://www.google.com/maps/search/?api=1&query=".urlencode($lat_long) : "";
    
    $adminSubject = "New Chatbot Lead from $name";
    $adminBody = "<h3>New Chatbot Lead — A2P Realtech</h3>
                  <p><b>Name:</b> $name<br>
                  <b>Email:</b> $email<br>
                  <b>Phone:</b> $phone<br>
                  <b>Interest:</b> $interest<br>
                  <b>Budget:</b> $budget<br>
                  <b>Location:</b> $city ". (!empty($googleMapLink) ? "(<a href='$googleMapLink' target='_blank'>View on Map</a>)" : "") ."<br>
                  <b>Coordinates:</b> $lat_long<br>
                  <b>Message:</b> $message</p>";

    // Send BOTH in one go
    sendAllMails($email, $name, $adminSubject, $adminBody);

    echo "success";
}
?>
