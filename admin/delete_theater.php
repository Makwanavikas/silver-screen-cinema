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
    $theater_id = intval($_GET['id']); // Secure the ID

    // VIVA NOTE: Smart Foreign Key Check
    // We must check if this theater has active shows before trying to delete it.
    $check_query = "SELECT id FROM shows WHERE theater_id = $theater_id";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // If shows exist, stop the deletion and show a friendly alert
        echo "<script>
                alert('Action Denied: You cannot delete this branch because there are active shows scheduled here. Please delete the shows first.');
                window.location='manage_theaters.php';
              </script>";
        exit;
    } else {
        // If no shows exist, it is safe to delete
        $delete_query = "DELETE FROM theaters WHERE id = $theater_id";
        
        if (mysqli_query($conn, $delete_query)) {
            echo "<script>
                    alert('Branch deleted successfully.');
                    window.location='manage_theaters.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Database Error: " . mysqli_error($conn) . "');
                    window.location='manage_theaters.php';
                  </script>";
        }
    }
} else {
    // If someone tries to access this page directly without an ID
    header("Location: manage_theaters.php");
    exit;
}
?>