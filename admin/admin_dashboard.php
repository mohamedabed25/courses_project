<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html"); // Redirect to login page if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #e0f7fa; /* Light blue background */
            background-image: 
                radial-gradient(circle at top right, rgba(0, 123, 191, 0.7) 30%, transparent 30%), /* Lighter dark blue */
                radial-gradient(circle at bottom left, rgba(0, 56, 94, 0.7) 30%, transparent 30%);
            background-repeat: no-repeat;
            background-size: 400px 400px; /* Increased the size of the circles */
            background-position: top right, bottom left;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        h1 {
            font-size: 36px;
            background-color: #007bb5; /* Deep Blue */
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: -60px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h2 {
            background-color: #00bcd4; /* Light blue */
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 0;
        }

        input[type="submit"] {
            background-color: #007bb5;
            color: white;
            padding: 14px 30px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 18px;
            width: 100%;
            margin: 10px 0;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #00bcd4;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: #007bb5;
            text-decoration: none;
        }

        .form-buttons {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?>!</h2>

        <form action="users_template.php" method="get">
            <input type="submit" value="Go to Users" />
        </form>

        <form action="visa_cards.php" method="get">
            <input type="submit" value="Go to Visas" />
        </form>

        <form action="courses.php" method="get">
            <input type="submit" value="Go to Courses" />
        </form>

        <a href="logout.php">Logout</a>
    </div>
</body>
</html>