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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user inputs for security
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $year  = mysqli_real_escape_string($conn, $_POST['year']);
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);
    $trailer = mysqli_real_escape_string($conn, $_POST['trailer_url']); 
    
    // Handle the poster image upload
    $posterName = $_FILES['poster']['name'];
    $tempName   = $_FILES['poster']['tmp_name'];
    $targetPath = "../assets/img/" . $posterName;

    
    $query = "INSERT INTO movies (title, genre, release_year, rating, poster, trailer_url) 
              VALUES ('$title', '$genre', '$year', '$rating', '$posterName', '$trailer')";

    if (mysqli_query($conn, $query)) {
        // Move the physical image file to your server folder
        if (move_uploaded_file($tempName, $targetPath)) {
            echo "<script>alert('Success: Movie added to library!'); window.location.href='manage_movies.php';</script>";
        } else {
            // Safety check in case the image folder lacks permissions
            echo "<script>alert('Warning: Movie added to database, but poster upload failed.'); window.location.href='manage_movies.php';</script>";
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <meta charset="UTF-8">
    <title>Add New Movie | Admin</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <header class="header">
            <h1>Add New Movie</h1>
            <a href="manage_movies.php" class="back-link">← Back to List</a>
        </header>

        <div class="form-card">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Movie Title</label>
                        <input type="text" name="title" placeholder="e.g. Deadpool & Wolverine" required>
                    </div>

                    <div class="form-group">
                        <label>Genre</label>
                        <input type="text" name="genre" placeholder="e.g. Action, Comedy, Sci-Fi" required>
                    </div>

                    <div class="form-group">
                        <label>Release Year</label>
                        <input type="number" name="year" placeholder="2026" required>
                    </div>

                    <div class="form-group">
                        <label>IMDb Rating</label>
                        <input type="text" name="rating" placeholder="e.g. 8.5" required>
                    </div>

                    <div class="form-group">
                        <label>Poster Image</label>
                        <input type="file" name="poster" accept="image/*" required>
                    </div>

                    <div class="form-group full-width">
                        <label>YouTube Trailer Link (Embed URL)</label>
                        <input type="url" name="trailer_url" placeholder="https://www.youtube.com/embed/dQw4w9WgXcQ" required>
                        <small class="helper-text" style="color: #94a3b8; font-size: 12px; margin-top: 5px; display: block;">Important: Use the 'Embed' link from YouTube for the player to function.</small>
                    </div>
                </div>
                <button type="submit" class="submit-btn">Upload Movie to Library</button>
            </form>
        </div>
    </main>
</body>
</html>