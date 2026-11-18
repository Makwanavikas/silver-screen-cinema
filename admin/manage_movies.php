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

$result = mysqli_query($conn, "SELECT * FROM movies ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Manage Movies | Admin</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <header class="header">
            <h1>Movie Library</h1>
            <div class="user-pill">Admin Control Panel</div>
        </header>

        <div class="table-container">
            <div class="table-header">
                <h2>All Movies</h2>
                <a href="add_movie.php" class="add-btn">+ Add Movie</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Poster</th>
                        <th>Title</th>
                        <th>Genre</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><img src="../assets/img/<?php echo $row['poster']; ?>" class="movie-thumb"></td>
                        <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['genre']); ?></td>
                        <td class="action-btns">
                            <a href="edit_movie.php?id=<?php echo $row['id']; ?>" class="edit-link">Edit</a>
                            <a href="delete_movie.php?id=<?php echo $row['id']; ?>" class="delete-link" onclick="return confirm('Delete this movie?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>