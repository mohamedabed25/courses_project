<?php
// Include your database connection file
require_once 'connect.php';

// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to check age and redirect
function checkAgeAndRedirect($pdo, $user_id) {
    try {
        // Fetch the user's age from the database
        $stmt = $pdo->prepare("SELECT age FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $age = (int)$user['age'];

            // Check age and redirect accordingly
            if ($age > 10) {
                header("Location: saetion_home/normal.php");
                exit;
            } else {
                header("Location: saetion_home/childern.html");
                exit;
            }
        } else {
            echo '<p style="color: red;">User not found.</p>';
            exit;
        }
    } catch (PDOException $e) {
        die('Error fetching user data: ' . $e->getMessage());
    }
}

// Check if the Home link is clicked
if (isset($_GET['page']) && $_GET['page'] === 'home') {
    $user_id = $_SESSION['user_id'] ?? 1; // Replace with actual session-based user_id
    checkAgeAndRedirect($pdo, $user_id);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar with Age Check</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            right: 0;
            top: 0;
            background-color: #333;
            color: white;
            display: flex;
            flex-direction: column;
        }
        .sidebar h2 {
            text-align: center;
            margin-top: 0;
            padding: 15px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 15px;
            border-bottom: 1px solid #444;
        }
        .sidebar a:hover {
            background-color: #575757;
        }
        .content {
            margin-right: 250px; /* Adjust content margin for sidebar width */
            padding: 20px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Site Menu</h2>

        <a href="?page=home">Home</a>
        <a href="my_courses.php">my_courses</a>
        <a href="saetion_home/all_courses.php">Courses</a>
        
        <a href="profile.php">Profile</a>
        <a href="add_wallet.php">Add to Wallet</a>
        <a href="logout.php">Logout</a>
    </div>


</body>
</html>
