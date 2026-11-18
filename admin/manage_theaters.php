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

// VIVA NOTE: Fetching all theaters (branches) to display in the admin table
$query = "SELECT * FROM theaters ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Manage Branches | SilverScreen Admin</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <header class="header">
            <h1>Cinema Branches</h1>
            <p style="color: #64748b;">Manage your franchise locations across different cities</p>
        </header>

        <div class="table-container">
            <div class="table-header">
                <h2>All Branches</h2>
                <a href="add_theater.php" class="add-btn">+ Add Branch</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Branch Name</th>
                        <th>City</th>
                        <th>Detailed Location</th>
                        <th>Total Screens</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($result) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                            
                            <td>
                                <?php 
                                    // If city is empty, show a warning badge, otherwise show the city
                                    if (!empty($row['city'])) {
                                        echo "🏙️ " . htmlspecialchars($row['city']);
                                    } else {
                                        echo "<span style='color: #ef4444; font-size: 12px;'>Not Set</span>";
                                    }
                                ?>
                            </td>
                            
                            <td>📍 <?php echo htmlspecialchars($row['location']); ?></td>
                            <td><?php echo isset($row['total_screens']) ? $row['total_screens'] : 'N/A'; ?></td>
                            <td class="action-btns">
                                <a href="delete_theater.php?id=<?php echo $row['id']; ?>" class="delete-link" onclick="return confirm('Delete this branch?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px; color: #94a3b8;">No branches found. Click '+ Add Branch' to create one.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>