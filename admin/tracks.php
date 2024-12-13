<?php
// Page for managing tracks
require_once '../users/connect.php';

// Initialize variables
$title = "";
$description = "";
$message = "";
$photoPath = "";

// Handle form submissions for tracks
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['insert']) || isset($_POST['update'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $id = $_POST['id'] ?? null;

        // Handle file upload
        if (!empty($_FILES['photo']['name'])) {
            $targetDir = "uploads/tracks/";
            $fileName = basename($_FILES['photo']['name']);
            $targetFilePath = $targetDir . time() . "_" . $fileName;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePath)) {
                $photoPath = $targetFilePath;
            } else {
                $message = "Error uploading photo.";
            }
        }

        if (isset($_POST['insert'])) {
            $stmt = $pdo->prepare("INSERT INTO tracks (title, description, photo, no_of_subtracks) VALUES (?, ?, ?, 0)");
            $message = $stmt->execute([$title, $description, $photoPath]) ? "Track inserted successfully!" : "Error inserting track.";
        } elseif (isset($_POST['update'])) {
            if ($photoPath) {
                $stmt = $pdo->prepare("UPDATE tracks SET title = ?, description = ?, photo = ? WHERE id = ?");
                $message = $stmt->execute([$title, $description, $photoPath, $id]) ? "Track updated successfully!" : "Error updating track.";
            } else {
                $stmt = $pdo->prepare("UPDATE tracks SET title = ?, description = ? WHERE id = ?");
                $message = $stmt->execute([$title, $description, $id]) ? "Track updated successfully!" : "Error updating track.";
            }
        }
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM tracks WHERE id = ?");
        $message = $stmt->execute([$id]) ? "Track deleted successfully!" : "Error deleting track.";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM tracks");
$stmt->execute();
$tracks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tracks</title>
</head>
<body>
    <h1>Tracks Management</h1>

    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <h2>Add/Update Track</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo isset($_GET['edit']) ? htmlspecialchars($_GET['edit']) : ''; ?>">
        <label for="title">Track Title:</label>
        <input type="text" id="title" name="title" required value="<?php 
            if (isset($_GET['edit'])) {
                $editKey = array_search($_GET['edit'], array_column($tracks, 'id'));
                echo $editKey !== false ? htmlspecialchars($tracks[$editKey]['title']) : '';
            }
        ?>">
        <br>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php 
            if (isset($_GET['edit'])) {
                $editKey = array_search($_GET['edit'], array_column($tracks, 'id'));
                echo $editKey !== false ? htmlspecialchars($tracks[$editKey]['description']) : '';
            }
        ?></textarea>
        <br>
        <label for="photo">Upload Photo:</label>
        <input type="file" id="photo" name="photo" accept="image/*">
        <br>
        <button type="submit" name="insert">Insert Track</button>
        <button type="submit" name="update">Update Track</button>
    </form>

    <h2>Track List</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Photo</th>
                <th>No of Subtracks</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tracks as $track): ?>
                <tr>
                    <td><?php echo htmlspecialchars($track['id']); ?></td>
                    <td><?php echo htmlspecialchars($track['title']); ?></td>
                    <td><?php echo htmlspecialchars($track['description']); ?></td>
                    <td>
                        <?php if ($track['photo']): ?>
                            <img src="<?php echo htmlspecialchars($track['photo']); ?>" alt="Track Photo" width="100">
                        <?php else: ?>
                            No Photo
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($track['no_of_subtracks']); ?></td>
                    <td>
                        <a href="?edit=<?php echo htmlspecialchars($track['id']); ?>">Edit</a>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($track['id']); ?>">
                            <button type="submit" name="delete">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
