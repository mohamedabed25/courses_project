<?php
// admin_edit_course.php

// Start session and check if the user is logged in as an admin
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html"); // Redirect to login if not logged in
    exit;
}

// Include the database connection
include('../users/connect.php'); // Assuming this file contains your PDO connection

// Get course id from query string
if (isset($_GET['id'])) {
    $course_id = $_GET['id'];

    // Fetch course details
    $sql = "SELECT * FROM courses WHERE id = :course_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
    $stmt->execute();
    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$course) {
        echo "Course not found.";
        exit;
    }
}

// Handle form submission to update course
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $instructor_name = $_POST['instructor_name'];
    $less_than_10 = $_POST['less_than_10'];

    // Validate input
    if (empty($title) || empty($description) || empty($instructor_name)) {
        echo "All fields are required.";
    } else {
        // Update course details in the database
        $update_sql = "UPDATE courses SET title = :title, description = :description, instructor_name = :instructor_name, less_than_10 = :less_than_10, updated_at = NOW() WHERE id = :course_id";
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->bindParam(':title', $title);
        $update_stmt->bindParam(':description', $description);
        $update_stmt->bindParam(':instructor_name', $instructor_name);
        $update_stmt->bindParam(':less_than_10', $less_than_10, PDO::PARAM_INT);
        $update_stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);

        if ($update_stmt->execute()) {
            echo "Course updated successfully!";
            header("Location: courses.php"); // Redirect back to courses list
            exit;
        } else {
            echo "Failed to update course.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
</head>
<body>
    <h1>Edit Course</h1>
    
    <form method="POST" action="edit_course.php?id=<?php echo $course['id']; ?>">
        <label for="title">Course Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($course['title']); ?>" required><br><br>

        <label for="description">Course Description:</label>
        <textarea name="description" required><?php echo htmlspecialchars($course['description']); ?></textarea><br><br>

        <label for="instructor_name">Instructor Name:</label>
        <input type="text" name="instructor_name" value="<?php echo htmlspecialchars($course['instructor_name']); ?>" required><br><br>

        <label for="less_than_10">Is the course for users under 10?</label>
        <select name="less_than_10" required>
            <option value="0" <?php echo $course['less_than_10'] == 0 ? 'selected' : ''; ?>>No</option>
            <option value="1" <?php echo $course['less_than_10'] == 1 ? 'selected' : ''; ?>>Yes</option>
        </select><br><br>

        <input type="submit" value="Update Course">
    </form>
</body>
</html>
