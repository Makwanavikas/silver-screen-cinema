<?php
$host = "host_name";
$user = "user_name";
$pass = "password";
$db   = "database_name";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>