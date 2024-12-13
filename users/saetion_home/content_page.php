<?php
// Include the database connection file
require_once '../connect.php';

// Initialize variables
$content = null;

// Check if id is provided and is valid
if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
    $id = (int) $_GET['id'];

    try {
        // Fetch content details based on id
        $stmt_content = $pdo->prepare("SELECT content_type, content_value, video_file_path, pdf_file_path, url, quiz_id FROM course_contents WHERE id = ?");
        $stmt_content->execute([$id]);
        $content = $stmt_content->fetch(PDO::FETCH_ASSOC);

        // If no content is found, show an error message
        if (!$content) {
            die('Content not found.');
        }

    } catch (PDOException $e) {
        die('Error fetching data: ' . $e->getMessage());
    }
} else {
    die('Invalid content ID.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content - <?php echo htmlspecialchars($content['content_type']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        iframe {
            width: 100%;
            height: 500px;
        }
        .pdf-viewer {
            width: 100%;
            height: 600px;
        }
        .content-wrapper {
            margin: 20px 0;
        }
        .content-wrapper p {
            font-size: 1.2em;
        }
        .external-link {
            color: blue;
            text-decoration: underline;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <h1><?php echo ucfirst(htmlspecialchars($content['content_type'])); ?> - Content</h1>

    <?php
        // Base URL for file storage (adjust this to reflect your server's root path)
        $upload_base_url = '../../admin/';

        // Display content based on content type
        switch ($content['content_type']) {
            case 'video':
                if ($content['video_file_path']) {
                    // Construct the video URL
                    $video_url = $upload_base_url . htmlspecialchars($content['video_file_path']);
                    echo '<iframe src="' . $video_url . '" frameborder="0" allowfullscreen></iframe>';
                } else {
                    echo '<p>No video available for this content.</p>';
                }
                break;

            case 'pdf':
                if ($content['pdf_file_path']) {
                    // Construct the PDF URL
                    $pdf_url = $upload_base_url . htmlspecialchars($content['pdf_file_path']);
                    echo '<iframe src="' . $pdf_url . '" class="pdf-viewer"></iframe>';
                } else {
                    echo '<p>No PDF available for this content.</p>';
                }
                break;

            case 'quiz':
                if ($content['quiz_id']) {
                    // If there's a quiz, you can provide a link to the quiz page
                    echo '<p><a href="quiz_page.php?quiz_id=' . htmlspecialchars($content['quiz_id']) . '">Start Quiz</a></p>';
                } else {
                    echo '<p>No quiz available for this content.</p>';
                }
                break;

            case 'url':
                if ($content['url']) {
                    // If there is an external URL, provide a link to it
                    echo '<p><a href="' . htmlspecialchars($content['url']) . '" class="external-link" target="_blank">Go to External Link</a></p>';
                } else {
                    echo '<p>No external link available for this content.</p>';
                }
                break;

            default:
                echo '<p>Unknown content type.</p>';
                break;
        }
    ?>

</body>
</html>
