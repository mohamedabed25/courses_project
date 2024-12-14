<?php
// Include the database connection file
require_once '../connect.php';

// Initialize variables
$courses = [];
$sub_track = null;
$user_id = 49; // Replace with the actual user ID, possibly from session

// Check if subtrack_id is provided
if (isset($_GET['subtrack_id']) && ctype_digit($_GET['subtrack_id'])) {
    $subtrack_id = (int)$_GET['subtrack_id'];

    try {
        // Fetch the sub-track details
        $stmt = $pdo->prepare("SELECT id, title, description FROM sub_tracks WHERE id = ?");
        $stmt->execute([$subtrack_id]);
        $sub_track = $stmt->fetch(PDO::FETCH_ASSOC);

        // Fetch the courses for the selected sub-track
        $stmt = $pdo->prepare("SELECT id, title, description, instructor_name, is_free, course_price, photo FROM courses WHERE subtrack_id = ?");
        $stmt->execute([$subtrack_id]);
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch the active subscriptions for the current user
        $stmt_sub = $pdo->prepare("SELECT course_id FROM subscriptions WHERE user_id = ? AND status = 'active'");
        $stmt_sub->execute([$user_id]);
        $active_subscriptions = $stmt_sub->fetchAll(PDO::FETCH_COLUMN); // Fetch as an array of course IDs
    } catch (PDOException $e) {
        die('Error fetching data: ' . $e->getMessage());
    }
} else {
    die('Invalid sub-track ID.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Courses</title>
</head>
<body>

    <?php if (!empty($courses)): ?>
        <?php foreach ($courses as $course): ?>
            <div class="course-card">
                <?php if ($course['photo']): ?>
                    <img src="../../admin/<?php echo htmlspecialchars($course['photo']); ?>" alt="Course Image" width="200">
                <?php endif; ?>

                <!-- Check if the user has an active subscription for the course -->
                <?php $course_subscribed = in_array($course['id'], $active_subscriptions); ?>

                <!-- Display course title -->
                <?php if ($course_subscribed): ?>
                    <h2><a href="course_details.php?course_id=<?php echo $course['id']; ?>"><?php echo htmlspecialchars($course['title']); ?></a></h2>
                <?php else: ?>
                    <h2><?php echo htmlspecialchars($course['title']); ?> (Not Subscribed)</h2>
                <?php endif; ?>

                <p><strong>Description:</strong> <?php echo htmlspecialchars($course['description']); ?></p>
                <p><strong>Instructor:</strong> <?php echo htmlspecialchars($course['instructor_name']); ?></p>

                <!-- Display subscription or payment links based on course subscription and status -->
                <?php if (!$course_subscribed): ?>
                    <?php if ($course['is_free']): ?>
                        <p><strong>Status:</strong> Free</p>
                        <a href="subscribe.php?course_id=<?php echo $course['id']; ?>">Subscribe</a>
                    <?php else: ?>
                        <p><strong>Status:</strong> Paid</p>
                        <a href="pay_for_course.php?course_id=<?php echo $course['id']; ?>">Pay</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No courses found in this sub-track.</p>
    <?php endif; ?>

</body>
</html>
