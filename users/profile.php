<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html"); // Redirect to login page if not logged in
    exit;
}

// Include the database connection
require_once 'connect.php';

// Fetch user details and photo from the database
$stmt = $pdo->prepare("SELECT username, email, phone, country, wallet, photo FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Check if the user exists in the database
if ($user) {
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['phone'] = $user['phone'];
    $_SESSION['country'] = $user['country'];
    $_SESSION['wallet'] = $user['wallet']; // Store wallet balance in session
    $_SESSION['photo'] = $user['photo']; // Store photo path in session
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
        <p><strong>Email:</strong> <input type="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" /></p>
        <p><strong>Phone:</strong> <input type="tel" name="phone" value="<?php echo htmlspecialchars($_SESSION['phone']); ?>" /></p>
        <p><strong>Country:</strong> <input type="text" name="country" value="<?php echo htmlspecialchars($_SESSION['country']); ?>" /></p>

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

    <!-- Display wallet balance -->
    <p><strong>Wallet Balance:</strong> <?php echo htmlspecialchars(number_format($_SESSION['wallet'], 2)); ?> EGP</p>

    <!-- Button to go to Add Wallet page -->
    <form action="add_wallet.php" method="get">
        <input type="submit" value="Add Money to Wallet" />
    </form>

    <a href="logout.php">Logout</a>
</body>
</html>
