<?php
// Include the database connection file
require_once 'connect.php';

// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Assume user_id is stored in the session
$user_id = $_SESSION['user_id'] ?? 1; // Replace with actual session-based user_id if available

try {
    // Fetch courses the user is subscribed to
    $query = "SELECT c.id, c.title, c.description, c.instructor_name, c.course_price, c.photo 
              FROM subscriptions s
              JOIN courses c ON s.course_id = c.id
              WHERE s.user_id = ? AND s.status = 'active'";

    $stmt = $pdo->prepare($query);
    $stmt->execute([$user_id]);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error fetching courses: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .course-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .course-card img {
            width: 100%;
            max-width: 300px;
            height: auto;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .course-card h2 {
            margin: 10px 0;
        }
        .course-card p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <h1>My Courses</h1>

    <?php if (!empty($courses)): ?>
        <?php foreach ($courses as $course): ?>
            <div class="course-card">
                <?php if ($course['photo']): ?>
                    <img src="../admin/<?php echo htmlspecialchars($course['photo']); ?>" alt="Course Image">
                <?php endif; ?>
                <h2><?php echo htmlspecialchars($course['title']); ?></h2>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($course['description']); ?></p>
                <p><strong>Instructor:</strong> <?php echo htmlspecialchars($course['instructor_name']); ?></p>
                <p><strong>Price:</strong> $<?php echo htmlspecialchars($course['course_price']); ?></p>
                <a href="saetion_home/course_details.php?course_id=<?php echo $course['id']; ?>">View Course Details</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>You are not subscribed to any courses.</p>
    <?php endif; ?>
</body>
</html>
