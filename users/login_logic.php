<?php
include 'connect.php'; // Make sure this is included

if ($_SERVER["REQUEST_METHOD"] == "POST") {   # check if the request method is POST to prevent any conflections 
    // Ensure the session is started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Retrieve form data
    $email = trim($_POST['email']); # trim function for removing whitespace
    $password = trim($_POST['password']);

    // Check if $pdo is defined
    if (isset($pdo)) {
        // Prepare statement to prevent SQL injection
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
        $stmt->bindParam(':email', $email); // Bind the email parameter
        $stmt->bindParam(':password', $password); // Bind the password parameter
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            // Store user details in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['age'] = $user['age'] ?? '';
            $_SESSION['email'] = $user['email'] ?? '';
            $_SESSION['phone'] = $user['phone'] ?? '';
            $_SESSION['country'] = $user['country'] ?? '';
            $_SESSION['photo'] = isset($user['photo']) ? 'uploads/' . $user['photo'] : ''; // Set photo path

            // Regenerate session ID to prevent session fixation attacks
            session_regenerate_id(true);

            header("Location: profile.php"); // Redirect to profile page
            exit;
        } else {
            echo "Invalid email or password. Please try again.";
        }

        // Close the prepared statement
        $stmt->closeCursor(); // Use this for PDO
    } else {
        echo "Database connection not established."; // Debugging line
    }
}

// No need to close the connection explicitly with PDO; it closes automatically when the script ends
?>
