<?php
session_start();

// If user already logged in → go to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

// If not logged in → go to login page
header("Location: views/loginpage.html");
exit;
