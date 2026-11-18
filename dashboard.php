<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: views/loginpage.html");
    exit();
}
require_once "config/db.php";

$fullName = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SilverScreen Cinema | Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/homepage.css">
</head>
<body>

<section class="hero" id="hero">
    <nav class="navbar">
        <a href="dashboard.php" class="logo-link"><div class="logo">SilverScreen</div></a>
        <ul class="nav-links">
            <li class="active"><a href="dashboard.php">Home</a></li>
            <li><a href="#now-showing">Movies</a></li>
            <li><a href="my_bookings.php">My Bookings</a></li>
            <li><a href="#footer">Contact</a></li>
        </ul>
        <div class="nav-actions">
            <form action="search_results.php" method="GET" class="search-container">
                <input type="text" name="search" placeholder="Search movies..." required>
                <button type="submit">🔍</button>
            </form>
            <span class="user-name-display">Hi, <?php echo htmlspecialchars($fullName); ?></span>
            
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
                <button class="admin-panel-btn" onclick="location.href='admin/dashboard.php'">⚙️ Admin Panel</button>
            <?php endif; ?>

            <button class="login-btn" onclick="location.href='auth/logout.php'">Logout</button>
        </div>
    </nav>

    <div class="slider-container">
        <?php
        $slider_query = mysqli_query($conn, "SELECT * FROM movies ORDER BY id DESC LIMIT 5");
        $slide_index = 0;
        
        while ($slide = mysqli_fetch_assoc($slider_query)) {
            $active_class = ($slide_index == 0) ? 'active' : ''; 
            ?>
            <div class="slide <?php echo $active_class; ?>">
                <div class="slide-blur-bg" style="background-image: url('assets/img/<?php echo htmlspecialchars($slide['poster']); ?>');"></div>
                <div class="slide-content">
                    <div class="hero-text">
                        <h1><?php echo htmlspecialchars($slide['title']); ?></h1>
                        <p class="slide-meta">
                            <?php echo htmlspecialchars($slide['genre']); ?> • 
                            <?php echo htmlspecialchars($slide['release_year']); ?> • 
                            ⭐ <?php echo htmlspecialchars($slide['rating']); ?>
                        </p>
                        <div class="slider-buttons">
                            <button class="explore-btn" onclick="location.href='booking/select_theater.php?movie_id=<?php echo $slide['id']; ?>'">🎟️ Book Tickets</button>
                            <button class="explore-btn secondary" onclick="document.getElementById('now-showing').scrollIntoView({behavior: 'smooth'})">Explore Movies →</button>
                        </div>
                    </div>
                    <div class="hero-image">
                        <img src="assets/img/<?php echo htmlspecialchars($slide['poster']); ?>" alt="Poster">
                    </div>
                </div>
            </div>
            <?php
            $slide_index++;
        }
        ?>
    </div>

    <div class="slider-dots">
        <?php for($i = 0; $i < $slide_index; $i++): ?>
            <span class="dot <?php echo ($i == 0) ? 'active' : ''; ?>" onclick="currentSlide(<?php echo $i; ?>)"></span>
        <?php endfor; ?>
    </div>
</section>

<section class="now-showing" id="now-showing">
    <div class="section-header">
        <h2>Now Showing</h2>
        <a href="#">View All →</a>
    </div>
    <div class="movie-grid">
        <?php
        $result = mysqli_query($conn, "SELECT * FROM movies ORDER BY id DESC");
        while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="movie-card">
                <a href="movie_details.php?id=<?php echo $row['id']; ?>" style="text-decoration:none; color:inherit;">
                    <img src="assets/img/<?php echo $row['poster']; ?>" alt="Poster">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p><?php echo htmlspecialchars($row['genre']); ?> • <?php echo $row['release_year']; ?></p>
                </a>
                <div class="card-footer">
                    <span>⭐ <?php echo $row['rating']; ?></span>
                    <button onclick="location.href='booking/select_theater.php?movie_id=<?php echo $row['id']; ?>'">Book Now</button>
                </div>
            </div>
        <?php } ?>
    </div>
</section>

