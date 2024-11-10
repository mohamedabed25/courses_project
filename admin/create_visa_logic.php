<?php
// Include the database connection file
require '../users/connect.php';

// Initialize variables
$cardholder_name = "";
$card_number = "";
$expiration_date = "";
$cvv = "";
$billing_address = "";
$phone_number = "";
$email = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cardholder_name = $_POST['cardholder_name'];
    $card_number = $_POST['card_number'];
    $expiration_date = $_POST['expiration_date'];
    $cvv = $_POST['cvv'];
    $billing_address = $_POST['billing_address'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];

    try {
        // Prepare SQL statement
        $stmt = $pdo->prepare("INSERT INTO visa_cards (cardholder_name, card_number, expiration_date, cvv, billing_address, phone_number, email) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        // Execute statement
        if ($stmt->execute([$cardholder_name, $card_number, $expiration_date, $cvv, $billing_address, $phone_number, $email])) {
            echo "New card record created successfully!";
        } else {
            echo "Error inserting record.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>