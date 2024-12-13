<?php
// Include the database connection file
require_once '../connect.php';

// Initialize variables
$sub_tracks = [];
$track = null;

// Check if track_id is provided
if (isset($_GET['track_id']) && ctype_digit($_GET['track_id'])) {
    $track_id = (int) $_GET['track_id'];

    try {
        // Fetch the track details
        $stmt = $pdo->prepare("SELECT id, title, description, photo FROM tracks WHERE id = ?");
        $stmt->execute([$track_id]);
        $track = $stmt->fetch(PDO::FETCH_ASSOC);

        // Fetch the sub-tracks
        $stmt = $pdo->prepare("SELECT id, title, description, photo FROM sub_tracks WHERE track_id = ?");
        $stmt->execute([$track_id]);
        $sub_tracks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die('Error fetching data: ' . $e->getMessage());
    }
} else {
    die('Invalid track ID.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Sub-Tracks</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        img {
            max-width: 200px;
            height: auto;
        }
        a {
            color: blue;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        hr {
            margin: 20px 0;
        }
    </style>
</head>
<body>


    <?php if (!empty($sub_tracks)): ?>
        <?php foreach ($sub_tracks as $sub_track): ?>
            <h2>
                <a href="view_courses.php?subtrack_id=<?php echo htmlspecialchars($sub_track['id']); ?>">
                    <?php echo htmlspecialchars($sub_track['title']); ?>
                </a>
            </h2>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($sub_track['description']); ?></p>
            <?php if (!empty($sub_track['photo'])): ?>
                <img src="<?php echo htmlspecialchars('../../admin/' . $sub_track['photo']); ?>" alt="Sub-Track Photo">
            <?php else: ?>
                <p>No photo available.</p>
            <?php endif; ?>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No sub-tracks available for this track.</p>
    <?php endif; ?>

    <a href="view_tracks.php">Back to Tracks</a>
</body>
</html>
