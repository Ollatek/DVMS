<?php
// Start session only if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function check_login()
{
    // Check the correct session key
    if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
        // Redirect to login page if user is not logged in
        $host = $_SERVER['HTTP_HOST'];
        $uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = "user_login.php"; // Redirect to login page
        header("Location: http://$host$uri/$extra");
        exit();
    }
}
?>
