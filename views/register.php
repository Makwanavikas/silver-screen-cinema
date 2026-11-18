<?php 
// VIVA NOTE: session_start() must be the very first thing in the file!
session_start(); 

if (empty($_GET)) {
    unset($_SESSION['temp_name']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="UTF-8">
  <title>SilverScreen Cinema - Register</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <div class="login-box">
    <h2>Register</h2>
    <form action="../auth/register.php" method="POST" id="registerForm">
      
      <div class="input-box">
        <input type="text" name="full_name" id="full_name" 
               value="<?php echo isset($_SESSION['temp_name']) ? htmlspecialchars($_SESSION['temp_name']) : ''; ?>" required>
        <label>Full Name</label>
        <span id="nameError" class="error-text"></span>
      </div>

      <div class="input-box">
        <input type="text" name="email" id="email"
               value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>"
               oninput="this.value = this.value.toLowerCase()" required>
        <label>Email</label>
        <span id="emailError" class="error-text"></span>
      </div>

      <?php if (isset($_GET['verified']) && $_GET['verified'] == 1): ?>
        <div class="input-box">
          <input type="password" name="password" id="password" required onkeyup="checkPasswordStrength()">
          <label>Password</label>
        </div>

        <div class="password-rules">
            <p>Password must contain:</p>
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li id="rule-length" class="invalid">❌ At least 8 characters</li>
                <li id="rule-upper" class="invalid">❌ One uppercase letter (A-Z)</li>
                <li id="rule-lower" class="invalid">❌ One lowercase letter (a-z)</li>
                <li id="rule-num" class="invalid">❌ One number (0-9)</li>
                <li id="rule-special" class="invalid">❌ One special character (@$!%*?&)</li>
            </ul>
        </div>
        <button type="submit" id = "submitBtn">Complete Registration</button>
      <?php else: ?>
        <button type="button" onclick="validateAndSendOTP()">Send OTP</button>
      <?php endif; ?>

      <p class="register">
        Already have an account? <a href="loginpage.html">Login</a>
      </p>
    </form>
  </div>

  <script>
    function validateAndSendOTP() {
        var fullName = document.getElementById("full_name").value.trim();
        var email = document.getElementById("email").value.trim();
        
        var nameError = document.getElementById("nameError");
        var emailError = document.getElementById("emailError");

        nameError.innerHTML = "";
        emailError.innerHTML = "";

        var isValid = true;
        var nameRegex = /^[A-Za-z\s]+$/; 
        
        if (fullName === "") {
            nameError.innerHTML = "Name cannot be empty.";
            isValid = false;
        } else if (!nameRegex.test(fullName)) {
            nameError.innerHTML = "Only letters and spaces are allowed.";
            isValid = false;
        }

        var emailRegex = /^[a-z0-9._-]+@[a-z0-9.-]+\.[a-z]{2,6}$/;

        if (email === "") {
            emailError.innerHTML = "Email cannot be empty.";
            isValid = false;
        } else if (!emailRegex.test(email)) {
            emailError.innerHTML = "Enter a valid email structure (e.g., user@gmail.com).";
            isValid = false;
        }

        if (isValid) {
            // Send the name to Step 2 so PHP can save it!
            window.location.href = '../auth/send_email_otp.php?email=' + encodeURIComponent(email) + '&name=' + encodeURIComponent(fullName);
        }
    }

    function checkPasswordStrength() {
        var pwd = document.getElementById("password").value;
        var submitBtn = document.getElementById("submitBtn");
        var isValid = true;

        // 1. Check Length (8+ chars)
        if (pwd.length >= 8) {
            document.getElementById("rule-length").className = "valid";
            document.getElementById("rule-length").innerHTML = "✅ At least 8 characters";
        } else {
            document.getElementById("rule-length").className = "invalid";
            document.getElementById("rule-length").innerHTML = "❌ At least 8 characters";
            isValid = false;
        }

        // 2. Check Uppercase
        if (/[A-Z]/.test(pwd)) {
            document.getElementById("rule-upper").className = "valid";
            document.getElementById("rule-upper").innerHTML = "✅ One uppercase letter (A-Z)";
        } else {
            document.getElementById("rule-upper").className = "invalid";
            document.getElementById("rule-upper").innerHTML = "❌ One uppercase letter (A-Z)";
            isValid = false;
        }

        // 3. Check Lowercase
        if (/[a-z]/.test(pwd)) {
            document.getElementById("rule-lower").className = "valid";
            document.getElementById("rule-lower").innerHTML = "✅ One lowercase letter (a-z)";
        } else {
            document.getElementById("rule-lower").className = "invalid";
            document.getElementById("rule-lower").innerHTML = "❌ One lowercase letter (a-z)";
            isValid = false;
        }

        // 4. Check Number
        if (/[0-9]/.test(pwd)) {
            document.getElementById("rule-num").className = "valid";
            document.getElementById("rule-num").innerHTML = "✅ One number (0-9)";
        } else {
            document.getElementById("rule-num").className = "invalid";
            document.getElementById("rule-num").innerHTML = "❌ One number (0-9)";
            isValid = false;
        }

        // 5. Check Special Character
        if (/[!\@\#\$\%\^\&\*\(\)\_\+\.\,\;\:\-]/.test(pwd)) {
            document.getElementById("rule-special").className = "valid";
            document.getElementById("rule-special").innerHTML = "✅ One special character (@$!%*?&)";
        } else {
            document.getElementById("rule-special").className = "invalid";
            document.getElementById("rule-special").innerHTML = "❌ One special character (@$!%*?&)";
            isValid = false;
        }

        // 6. Lock or Unlock the Submit Button
        // If ALL rules passed, isValid stays true, and we unlock the button!
        submitBtn.disabled = !isValid;
    }
  </script>
</body>
</html>