<?php
// Ensure session is started (if not already started)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require 'connect.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch user details for editing
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo "User not found.";
            exit();
        }
    } catch (PDOException $e) {
        echo "Error fetching user: " . $e->getMessage();
        exit();
    }
} else {
    echo "Invalid user ID.";
    exit();
}

// Handle user update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $password = htmlspecialchars(trim($_POST['password']));
    $wallet = htmlspecialchars(trim($_POST['wallet']));

    // Handle file upload for photo
    // if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    //     $photo_tmp = $_FILES['photo']['tmp_name'];
    //     $photo_name = basename($_FILES['photo']['name']);
    //     $photo_path = "uploads/" . $photo_name;

    //     // Move the uploaded photo to the uploads folder
    //     move_uploaded_file($photo_tmp, $photo_path);
    // } else {
    //     // Use existing photo if no new one is uploaded
    //     $photo_path = $user['photo'];
    // }

    // Update user in the database
    try {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, phone = ?, password = ?, wallet = ? WHERE id = ?");
        $stmt->execute([$username, $email, $phone, $password, $wallet, $user_id]);

        // Redirect back to users page after update
        header("Location: users_template.php");
        exit();
    } catch (PDOException $e) {
        echo "Error updating user: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>
<body>
    <h2>Edit User: <?php echo htmlspecialchars($user['username']); ?></h2>
    <form action="edit_user.php?id=<?php echo $user['id']; ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="update_user" value="1">

        <p>Username: <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required></p>
        <p>Email: <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required></p>
        <p>Phone: <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required></p>
        <p>Password: <input type="password" name="password" value="<?php echo htmlspecialchars($user['password']); ?>" required></p>

        <p>wallet: <input type="number" name="wallet" value="<?php echo htmlspecialchars($user['wallet']); ?>" required step="0.01"></p>


        <p><input type="submit" value="Update User"></p>
    </form>

    <a href="users_template.php">Back to Users</a>
</body>
</html>
