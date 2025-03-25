<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');  // Change if you have a different user
define('DB_PASS', 'Olakunle');      // Add password if set
define('DB_NAME', 'hmisphp');  // Update with your actual database name

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
