<?php
session_start();
require 'connect.php'; // Include the database connection file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Query to find the admin user by email
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the user exists and the password is correct
    if ($user && $password === $user['password']) {
        // Store user information in session
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];

        // Redirect to admin dashboard
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Invalid email or password.";
    }
}
?>
