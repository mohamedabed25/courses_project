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
    $course_price = $_POST['course_price'];
    $is_free = $_POST['is_free'];

    // Validate input
    if (empty($title) || empty($description) || empty($instructor_name) || empty($course_price)) {
        echo "All fields are required.";
    } else {
        // Update course details in the database
        $update_sql = "UPDATE courses SET 
                        title = :title, 
                        description = :description, 
                        instructor_name = :instructor_name, 
                        less_than_10 = :less_than_10, 
                        course_price = :course_price,
                        is_free = :is_free,
                        updated_at = NOW() 
                        WHERE id = :course_id";
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->bindParam(':title', $title);
        $update_stmt->bindParam(':description', $description);
        $update_stmt->bindParam(':instructor_name', $instructor_name);
        $update_stmt->bindParam(':less_than_10', $less_than_10, PDO::PARAM_INT);
        $update_stmt->bindParam(':course_price', $course_price, PDO::PARAM_STR);
        $update_stmt->bindParam(':is_free', $is_free, PDO::PARAM_INT);
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
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e0f7fa, #ffffff); /* خلفية متدرجة */
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        h1 {
            margin-bottom: 30px;
            text-align: center;
            color: #1E90FF; /* لون أزرق فاتح */
            font-size: 2.5rem;
        }

        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: center;
        }

        label {
            display: block;
            margin: 10px 0;
            font-size: 1rem;
        }

        input[type="text"], input[type="number"], textarea, select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 2px solid #0288d1;
            border-radius: 8px;
            font-size: 1rem;
        }

        input[type="submit"] {
            background-color: #0288d1;
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            font-size: 1.2rem;
            cursor: pointer;
            border: none;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #026ca0;
        }

        .background-shape {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: #0288d1;
            opacity: 0.2;
            z-index: -1;
        }

        .shape-1 {
            top: -100px;
            left: -150px;
        }

        .shape-2 {
            bottom: -100px;
            right: -150px;
        }
    </style>
</head>
<body>
    <!-- Background Shapes -->
    <div class="background-shape shape-1"></div>
    <div class="background-shape shape-2"></div>

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

        <label for="course_price">Course Price:</label>
        <input type="number" name="course_price" value="<?php echo htmlspecialchars($course['course_price']); ?>" step="0.01" required><br><br>

        <label for="is_free">Is the course free?</label>
        <select name="is_free" required>
            <option value="0" <?php echo $course['is_free'] == 0 ? 'selected' : ''; ?>>No</option>
            <option value="1" <?php echo $course['is_free'] == 1 ? 'selected' : ''; ?>>Yes</option>
        </select><br><br>

        <input type="submit" value="Update Course">
    </form>
</body>
</html>