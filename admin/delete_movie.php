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

// Check if an ID is provided in the URL
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // 1. Fetch the poster filename before deleting the record 
    // This allows us to delete the actual image file from assets/img
    $result = mysqli_query($conn, "SELECT poster FROM movies WHERE id = '$id'");
    $movie = mysqli_fetch_assoc($result);

    if ($movie) {
        $posterPath = "../assets/img/" . $movie['poster'];
        
        // Delete the image file from the folder if it exists
        if (file_exists($posterPath)) {
            unlink($posterPath);
        }

        // 2. Delete the record from the database
        $deleteQuery = "DELETE FROM movies WHERE id = '$id'";
        
        if (mysqli_query($conn, $deleteQuery)) {
            // Redirect back with a success message
            header("Location: manage_movies.php?msg=deleted");
            exit();
        } else {
            echo "Error deleting record: " . mysqli_error($conn);
        }
    }
} else {
    // If no ID is found, just go back to the list
    header("Location: manage_movies.php");
    exit();
}
?>