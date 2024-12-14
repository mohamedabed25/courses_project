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
        // Check if the user already has an active subscription for this course
        $stmt = $pdo->prepare("SELECT * FROM subscriptions WHERE user_id = ? AND course_id = ? AND status = 'active'");
        $stmt->execute([$user_id, $course_id]);
        $existing_subscription = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing_subscription) {
            // User is already subscribed to this course
            echo '<p style="color: red;">You are already subscribed to this course.</p>';
        } else {
            // Add a new subscription
            $status = 'inactive'; // Default status for a subscription
            $stmt = $pdo->prepare("INSERT INTO subscriptions (course_id, user_id, status) VALUES (?, ?, ?)");
            $stmt->execute([$course_id, $user_id, $status]);

            echo '<p style="color: green;">ur request send succsefully the admin will contact u  </p>';
        }
    } catch (PDOException $e) {
        die('Error processing subscription: ' . $e->getMessage());
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
    <title>Subscribe</title>
</head>
<body>
    <a href="subtrack_courses.php">Back to Courses</a>
</body>
</html>
