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

$message = "";

// Convert YouTube link to embed URL
function extractYoutubeEmbedUrl($url) {
    $video_id = "";
    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
        $video_id = $match[1];
    }
    
    if (!empty($video_id)) {
        return "https://www.youtube.com/embed/" . $video_id;
    }
    return $url; 
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_trailer'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $raw_url = mysqli_real_escape_string($conn, $_POST['youtube_url']);
    
    $clean_embed_url = extractYoutubeEmbedUrl($raw_url);
    
    $thumbnail = $_FILES['thumbnail']['name'];
    $target_dir = "../assets/img/";
    $target_file = $target_dir . basename($thumbnail);
    
    if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $target_file)) {
        $query = "INSERT INTO trailers (title, youtube_url, thumbnail) VALUES ('$title', '$clean_embed_url', '$thumbnail')";
        if (mysqli_query($conn, $query)) {
            $message = "<div class='alert success'>✅ Trailer added successfully!</div>";
        } else {
            $message = "<div class='alert error'>❌ Database Error: " . mysqli_error($conn) . "</div>";
        }
    } else {
        $message = "<div class='alert error'>❌ Failed to upload image.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Manage Trailers | Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
    <link rel="stylesheet" href="../assets/css/add_trailer_style.css">
</head>
<body class="admin-layout">

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <div class="content-header">
            <h1>Manage Trailers</h1>
            <span class="admin-badge">Admin Control Panel</span>
        </div>

        <div class="trailer-form-container">
            <div class="form-header">
                <h2>🎬 Add New "Coming Soon" Trailer</h2>
                <p>Upload promotional content to the homepage slider</p>
            </div>

            <?php echo $message; ?>

            <form action="add_trailer.php" method="POST" enctype="multipart/form-data">
                <div class="input-group">
                    <label>Trailer Title</label>
                    <input type="text" name="title" placeholder="e.g., Pushpa 2 Teaser" required autocomplete="off">
                </div>

                <div class="input-group">
                    <label>YouTube Link</label>
                    <input type="url" name="youtube_url" placeholder="Paste standard YouTube link here..." required autocomplete="off">
                </div>

                <div class="input-group">
                    <label>Thumbnail Image</label>
                    <input type="file" name="thumbnail" accept="image/png, image/jpeg, image/jpg" required class="file-input">
                </div>

                <button type="submit" name="add_trailer" class="submit-btn">+ Upload Trailer</button>
            </form>
        </div>
    </main>

</body>
</html>