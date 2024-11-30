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
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc; /* Light greyish blue */
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            text-align: center;
            background-color: #1E90FF; /* New blue */
            color: white;
            padding: 40px 20px;
            margin: 0;
            font-size: 2.5em;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-bottom: 5px solid #1C86EE; /* Slightly darker blue */
        }

        .content-item {
            background-color: white;
            padding: 30px;
            margin: 20px auto;
            width: 85%;
            max-width: 900px;
            border-radius: 8px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .content-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }

        .content-title {
            font-size: 1.9em;
            color: #1E90FF; /* New blue */
            margin-bottom: 15px;
            font-weight: bold;
            text-transform: capitalize;
            border-bottom: 2px solid #d5dbdb;
            padding-bottom: 10px;
        }

        .content-meta {
            font-size: 1em;
            color: #7f8c8d;
            margin-bottom: 20px;
        }

        .content-meta p {
            margin: 5px 0;
        }

        .content-body {
            margin-top: 20px;
            font-size: 1.1em;
        }

        a {
            color: #1E90FF; /* New blue */
            text-decoration: none;
            transition: color 0.3s ease;
            padding: 8px 20px;
            border-radius: 5px;
            font-weight: bold;
        }

        a:hover {
            color: #fff;
            background-color: #1C86EE; /* Slightly darker blue on hover */
            text-decoration: none;
        }

        video {
            width: 100%;
            max-width: 800px;
            margin-top: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
        }

        .download-link, .url-link {
            display: inline-block;
            margin-top: 15px;
            color: #fff;
            background-color: #1E90FF; /* New blue */
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .download-link:hover, .url-link:hover {
            background-color: #1C86EE; /* Slightly darker blue on hover */
        }

        .content-body p {
            line-height: 1.6;
            color: #34495e;
        }
.content-item .meta-info {
            display: flex;
            justify-content: space-between;
            font-size: 0.9em;
            color: #95a5a6;
        }

        .content-item .meta-info div {
            margin-left: 20px;
        }
    </style>
</head>
<body>

<h1>Course Content</h1>

<?php foreach ($course_contents as $content): ?>
    <div class="content-item">
        <div class="content-title"><?php echo htmlspecialchars($content['title']); ?></div>

        <div class="content-meta">
            <div class="meta-info">
                <div><strong>Order:</strong> <?php echo $content['order']; ?></div>
                <div><strong>Created At:</strong> <?php echo htmlspecialchars($content['created_at']); ?></div>
                <div><strong>Updated At:</strong> <?php echo htmlspecialchars($content['updated_at']); ?></div>
            </div>
        </div>

        <div class="content-body">
            <?php 
            switch ($content['content_type']) {
                case 'text':
                    echo "<p>" . nl2br(htmlspecialchars($content['content_value'])) . "</p>";  // Display text content
                    break;
                case 'video':
                    echo "<video controls><source src='" . htmlspecialchars($content['video_file_path']) . "' type='video/mp4'>Your browser does not support the video tag.</video>";
                    break;
                case 'pdf':
                    echo "<a href='" . htmlspecialchars($content['pdf_file_path']) . "' target='_blank' class='download-link'>Download PDF</a>";
                    break;
                case 'url':
                    echo "<a href='" . htmlspecialchars($content['url']) . "' target='_blank' class='url-link'>Visit URL</a>";
                    break;
                case 'quiz':
                    echo "<a href='quiz.php?id=" . htmlspecialchars($content['quiz_id']) . "' class='download-link'>Take Quiz</a>";
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