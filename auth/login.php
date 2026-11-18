<?php
session_start();
// This connects to the database file in your config folder
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass  = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // Check if account is verified (OTP check)
        if ($user['is_verified'] == 0) {
            echo "<script>alert('Please verify your account first'); window.location.href='../views/loginpage.html';</script>";
            exit;
        }

        // Verify hashed password
        if (password_verify($pass, $user['password'])) {
            // Store data in Session
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['role']      = $user['role']; // 1 for Admin, 0 for User

            // REDIRECT BASED ON ROLE
            if ($user['role'] == 1) {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../dashboard.php");
            }
            exit;
            
        } else {
            echo "<script>alert('Incorrect password'); window.location.href='../views/loginpage.html';</script>";
        }
    } else {
        echo "<script>alert('Email not registered'); window.location.href='../views/register.php';</script>";
    }
}
?>