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

// Fetch movies for the dropdown
$movies = mysqli_query($conn, "SELECT id, title FROM movies");

// ✨ VIVA NOTE: Added 'city' to this query so the admin knows which franchise location they are scheduling for!
$theaters = mysqli_query($conn, "SELECT id, name, location, city FROM theaters");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitizing inputs for security
    $movie_id = mysqli_real_escape_string($conn, $_POST['movie_id']);
    $theater_id = mysqli_real_escape_string($conn, $_POST['theater_id']);
    $show_time = mysqli_real_escape_string($conn, $_POST['show_time']);
    $screen_number = mysqli_real_escape_string($conn, $_POST['screen_number']);
    
    // NEW: Catch the dynamic pricing inputs
    $classic_price = mysqli_real_escape_string($conn, $_POST['classic_price']);
    $prime_price = mysqli_real_escape_string($conn, $_POST['prime_price']);
    $recliner_price = mysqli_real_escape_string($conn, $_POST['recliner_price']);

    // VIVA NOTE: Updated INSERT query to include the 3 new pricing columns
    $query = "INSERT INTO shows (movie_id, theater_id, show_time, screen_number, classic_price, prime_price, recliner_price) 
              VALUES ('$movie_id', '$theater_id', '$show_time', '$screen_number', '$classic_price', '$prime_price', '$recliner_price')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Show added successfully!'); window.location='manage_shows.php';</script>";
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
    <title>Add Show | SilverScreen Admin</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
    
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <header class="header">
            <h1>Add New Showtime</h1>
            <p class="header-subtitle">Schedule a movie for a specific theater</p>
        </header>

        <div class="form-card">
            <form action="" method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Select Movie</label>
                        <select name="movie_id" class="form-select" required>
                            <option value="">-- Select Movie --</option>
                            <?php while($m = mysqli_fetch_assoc($movies)): ?>
                                <option value="<?php echo $m['id']; ?>"><?php echo htmlspecialchars($m['title']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Select Theater</label>
                        <select name="theater_id" class="form-select" required>
                            <option value="">-- Select Theater --</option>
                            <?php while($t = mysqli_fetch_assoc($theaters)): ?>
                                <option value="<?php echo $t['id']; ?>">
                                    <?php echo htmlspecialchars($t['city'] ? $t['city'] . ' - ' : '') . htmlspecialchars($t['name']); ?> (<?php echo htmlspecialchars($t['location']); ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Show Date & Time</label>
                        <input type="datetime-local" name="show_time" class="form-select" required>
                    </div>

                    <div class="form-group">
                        <label>Screen Number</label>
                        <input type="number" name="screen_number" class="form-select" placeholder="e.g. 1" min="1" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Classic Price (₹)</label>
                        <input type="number" step="0.01" name="classic_price" class="form-select" value="120.00" min="0" required>
                    </div>

                    <div class="form-group">
                        <label>Prime Price (₹)</label>
                        <input type="number" step="0.01" name="prime_price" class="form-select" value="150.00" min="0" required>
                    </div>

                    <div class="form-group">
                        <label>Recliner Price (₹)</label>
                        <input type="number" step="0.01" name="recliner_price" class="form-select" value="250.00" min="0" required>
                    </div>
                </div>

                <button type="submit" class="submit-btn" style="margin-top: 20px;">Schedule Show</button>
            </form>
        </div>
    </main>
</body>
</html>