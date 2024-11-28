<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html");
    exit();
}

require 'connect.php';  // Include the database connection

// Check if the card_id is provided
if (isset($_GET['card_id'])) {
    $card_id = $_GET['card_id'];

    // Prepare and execute the delete query using card_id
    $stmt = $pdo->prepare("DELETE FROM visa_cards WHERE card_id = ?");
    $stmt->execute([$card_id]);

    // Redirect back to the visa cards page
    header("Location: visa_cards.php");
    exit();
} else {
    echo "Card ID is required.";
    exit();
}
?>
