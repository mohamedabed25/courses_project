<?php
// Start session and check if admin is logged in
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html"); // Redirect to login if not logged in
    exit();
}

// Include the database connection
require 'connect.php'; 

// Enable error reporting for debugging (Remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch the visa card data based on the card_id passed in the URL
if (isset($_GET['card_id'])) {
    $card_id = $_GET['card_id'];

    // Prepare the SQL statement to fetch the visa card data by card_id
    $stmt = $pdo->prepare("SELECT * FROM visa_cards WHERE card_id = ?");
    $stmt->execute([$card_id]);
    $visa_card = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the visa card exists
    if (!$visa_card) {
        echo "Visa card not found.";
        exit();
    }
} else {
    echo "Invalid Visa card ID.";
    exit();
}

// Handle the form submission to update the visa card data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_visa_card'])) {
    // Sanitize and retrieve form inputs
    $cardholder_name = htmlspecialchars(trim($_POST['cardholder_name']));
    $card_number = htmlspecialchars(trim($_POST['card_number']));
    $expiration_date = htmlspecialchars(trim($_POST['expiration_date']));
    $cvv = htmlspecialchars(trim($_POST['cvv']));
    $billing_address = htmlspecialchars(trim($_POST['billing_address']));
    $phone_number = htmlspecialchars(trim($_POST['phone_number']));
    $email = htmlspecialchars(trim($_POST['email']));

    // Prepare the SQL statement to update the visa card
    $stmt = $pdo->prepare("UPDATE visa_cards SET cardholder_name = ?, card_number = ?, expiration_date = ?, cvv = ?, billing_address = ?, phone_number = ?, email = ? WHERE card_id = ?");
    $stmt->execute([$cardholder_name, $card_number, $expiration_date, $cvv, $billing_address, $phone_number, $email, $card_id]);

    // Redirect to the Visa cards list page after update
    header("Location: visa_cards.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Visa Card</title>
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

    <h1>Edit Visa Card</h1>

    <!-- Form to Edit Visa Card -->
    <form method="post" action="edit_visa_card.php?card_id=<?php echo $visa_card['card_id']; ?>">
        <label for="cardholder_name">Cardholder Name:</label>
        <input type="text" name="cardholder_name" value="<?php echo htmlspecialchars($visa_card['cardholder_name']); ?>" required>

        <label for="card_number">Card Number:</label>
        <input type="text" name="card_number" value="<?php echo htmlspecialchars($visa_card['card_number']); ?>" required pattern="\d{16}">

        <label for="expiration_date">Expiration Date (YYYY-MM-DD):</label>
        <input type="date" name="expiration_date" value="<?php echo htmlspecialchars($visa_card['expiration_date']); ?>" required>

        <label for="cvv">CVV:</label>
        <input type="text" name="cvv" value="<?php echo htmlspecialchars($visa_card['cvv']); ?>" required pattern="\d{3}">

        <label for="billing_address">Billing Address:</label>
        <input type="text" name="billing_address" value="<?php echo htmlspecialchars($visa_card['billing_address']); ?>">

        <label for="phone_number">Phone Number:</label>
        <input type="text" name="phone_number" value="<?php echo htmlspecialchars($visa_card['phone_number']); ?>">

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($visa_card['email']); ?>">

        <input type="submit" name="update_visa_card" value="Update Card">
    </form>
<br>
    <a href="visa_cards.php">Back to Visa Cards List</a>
</body>
</html>