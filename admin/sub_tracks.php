<?php
// Include the database connection file
require_once '../users/connect.php';

// Initialize variables
$title = "";
$description = "";
$track_id = "";
$message = "";
$photoPath = "";

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['insert']) || isset($_POST['update'])) {
        // Insert or Update sub-track
        $title = $_POST['title'];
        $description = $_POST['description'];
        $track_id = $_POST['track_id'];
        $id = $_POST['id'] ?? null;

        // Handle file upload
        if (!empty($_FILES['photo']['name'])) {
            $targetDir = "uploads/sub_tracks/";
            $fileName = basename($_FILES['photo']['name']);
            $targetFilePath = $targetDir . time() . "_" . $fileName;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePath)) {
                $photoPath = $targetFilePath;
            } else {
                $message = "Error uploading photo.";
            }
        }

        if (isset($_POST['insert'])) {
            // Validate track_id
            $checkTrackStmt = $pdo->prepare("SELECT COUNT(*) FROM tracks WHERE id = ?");
            $checkTrackStmt->execute([$track_id]);
            $trackExists = $checkTrackStmt->fetchColumn();

            if ($trackExists) {
                $stmt = $pdo->prepare("INSERT INTO sub_tracks (title, description, track_id, photo) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$title, $description, $track_id, $photoPath])) {
                    // Update the no_of_subtracks in the tracks table
                    $updateStmt = $pdo->prepare("UPDATE tracks SET no_of_subtracks = no_of_subtracks + 1 WHERE id = ?");
                    $updateStmt->execute([$track_id]);

                    $message = "Sub-track inserted successfully and track updated!";
                } else {
                    $message = "Error inserting sub-track.";
                }
            } else {
                $message = "Invalid track ID. Please select a valid track.";
            }
        } elseif (isset($_POST['update'])) {
            // Update sub-track
            if ($photoPath) {
                $stmt = $pdo->prepare("UPDATE sub_tracks SET title = ?, description = ?, track_id = ?, photo = ? WHERE id = ?");
                $success = $stmt->execute([$title, $description, $track_id, $photoPath, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE sub_tracks SET title = ?, description = ?, track_id = ? WHERE id = ?");
                $success = $stmt->execute([$title, $description, $track_id, $id]);
            }
            $message = $success ? "Sub-track updated successfully!" : "Error updating sub-track.";
        }
    } elseif (isset($_POST['delete'])) {
        // Delete sub-track
        $id = $_POST['id'];

        // Get the track_id and photo before deleting
        $trackStmt = $pdo->prepare("SELECT track_id, photo FROM sub_tracks WHERE id = ?");
        $trackStmt->execute([$id]);
        $track = $trackStmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("DELETE FROM sub_tracks WHERE id = ?");
        if ($stmt->execute([$id])) {
            // Decrease the no_of_subtracks in the tracks table
            if ($track) {
                $updateStmt = $pdo->prepare("UPDATE tracks SET no_of_subtracks = no_of_subtracks - 1 WHERE id = ?");
                $updateStmt->execute([$track['track_id']]);

                // Delete the photo file
                if ($track['photo'] && file_exists($track['photo'])) {
                    unlink($track['photo']);
                }
            }
            $message = "Sub-track deleted successfully and track updated!";
        } else {
            $message = "Error deleting sub-track.";
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit; # this to solve duplicate entry
}

// Fetch all sub-tracks
$stmt = $pdo->prepare("SELECT sub_tracks.*, tracks.title AS track_title FROM sub_tracks JOIN tracks ON sub_tracks.track_id = tracks.id");
$stmt->execute();
$sub_tracks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all tracks for the dropdown
$stmt = $pdo->prepare("SELECT id, title FROM tracks");
$stmt->execute();
$tracks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Sub-Tracks</title>
</head>
<body>
    <h1>Sub-Tracks Management</h1>

    <!-- Display message -->
    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <!-- Form to insert/update sub-tracks -->
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo isset($_GET['edit']) ? htmlspecialchars($_GET['edit']) : ''; ?>">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required value="<?php echo isset($_GET['edit']) ? htmlspecialchars($sub_tracks[array_search($_GET['edit'], array_column($sub_tracks, 'id'))]['title']) : ''; ?>">
        <br>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo isset($_GET['edit']) ? htmlspecialchars($sub_tracks[array_search($_GET['edit'], array_column($sub_tracks, 'id'))]['description']) : ''; ?></textarea>
        <br>
        <label for="track_id">Track:</label>
        <select id="track_id" name="track_id" required>
            <option value="">Select a Track</option>
            <?php foreach ($tracks as $track): ?>
                <option value="<?php echo htmlspecialchars($track['id']); ?>" <?php echo (isset($_GET['edit']) && $sub_tracks[array_search($_GET['edit'], array_column($sub_tracks, 'id'))]['track_id'] == $track['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($track['title']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="photo">Upload Photo:</label>
        <input type="file" id="photo" name="photo" accept="image/*">
        <br>
        <button type="submit" name="insert">Insert</button>
        <button type="submit" name="update">Update</button>
    </form>

    <!-- List of sub-tracks -->
    <h2>Sub-Tracks List</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Track</th>
                <th>Photo</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sub_tracks as $sub_track): ?>
                <tr>
                    <td><?php echo htmlspecialchars($sub_track['id']); ?></td>
                    <td><?php echo htmlspecialchars($sub_track['title']); ?></td>
                    <td><?php echo htmlspecialchars($sub_track['description']); ?></td>
                    <td><?php echo htmlspecialchars($sub_track['track_title']); ?></td>
                    <td>
                        <?php if ($sub_track['photo']): ?>
                            <img src="<?php echo htmlspecialchars($sub_track['photo']); ?>" alt="Photo" width="100">
                        <?php else: ?>
                            No Photo
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="?edit=<?php echo htmlspecialchars($sub_track['id']); ?>">Edit</a>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($sub_track['id']); ?>">
                            <button type="submit" name="delete">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
