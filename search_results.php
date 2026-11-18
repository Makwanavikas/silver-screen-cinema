<?php
session_start();
require_once "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: views/loginpage.html");
    exit;
}

$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Results | SilverScreen</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/homepage.css">
</head>
<body>

  <nav class="navbar" style="margin-top: 20px;">
    <div class="logo">SilverScreen Cinema</div>
    <div class="nav-actions">
       <button class="login-btn" onclick="location.href='dashboard.php'">← Back to Home</button>
    </div>
  </nav>

  <section class="now-showing" style="min-height: 80vh;">
    <div class="section-header">
      <h2>Search Results for: "<?php echo htmlspecialchars($searchTerm); ?>"</h2>
    </div>

    <div class="movie-grid">
      <?php
      if (!empty($searchTerm)) {
          $query = "SELECT * FROM movies WHERE title LIKE '%$searchTerm%' ORDER BY id DESC";
          $result = mysqli_query($conn, $query);

          if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                  ?>
                  <a href="movie_details.php?id=<?php echo $row['id']; ?>">
                      <div class="movie-card">
                        <img src="assets/img/<?php echo htmlspecialchars($row['poster']); ?>" alt="Poster">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p><?php echo htmlspecialchars($row['genre']); ?> • <?php echo $row['release_year']; ?></p>
                        <div class="card-footer">
                          <span>⭐ <?php echo $row['rating']; ?></span>
                          <button>Book Now</button>
                        </div>
                      </div>
                  </a>
                  <?php
              }
          } else {
              ?>
              <div style="grid-column: 1/-1; text-align: center; padding: 50px; background: rgba(255,255,255,0.02); border-radius: 20px; border: 1px dashed rgba(255,255,255,0.1);">
                  <h3 style="color: #ff3d68; margin-bottom: 15px;">Movie Not Found</h3>
                  <p style="color: #9ca3af; line-height: 1.6;">
                    We couldn't find any movie matching "<strong><?php echo htmlspecialchars($searchTerm); ?></strong>".<br>
                    <strong>Try these tips:</strong><br>
                    1. Check for spelling errors.<br>
                    2. Use broader keywords (e.g., "Star" instead of "Star Wars").<br>
                    3. Search by genre instead.<br><br>
                    <a href="dashboard.php" style="color: #ffd86b; text-decoration: none;">View all movies here →</a>
                  </p>
              </div>
              <?php
          }
      } else {
          echo "<p style='grid-column: 1/-1; text-align: center;'>Please enter a search term.</p>";
      }
      ?>
    </div>
  </section>

  <footer class="footer">
    <div class="footer-bottom">© 2026 SilverScreen Cinema. All Rights Reserved.</div>
  </footer>

</body>
</html>