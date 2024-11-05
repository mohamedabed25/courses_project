<?php
session_start();
include 'connect.php'; // Adjust the path as needed to locate connect.php

// Check if the form is submitted   
if (isset($_POST['signup'])) {
    // Retrieve and sanitize form data
    $username = trim($_POST['username']);
    $password = $_POST['password']; // Keeping the plain password (not recommended)
    $age = (int)$_POST['age'];
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $country = trim($_POST['country']);

    // Check if the email or phone number already exists
    $check_query = "SELECT * FROM users WHERE email = :email OR phone = :phone";
    $stmt = $pdo->prepare($check_query);
    $stmt->execute(['email' => $email, 'phone' => $phone]);
    
    if ($stmt->rowCount() > 0) {
        // Redirect back with an error message
        $_SESSION['error'] = "Email or phone number already exists";
        header("Location: login.html"); // Assuming this is the registration page
        exit;
    } else {
        // Initialize the photo variable
        $photoName = null;

        // File upload logic
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            $photoName = basename($_FILES['photo']['name']);
            $uploadFilePath = $uploadDir . $photoName;

            // Ensure the uploads directory exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true); // Create the directory if it doesn't exist
            }

            // Move the uploaded file to the uploads directory
            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFilePath)) {
                $_SESSION['error'] = "Failed to upload photo. Check directory permissions.";
                header("Location: login.html"); // Redirect on error
                exit;
            }
        }

        // Prepare the SQL insert query
        $insert_query = "INSERT INTO users (username, password, age, email, phone, country" . 
                        ($photoName ? ', photo' : '') . 
                        ") VALUES (:username, :password, :age, :email, :phone, :country" . 
                        ($photoName ? ', :photo' : '') . 
                        ")";

        $stmt = $pdo->prepare($insert_query);

        // Prepare the parameters array
        $params = [
            'username' => $username,
            'password' => $password, // Keeping the plain password
            'age' => $age,
            'email' => $email,
            'phone' => $phone,
            'country' => $country,
        ];

        // Add photo parameter only if it's not null
        if ($photoName) {
            $params['photo'] = $photoName;
        }

        // Execute the query
        if ($stmt->execute($params)) {
            header("Location: login.html"); // Redirect to login page after successful registration
            exit;
        } else {
            $_SESSION['error'] = "Registration failed. Please try again.";
            header("Location: login.html"); // Redirect on error
            exit;
        }
    }
}
?>