<section class="trailers">
    <div class="section-header">
        <h2>Coming Soon & Trailers</h2>
        <a href="#">View All →</a>
    </div>

    <?php
    // Fetch the 5 most recently uploaded trailers
    $trailer_query = mysqli_query($conn, "SELECT * FROM trailers ORDER BY id DESC LIMIT 5");
    $trailers = [];
    while ($t_row = mysqli_fetch_assoc($trailer_query)) {
        $trailers[] = $t_row;
    }

    // Check if there is at least 1 trailer in the database
    if (count($trailers) > 0): 
        $main_trailer = $trailers[0]; // The very newest one gets the spotlight
    ?>
        <div class="trailer-card" onclick="openTrailerByUrl('<?php echo htmlspecialchars($main_trailer['youtube_url']); ?>')">
            <img src="assets/img/<?php echo htmlspecialchars($main_trailer['thumbnail']); ?>" alt="Main Trailer">
            <div class="play-btn">▶</div>
        </div>

        <?php if (count($trailers) > 1): ?>
            <div class="trailer-thumbnails">
                <?php 
                // Start from index 1 to skip the main trailer
                for ($i = 1; $i < count($trailers); $i++): 
                    $thumb = $trailers[$i];
                ?>
                    <div class="thumb" onclick="openTrailerByUrl('<?php echo htmlspecialchars($thumb['youtube_url']); ?>')">
                        <img src="assets/img/<?php echo htmlspecialchars($thumb['thumbnail']); ?>" alt="Thumbnail">
                        <div class="thumb-play">▶</div>
                    </div>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <p style="text-align: center; color: #64748b; padding: 40px;">No new trailers available at the moment. Check back soon!</p>
    <?php endif; ?>
</section>

<div class="trailer-popup" id="trailerPopup">
    <span class="close-btn" onclick="closeTrailer()">&times; Close</span>
    <div class="trailer-video">
        <iframe id="trailerFrame" src="" allow="autoplay; encrypted-media" allowfullscreen></iframe>
    </div>
</div>

<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-col brand-col">
            <h2 class="footer-logo">Silver<span>Screen</span></h2>
            <p>Your premium gateway to the latest blockbusters and cinematic experiences.</p>
        </div>

        <div class="footer-col">
            <h3>Company</h3>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="movies.php">All Movies</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h3>Support</h3>
            <ul>
                <li><a href="faq.php">FAQs</a></li>
                <li><a href="privacy.php">Privacy Policy</a></li>
                <li><a href="terms.php">Terms & Conditions</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h3>Contact Us</h3>
            <ul class="contact-info">
                <li>📧 support.silverscreen@gmail.com</li>
                <li>📞 +91 98765 43210</li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; 2026 SilverScreen Cinema. All Rights Reserved.</p>
    </div>
</footer>

<script>
    // Trailer Logic
    function openTrailerByUrl(url) {
        const popup = document.getElementById("trailerPopup");
        const frame = document.getElementById("trailerFrame");
        // Ensure auto-play triggers instantly when the popup opens
        const cleanUrl = url.includes('?') ? url + "&autoplay=1&rel=0" : url + "?autoplay=1&rel=0";
        frame.src = cleanUrl;
        popup.style.display = "flex";
        document.body.style.overflow = "hidden";
    }

    function closeTrailer() {
        const popup = document.getElementById("trailerPopup");
        const frame = document.getElementById("trailerFrame");
        popup.style.display = "none";
        frame.src = ""; // Stops the video from playing in the background
        document.body.style.overflow = "auto";
    }

    window.onclick = function(event) {
        const popup = document.getElementById("trailerPopup");
        if (event.target == popup) { closeTrailer(); }
    }

    // Slider Logic
    let slideIndex = 0;
    let slides = document.querySelectorAll(".slide");
    let dots = document.querySelectorAll(".dot");
    let slideInterval;

    function showSlides(n) {
        if(slides.length === 0) return; 
        slides.forEach(slide => slide.classList.remove("active"));
        dots.forEach(dot => dot.classList.remove("active"));

        slideIndex = n;
        if (slideIndex >= slides.length) slideIndex = 0;
        if (slideIndex < 0) slideIndex = slides.length - 1;

        slides[slideIndex].classList.add("active");
        dots[slideIndex].classList.add("active");
    }

    function nextSlide() { showSlides(slideIndex + 1); }
    function currentSlide(n) { showSlides(n); resetInterval(); }

    function resetInterval() {
        clearInterval(slideInterval);
        slideInterval = setInterval(nextSlide, 4000); 
    }

    if(slides.length > 0) resetInterval();
</script>
</body>
</html>