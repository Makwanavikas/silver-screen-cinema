<?php
$email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP | SilverScreen</title>
    <link rel="stylesheet" href="../assets/css/style.css"> 
</head>
<body>

    <div class="login-box">
        <h2>Verify OTP</h2>
        <p class="auth-subtitle">
            Enter the 6-digit code sent to<br>
            <strong><?php echo $email; ?></strong>
        </p>

        <form action="../auth/process_reset_otp.php" method="POST">
            <input type="hidden" name="email" value="<?php echo $email; ?>">
            
            <div class="input-box">
                <input type="text" name="otp" required autocomplete="off" maxlength="6" style="letter-spacing: 5px; text-align: center;">
                <label>Enter OTP</label>
            </div>
            
            <button type="submit">Verify Code</button>
        </form>
    </div>

</body>
</html>