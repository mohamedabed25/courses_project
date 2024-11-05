<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add to Wallet</title>
</head>
<body>
    <h1>if u have not added ur visa into our sys before please contact with admin </h1>
    <h2>Add Amount to Wallet</h2>

    <!-- Display error message if exists -->
    <?php
    if (isset($_SESSION['error'])) {
        echo "<p style='color: red;'>".htmlspecialchars($_SESSION['error'])."</p>";
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo "<p style='color: green;'>".htmlspecialchars($_SESSION['success'])."</p>";
        unset($_SESSION['success']);
    }
    ?>

    <form method="POST" action="add_wallet_logic.php">
        <p>
            <label for="amount">Amount:</label>
            <input type="number" name="amount" id="amount" required step="0.01" min="0">
        </p>
        <h3>Visa Card Details</h3>
        <p>
            <label for="cardholder_name">Cardholder Name:</label>
            <input type="text" name="cardholder_name" id="cardholder_name" required>
        </p>
        <p>
            <label for="card_number">Card Number:</label>
            <input type="text" name="card_number" id="card_number" required pattern="\d{16}">
        </p>
        <p>
            <label for="expiration_date">Expiration Date (YYYY-MM-DD):</label>
            <input type="date" name="expiration_date" id="expiration_date" required>
        </p>
        <p>
            <label for="cvv">CVV:</label>
            <input type="text" name="cvv" id="cvv" required pattern="\d{3}">
        </p>
        <p>
            <label for="billing_address">Billing Address:</label>
            <input type="text" name="billing_address" id="billing_address" required>
        </p>
        <p>
            <label for="phone_number">Phone Number:</label>
            <input type="tel" name="phone_number" id="phone_number" required>
        </p>
        <input type="submit" value="Add to Wallet">
    </form>

    <a href="profile.php">Back to Profile</a>
</body>
</html>
