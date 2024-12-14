<?php
// Include the database connection file
require_once '../connect.php';

// Check if the session is already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Assume user_id is stored in the session
$user_id = $_SESSION['user_id'] ?? 1; // Replace with actual session-based user_id if available

// Check if course_id is provided in the URL
if (isset($_GET['course_id']) && ctype_digit($_GET['course_id'])) {
    $course_id = (int)$_GET['course_id'];

    try {
        // Fetch the course price
        $stmt = $pdo->prepare("SELECT course_price FROM courses WHERE id = ?");
        $stmt->execute([$course_id]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$course) {
            echo '<p style="color: red;">Course not found.</p>';
            exit;
        }

        $course_price = $course['course_price'];

        // Check if the user already has an active subscription for this course
        $stmt = $pdo->prepare("SELECT * FROM subscriptions WHERE user_id = ? AND course_id = ? AND status = 'active'");
        $stmt->execute([$user_id, $course_id]);
        $existing_subscription = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing_subscription) {
            // User is already subscribed to this course
            echo '<p style="color: red;">You are already subscribed to this course.</p>';
        } else {
            // Check user's wallet balance
            $stmt = $pdo->prepare("SELECT wallet FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                echo '<p style="color: red;">User not found.</p>';
                exit;
            }

            $wallet_balance = $user['wallet'];

            if ($wallet_balance >= $course_price) {
                // Deduct the course price from the user's wallet
                $stmt = $pdo->prepare("UPDATE users SET wallet = wallet - ? WHERE id = ?");
                $stmt->execute([$course_price, $user_id]);

                // Add a new subscription
                $status = 'active'; // Default status for a subscription
                $stmt = $pdo->prepare("INSERT INTO subscriptions (course_id, user_id, status) VALUES (?, ?, ?)");
                $stmt->execute([$course_id, $user_id, $status]);

                echo '<p style="color: green;">Subscription successful! Course price has been deducted from your wallet.</p>';
            } else {
                // Insufficient wallet balance
                echo '<p style="color: red;">Insufficient wallet balance. Please top up your wallet.</p>';
            }
        }
    } catch (PDOException $e) {
        die('Error processing payment: ' . $e->getMessage());
    }
} else {
    echo '<p style="color: red;">Invalid course ID.</p>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay for Course</title>
</head>
<body>
    <a href="subtrack_courses.php">Back to Courses</a>
</body>
</html>
