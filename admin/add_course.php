<?php
// admin_add_course.php

// Start session and check if the user is logged in as an admin
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html"); // Redirect to login if not logged in
    exit;
}

// Include the database connection
include('../users/connect.php');

// Fetch tracks and subtracks for dropdowns
$tracks = $subtracks = [];
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
    $less_than_10 = isset($_POST['less_than_10']) ? 1 : 0;
    $track_id = $_POST['track_id'];
    $subtrack_id = $_POST['subtrack_id'];
    $photo = null;

    // Handle file upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photo = 'uploads/' . basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
    }

    // Validate input
    if ($title && $description && $instructor_name && $track_id && $subtrack_id) {
        try {
            // Insert course into the database
            $stmt = $pdo->prepare("INSERT INTO courses (title, description, instructor_name, less_than_10, track_id, subtrack_id, photo, created_at, updated_at) 
                                    VALUES (:title, :description, :instructor_name, :less_than_10, :track_id, :subtrack_id, :photo, NOW(), NOW())");
            $stmt->execute([
                ':title' => $title,
                ':description' => $description,
                ':instructor_name' => $instructor_name,
                ':less_than_10' => $less_than_10,
                ':track_id' => $track_id,
                ':subtrack_id' => $subtrack_id,
                ':photo' => $photo,
            ]);
            echo "Course added successfully!";
        } catch (PDOException $e) {
            echo "Error adding course: " . $e->getMessage();
        }
    } else {
        echo "All fields are required.";
    }
    header("Location: courses.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, textarea, select {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px;
        }
        input[type="submit"] {
            width: auto;
        }
    </style>
</head>
<body>

<h2>Add New Course</h2>
<form action="  add_course.php" method="POST" enctype="multipart/form-data">
    <label for="title">Course Title:</label>
    <input type="text" name="title" required><br>

    <label for="description">Description:</label>
    <textarea name="description" required></textarea><br>

    <label for="instructor_name">Instructor Name:</label>
    <input type="text" name="instructor_name" required><br>

    <label for="less_than_10">Less Than 10 Seats:</label>
    <input type="checkbox" name="less_than_10" value="1"><br>

    <label for="track_id">Track:</label>
    <select name="track_id" required>
        <?php foreach ($tracks as $track): ?>
            <option value="<?php echo $track['id']; ?>"><?php echo htmlspecialchars($track['title']); ?></option>
        <?php endforeach; ?>
    </select><br>

    <label for="subtrack_id">Sub-Track:</label>
    <select name="subtrack_id" required>
        <?php foreach ($subtracks as $subtrack): ?>
            <option value="<?php echo $subtrack['id']; ?>"><?php echo htmlspecialchars($subtrack['title']); ?></option>
        <?php endforeach; ?>
    </select><br>

    <label for="photo">Course Photo:</label>
    <input type="file" name="photo" accept="image/*"><br>

    <input type="submit" value="Add Course">
</form>

</body>
</html>
