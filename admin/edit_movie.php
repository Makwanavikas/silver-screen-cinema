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

// 1. Fetch existing movie data
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $res = mysqli_query($conn, "SELECT * FROM movies WHERE id = '$id'");
    if (mysqli_num_rows($res) > 0) {
        $movie = mysqli_fetch_assoc($res);
    } else {
        die("Movie not found.");
    }
} else {
    header("Location: manage_movies.php");
    exit();
}

// 2. Handle Update Logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $year  = mysqli_real_escape_string($conn, $_POST['year']);
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);
    $trailer = mysqli_real_escape_string($conn, $_POST['trailer_url']);

    // Check if a new poster is being uploaded
    if (!empty($_FILES['poster']['name'])) {
        $posterName = $_FILES['poster']['name'];
        $tempName   = $_FILES['poster']['tmp_name'];
        move_uploaded_file($tempName, "../assets/img/" . $posterName);
        
        // Update query including new poster
        $sql = "UPDATE movies SET 
                title='$title', 
                genre='$genre', 
                release_year='$year', 
                rating='$rating', 
                poster='$posterName', 
                trailer_url='$trailer' 
                WHERE id='$id'";
    } else {
        // Update query keeping the old poster
        $sql = "UPDATE movies SET 
                title='$title', 
                genre='$genre', 
                release_year='$year', 
                rating='$rating', 
                trailer_url='$trailer' 
                WHERE id='$id'";
    }

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Movie Updated Successfully!'); window.location.href='manage_movies.php';</script>";
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
    <title>Edit Movie | SilverScreen Admin</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>

    <aside class="sidebar">
        <div class="admin-logo">SilverScreen Admin</div>
        <ul class="menu-links">
            <li><a href="dashboard.php">📊 Dashboard</a></li>
            <li><a href="manage_movies.php" class="active">🎬 Manage Movies</a></li>
            <li><a href="../dashboard.php">🌐 View Website</a></li>
        </ul>
        <a href="../auth/logout.php" class="logout-btn">Logout →</a>
    </aside>

    <main class="main-content">
        <header class="header">
            <h1>Edit Movie: <?php echo htmlspecialchars($movie['title']); ?></h1>
            <a href="manage_movies.php" style="color: #94a3b8; text-decoration: none;">← Cancel</a>
        </header>

        <div class="form-card">
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $movie['id']; ?>">

                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Movie Title</label>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($movie['title']); ?>" placeholder="e.g. Inception" required>
                    </div>

                    <div class="form-group">
                        <label>Genre</label>
                        <input type="text" name="genre" value="<?php echo htmlspecialchars($movie['genre']); ?>" placeholder="e.g. Action, Sci-Fi" required>
                    </div>

                    <div class="form-group">
                        <label>Release Year</label>
                        <input type="number" name="year" value="<?php echo $movie['release_year']; ?>" placeholder="2024" required>
                    </div>

                    <div class="form-group">
                        <label>IMDb Rating</label>
                        <input type="text" name="rating" value="<?php echo $movie['rating']; ?>" placeholder="8.8" required>
                    </div>

                    <div class="form-group">
                        <label>Update Poster Image</label>
                        <div class="edit-preview-container">
                            <img src="../assets/img/<?php echo $movie['poster']; ?>" class="edit-preview-img" alt="current">
                            <input type="file" name="poster" accept="image/*">
                        </div>
                        <small class="helper-text">Leave empty to keep the current poster.</small>
                    </div>

                    <div class="form-group full-width">
                        <label>YouTube Trailer Link (Embed URL)</label>
                        <input type="url" name="trailer_url" value="<?php echo htmlspecialchars($movie['trailer_url']); ?>" placeholder="https://www.youtube.com/embed/..." required>
                        <small class="helper-text">Matches DB column: trailer_url</small>
                    </div>
                </div>
                <button type="submit" class="submit-btn">Update Movie Details</button>
            </form>
        </div>
    </main>

</body>
</html>