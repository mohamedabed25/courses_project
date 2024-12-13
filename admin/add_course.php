<?php
// admin_add_course.php

// Start session and check if the user is logged in as an admin
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html"); // Redirect to login if not logged in
    exit;
}

// Include the database connection
include('../users/connect.php'); // Assuming this file contains your PDO connection

// Fetch tracks and subtracks for dropdowns
$tracks = [];
$subtracks = [];
try {
    $tracksStmt = $pdo->prepare("SELECT id, title FROM tracks");
    $tracksStmt->execute();
    $tracks = $tracksStmt->fetchAll(PDO::FETCH_ASSOC);

    $subtracksStmt = $pdo->prepare("SELECT id, title FROM sub_tracks");
    $subtracksStmt->execute();
    $subtracks = $subtracksStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching tracks or subtracks: " . $e->getMessage();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $instructor_name = $_POST['instructor_name'];
    $less_than_10 = $_POST['less_than_10'];
    $track_id = $_POST['track_id'];
    $subtrack_id = $_POST['subtrack_id'];

    // Validate input
    if (empty($title) || empty($description) || empty($instructor_name) || !isset($less_than_10) || empty($track_id) || empty($subtrack_id)) {
        echo "All fields are required.";
    } else {
        try {
            // Start transaction
            $pdo->beginTransaction();

            // Insert course into the database
            $sql = "INSERT INTO courses (title, description, instructor_name, less_than_10, track_id, subtrack_id, created_at, updated_at) 
                    VALUES (:title, :description, :instructor_name, :less_than_10, :track_id, :subtrack_id, NOW(), NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':instructor_name', $instructor_name);
            $stmt->bindParam(':less_than_10', $less_than_10, PDO::PARAM_INT);
            $stmt->bindParam(':track_id', $track_id, PDO::PARAM_INT);
            $stmt->bindParam(':subtrack_id', $subtrack_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Increment no_of_courses in the tracks and sub_tracks tables
                $updateTrack = $pdo->prepare("UPDATE tracks SET no_of_subtracks = no_of_subtracks + 1 WHERE id = :track_id");
                $updateTrack->bindParam(':track_id', $track_id, PDO::PARAM_INT);
                $updateTrack->execute();

                $updateSubtrack = $pdo->prepare("UPDATE sub_tracks SET no_of_courses = no_of_courses + 1 WHERE id = :subtrack_id");
                $updateSubtrack->bindParam(':subtrack_id', $subtrack_id, PDO::PARAM_INT);
                $updateSubtrack->execute();

                // Commit transaction
                $pdo->commit();

                // Redirect to courses.php after successful insertion
                header("Location: courses.php");
                exit;
            } else {
                throw new Exception("Failed to add course.");
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Transaction failed: " . $e->getMessage();
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

        <!-- Dropdown for Tracks -->
        <label for="track_id">Select Track:</label>
        <select name="track_id" required>
            <option value="">-- Select Track --</option>
            <?php foreach ($tracks as $track): ?>
                <option value="<?php echo $track['id']; ?>">
                    <?php echo htmlspecialchars($track['title']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <!-- Dropdown for Subtracks -->
        <label for="subtrack_id">Select Subtrack:</label>
        <select name="subtrack_id" required>
            <option value="">-- Select Subtrack --</option>
            <?php foreach ($subtracks as $subtrack): ?>
                <option value="<?php echo $subtrack['id']; ?>">
                    <?php echo htmlspecialchars($subtrack['title']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <input type="submit" value="Add Course">
    </form>

</body>
</html>
