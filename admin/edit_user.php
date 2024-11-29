<?php
// Ensure session is started (if not already started)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require 'connect.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html");
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
    <style>
        /* Basic reset */
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
        }

        /* Centering the content vertically and horizontally */
        .wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full viewport height */
            background-color: #e0f7fa; /* Light blue background */
        }

        /* Container for form */
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px; /* Max width for larger screens */
        }

        h2 {
            background-color: #00bcd4; /* Light blue background */
            color: #fff;
            padding: 15px;
            text-align: center;
            margin-top: 0;
            border-radius: 8px 8px 0 0;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .input-group {
            display: flex;
            flex-direction: column;
        }

        .input-group input {
            padding: 12px;
            border: 2px solid #007bb5; /* Blue border */
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .input-group input:focus {
            border-color: #00bcd4; /* Lighter blue on focus */
            outline: none;
        }

        input[type="submit"] {
            background-color: #007bb5; /* Blue button */
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            align-self: center; /* Center the button */
            margin-top: 20px; /* Add space above the button */
        }

        input[type="submit"]:hover {
            background-color: #00bcd4; /* Lighter blue on hover */
        }

        a {
            text-align: center;
            display: block;
            margin-top: 20px;
            color: #007bb5;
            text-decoration: none;
            font-size: 16px;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .input-group input {
                width: 100%; /* Full width on smaller screens */
            }

            input[type="submit"] {
                width: 100%; /* Full width on smaller screens */
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <h2>Edit User: <?php echo htmlspecialchars($user['username']); ?></h2>
            <form action="edit_user.php?id=<?php echo $user['id']; ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="update_user" value="1">

                <div class="input-group">
                    <p>Username:</p><input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                
                <div class="input-group">
                    <p>Email:</p><input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                
                <div class="input-group">
                    <p>Phone:</p><input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                </div>
                
                <div class="input-group">
                    <p>Password:</p><input type="password" name="password" value="<?php echo htmlspecialchars($user['password']); ?>" required>
                </div>

                <div class="input-group">
                    <p>Wallet:</p><input type="number" name="wallet" value="<?php echo htmlspecialchars($user['wallet']); ?>" required step="0.01">
                </div>

                <p><input type="submit" value="Update User"></p>
            </form>

            <a href="users_template.php">Back to Users</a>
        </div>
    </div>
</body>
</html>