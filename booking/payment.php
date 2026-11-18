<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/loginpage.html");
    exit();
}

// Redirect back if someone tries to open this page directly
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../dashboard.php");
    exit;
}

// Safely catch the data passed directly from select_seats.php
$show_id = intval($_POST['show_id']);
$selected_seats = htmlspecialchars($_POST['selected_seats']);
$total_price = floatval($_POST['total_price']); // We only need the total price now!
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Checkout | SilverScreen</title>
    <link rel="stylesheet" href="../assets/css/checkout.css">
</head>
<body>

<div class="checkout-wrapper">
    
    <div class="order-summary">
        <h2>🧾 Order Summary</h2>
        
        <div class="summary-row">
            <span>Selected Seats</span>
            <span style="font-weight: 600; color: #fff;"><?php echo $selected_seats; ?></span>
        </div>
        
        <div class="summary-row">
            <span>Tickets Amount</span>
            <span>₹<?php echo number_format($total_price, 2); ?></span>
        </div>
        
        <div class="summary-row">
            <span>Convenience Fee</span>
            <span>₹0.00</span> 
        </div>
        
        <div class="summary-total">
            <span>Amount Payable</span>
            <span style="color: #facc15;">₹<?php echo number_format($total_price, 2); ?></span>
        </div>
    </div>

    <div class="payment-form-container">
        <h2>💳 Payment Details</h2>
        
        <form action="process_payment.php" method="POST">
            
            <input type="hidden" name="show_id" value="<?php echo $show_id; ?>">
            <input type="hidden" name="seat_numbers" value="<?php echo $selected_seats; ?>">
            <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">

            <div class="input-group">
                <label>Cardholder Name</label>
                <input type="text" placeholder="e.g. Makwana Vikaskumar" required autocomplete="off">
            </div>

            <div class="input-group">
                <label>Card Number</label>
                <input type="text" id="cardNumber" placeholder="0000 0000 0000 0000" required maxlength="19" autocomplete="off">
            </div>

            <div class="row">
                <div class="input-group">
                    <label>Expiry Date (MM/YY)</label>
                    <input type="text" id="expiryDate" placeholder="12/28" required maxlength="5" autocomplete="off">
                </div>
                <div class="input-group">
                    <label>CVV</label>
                    <input type="password" placeholder="***" required maxlength="3" autocomplete="off">
                </div>
            </div>

            <button type="submit" class="pay-btn">Pay ₹<?php echo number_format($total_price, 2); ?> Securely</button>
            
            <div class="secure-notice">
                🔒 Protected by SilverScreen 256-bit SSL Encryption
            </div>
        </form>
    </div>

</div>

<script>
    // Grab the card number input field
    const cardNumberInput = document.getElementById('cardNumber');

    // Listen for every single keystroke the user types
    cardNumberInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, ''); 
        if (value.length > 16) {
            value = value.substring(0, 16);
        }
        let formattedValue = value.replace(/(\d{4})(?=\d)/g, '$1 ');
        e.target.value = formattedValue;
    });

    // Grab the expiry date input field
    const expiryDateInput = document.getElementById('expiryDate');

    // Listen for keystrokes on the expiry field
    expiryDateInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, ''); 
        if (value.length > 4) {
            value = value.substring(0, 4);
        }
        if (value.length > 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        e.target.value = value;
    });
</script>
</body>
</html>