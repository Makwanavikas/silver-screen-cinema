<?php
// START PHP LOGIC
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/loginpage.html");
    exit();
}

require_once "../config/db.php";

// Redirect to dashboard if show_id is missing
if (!isset($_GET['show_id'])) {
    header("Location: ../dashboard.php");
    exit;
}

$show_id = intval($_GET['show_id']);

// Fetch show, movie, and theater details (This grabs our new dynamic pricing columns!)
$query = "SELECT s.*, m.title, t.name as theater_name 
          FROM shows s
          JOIN movies m ON s.movie_id = m.id
          JOIN theaters t ON s.theater_id = t.id
          WHERE s.id = $show_id";

$result = mysqli_query($conn, $query);
$show = mysqli_fetch_assoc($result);

// Fetch already booked seats for this specific show
$booked_query = mysqli_query($conn, "SELECT seat_numbers FROM bookings WHERE show_id = $show_id");

$booked_seats = [];
while ($row = mysqli_fetch_assoc($booked_query)) {
    // Split comma-separated seats into an array
    $seats = explode(',', $row['seat_numbers']);
    $booked_seats = array_merge($booked_seats, $seats);
}

// ✨ VIVA NOTE: Replaced hardcoded numbers with the dynamic prices from the admin's database input!
$sections = [
    "CLASSIC" => [
        "rows" => ['A','B','C'],
        "cols" => 13,
        "price" => $show['classic_price'] 
    ],
    "PRIME" => [
        "rows" => ['D','E','F','G'],
        "cols" => 13,
        "price" => $show['prime_price']
    ],
    "RECLINER" => [
        "rows" => ['H'],
        "cols" => 8,
        "price" => $show['recliner_price']
    ]
];
// END PHP LOGIC
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Select Seats</title>
    <link rel="stylesheet" href="../assets/css/seat_booking_style.css">
</head>
<body>

<div class="header">
    <h2><?php echo htmlspecialchars($show['title']); ?></h2>
    <div class="sub-title">
        <?php echo htmlspecialchars($show['theater_name']); ?> | 
        <?php echo date('g:i A', strtotime($show['show_time'])); ?>
    </div>
</div>

<div class="screen">SCREEN</div>

<div class="theater-layout">

<?php foreach ($sections as $sectionName => $section): ?>

    <div class="section-title">
        <?php echo $sectionName . " (₹" . round($section['price']) . ")"; ?>
    </div>

    <?php foreach ($section['rows'] as $row): ?>
        <div class="seat-row">

            <div class="row-label"><?php echo $row; ?></div>

            <?php for ($num = $section['cols']; $num >= 1; $num--): ?>
                <?php
                // Generate Seat ID (e.g., A1, A2)
                $seat_id = $row . $num;
                // Check if seat exists in the booked_seats array
                $is_taken = in_array($seat_id, $booked_seats) ? 'occupied' : '';
                ?>
                <div class="seat <?php echo $is_taken; ?>"
                     data-seat="<?php echo $seat_id; ?>"
                     data-price="<?php echo $section['price']; ?>">
                    <?php echo $num; ?>
                </div>
            <?php endfor; ?>

            <div class="row-label"><?php echo $row; ?></div>

        </div>
    <?php endforeach; ?>

<?php endforeach; ?>

</div>

<form action="payment.php" method="POST" class="booking-bar">
    <input type="hidden" name="show_id" value="<?php echo $show_id; ?>">
    <input type="hidden" name="selected_seats" id="selected_seats_input">
    <input type="hidden" name="total_price" id="total_price_input">

    <div class="total-text">
        Total: ₹<span id="price_display">0</span>
    </div>

    <button type="submit" class="pay-btn" id="checkoutBtn" disabled>
        Book Tickets
    </button>
</form>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Select all seats that are not already occupied
    const seats = document.querySelectorAll('.seat:not(.occupied)');
    const selectedInput = document.getElementById('selected_seats_input');
    const priceInput = document.getElementById('total_price_input');
    const priceDisplay = document.getElementById('price_display');
    const checkoutBtn = document.getElementById('checkoutBtn');

    let selectedSeats = [];
    let totalPrice = 0;

    seats.forEach(seat => {
        seat.addEventListener('click', () => {

            // Toggle visual selection
            seat.classList.toggle('selected');

            const seatId = seat.getAttribute('data-seat');
            const seatPrice = parseFloat(seat.getAttribute('data-price'));

            // Logic to add or remove seat from the cart
            if (selectedSeats.find(s => s.id === seatId)) {
                selectedSeats = selectedSeats.filter(s => s.id !== seatId);
                totalPrice -= seatPrice;
            } else {
                selectedSeats.push({id: seatId, price: seatPrice});
                totalPrice += seatPrice;
            }

            updateUI();
        });
    });

    // Update the bottom bar with current selections
    function updateUI() {
        priceDisplay.innerText = totalPrice;
        selectedInput.value = selectedSeats.map(s => s.id).join(',');
        priceInput.value = totalPrice;
        // Enable button only if at least one seat is selected
        checkoutBtn.disabled = selectedSeats.length === 0;
    }
});
</script>
</body>
</html>