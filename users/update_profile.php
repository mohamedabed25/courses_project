<?php
// update_profile.php

require 'connect.php'; // Include the PDO connection file

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $username = htmlspecialchars(trim($_POST['username']));
    $age = htmlspecialchars(trim($_POST['age']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $country = htmlspecialchars(trim($_POST['country']));

    // Update session variables
    $_SESSION['username'] = $username;
    $_SESSION['age'] = $age;
    $_SESSION['email'] = $email;
    $_SESSION['phone'] = $phone;
    $_SESSION['country'] = $country;

    // Check if user_id is set in the session
    if (!isset($_SESSION['user_id'])) {
        die("User not logged in.");
    }

    // Prepare the update statement
    $stmt = $pdo->prepare("UPDATE users SET username = ?, age = ?, email = ?, phone = ?, country = ? WHERE id = ?");
    
    // Execute the statement
    if ($stmt->execute([$username, $age, $email, $phone, $country, $_SESSION['user_id']])) {
        // Redirect or show a success message
        header("Location: profile.php"); // Redirect to the profile page or wherever you want
        exit();
    } else {
        // Handle error if the update fails
        echo "Error updating profile.";
    }
}
?>
