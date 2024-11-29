<?php
// admin_courses.php

// Start session and check if the user is logged in as an admin
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html"); // Redirect to login if not logged in
    exit;
}

// Include the database connection
include('../users/connect.php'); // Assuming this file contains your PDO connection

// Fetch courses from the database
$sql = "SELECT * FROM courses";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle delete course
if (isset($_GET['delete'])) {
    $course_id = $_GET['delete'];
    $delete_sql = "DELETE FROM courses WHERE id = :course_id";
    $delete_stmt = $pdo->prepare($delete_sql);
    $delete_stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
    if ($delete_stmt->execute()) {
        echo "Course deleted successfully!";
        header("Location: courses.php"); // Redirect back to the course list
        exit;
    } else {
        echo "Failed to delete course.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
</head>
<body>
    <h1>Manage Courses</h1>
    
    <table border="1">
        <thead>
            <tr>
                <th>Course Title</th>
                <th>Instructor Name</th>
                <th>Description</th>
                <th>Age Group (Under 10)</th>
                <th>is_free</th> <!-- Add is_free column header -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($courses as $course): ?>
                <tr>
                    <td><?php echo htmlspecialchars($course['title']); ?></td>
                    <td><?php echo htmlspecialchars($course['instructor_name']); ?></td>
                    <td><?php echo htmlspecialchars($course['description']); ?></td>
                    <td><?php echo $course['less_than_10'] == 1 ? 'Yes' : 'No'; ?></td>
                    <td><?php echo $course['is_free'] == 1 ? 'Yes' : 'No'; ?></td> <!-- Display is_free status -->
                    <td>
                        <!-- Edit Link -->
                        <a href="edit_course.php?id=<?php echo $course['id']; ?>">Edit</a> | 
                        <!-- Delete Link -->
                        <a href="?delete=<?php echo $course['id']; ?>" onclick="return confirm('Are you sure you want to delete this course?');">Delete</a> | 
                        <!-- View Course Content Link -->
                        <a href="each_course_content.php?id=<?php echo $course['id']; ?>">View Course Content</a> | 
                        <!-- Add New Content Link -->
                        <a href="course_content.php?id=<?php echo $course['id']; ?>">Add New Content</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br>
    <a href="add_course.php">Add New Course</a>
</body>
</html>
