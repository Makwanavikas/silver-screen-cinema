<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | SilverScreen</title>
    <link rel="stylesheet" href="../assets/css/style.css"> 
</head>
<body>

    <div class="login-box">
        <h2>Forgot Password?</h2>
        <p class="auth-subtitle">
            Enter your registered email and we will send you an OTP to reset your password.
        </p>

        <form action="../auth/process_forgot_password.php" method="POST">
            <div class="input-box">
                <input type="email" name="email" required autocomplete="off">
                <label>Registered Email</label>
            </div>
            
            <button type="submit" name="request_reset">Send OTP</button>
            
            <div class="register" style="margin-top: 25px;">
                <a href="loginpage.html">← Back to Login</a>
            </div>
        </form>
    </div>

</body>
</html>