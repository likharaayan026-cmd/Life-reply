<?php
session_start();

if (!isset($_POST['phone']) || empty($_POST['phone'])) {
    die("Mobile number missing hai!");
}

// 10 digit phone number sanitize karein (+91 hatayein agar aaye)
$PHONE = preg_replace('/[^0-9]/', '', $_POST['phone']);
if (strlen($PHONE) > 10) {
    $PHONE = substr($PHONE, -10);
}

// OTP Generate karein (4 Digits)
$OTP = rand(1111, 9999);
$_SESSION['OTP'] = $OTP;
$_SESSION['phone'] = $PHONE;

// API Details
$API = "bdc363788b2b48c031bf406cf15aa252"; 
$URL = "https://sms.renflair.in/V1.php?API=$API&PHONE=$PHONE&OTP=$OTP";

// cURL Call
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $URL);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 

$resp = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

// Response Check Debugging
if ($err) {
    die("cURL Error: " . $err);
} else {
    // Agar API response check karna ho
    // Echo response for testing: // die($resp);
    
    // Success hone par OTP page par bhejein
    header("Location: otp.php");
    exit();
}
?>