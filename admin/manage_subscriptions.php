<?php
// Include the database connection file
require_once 'connect.php';

// Initialize variables
$subscriptions = [];
$subscription = null;
$courses = [];
$users = [];

// Fetch all subscriptions
try {
    $stmt = $pdo->prepare("SELECT s.id, c.title AS course_title, u.username AS user_name, s.status 
                           FROM subscriptions s
                           JOIN courses c ON s.course_id = c.id
                           JOIN users u ON s.user_id = u.id");
    $stmt->execute();
    $subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error fetching subscriptions: ' . $e->getMessage());
}

// Fetch all courses for dropdown
try {
    $stmt = $pdo->prepare("SELECT id, title FROM courses");
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error fetching courses: ' . $e->getMessage());
}

// Fetch all users for dropdown
try {
    $stmt = $pdo->prepare("SELECT id, username FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error fetching users: ' . $e->getMessage());
}

// Handle add subscription
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_subscription'])) {
    $course_id = $_POST['course_id'];
    $user_id = $_POST['user_id'];
    $status = $_POST['status'];

    try {
        $stmt = $pdo->prepare("INSERT INTO subscriptions (course_id, user_id, status) 
                               VALUES (?, ?, ?)");
        $stmt->execute([$course_id, $user_id, $status]);
        header("Location: manage_subscriptions.php"); // Refresh the page
        exit;
    } catch (PDOException $e) {
        die('Error adding subscription: ' . $e->getMessage());
    }
}

// Handle delete subscription
if (isset($_GET['delete_id']) && ctype_digit($_GET['delete_id'])) {
    $delete_id = (int) $_GET['delete_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM subscriptions WHERE id = ?");
        $stmt->execute([$delete_id]);
        header("Location: manage_subscriptions.php"); // Refresh the page
        exit;
    } catch (PDOException $e) {
        die('Error deleting subscription: ' . $e->getMessage());
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Subscriptions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .form-container {
            margin-bottom: 20px;
        }
        .form-container input, .form-container select {
            margin: 10px 0;
            padding: 8px;
            width: 100%;
        }
        .form-container button {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #218838;
        }
        .action-buttons {
            text-align: right;
        }
    </style>
</head>
<body>

    <h1>Manage Subscriptions</h1>

    <!-- Add Subscription Form -->
    <div class="form-container">
        <h3>Add New Subscription</h3>
        <form method="POST">
            <label for="course_id">Select Course</label>
            <select name="course_id" id="course_id" required>
                <option value="">-- Select Course --</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo $course['id']; ?>"><?php echo htmlspecialchars($course['title']); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="user_id">Select User</label>
            <select name="user_id" id="user_id" required>
                <option value="">-- Select User --</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['username']); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="status">Status</label>
            <select name="status" id="status">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="pending">Pending</option>
            </select>

            <button type="submit" name="add_subscription">Add Subscription</button>
        </form>
    </div>

    <!-- Subscription List -->
    <h3>All Subscriptions</h3>
    <table>
        <thead>
            <tr>
                <th>Course Title</th>
                <th>User Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($subscriptions)): ?>
                <?php foreach ($subscriptions as $sub): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($sub['course_title']); ?></td>
                        <td><?php echo htmlspecialchars($sub['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($sub['status']); ?></td>
                        <td class="action-buttons">
                            <a href="edit_subscription.php?id=<?php echo $sub['id']; ?>">Edit</a> | 
                            <a href="?delete_id=<?php echo $sub['id']; ?>" onclick="return confirm('Are you sure you want to delete this subscription?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No subscriptions found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
