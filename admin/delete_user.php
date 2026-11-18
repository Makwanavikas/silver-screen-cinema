<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/loginpage.html");
    exit();
}
if (isset($_SESSION['role']) && $_SESSION['role'] != 1) {
    header("Location: ../dashboard.php");
    exit();
}
require_once "../config/db.php";

// Check if an ID was passed in the URL
if (isset($_GET['id'])) {
    $user_id_to_delete = intval($_GET['id']); // Secure the ID
    
    // Check who is currently logged in
    $current_admin_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

    // VIVA NOTE: Security Check - Prevent the admin from deleting their own account
    if ($user_id_to_delete == $current_admin_id) {
        echo "<script>
                alert('Action Denied: You cannot delete your own admin account while logged in!');
                window.location='manage_users.php';
              </script>";
        exit;
    }

    // If it is a different user, proceed with deletion
    $delete_query = "DELETE FROM users WHERE id = $user_id_to_delete";
    
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>
                alert('User removed successfully.');
                window.location='manage_users.php';
              </script>";
    } else {
        echo "<script>
                alert('Database Error: " . mysqli_error($conn) . "');
                window.location='manage_users.php';
              </script>";
    }

} else {
    // If accessed directly without an ID, send them back
    header("Location: manage_users.php");
    exit;
}
?>