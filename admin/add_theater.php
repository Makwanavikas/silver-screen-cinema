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

// Array of your franchise cities
$franchise_cities = ['Mumbai', 'Delhi', 'Vadodara', 'Surat', 'Ahmedabad', 'Pune', 'Nadiad'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize user inputs to prevent SQL Injection
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $city = mysqli_real_escape_string($conn, $_POST['city']); // Fetch the city
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $screens = (int) $_POST['total_screens'];

    // Updated query to insert the city into the database
    $query = "INSERT INTO theaters (name, city, location, total_screens) 
              VALUES ('$name', '$city', '$location', '$screens')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Branch added successfully!'); window.location='manage_theaters.php';</script>";
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
    <title>Add Branch | SilverScreen Admin</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <header class="header">
            <h1>Add New Cinema Branch</h1>
        </header>

        <div class="form-card">
            <form action="" method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Branch Name</label>
                        <input type="text" name="name" placeholder="e.g. SilverScreen VR Mall" required>
                    </div>

                    <div class="form-group">
                        <label>City</label>
                        <select name="city" required class="form-select">
                            <option value="" disabled selected>-- Select City --</option>
                            <?php foreach($franchise_cities as $c): ?>
                                <option value="<?php echo htmlspecialchars($c); ?>"><?php echo htmlspecialchars($c); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Detailed Location / Area</label>
                        <input type="text" name="location" placeholder="e.g. College Road, Nadiad" required>
                    </div>

                    <div class="form-group">
                        <label>Total Screens</label>
                        <input type="number" name="total_screens" placeholder="e.g. 3" min="1" required>
                    </div>
                </div>

                <button type="submit" class="submit-btn">Save Branch</button>
            </form>
        </div>
    </main>
</body>
</html>