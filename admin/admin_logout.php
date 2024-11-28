<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html"); // Redirect to login page if not logged in
    exit();
}

echo "Welcome, " . htmlspecialchars($_SESSION['email']) . "!<br>";
echo "<a href='logout.php'>Logout</a>";
?>
