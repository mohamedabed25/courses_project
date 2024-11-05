<?php
session_start();
include 'connect.php'; // Make sure to include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html"); // Redirect to login page if not logged in
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $amount = floatval(trim($_POST['amount']));
    $cardholder_name = trim($_POST['cardholder_name']);
    $card_number = trim($_POST['card_number']);
    $expiration_date = trim($_POST['expiration_date']);
    $cvv = trim($_POST['cvv']);
    $billing_address = trim($_POST['billing_address']);
    $phone_number = trim($_POST['phone_number']);

    // Prepare SQL query to check if the card details match any records
    $check_query = "SELECT * FROM visa_cards WHERE 
                    cardholder_name = :cardholder_name AND 
                    card_number = :card_number AND 
                    expiration_date = :expiration_date AND 
                    cvv = :cvv AND 
                    phone_number = :phone_number";

    $stmt = $pdo->prepare($check_query);
    $stmt->execute([
        'cardholder_name' => $cardholder_name,
        'card_number' => $card_number,
        'expiration_date' => $expiration_date,
        'cvv' => $cvv,
        'phone_number' => $phone_number,
    ]);

    if ($stmt->rowCount() > 0) {
        // Card details matched, proceed to add to wallet (update user wallet balance)
        $update_query = "UPDATE users SET wallet = wallet + :amount WHERE id = :user_id"; 
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->execute([
            'amount' => $amount,
            'user_id' => $_SESSION['user_id']
        ]);

        $_SESSION['success'] = "Successfully added $amount to your wallet!";
        header("Location: profile.php"); // Redirect back to the profile page
    } else {
        // Card details did not match, display an error message
        $_SESSION['error'] = "Card details do not match. Please check your information.";
        header("Location: add_wallet.php"); // Redirect back to add wallet page
    }
    exit;
}
?>
