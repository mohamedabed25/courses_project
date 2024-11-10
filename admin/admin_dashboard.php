<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?>!</h2>

    <!-- Go to Users Button -->
    <form action="users_template.php" method="get">
        <input type="submit" value="Go to User" />
    </form>

    <form action="visa_cards.php" method="get">
        <input type="submit" value="Go to visas" />
    </form>
    <!-- Logout Button -->
    <a href="logout.php">Logout</a>
</body>
</html>
