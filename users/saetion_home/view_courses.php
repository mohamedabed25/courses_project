<?php
// Include the database connection file
require_once '../connect.php';

// Initialize variables
$courses = [];
$sub_track = null;

// Check if subtrack_id is provided
if (isset($_GET['subtrack_id']) && ctype_digit($_GET['subtrack_id'])) {
    $subtrack_id = (int) $_GET['subtrack_id'];

    try {
        // Fetch the sub-track details
        $stmt = $pdo->prepare("SELECT id, title, description FROM sub_tracks WHERE id = ?");
        $stmt->execute([$subtrack_id]);
        $sub_track = $stmt->fetch(PDO::FETCH_ASSOC);

        // Fetch the courses for the selected sub-track (with additional fields)
        $stmt = $pdo->prepare("SELECT id, title, description, instructor_name, is_free, course_price, photo FROM courses WHERE subtrack_id = ?");
        $stmt->execute([$subtrack_id]);
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        img {
            max-width: 200px;
            height: auto;
        }
        a {
            color: blue;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        hr {
            margin: 20px 0;
        }
    </style>
</head>
<body>


    <?php if (!empty($courses)): ?>
        <?php foreach ($courses as $course): ?>
            <a href="course_details.php?course_id=<?php echo $course['id']; ?>">
                <h2><?php echo htmlspecialchars($course['title']); ?></h2>
            </a>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($course['description']); ?></p>
            <p><strong>Instructor:</strong> <?php echo htmlspecialchars($course['instructor_name']); ?></p>

            <?php if ($course['is_free']): ?>
                <p><strong>Status:</strong> Free</p>
            <?php else: ?>
                <p><strong>Price:</strong> $<?php echo htmlspecialchars($course['course_price']); ?></p>
            <?php endif; ?>

            <?php if (!empty($course['photo'])): ?>
                <img src="<?php echo htmlspecialchars('../../admin/' . $course['photo']); ?>" alt="Course Photo">
            <?php else: ?>
                <p>No photo available.</p>
            <?php endif; ?>
            
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No courses available for this sub-track.</p>
    <?php endif; ?>

</body>
</html>
