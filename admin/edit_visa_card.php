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
</head>
<body>
    <h1>Edit Visa Card</h1>

    <!-- Form to Edit Visa Card -->
    <form method="post" action="edit_visa_card.php?card_id=<?php echo $visa_card['card_id']; ?>">
        <label for="cardholder_name">Cardholder Name:</label>
        <input type="text" name="cardholder_name" value="<?php echo htmlspecialchars($visa_card['cardholder_name']); ?>" required><br><br>

        <label for="card_number">Card Number:</label>
        <input type="text" name="card_number" value="<?php echo htmlspecialchars($visa_card['card_number']); ?>" required pattern="\d{16}"><br><br>

        <label for="expiration_date">Expiration Date (YYYY-MM-DD):</label>
        <input type="date" name="expiration_date" value="<?php echo htmlspecialchars($visa_card['expiration_date']); ?>" required><br><br>

        <label for="cvv">CVV:</label>
        <input type="text" name="cvv" value="<?php echo htmlspecialchars($visa_card['cvv']); ?>" required pattern="\d{3}"><br><br>

        <label for="billing_address">Billing Address:</label>
        <input type="text" name="billing_address" value="<?php echo htmlspecialchars($visa_card['billing_address']); ?>"><br><br>

        <label for="phone_number">Phone Number:</label>
        <input type="text" name="phone_number" value="<?php echo htmlspecialchars($visa_card['phone_number']); ?>"><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($visa_card['email']); ?>"><br><br>

        <input type="submit" name="update_visa_card" value="Update Card">
    </form>

    <br>
    <a href="visa_cards.php">Back to Visa Cards List</a>
</body>
</html>
