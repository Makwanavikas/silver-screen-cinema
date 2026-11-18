<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/loginpage.html");
    exit();
}
require_once "../config/db.php"; 

if (!isset($_GET['movie_id'])) {
    header("Location: ../dashboard.php");
    exit;
}

$movie_id = intval($_GET['movie_id']);

// If the user selects a city on this page, save it to their session
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['city'])) {
    $_SESSION['user_city'] = mysqli_real_escape_string($conn, $_POST['city']);
}

$user_city = isset($_SESSION['user_city']) ? $_SESSION['user_city'] : '';

// 1. Fetch Movie Info SECURELY
$movie_query = mysqli_query($conn, "SELECT * FROM movies WHERE id = $movie_id");
if ($movie_query && mysqli_num_rows($movie_query) > 0) {
    $movie = mysqli_fetch_assoc($movie_query);
} else {
    echo "<script>alert('Movie not found!'); window.location='../dashboard.php';</script>";
    exit;
}

// ✨ FIX 1: Only fetch cities that have FUTURE scheduled shows! ✨
$city_query = "SELECT DISTINCT t.city 
               FROM shows s 
               JOIN theaters t ON s.theater_id = t.id 
               WHERE s.movie_id = $movie_id 
               AND t.city != '' 
               AND s.show_time > NOW()"; // Time-Travel Fix Here!

$city_result = mysqli_query($conn, $city_query);

$available_cities = [];
while ($row = mysqli_fetch_assoc($city_result)) {
    $available_cities[] = $row['city'];
}

// 2. Fetch Shows & Theaters ONLY if a city is selected
$theaters = [];
if (!empty($user_city)) {
    
    // ✨ FIX 2: Only fetch the actual showtimes that are in the FUTURE! ✨
    $query = "SELECT s.id as show_id, s.show_time, t.name as theater_name, t.location 
              FROM shows s 
              JOIN theaters t ON s.theater_id = t.id 
              WHERE s.movie_id = $movie_id 
              AND t.city = '$user_city' 
              AND s.show_time > NOW() 
              ORDER BY t.name, s.show_time ASC"; // Time-Travel Fix Here!

    $result = mysqli_query($conn, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Group the shows by theater name, and then by the specific Date
            $theater_key = $row['theater_name'] . ' - ' . $row['location'];
            $date_key = date('d M Y', strtotime($row['show_time'])); // e.g., "01 Apr 2026"
            
            $theaters[$theater_key][$date_key][] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Book Tickets: <?php echo htmlspecialchars($movie['title']); ?></title>
    <link rel="stylesheet" href="../assets/css/homepage.css">
    <link rel="stylesheet" href="../assets/css/booking_style.css"> 
</head>
<body>

<div class="booking-container">
    <a href="../dashboard.php#now-showing" class="back-link">← Back to Movies</a>

    <h1 class="page-title"><?php echo htmlspecialchars($movie['title']); ?></h1>
    <div class="sub-title">Where would you like to watch it?</div>

    <div class="city-selector-container">
        <form action="" method="POST">
            <label for="city" style="color: #94a3b8; font-weight: 600;">Select City: </label>
            <select name="city" id="city" class="city-select-dropdown" onchange="this.form.submit()" required>
                <option value="" disabled <?php echo empty($user_city) ? 'selected' : ''; ?>>📍 Choose a location...</option>
                <?php foreach ($available_cities as $city): ?>
                    <option value="<?php echo htmlspecialchars($city); ?>" <?php echo ($user_city == $city) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($city); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <?php if (empty($user_city)): ?>
        <div class="theater-card" style="text-align: center; padding: 40px;">
            <h3 style="color: #64748b;">👆 Please select a city above to view available showtimes.</h3>
        </div>
    <?php elseif (!in_array($user_city, $available_cities)): ?>
        <div class="theater-card" style="text-align: center; padding: 40px;">
            <h3 style="color: #ef4444;">No shows currently scheduled in <?php echo htmlspecialchars($user_city); ?>.</h3>
            <p style="color: #64748b; margin-top: 10px;">Please select a different city from the dropdown.</p>
        </div>
    <?php else: ?>
        
        <?php foreach ($theaters as $theater_name => $dates): ?>
            <div class="theater-card">
                <div class="theater-header">
                    <div>
                        <div class="theater-name">🍿 <?php echo htmlspecialchars(explode(' - ', $theater_name)[0]); ?></div>
                        <div class="theater-location">📍 <?php echo htmlspecialchars(explode(' - ', $theater_name)[1]); ?></div>
                    </div>
                    <span class="status-text">Available</span>
                </div>

                <?php foreach ($dates as $date => $shows): ?>
                    <div class="date-group" style="margin-top: 15px;">
                        
                        <h4 style="color: #f5c542; margin-bottom: 10px; font-size: 14px; border-bottom: 1px solid #334155; padding-bottom: 5px;">
                            📅 <?php echo $date; ?>
                        </h4>
                        
                        <div class="time-slots">
                            <?php foreach ($shows as $show): ?>
                                <?php $time_formatted = date('g:i A', strtotime($show['show_time'])); ?>
                                <a href="select_seats.php?show_id=<?php echo $show['show_id']; ?>" class="time-btn">
                                    <?php echo $time_formatted; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>

                    </div>
                <?php endforeach; ?>

            </div>
        <?php endforeach; ?>

    <?php endif; ?>
</div>

</body>
</html>