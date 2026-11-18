<?php
session_start();
require_once "../config/db.php";
require '../vendor/autoload.php';
$mailCfg = require '../config/mail.php';

if(isset($_GET['name'])) {
    $_SESSION['temp_name'] = $_GET['name'];
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = $_GET['email'];
$otp   = rand(100000, 999999);
$expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

// Store OTP
mysqli_query($conn, "INSERT INTO otp_verification (email, otp, expiry) VALUES ('$email', '$otp', '$expiry')");

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $mailCfg['username'];
    $mail->Password = $mailCfg['password'];
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom($mailCfg['username'], $mailCfg['from_name']);
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Your OTP Code For Registration - SilverScreen Cinemas';
    $mail->Body = "
    <div style='font-family: Arial, sans-serif; padding: 20px; background-color: #020617; color: #fff;'>
        <div style='max-width: 600px; margin: 0 auto; background: #0f172a; padding: 30px; border-radius: 10px; border: 1px solid #1e293b;'>
            <h2 style='color: #eab308; margin-bottom: 20px;'>SilverScreen Cinemas</h2>
            
            <p style='font-size: 16px; color: #ccc;'>Hello " . htmlspecialchars($_SESSION['temp_name']) . ",</p>
            <p style='font-size: 16px; color: #ccc;'>Thank you for starting your registration! Please use the following 6-digit verification code to complete your secure signup:</p>
            
            <div style='margin: 30px 0;'>
                <span style='font-size: 32px; font-weight: bold; letter-spacing: 6px; color: #fff; padding: 15px 30px; background-color: #1e293b; border-radius: 8px;'>$otp</span>
            </div>
            
            <p style='font-size: 14px; color: #94a3b8;'>This code will expire in 10 minutes. If you did not request this, please ignore this email.</p>
        </div>
    </div>
";

    $mail->send();
    echo "<script>alert('OTP sent to email'); window.location.href='../views/verify_otp.php?email=$email';</script>";
} catch (Exception $e) {
    echo "Mailer Error: {$mail->ErrorInfo}";
}
?>