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

// Fetch courses from the database with related tracks and subtracks
$sql = "
    SELECT courses.*, tracks.title AS track_title, sub_tracks.title AS subtrack_title
    FROM courses
    LEFT JOIN tracks ON courses.track_id = tracks.id
    LEFT JOIN sub_tracks ON courses.subtrack_id = sub_tracks.id";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle delete course
if (isset($_GET['delete'])) {
    $course_id = $_GET['delete'];

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Fetch track_id and subtrack_id before deleting
        $fetchSql = "SELECT track_id, subtrack_id FROM courses WHERE id = :course_id";
        $fetchStmt = $pdo->prepare($fetchSql);
        $fetchStmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $fetchStmt->execute();
        $courseData = $fetchStmt->fetch(PDO::FETCH_ASSOC);

        if ($courseData) {
            $track_id = $courseData['track_id'];
            $subtrack_id = $courseData['subtrack_id'];

            // Delete course
            $delete_sql = "DELETE FROM courses WHERE id = :course_id";
            $delete_stmt = $pdo->prepare($delete_sql);
            $delete_stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);

            if ($delete_stmt->execute()) {
                // Decrement no_of_courses in the tracks and sub_tracks tables
                if ($track_id) {
                    $updateTrack = $pdo->prepare("UPDATE tracks SET no_of_subtracks = no_of_subtracks - 1 WHERE id = :track_id");
                    $updateTrack->bindParam(':track_id', $track_id, PDO::PARAM_INT);
                    $updateTrack->execute();
                }

                if ($subtrack_id) {
                    $updateSubtrack = $pdo->prepare("UPDATE sub_tracks SET no_of_courses = no_of_courses - 1 WHERE id = :subtrack_id");
                    $updateSubtrack->bindParam(':subtrack_id', $subtrack_id, PDO::PARAM_INT);
                    $updateSubtrack->execute();
                }

                // Commit transaction
                $pdo->commit();

                echo "Course deleted successfully!";
                header("Location: courses.php"); // Redirect back to the course list
                exit;
            } else {
                throw new Exception("Failed to delete course.");
            }
        } else {
            throw new Exception("Course not found.");
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <style>
        /* Your previous styles */
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e0f7fa, #ffffff);
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        h1 {
            margin-bottom: 30px;
            text-align: center;
            color: #1E90FF;
            font-size: 2.5rem;
        }

        table {
            width: 100%;
            max-width: 1100px;
            border-collapse: collapse;
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        th, td {
            padding: 20px;
            text-align: center;
            vertical-align: middle;
            font-size: 1rem;
        }

        th {
            background: #0288d1;
            color: #fff;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        tr:hover {
            background: #e3f2fd;
        }

        .action-btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            margin: 0 5px 10px 5px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .edit-btn {
            background: #26a69a;
        }

        .delete-btn {
            background: #e57373;
        }

        .view-btn {
            background: #64b5f6;
        }

        .add-btn {
            background: #81c784;
        }

        .add-course {
            margin-top: 40px;
            padding: 14px 30px;
            font-size: 18px;
            font-weight: bold;
            color: #fff;
            background: #1E90FF;
            border-radius: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            text-decoration: none;
            transition: all 0.3s ease-in-out;
        }

        .add-course:hover {
            transform: scale(1.05);
            background: #1565c0;
        }

        td a {
            display: inline-block;
            margin-right: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Manage Courses</h1>

    <table>
        <thead>
            <tr>
                <th>Course Title</th>
                <th>Instructor Name</th>
                <th>Description</th>
                <th>Age Group (Under 10)</th>
                <th>Is Free</th>
                <th>Course Price</th>
                <th>Track</th>
                <th>Subtrack</th>
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
                    <td><?php echo $course['is_free'] == 1 ? 'Yes' : 'No'; ?></td>
                    <td><?php echo number_format($course['course_price'], 2); ?></td>
                    <td><?php echo htmlspecialchars($course['track_title'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($course['subtrack_title'] ?? 'N/A'); ?></td>
                    <td>
                        <a href="edit_course.php?id=<?php echo $course['id']; ?>" class="action-btn edit-btn">Edit</a>
                        <a href="?delete=<?php echo $course['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this course?');">Delete</a>
                        <a href="each_course_content.php?id=<?php echo $course['id']; ?>" class="action-btn view-btn">View Content</a>
                        <a href="course_content.php?id=<?php echo $course['id']; ?>" class="action-btn add-btn">Add Content</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="add_course.php" class="add-course">Add New Course</a>
</body>
</html>
