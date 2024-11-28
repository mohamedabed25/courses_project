<?php
// Ensure session is started
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html"); // Redirect to login page if not logged in
    exit();
}

// Include database connection
require 'connect.php'; 

// Enable error reporting for debugging (Remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle user creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_user'])) {
    // Sanitize user inputs
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Hash the password before storing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into the database
    $stmt = $pdo->prepare("INSERT INTO users (username, email, phone, password) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $email, $phone, $hashed_password]);

    // Redirect back to users page
    header("Location: users_template.php");
    exit();
}

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Delete user from the database
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$delete_id]);

    // Redirect back to users page
    header("Location: users_template.php");
    exit();
}

// Fetch users for display, including new fields
try {
    $stmt = $pdo->prepare("SELECT * FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching users: " . $e->getMessage();
    $users = []; // Ensure $users is always set
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?>!</h2>

    <!-- Form to Create New User -->
    <h3>Create New User</h3>
    <form action="manage_users.php" method="POST">
        <input type="hidden" name="create_user" value="1">
        <p>Username: <input type="text" name="username" required></p>
        <p>Email: <input type="email" name="email" required></p>
        <p>Phone: <input type="tel" name="phone" required></p>
        <p>Password: <input type="password" name="password" required></p>
        <p><input type="submit" value="Create User"></p>
    </form>

    <!-- Display Users Table -->
    <h3>Manage Users</h3>
    <?php if (!empty($users)): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Wallet Balance</th>
                    <th>Created At</th> <!-- Added Created At column -->
                    <th>Updated At</th> <!-- Added Updated At column -->
                    <th>Country</th> <!-- Added Country column -->
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                        <td><?php echo htmlspecialchars($user['wallet']); ?></td>
                        
                        <!-- Display Created At -->
                        <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                        
                        <!-- Display Updated At -->
                        <td><?php echo htmlspecialchars($user['updated_at']); ?></td>

                        <!-- Display Country -->
                        <td><?php echo htmlspecialchars($user['country']); ?></td>

                        <td>
                            <!-- Edit and Delete Actions -->
                            <a href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a> | 
                            <a href="users_template.php?delete_id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>

    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>
