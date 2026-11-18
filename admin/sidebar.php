<?php
// Get the current page name to highlight the active menu item
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside class="sidebar">
    <div class="admin-logo">SilverScreen Admin</div>
    <ul class="menu-links">
        <li>
            <a href="dashboard.php" class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                📊 Dashboard
            </a>
        </li>
        
        <li>
            <a href="manage_movies.php" class="<?php echo ($current_page == 'manage_movies.php') ? 'active' : ''; ?>">
                🎬 Manage Movies
            </a>
        </li>
        
        <li>
            <a href="add_trailer.php" class="<?php echo ($current_page == 'add_trailer.php') ? 'active' : ''; ?>">
                🍿 Manage Trailers
            </a>
        </li>

        <li>
            <a href="manage_theaters.php" class="<?php echo ($current_page == 'manage_theaters.php') ? 'active' : ''; ?>">
                🏢 Branches
            </a>
        </li>
        <li>
            <a href="manage_shows.php" class="<?php echo ($current_page == 'manage_shows.php') ? 'active' : ''; ?>">
                📅 Shows
            </a>
        </li>
        <li>
            <a href="manage_users.php" class="<?php echo ($current_page == 'manage_users.php') ? 'active' : ''; ?>">
                👤 Users
            </a>
        </li>
        
        <li class="website-link-container">
            <a href="../dashboard.php" class="website-link">
                🌐 View Website ↗
            </a>
        </li>
    </ul>
    <a href="../auth/logout.php" class="logout-btn">Logout →</a>
</aside>