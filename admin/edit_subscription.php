<?php
// Include the database connection file
require_once 'connect.php';

// Initialize variables
$subscription = null;
$courses = [];
$users = [];

// Check if the id is provided in the URL and is a valid number
if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
    $id = (int) $_GET['id'];

    try {
        // Fetch the current subscription data
        $stmt = $pdo->prepare("SELECT * FROM subscriptions WHERE id = ?");
        $stmt->execute([$id]);
        $subscription = $stmt->fetch(PDO::FETCH_ASSOC);

        // If no subscription is found, display an error message
        if (!$subscription) {
            die('Subscription not found.');
        }

    } catch (PDOException $e) {
        die('Error fetching subscription: ' . $e->getMessage());
    }
} else {
    die('Invalid subscription ID.');
}

// Fetch all courses for the dropdown
try {
    $stmt = $pdo->prepare("SELECT id, title FROM courses");
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error fetching courses: ' . $e->getMessage());
}

// Fetch all users for the dropdown
try {
    $stmt = $pdo->prepare("SELECT id, username FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error fetching users: ' . $e->getMessage());
}

// Handle the form submission (update subscription)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_subscription'])) {
    $course_id = $_POST['course_id'];
    $user_id = $_POST['user_id'];
    $status = $_POST['status'];

    try {
        // Update the subscription
        $stmt = $pdo->prepare("UPDATE subscriptions SET course_id = ?, user_id = ?, status = ? WHERE id = ?");
        $stmt->execute([$course_id, $user_id, $status, $id]);

        // Redirect to the manage subscriptions page or show a success message
        header("Location: manage_subscriptions.php");
        exit;
    } catch (PDOException $e) {
        die('Error updating subscription: ' . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Subscription</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        form {
            margin: 20px;
        }
        label, select {
            margin-bottom: 10px;
        }
        input[type="submit"] {
            padding: 10px 15px;
            background-color: blue;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: darkblue;
        }
    </style>
</head>
<body>

    <h1>Edit Subscription</h1>

    <?php if ($subscription): ?>
        <form method="POST">
            <label for="course_id">Select Course</label>
            <select name="course_id" id="course_id">
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo $course['id']; ?>" <?php echo ($course['id'] == $subscription['course_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($course['title']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br>

            <label for="user_id">Select User</label>
            <select name="user_id" id="user_id">
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['id']; ?>" <?php echo ($user['id'] == $subscription['user_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($user['username']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br>

            <label for="status">Status</label>
            <select name="status" id="status">
                <option value="active" <?php echo ($subscription['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                <option value="inactive" <?php echo ($subscription['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                <option value="pending" <?php echo ($subscription['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
            </select>
            <br>

            <input type="submit" name="update_subscription" value="Update Subscription">
        </form>
    <?php endif; ?>

</body>
</html>
