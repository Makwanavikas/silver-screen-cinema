<?php
session_start();

// 1. SECURITY CHECK: If they are not logged in, kick them to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: views/loginpage.html");
    exit();
}

require_once "config/db.php";

$user_id = $_SESSION['user_id'];

// ✨ VIVA TALKING POINT: Relational Database Query ✨
// Explain to the examiner: "To avoid storing duplicate data, I used JOINs to fetch 
// the movie poster, theater name, and show time from their respective tables 
// based on the foreign keys stored in the bookings table."
$query = "SELECT b.id as booking_id, b.seat_numbers, 
                 m.title, m.poster, 
                 t.name as theater_name, t.city, 
                 s.show_time 
          FROM bookings b
          JOIN shows s ON b.show_id = s.id
          JOIN movies m ON s.movie_id = m.id
          JOIN theaters t ON s.theater_id = t.id
          WHERE b.user_id = $user_id
          ORDER BY s.show_time DESC"; // Shows the newest bookings first

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings | SilverScreen</title>
    <link rel="stylesheet" href="assets/css/my_bookings_style.css">
</head>
<body>

    <div class="container">
        
        <a href="dashboard.php" style="color: #94a3b8; text-decoration: none; display: inline-block; margin-bottom: 20px;">← Back to Dashboard</a>

        <h1 class="page-title">🎟️ My Bookings</h1>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="bookings-grid">
                
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="booking-card">
                        
                        <div class="booking-poster" style="background-image: url('assets/img/<?php echo htmlspecialchars($row['poster']); ?>');"></div>
                        
                        <div class="booking-info">
                            <div>
                                <div class="movie-title"><?php echo htmlspecialchars($row['title']); ?></div>
                                <div class="booking-detail"><strong>Theater:</strong> <?php echo htmlspecialchars($row['theater_name']); ?>, <?php echo htmlspecialchars($row['city']); ?></div>
                                <div class="booking-detail"><strong>Date:</strong> <?php echo date('d M Y, g:i A', strtotime($row['show_time'])); ?></div>
                                <div class="booking-detail"><strong>Seats:</strong> <span style="color: #f5c542; font-weight: bold;"><?php echo htmlspecialchars($row['seat_numbers']); ?></span></div>
                                
                                <?php if (strtotime($row['show_time']) > time()): ?>
                                    <div class="ticket-status status-upcoming">Upcoming Show</div>
                                <?php else: ?>
                                    <div class="ticket-status status-completed">Show Completed</div>
                                <?php endif; ?>
                            </div>
                            
                            <a href="booking/confirmation.php?id=<?php echo $row['booking_id']; ?>" class="view-ticket-btn">View Digital Ticket</a>
                        </div>
                    </div>
                <?php endwhile; ?>

            </div>
        <?php else: ?>
            
            <div class="empty-state">
                <h2>No bookings found!</h2>
                <p>Looks like you haven't booked any movie tickets yet.</p>
                <a href="dashboard.php">Browse Movies →</a>
            </div>
            
        <?php endif; ?>
    </div>

</body>
</html>