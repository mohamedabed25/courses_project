<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html"); // Redirect to login page if not logged in
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    
    <!-- Display user profile information -->
    <form method="POST" action="update_profile.php" enctype="multipart/form-data">
        <p><strong>Username:</strong> <input type="text" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" /></p>
        <p><strong>Age:</strong> <input type="number" name="age" value="<?php echo htmlspecialchars($_SESSION['age'] ?? ''); ?>" /></p>
        <p><strong>Email:</strong> <input type="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>" /></p>
        <p><strong>Phone:</strong> <input type="tel" name="phone" value="<?php echo htmlspecialchars($_SESSION['phone'] ?? ''); ?>" /></p>
        <p><strong>Country:</strong> <input type="text" name="country" value="<?php echo htmlspecialchars($_SESSION['country'] ?? ''); ?>" /></p>
        
        <!-- File input for photo upload -->
        <p><strong>Profile Photo:</strong> 
            <input type="file" name="photo" accept="image/*" />
            <?php if (!empty($_SESSION['photo'])): ?>
                <br><img src="<?php echo htmlspecialchars($_SESSION['photo']); ?>" alt="User Photo" style="width: 150px; height: auto;">
            <?php endif; ?>
        </p>

        <input type="submit" value="Update Profile" />
    </form>

    <hr>

    <!-- Button to go to Add Wallet page -->
    <form action="add_wallet.php" method="get">
        <input type="submit" value="Add Money to Wallet" />
    </form>

    <a href="logout.php">Logout</a>
</body>
</html>
