<?php
// Include the database connection file
require_once '../connect.php';

// Fetch all tracks
$stmt = $pdo->prepare("SELECT id, title, description, no_of_subtracks, photo FROM tracks ORDER BY id");
$stmt->execute();
$tracks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Tracks</title>
</head>
<body>
    <h1>Tracks</h1>

    <?php if (empty($tracks)): ?>
        <p>No tracks available.</p>
    <?php else: ?>
        <?php foreach ($tracks as $track): ?>
            <h2>
                <a href="view_sub_tracks.php?track_id=<?php echo htmlspecialchars($track['id']); ?>">
                    <?php echo htmlspecialchars($track['title']); ?>
                </a>
            </h2>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($track['description']); ?></p>
            <p><strong>Number of Sub-Tracks:</strong> <?php echo htmlspecialchars($track['no_of_subtracks']); ?></p>
            <?php if (!empty($track['photo'])): ?>
                <img src="<?php echo htmlspecialchars('../../admin/' . $track['photo']); ?>" alt="Track Photo" width="200">
            <?php else: ?>
                <p>No photo available.</p>
            <?php endif; ?>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
