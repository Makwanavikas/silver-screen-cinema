<?php
session_start();
require_once "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // 1. Basic validation: Do the passwords match?
    if ($new_password !== $confirm_password) {
        echo "<script>alert('Passwords do not match! Please try again.'); window.history.back();</script>";
        exit;
    }

    // 2. VIVA NOTE: Cybersecurity Best Practice!
    // Never store plain text passwords. We must hash the password before saving it to the database.
    // If your login logic uses plain text, you can skip this, but password_hash() is highly recommended for Vivas.
    // $final_password = $new_password; // <-- Use this ONLY if you are not hashing passwords in your login.php
    $final_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 3. Update the database
    $update_query = "UPDATE users SET password = '$final_password' WHERE email = '$email'";

    if (mysqli_query($conn, $update_query)) {
        echo "<script>
                alert('Success! Your password has been updated. You can now log in.');
                window.location.href='../views/loginpage.html';
              </script>";
    } else {
        echo "<script>alert('Database error. Please try again.'); window.history.back();</script>";
    }
} else {
    header("Location: ../views/loginpage.html");
    exit;
}
?>