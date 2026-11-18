<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/loginpage.html");
    exit();
}
require_once "../config/db.php";

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: ../dashboard.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$booking_id = intval($_GET['id']);

$query = "SELECT b.seat_numbers, b.total_price, 
                 m.title, m.poster, 
                 t.name as theater_name, t.city, 
                 s.show_time 
          FROM bookings b
          JOIN shows s ON b.show_id = s.id
          JOIN movies m ON s.movie_id = m.id
          JOIN theaters t ON s.theater_id = t.id
          WHERE b.id = $booking_id AND b.user_id = $user_id";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    die("<h2 style='color:white; text-align:center; margin-top:50px;'>Error: Ticket not found.</h2>");
}

$ticket = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed</title>
    <link rel="stylesheet" href="../assets/css/ticket_style.css">
</head>
<body>

    <div class="success-header">
        <h1>Payment Successful!</h1>
        <p>Your seats are confirmed. Please show this at the entrance.</p>
    </div>

    <div class="ticket-glass-box">
        
        <div class="ticket-poster" style="background-image: url('../assets/img/<?php echo htmlspecialchars($ticket['poster']); ?>');"></div>
        
        <div class="ticket-details">
            <h2><?php echo htmlspecialchars($ticket['title']); ?></h2>
            <div class="sub-brand">SilverScreen Official Ticket</div>

            <div class="info-grid">
                <div class="info-item">
                    <label>Theater</label>
                    <span><?php echo htmlspecialchars($ticket['theater_name']); ?></span>
                </div>
                <div class="info-item">
                    <label>City</label>
                    <span><?php echo htmlspecialchars($ticket['city']); ?></span>
                </div>
                <div class="info-item">
                    <label>Date & Time</label>
                    <span><?php echo date('d M Y, g:i A', strtotime($ticket['show_time'])); ?></span>
                </div>
                <div class="info-item">
                    <label>Seats</label>
                    <span class="highlight-text"><?php echo htmlspecialchars($ticket['seat_numbers']); ?></span>
                </div>
            </div>
            
            <div class="info-item">
                <label>Total Paid</label>
                <span class="highlight-text" style="font-size: 20px;">₹<?php echo number_format($ticket['total_price'], 2); ?></span>
            </div>
        </div>

        <div class="ticket-qr">
            <div class="qr-box">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=BKG-<?php echo $booking_id; ?>" alt="QR Code">
            </div>
            <div class="info-item" style="text-align: center;">
                <label>Booking ID</label>
                <span>#<?php echo str_pad($booking_id, 5, "0", STR_PAD_LEFT); ?></span>
            </div>
        </div>

    </div>

    <div class="action-buttons">
        <button onclick="window.print()" class="btn btn-primary">Print Ticket</button>
        <a href="../dashboard.php" class="btn btn-secondary">Go to Home</a>
    </div>

</body>
</html>