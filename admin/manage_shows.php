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

// VIVA NOTE: This complex query now also fetches the 3 dynamic seat prices (classic, prime, recliner)
// so the admin can verify the pricing set for each specific showtime.
$query = "SELECT s.id, s.show_time, s.screen_number, 
                 s.classic_price, s.prime_price, s.recliner_price,
                 m.title AS movie_title, 
                 t.name AS theater_name, t.location 
          FROM shows s
          JOIN movies m ON s.movie_id = m.id
          JOIN theaters t ON s.theater_id = t.id
          ORDER BY s.show_time DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Manage Shows | SilverScreen Admin</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <header class="header">
            <h1>Movie Showtimes</h1>
            <p class="text-muted">Manage scheduled shows across all theaters</p>
        </header>

        <div class="table-container">
            <div class="table-header">
                <h2>All Scheduled Shows</h2>
                <a href="add_show.php" class="add-btn">+ Schedule Show</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Movie</th>
                        <th>Theater</th>
                        <th>Date & Time</th>
                        <th>Screen</th>
                        <th>Pricing (₹)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($result) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($row['movie_title']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['theater_name']); ?><br>
                                <small class="text-muted">📍 <?php echo htmlspecialchars($row['location']); ?></small>
                            </td>
                            <td>
                                <?php echo date('d M Y, g:i A', strtotime($row['show_time'])); ?>
                            </td>
                            <td>Screen <?php echo $row['screen_number']; ?></td>
                            
                            <td class="price-cell">
                                <span class="price-badge">Classic: ₹<?php echo htmlspecialchars($row['classic_price']); ?></span><br>
                                <span class="price-badge">Prime: ₹<?php echo htmlspecialchars($row['prime_price']); ?></span><br>
                                <span class="price-badge">Recliner: ₹<?php echo htmlspecialchars($row['recliner_price']); ?></span>
                            </td>
                            
                            <td class="action-btns">
                                <a href="delete_show.php?id=<?php echo $row['id']; ?>" class="delete-link" onclick="return confirm('Delete this showtime?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="empty-state">No shows scheduled yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>