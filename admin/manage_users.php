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

$query = "SELECT id, full_name, email, role FROM users ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Manage Users | Admin</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <header class="header">
            <h1>User Management</h1>
        </header>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Full Name</th> <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result && mysqli_num_rows($result) > 0): ?>
                        <?php while($user = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($user['full_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <?php echo ($user['role'] == 1) ? 'Admin' : 'User'; ?>
                            </td>
                            <td class="action-btns">
                                <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="delete-link" onclick="return confirm('Remove this user?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="text-align:center;">No users found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>