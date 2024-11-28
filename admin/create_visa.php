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
</head>
<body>
    <h1>Create a Visa Card</h1>
    <form method="post" action="create_visa_logic.php">
        <label for="cardholder_name">Cardholder Name:</label>
        <input type="text" name="cardholder_name" required><br><br>

        <label for="card_number">Card Number:</label>
        <input type="text" name="card_number" required pattern="\d{16}"><br><br>

        <label for="expiration_date">Expiration Date (YYYY-MM-DD):</label>
        <input type="date" name="expiration_date" required><br><br>

        <label for="cvv">CVV:</label>
        <input type="text" name="cvv" required pattern="\d{3}"><br><br>

        <label for="billing_address">Billing Address:</label>
        <input type="text" name="billing_address"><br><br>

        <label for="phone_number">Phone Number:</label>
        <input type="text" name="phone_number"><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email"><br><br>

        <input type="submit" value="Create Card">
    </form>
</body>
</html>
