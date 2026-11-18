<?php
$email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';
if(empty($email)) { header("Location: loginpage.html"); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Password | SilverScreen</title>
    <link rel="stylesheet" href="../assets/css/style.css"> 
</head>
<body>

    <div class="login-box">
        <h2>New Password</h2>
        <p class="auth-subtitle">Create a strong new password for your account.</p>

        <form action="../auth/update_password.php" method="POST">
            <input type="hidden" name="email" value="<?php echo $email; ?>">

            <div class="input-box">
                <input type="password" name="new_password" id="password" required onkeyup="validatePassword()">
                <label>New Password</label>
            </div>

            <div class="input-box">
                <input type="password" name="confirm_password" id="confirm_password" required onkeyup="validatePassword()">
                <label>Confirm Password</label>
            </div>

            <div class="password-rules">
                <p>Password requirements:</p>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li id="rule-length" class="invalid">❌ At least 8 characters</li>
                    <li id="rule-upper" class="invalid">❌ One uppercase letter (A-Z)</li>
                    <li id="rule-lower" class="invalid">❌ One lowercase letter (a-z)</li>
                    <li id="rule-num" class="invalid">❌ One number (0-9)</li>
                    <li id="rule-special" class="invalid">❌ One special character (@$!%*?&)</li>
                    <li id="rule-match" class="invalid">❌ Passwords match</li>
                </ul>
            </div>

            <button type="submit" id="submitBtn" disabled style="opacity: 0.5; cursor: not-allowed;">Update Password</button>
        </form>
    </div>

    <script>
        function validatePassword() {
            var pwd = document.getElementById("password").value;
            var confirmPwd = document.getElementById("confirm_password").value;
            var submitBtn = document.getElementById("submitBtn");
            var isStrong = true;

            // 1. Check Length
            if (pwd.length >= 8) {
                document.getElementById("rule-length").className = "valid";
                document.getElementById("rule-length").innerHTML = "✅ At least 8 characters";
            } else {
                document.getElementById("rule-length").className = "invalid";
                document.getElementById("rule-length").innerHTML = "❌ At least 8 characters";
                isStrong = false;
            }

            // 2. Check Uppercase
            if (/[A-Z]/.test(pwd)) {
                document.getElementById("rule-upper").className = "valid";
                document.getElementById("rule-upper").innerHTML = "✅ One uppercase letter (A-Z)";
            } else {
                document.getElementById("rule-upper").className = "invalid";
                document.getElementById("rule-upper").innerHTML = "❌ One uppercase letter (A-Z)";
                isStrong = false;
            }

            // 3. Check Lowercase
            if (/[a-z]/.test(pwd)) {
                document.getElementById("rule-lower").className = "valid";
                document.getElementById("rule-lower").innerHTML = "✅ One lowercase letter (a-z)";
            } else {
                document.getElementById("rule-lower").className = "invalid";
                document.getElementById("rule-lower").innerHTML = "❌ One lowercase letter (a-z)";
                isStrong = false;
            }

            // 4. Check Number
            if (/[0-9]/.test(pwd)) {
                document.getElementById("rule-num").className = "valid";
                document.getElementById("rule-num").innerHTML = "✅ One number (0-9)";
            } else {
                document.getElementById("rule-num").className = "invalid";
                document.getElementById("rule-num").innerHTML = "❌ One number (0-9)";
                isStrong = false;
            }

            // 5. Check Special Character
            if (/[!\@\#\$\%\^\&\*\(\)\_\+\.\,\;\:\-]/.test(pwd)) {
                document.getElementById("rule-special").className = "valid";
                document.getElementById("rule-special").innerHTML = "✅ One special character (@$!%*?&)";
            } else {
                document.getElementById("rule-special").className = "invalid";
                document.getElementById("rule-special").innerHTML = "❌ One special character (@$!%*?&)";
                isStrong = false;
            }

            // 6. Check if Passwords Match
            var isMatch = false;
            if (pwd !== "" && pwd === confirmPwd) {
                document.getElementById("rule-match").className = "valid";
                document.getElementById("rule-match").innerHTML = "✅ Passwords match";
                isMatch = true;
            } else {
                document.getElementById("rule-match").className = "invalid";
                document.getElementById("rule-match").innerHTML = "❌ Passwords match";
            }

            // 7. Lock or Unlock the Submit Button (Must be strong AND matching)
            if (isStrong && isMatch) {
                submitBtn.disabled = false;
                submitBtn.style.opacity = "1";
                submitBtn.style.cursor = "pointer";
            } else {
                submitBtn.disabled = true;
                submitBtn.style.opacity = "0.5";
                submitBtn.style.cursor = "not-allowed";
            }
        }
    </script>
</body>
</html>