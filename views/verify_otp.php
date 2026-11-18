<?php
// Safely fetch the email from the URL so we can show it to the user
$email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP | SilverScreen</title>
    
    <link rel="stylesheet" href="../assets/css/verify_otp_style.css"> 
</head>
<body>

    <div class="login-box">
        <h2>Verify OTP</h2>
        <p class="auth-subtitle">
            Enter the 6-digit code sent to<br>
            <strong><?php echo $email; ?></strong>
        </p>

        <form action="../auth/verify_email_otp.php" method="POST">
            <input type="hidden" name="email" value="<?php echo $email; ?>">
            
            <div class="input-box">
                <input type="text" name="otp" required autocomplete="off" maxlength="6" autofocus>
                <label>Enter OTP</label>
            </div>
            
            <button type="submit" class="auth-btn">Verify</button>
        </form>
    </div>

</body>
</html>