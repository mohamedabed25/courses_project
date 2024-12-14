<?php
session_start();
include 'connect.php'; // Include your database connection file

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

    try {
        // Check if the card exists and has sufficient funds
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

        $card = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($card) {
            if ($card['amount'] >= $amount) {
                // Begin transaction
                $pdo->beginTransaction();

                // Deduct the amount from the visa card balance
                $deduct_query = "UPDATE visa_cards SET amount = amount - :amount WHERE card_id = :card_id";
                $deduct_stmt = $pdo->prepare($deduct_query);
                $deduct_stmt->execute([
                    'amount' => $amount,
                    'card_id' => $card['card_id'],
                ]);

                // Add the amount to the user's wallet
                $update_query = "UPDATE users SET wallet = wallet + :amount WHERE id = :user_id";
                $update_stmt = $pdo->prepare($update_query);
                $update_stmt->execute([
                    'amount' => $amount,
                    'user_id' => $_SESSION['user_id'],
                ]);

                // Commit transaction
                $pdo->commit();

                $_SESSION['success'] = "Successfully added $amount to your wallet!";
                header("Location: profile.php"); // Redirect back to the profile page
            } else {
                // Insufficient funds in the visa card
                $_SESSION['error'] = "Insufficient funds on the card. Please try a lower amount.";
                header("Location: add_wallet.php"); // Redirect back to add wallet page
            }
        } else {
            // Card details did not match, display an error message
            $_SESSION['error'] = "Card details do not match. Please check your information.";
            header("Location: add_wallet.php"); // Redirect back to add wallet page
        }
    } catch (Exception $e) {
        // Rollback transaction if an error occurs
        $pdo->rollBack();
        $_SESSION['error'] = "An error occurred: " . $e->getMessage();
        header("Location: add_wallet.php");
    }

    exit;
}
?>
