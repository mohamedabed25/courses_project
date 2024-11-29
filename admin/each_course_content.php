<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html"); // Redirect to login if not logged in
    exit;
}

include('../users/connect.php'); // Assuming this file contains your PDO connection

// Check if course_id is passed in the URL
if (!isset($_GET['id'])) {
    echo "Course ID is missing.";
    exit;
}

$course_id = $_GET['id']; // Store course ID from URL

// Fetch course content based on course_id, including created_at, updated_at, order, and content type
$sql = "SELECT id, title, content_type, content_value, video_file_path, pdf_file_path, url, quiz_id, `order`, created_at, updated_at
        FROM course_contents
        WHERE course_id = :course_id
        ORDER BY `order` ASC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':course_id', $course_id);
$stmt->execute();
$course_contents = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($course_contents)) {
    echo "No content found for this course.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Content</title>
    <style>
        .content-item { margin-bottom: 20px; }
        .content-title { font-weight: bold; }
        .content-body { margin-top: 10px; }
        .content-meta { font-size: 0.9em; color: #777; }
    </style>
</head>
<body>

<h1>Course Content</h1>

<?php foreach ($course_contents as $content): ?>
    <div class="content-item">
        <div class="content-title"><?php echo htmlspecialchars($content['title']); ?></div>

        <!-- Meta information for created_at, updated_at, and order -->
        <div class="content-meta">
            <p>Order: <?php echo $content['order']; ?></p>
            <p>Created At: <?php echo htmlspecialchars($content['created_at']); ?></p>
            <p>Updated At: <?php echo htmlspecialchars($content['updated_at']); ?></p>
        </div>
        
        <div class="content-body">
            <?php 
            switch ($content['content_type']) {
                case 'text':
                    echo "<p>Content Value: " . nl2br(htmlspecialchars($content['content_value'])) . "</p>";  // Display text content
                    break;
                case 'video':
                    echo "<video controls><source src='" . htmlspecialchars($content['video_file_path']) . "' type='video/mp4'>Your browser does not support the video tag.</video>";
                    break;
                case 'pdf':
                    echo "<a href='" . htmlspecialchars($content['pdf_file_path']) . "' target='_blank'>Download PDF</a>";
                    break;
                case 'url':
                    echo "<a href='" . htmlspecialchars($content['url']) . "' target='_blank'>Visit URL</a>";
                    break;
                case 'quiz':
                    echo "<a href='quiz.php?id=" . htmlspecialchars($content['quiz_id']) . "'>Take Quiz</a>";
                    break;
                default:
                    echo "Content type not supported.";
            }
            ?>
        </div>
    </div>
<?php endforeach; ?>

</body>
</html>
