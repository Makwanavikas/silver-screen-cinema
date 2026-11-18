<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: views/loginpage.html");
    exit();
}
require_once "config/db.php";

$id = $_GET['id'];
$query = "SELECT * FROM movies WHERE id = $id";
$result = mysqli_query($conn, $query);
$movie = mysqli_fetch_assoc($result);

// Convert YouTube URL to Embed format
$trailer_id = "";
if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $movie['trailer_url'], $matches)) {
    $trailer_id = $matches[1];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $movie['title']; ?> | SilverScreen</title>
    <link rel="stylesheet" href="assets/css/details_style.css">
</head>
<body>

<div class="details-page">
    <a href="dashboard.php" class="back-btn">← Back to Movies</a>

    <div class="details-container">
        <div class="details-poster">
            <img src="assets/img/<?php echo $movie['poster']; ?>" alt="Poster">
        </div>

        <div class="details-info">
            <h1 class="movie-title"><?php echo $movie['title']; ?></h1>
            
            <div class="meta-row">
                <span class="badge genre"><?php echo $movie['genre']; ?></span>
                <span class="badge year"><?php echo $movie['release_year']; ?></span>
                <span class="rating">⭐ <?php echo $movie['rating']; ?>/10</span>
            </div>

            <p class="movie-description">
                Experience the magic of <?php echo $movie['title']; ?>. This <?php echo $movie['genre']; ?> 
                masterpiece released in <?php echo $movie['release_year']; ?> is a must-watch for all cinema lovers.
            </p>

            <div class="action-bar">
                <a href="booking/select_theater.php?movie_id=<?php echo $movie['id']; ?>" class="buy-ticket-btn">Book Tickets Now</a>
            </div>
        </div>
    </div>

    <?php if ($trailer_id): ?>
    <div class="trailer-section">
        <h2 class="section-title">Official Trailer</h2>
        <div class="video-wrapper">
            <iframe src="https://www.youtube.com/embed/<?php echo $trailer_id; ?>?autoplay=0&rel=0" allowfullscreen></iframe>
        </div>
    </div>
    <?php endif; ?>
</div>

</body>
</html>