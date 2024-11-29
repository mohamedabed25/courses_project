<?php
// admin_add_course.php

// Start session and check if the user is logged in as an admin
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html"); // Redirect to login if not logged 
    exit;
}

// Include the database connection
include('../users/connect.php'); // Assuming this file contains your PDO connection

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $instructor_name = $_POST['instructor_name']; // Changed from instructor_id to instructor_name
    $less_than_10 = $_POST['less_than_10']; // Get the less_than_10 value from the dropdown

    // Validate input
    if (empty($title) || empty($description) || empty($instructor_name) || !isset($less_than_10)) {
        echo "All fields are required.";
    } else {
        // Insert course into the database
        $sql = "INSERT INTO courses (title, description, instructor_name, less_than_10, created_at, updated_at) 
                VALUES (:title, :description, :instructor_name, :less_than_10, NOW(), NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':instructor_name', $instructor_name); // Bind instructor_name
        $stmt->bindParam(':less_than_10', $less_than_10, PDO::PARAM_INT); // Bind less_than_10 as integer
        
        if ($stmt->execute()) {
            echo "Course added successfully!";
        } else {
            echo "Failed to add course.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course</title>
</head>
<body>
    <h1>Add New Course</h1>
    
    <form method="POST" action="add_course.php">
        <label for="title">Course Title:</label>
        <input type="text" name="title" required><br><br>

        <label for="description">Course Description:</label>
        <textarea name="description" required></textarea><br><br>

        <label for="instructor_name">Instructor Name:</label>
        <input type="text" name="instructor_name" required><br><br>

        <!-- Dropdown for Less Than 10 Age -->
        <label for="less_than_10">Is the course intended for users under the age of 10?</label>
        <select name="less_than_10" required>
            <option value="0">No</option>
            <option value="1">Yes</option>
        </select><br><br>

        <input type="submit" value="Add Course">
    </form>

</body>
</html>
