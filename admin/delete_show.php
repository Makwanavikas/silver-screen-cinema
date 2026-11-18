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
    $id = intval($_GET['id']); // intval secures against SQL injection
    
    // Delete the show from the database
    $query = "DELETE FROM shows WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        // Redirect back to the manage shows page
        header("Location: manage_shows.php");
        exit;
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    header("Location: manage_shows.php");
    exit;
}
?>