<?php
// VIVA NOTE: Start the session at the very top so the server remembers the user's name!
session_start(); 
require_once "../config/db.php";

$email = mysqli_real_escape_string($conn, $_POST['email']);
$otp   = mysqli_real_escape_string($conn, $_POST['otp']);

$result = mysqli_query($conn, "SELECT * FROM otp_verification WHERE email='$email' ORDER BY id DESC LIMIT 1");
$userOtp = mysqli_fetch_assoc($result);

if ($userOtp && $userOtp['otp'] == $otp && strtotime($userOtp['expiry']) > time()) {
    // OTP is valid -> delete it
    mysqli_query($conn, "DELETE FROM otp_verification WHERE id=".$userOtp['id']);

    // Redirect back to register page with email prefilled. 
    // The name is safely hidden inside $_SESSION['temp_name'] so we don't need it in the URL!
    echo "<script>alert('OTP verified! Please set your password to complete registration');
          window.location.href='../views/register.php?email=$email&verified=1';</script>";
} else {
    echo "<script>alert('Invalid or expired OTP'); window.location.href='../views/verify_otp.php?email=$email';</script>";
}
?>