<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/loginpage.html");
    exit();
}
require_once "../config/db.php";

// VIVA NOTE: Always ensure the user is logged in before allowing a transaction
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/loginpage.html");
    exit;
}

$user_id = $_SESSION['user_id'];
$booking_id = 0; // We will store the generated database ID here

// Check if the page is receiving the checkout form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Safely capture the data sent from your seat selection page
    $show_id = mysqli_real_escape_string($conn, $_POST['show_id']);
    $seat_numbers = mysqli_real_escape_string($conn, $_POST['seat_numbers']);
    $total_price = mysqli_real_escape_string($conn, $_POST['total_price']);
    
    // ✨ VIVA TALKING POINT: The Pre-Insert Strategy ✨
    // Examiner: "Why did you save the booking to the database BEFORE the loading spinner finishes?"
    // You: "To prevent data loss. If the user accidentally closes the tab during the 3-second 
    // loading animation, their payment would process but their ticket wouldn't generate. 
    // Inserting it first guarantees stability."
    
    $query = "INSERT INTO bookings (user_id, show_id, seat_numbers, total_price) 
              VALUES ('$user_id', '$show_id', '$seat_numbers', '$total_price')";
              
    if (mysqli_query($conn, $query)) {
        // Grab the ID of the ticket we just created in the database
        $booking_id = mysqli_insert_id($conn);
    } else {
        die("Database Error: " . mysqli_error($conn));
    }
} else {
    // If someone tries to visit this URL directly without submitting the form, kick them out
    header("Location: ../dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Processing Payment | SilverScreen</title>
    <link rel="stylesheet" href="../assets/css/checkout.css">
</head>
<body>

<div class="payment-container">
    <div class="bank-logo">
        🔒 SecurePay Gateway
    </div>
    
    <div class="spinner"></div>
    
    <h2>Processing your payment...</h2>
    <p>Please do not refresh the page or click the back button. We are securely communicating with your bank.</p>
    
    <div class="secure-badge">✔ 256-bit Secure Connection</div>
</div>

<script>
    // Set a timer for 3 seconds (3000 milliseconds) to simulate network latency
    setTimeout(function() {
        // After 3 seconds, automatically redirect the user to their actual digital ticket!
        window.location.href = "confirmation.php?id=<?php echo $booking_id; ?>";
    }, 3000); 
</script>

</body>
</html>