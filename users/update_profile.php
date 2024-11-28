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

    // Handle photo upload if a file is provided
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $fileTmpPath = $_FILES['photo']['tmp_name'];
        $fileName = $_FILES['photo']['name'];
        $fileSize = $_FILES['photo']['size'];
        $fileType = $_FILES['photo']['type'];

        // Ensure the file is an image
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($fileType, $allowedTypes)) {
            // Define the upload directory and new file name
            $uploadDir = 'uploads\\';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Create the directory if it doesn't exist
            }
            $newFileName = uniqid() . '-' . $fileName;
            $destPath = $uploadDir . $newFileName;

            // Move the uploaded file to the desired location
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // Update the session with the new photo path
                $_SESSION['photo'] = $destPath;

                // Update the photo path in the database
                $stmt = $pdo->prepare("UPDATE users SET username = ?, age = ?, email = ?, phone = ?, country = ?, photo = ? WHERE id = ?");
                $stmt->execute([$username, $age, $email, $phone, $country, $destPath, $_SESSION['user_id']]);
            } else {
                // Handle error in uploading file
                echo "Error uploading file.";
            }
        } else {
            echo "Invalid file type. Only images are allowed.";
        }
    } else {
        // If no file was uploaded, just update the user info
        $stmt = $pdo->prepare("UPDATE users SET username = ?, age = ?, email = ?, phone = ?, country = ? WHERE id = ?");
        $stmt->execute([$username, $age, $email, $phone, $country, $_SESSION['user_id']]);
    }

    // Redirect or show a success message
    header("Location: profile.php"); // Redirect to the profile page or wherever you want
    exit();
}
?>
