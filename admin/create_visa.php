<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html"); // Redirect to the login page if the user is not logged in
    exit; // Stop further script execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Visa Card</title>
    <style>
        /* General page styles */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #e0f7ff, #ffffff);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            color: #0077b6;
            margin-top: 20px;
        }

        /* Background circles */
        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(0, 119, 182, 0.1);
            z-index: -1;
        }

        .circle1 {
            width: 200px;
            height: 200px;
            top: 50px;
            left: 20%;
        }

        .circle2 {
            width: 300px;
            height: 300px;
            bottom: 100px;
            right: 20%;
        }

        /* Form styles */
        form {
            background: #ffffff;
            padding: 20px;
            margin: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #0077b6;
        }

        input[type="text"], input[type="date"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        input[type="submit"] {
            background: #0077b6;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        input[type="submit"]:hover {
            background: #005f8b;
        }

        a {
            color: #0077b6;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            form {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="circle circle1"></div>
    <div class="circle circle2"></div>

    <h1>Create a Visa Card</h1>

    <!-- Form to Create Visa Card -->
    <form method="post" action="create_visa_logic.php">
        <label for="cardholder_name">Cardholder Name:</label>
        <input type="text" name="cardholder_name" required>

        <label for="card_number">Card Number:</label>
        <input type="text" name="card_number" required pattern="\d{16}">

        <label for="expiration_date">Expiration Date (YYYY-MM-DD):</label>
        <input type="date" name="expiration_date" required>

        <label for="cvv">CVV:</label>
        <input type="text" name="cvv" required pattern="\d{3}">

        <label for="billing_address">Billing Address:</label>
        <input type="text" name="billing_address">

        <label for="phone_number">Phone Number:</label>
        <input type="text" name="phone_number">

        <label for="email">Email:</label>
        <input type="email" name="email">

        <input type="submit" value="Create Card">
    </form>
</body>
</html>