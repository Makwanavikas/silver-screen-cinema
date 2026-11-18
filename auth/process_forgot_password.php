<?php
session_start();
require_once "../config/db.php";

// VIVA NOTE: Including PHPMailer using your existing vendor folder
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Adjust this path if your vendor folder is in a different location
require '../vendor/autoload.php';
$mailCfg = require '../config/mail.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['request_reset'])) {
    
    // 1. Sanitize the email input
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // 2. Check if the email actually exists in the users table
    $check_user = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");

    if (mysqli_num_rows($check_user) > 0) {
        // User exists! Generate a random 6-digit OTP
        $otp = rand(100000, 999999);
        
        // Set expiry time (10 minutes from now)
        $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

        // 3. Save OTP to your otp_verification table
        $insert_otp = "INSERT INTO otp_verification (email, otp, expiry) VALUES ('$email', '$otp', '$expiry')";
        
        if (mysqli_query($conn, $insert_otp)) {
            
            // 4. SEND THE EMAIL USING PHPMAILER
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; 
                $mail->SMTPAuth   = true;
                $mail->Username   = $mailCfg['username'];
                $mail->Password   = $mailCfg['password'];
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // Recipients
                $mail->setFrom($mailCfg['username'], $mailCfg['from_name']);
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset OTP - SilverScreen';
                
                // VIVA NOTE: Inline CSS is required for HTML emails to display correctly in Gmail/Outlook
                $mail->Body    = "
                    <div style='font-family: Arial, sans-serif; padding: 20px; background-color: #020617; color: #fff;'>
                        <div style='max-width: 600px; margin: 0 auto; background: #0f172a; padding: 30px; border-radius: 10px; border: 1px solid #eab308; text-align: center;'>
                            <h2 style='color: #eab308; margin-bottom: 20px;'>SilverScreen Cinemas</h2>
                            <p style='font-size: 16px; color: #ccc;'>Hello,</p>
                            <p style='font-size: 16px; color: #ccc;'>You requested a password reset. Here is your 6-digit verification code:</p>
                            
                            <div style='margin: 30px 0;'>
                                <span style='font-size: 32px; font-weight: bold; letter-spacing: 6px; color: #fff; padding: 15px 30px; background: rgba(234, 179, 8, 0.2); border: 1px solid #eab308; border-radius: 8px;'>{$otp}</span>
                            </div>
                            
                            <p style='font-size: 14px; color: #94a3b8;'>This code will expire in 10 minutes. If you did not request this, please ignore this email.</p>
                        </div>
                    </div>
                ";

                $mail->send();
                
                // 5. Redirect user to the OTP verification page
                echo "<script>
                        alert('An OTP has been sent to your email.');
                        window.location.href='../views/verify_reset_otp.php?email=$email';
                      </script>";
                exit;

            } catch (Exception $e) {
                echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}'); window.history.back();</script>";
            }

        } else {
            echo "<script>alert('Database error. Please try again later.'); window.history.back();</script>";
        }

    } else {
        // SECURITY BEST PRACTICE: Generic message to stop email guessing
        echo "<script>
                alert('If this email is registered, an OTP will be sent to it.');
                window.location.href='../views/forgot_password.php';
              </script>";
    }
} else {
    // If accessed directly without submitting the form
    header("Location: ../views/forgot_password.php");
    exit;
}
?>