<?php
session_start();
require_once "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $otp   = mysqli_real_escape_string($conn, $_POST['otp']);

    // Check the database for the most recent OTP for this email
    $query = "SELECT * FROM otp_verification WHERE email='$email' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    $userOtp = mysqli_fetch_assoc($result);

    // Verify OTP and check if it has expired
    if ($userOtp && $userOtp['otp'] == $otp && strtotime($userOtp['expiry']) > time()) {
        
        // VIVA NOTE: OTP is valid! Delete it from the database so it cannot be reused by hackers.
        mysqli_query($conn, "DELETE FROM otp_verification WHERE id=".$userOtp['id']);

        // Redirect to the final page to set the new password
        header("Location: ../views/new_password.php?email=$email");
        exit;
    } else {
        // Incorrect or expired OTP
        echo "<script>
                alert('Invalid or expired OTP. Please try again.'); 
                window.location.href='../views/verify_reset_otp.php?email=$email';
              </script>";
    }
} else {
    header("Location: ../views/forgot_password.php");
    exit;
}
?>