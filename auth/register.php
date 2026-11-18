<?php
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name  = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Email already registered'); 
              window.location.href='../views/loginpage.html';</script>";
        exit;
    }

    // Insert new user with is_verified=1 (OTP already passed)
    $sql = "INSERT INTO users (full_name, email, password, is_verified)
            VALUES ('$name', '$email', '$pass', 1)";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Registration complete! You can now login'); 
              window.location.href='../views/loginpage.html';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>