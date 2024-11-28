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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content_type = $_POST['content_type'];
    $order = $_POST['order'];
    $content_value = ''; // This will hold the value for text content or URL

    // Handle file uploads based on the content type
    $video_file_path = null;
    $pdf_file_path = null;

    // Check if the 'order' is unique for the current course_id
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM course_contents WHERE course_id = :course_id AND `order` = :order");
    $stmt->bindParam(':course_id', $course_id);
    $stmt->bindParam(':order', $order);
    $stmt->execute();
    $orderCount = $stmt->fetchColumn();

    if ($orderCount > 0) {
        echo "The 'order' value must be unique for this course. Please select a different order number.";
        exit;
    }

    if ($content_type === 'text') {
        $content_value = $_POST['editor'];  // If it's text, use the editor content
    } elseif ($content_type === 'url') {
        $content_value = $_POST['url'];  // If it's a URL, store the URL
    } elseif ($content_type === 'video') {
        // Handle video file upload
        if (isset($_FILES['video_file']) && $_FILES['video_file']['error'] === 0) {
            $video_tmp = $_FILES['video_file']['tmp_name'];
            $video_name = $_FILES['video_file']['name'];
            $video_file_path = 'uploads/videos/' . $video_name;  // Define upload directory for videos

            // Move the uploaded file to the specified folder
            if (move_uploaded_file($video_tmp, $video_file_path)) {
                $content_value = null;  // No need to store content_value for video, since we're storing the file path
            } else {
                echo "Failed to upload the video file.";
                exit;
            }
        }
    } elseif ($content_type === 'pdf') {
        // Handle PDF file upload
        if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === 0) {
            $pdf_tmp = $_FILES['pdf_file']['tmp_name'];
            $pdf_name = $_FILES['pdf_file']['name'];
            $pdf_file_path = 'uploads/pdfs/' . $pdf_name;  // Define upload directory for PDFs

            // Move the uploaded file to the specified folder
            if (move_uploaded_file($pdf_tmp, $pdf_file_path)) {
                $content_value = null;  // No need to store content_value for PDF, we're storing the file path
            } else {
                echo "Failed to upload the PDF file.";
                exit;
            }
        }
    } elseif ($content_type === 'quiz') {
        $quiz_id = $_POST['quiz_id'];  // For quiz, store quiz ID
        $content_value = $quiz_id;
    } else {
        echo "Invalid content type.";
        exit;
    }

    // Validate inputs
    if (empty($title) || empty($content_type) || empty($order)) {
        echo "All fields are required.";
    } else {
        // Insert content into the database
        $sql = "INSERT INTO course_contents (course_id, title, content_type, content_value, video_file_path, pdf_file_path, url, quiz_id, `order`, created_at, updated_at) 
                VALUES (:course_id, :title, :content_type, :content_value, :video_file_path, :pdf_file_path, :url, :quiz_id, :order, NOW(), NOW())";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':course_id', $course_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content_type', $content_type);
        $stmt->bindParam(':content_value', $content_value);
        $stmt->bindParam(':video_file_path', $video_file_path);
        $stmt->bindParam(':pdf_file_path', $pdf_file_path);
        $stmt->bindParam(':url', $url);
        $stmt->bindParam(':quiz_id', $quiz_id);
        $stmt->bindParam(':order', $order);

        if ($stmt->execute()) {
            echo "Content added successfully!";
        } else {
            echo "Failed to add content.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course Content</title>
    <script>
        function changeContentType() {
            var contentType = document.getElementById("content_type").value;
            var fileInputContainer = document.getElementById("file_input_container");
            var videoInputContainer = document.getElementById("video_input_container");
            var urlInputContainer = document.getElementById("url_input_container");
            var quizInputContainer = document.getElementById("quiz_input_container");
            var textAreaContainer = document.getElementById("text_area_container");

            // Hide all input fields initially
            fileInputContainer.style.display = 'none';
            videoInputContainer.style.display = 'none';
            urlInputContainer.style.display = 'none';
            quizInputContainer.style.display = 'none';
            textAreaContainer.style.display = 'none';

            // Show fields based on the content type
            if (contentType === 'pdf') {
                fileInputContainer.style.display = 'block';
            } else if (contentType === 'video') {
                videoInputContainer.style.display = 'block';
            } else if (contentType === 'url') {
                urlInputContainer.style.display = 'block';
            } else if (contentType === 'text') {
                textAreaContainer.style.display = 'block';
            } else if (contentType === 'quiz') {
                quizInputContainer.style.display = 'block';
            }
        }

        // Initialize content type change handler
        window.onload = changeContentType;
    </script>
</head>
<body>
    <h1>Add Content to Course</h1>

    <form method="POST" action="course_content.php?id=<?php echo $_GET['id']; ?>" enctype="multipart/form-data">
        <label for="title">Content Title:</label>
        <input type="text" name="title" required><br><br>

        <label for="content_type">Content Type:</label>
        <select name="content_type" id="content_type" onchange="changeContentType()" required>
            <option value="text">Text</option>
            <option value="video">Video</option>
            <option value="url">URL</option>
            <option value="pdf">PDF</option>
            <option value="quiz">Quiz</option>
        </select><br><br>

        <!-- Order input -->
        <label for="order">Order:</label>
        <input type="number" name="order" required ><br><br> 

        <!-- File input container for PDF -->
        <div id="file_input_container" style="display:none;">
            <label for="pdf_file">PDF File:</label>
            <input type="file" name="pdf_file" id="pdf_file" accept="application/pdf"><br><br>
        </div>

        <!-- Video input container -->
        <div id="video_input_container" style="display:none;">
            <label for="video_file">Video File:</label>
            <input type="file" name="video_file" id="video_file" accept="video/*"><br><br>
        </div>

        <!-- URL input container -->
        <div id="url_input_container" style="display:none;">
            <label for="url">URL:</label>
            <input type="url" name="url" id="url"><br><br>
        </div>

        <!-- Text input container -->
        <div id="text_area_container" style="display:none;">
            <label for="editor">Content (Text):</label>
            <textarea id="editor" name="editor" ></textarea><br><br>
        </div>

        <!-- Quiz ID input container -->
        <div id="quiz_input_container" style="display:none;">
            <label for="quiz_id">Quiz ID:</label>
            <input type="text" name="quiz_id" id="quiz_id"><br><br>
        </div>

        <input type="submit" value="Add Content">
    </form>
</body>
</html>
