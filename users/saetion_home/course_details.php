<?php
// Include the database connection file
require_once '../connect.php';

// Initialize variables
$course = null;
$course_contents = [];

// Check if course_id is provided and is valid
if (isset($_GET['course_id']) && ctype_digit($_GET['course_id'])) {
    $course_id = (int) $_GET['course_id'];

    try {
        // Fetch the course details based on the course_id
        $stmt = $pdo->prepare("SELECT id, title, description, instructor_name, is_free, course_price, photo FROM courses WHERE id = ?");
        $stmt->execute([$course_id]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);

        // Fetch course content for the given course_id
        $stmt_content = $pdo->prepare("SELECT id, content_type, content_value, video_file_path, pdf_file_path, url, quiz_id FROM course_contents WHERE course_id = ? ORDER BY `order` ASC");
        $stmt_content->execute([$course_id]);
        $course_contents = $stmt_content->fetchAll(PDO::FETCH_ASSOC);

        // If no course is found, show an error message
        if (!$course) {
            die('Course not found.');
        }

    } catch (PDOException $e) {
        die('Error fetching data: ' . $e->getMessage());
    }
} else {
    die('Invalid course ID.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['title']); ?> - Course Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .content-section {
            margin-bottom: 20px;
        }
        .content-section h3 {
            font-size: 1.2em;
        }
        iframe {
            width: 100%;
            height: 400px;
        }
        .pdf-viewer {
            width: 100%;
            height: 600px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <h1><?php echo htmlspecialchars($course['title']); ?> - Course Details</h1>
    <p><strong>Instructor:</strong> <?php echo htmlspecialchars($course['instructor_name']); ?></p>
    <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
    <p><strong>Price:</strong> <?php echo $course['is_free'] ? 'Free' : '$' . htmlspecialchars($course['course_price']); ?></p>
    <img src="<?php echo htmlspecialchars($course['photo']); ?>" alt="Course Image" style="max-width: 100%; height: auto; margin-top: 10px;">

    <h2>Course Contents</h2>
    <?php foreach ($course_contents as $content): ?>
        <div class="content-section">
            <h3>
                <?php
                    // Display content type as a title
                    switch ($content['content_type']) {
                        case 'video':
                            echo 'Video';
                            break;
                        case 'pdf':
                            echo 'PDF';
                            break;
                        case 'quiz':
                            echo 'Quiz';
                            break;
                        case 'url':
                            echo 'External Link';
                            break;
                        default:
                            echo 'Content';
                            break;
                    }
                ?>
            </h3>
            <!-- Make each content type a clickable button that redirects to another page -->
            <a href="content_page.php?id=<?php echo htmlspecialchars($content['id']); ?>" class="btn">
                <?php echo ucfirst($content['content_type']); ?> - View Content
            </a>
        </div>
    <?php endforeach; ?>

</body>
</html>
