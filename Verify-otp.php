<?php
session_start();

if(!isset($_SESSION['OTP']) || !isset($_POST['otp'])){
    header("location:index.php");
    exit();
}

$actual_sent_otp = $_SESSION['OTP'];
$entered_otp = $_POST['otp'];

if($actual_sent_otp == $entered_otp){
    // OTP Verified Successfully!
    $_SESSION['user_logged_in'] = true;
    header("location:index.php");
    exit();
} else {
    // Error Message ke saath wapas bhejien
    header("location:otp.php?msg=Incorrect OTP Entered! Try Again.");
    exit();
}
?>