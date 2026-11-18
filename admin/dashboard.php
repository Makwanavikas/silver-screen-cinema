<?php
// Start session BEFORE any HTML is loaded
session_start();

// 1. SECURITY CHECK: If they are NOT logged in at all -> Kick them to the login page!
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/loginpage.html");
    exit(); // EXIT is mandatory to stop the dashboard from loading in the background
}

// 2. SECURITY CHECK: If they are logged in, but their role is NOT 1 (Admin) -> Kick them to the User dashboard!
if (isset($_SESSION['role']) && $_SESSION['role'] != 1) {
    header("Location: ../dashboard.php");
    exit();
}

// If they pass the security checks, connect to the database
require_once "../config/db.php"; 

// 3. Fetch live stats from the database
$movie_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM movies");
$movie_count = mysqli_fetch_assoc($movie_query)['total'];

$user_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$user_count = mysqli_fetch_assoc($user_query)['total'];

// Count theaters safely
$theater_count = 0;
$check_theater = mysqli_query($conn, "SHOW TABLES LIKE 'theaters'");
if(mysqli_num_rows($check_theater) > 0) {
    $theater_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM theaters");
    $theater_count = mysqli_fetch_assoc($theater_query)['total'];
}

// ✨ NEW: Financial Metrics (Revenue & Bookings)
$revenue_query = mysqli_query($conn, "SELECT SUM(total_price) as total_rev FROM bookings");
$revenue_data = mysqli_fetch_assoc($revenue_query);
// If there are no bookings yet, set revenue to 0
$total_revenue = $revenue_data['total_rev'] ? $revenue_data['total_rev'] : 0; 

$booking_count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings");
$total_bookings = mysqli_fetch_assoc($booking_count_query)['total'];

// ✨ NEW: Operational Metrics (Shows Today)
// VIVA NOTE: CURDATE() is a built-in MySQL function that gets today's date!
$shows_today_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM shows WHERE DATE(show_time) = CURDATE()");
$shows_today = mysqli_fetch_assoc($shows_today_query)['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | SilverScreen Cinema</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <header class="header">
            <h1>Dashboard Overview</h1>
            <div class="user-pill">Admin: <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Superuser'); ?></div>
        </header>

        <div class="welcome-banner">
            <h2>Welcome back, Admin!</h2>
            <p style="color: #94a3b8;">Here is what's happening with your cinema library today.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Movies</h3>
                <div class="number"><?php echo $movie_count; ?></div>
                <span class="trend">↑ Active in Library</span>
            </div>

            <div class="stat-card">
                <h3>Registered Users</h3>
                <div class="number"><?php echo $user_count; ?></div>
                <span class="trend">↑ Community Members</span>
            </div>

            <div class="stat-card">
                <h3>Cinema Halls</h3>
                <div class="number"><?php echo $theater_count; ?></div>
                <span class="trend">↑ Locations Managed</span>
            </div>
            
            <div class="stat-card">
                <h3 style="color: #eab308;">Total Revenue</h3>
                <div class="number">₹<?php echo number_format($total_revenue, 2); ?></div>
                <span class="trend">↑ Lifetime Sales</span>
            </div>

            <div class="stat-card">
                <h3 style="color: #eab308;">Total Bookings</h3>
                <div class="number"><?php echo $total_bookings; ?></div>
                <span class="trend">↑ Successful Transactions</span>
            </div>

            <div class="stat-card">
                <h3 style="color: #38bdf8;">Shows Today</h3>
                <div class="number"><?php echo $shows_today; ?></div>
                <span class="trend">Live Operations</span>
            </div>
        </div>

        <div class="action-section">
            <h3>Quick Actions</h3>
            <div style="display: flex; gap: 15px; margin-top: 15px;">
                <a href="add_movie.php" class="add-btn" style="text-decoration: none;">+ Add New Movie</a>
                <a href="manage_movies.php" class="add-btn" style="text-decoration: none; background: #334155;">View Library</a>
            </div>
        </div>
    </main>

</body>
</html>