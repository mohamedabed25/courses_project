<?php
// Ensure session is started
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php"); // Redirect to login page if not logged in
    exit();
}

// Include database connection
require 'connect.php'; 

// Enable error reporting for debugging (Remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch all Visa cards for display
try {
    $stmt = $pdo->prepare("SELECT * FROM visa_cards");
    $stmt->execute();
    $visa_cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching Visa cards: " . $e->getMessage();
    $visa_cards = []; // Ensure $visa_cards is always set
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Visa Cards</title>
</head>
<body>
    <h1>View Visa Cards</h1>

    <!-- Display Visa Cards Table -->
    <?php if (!empty($visa_cards)): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>card_id</th>
                    <th>Cardholder Name</th>
                    <th>Card Number</th>
                    <th>Expiration Date</th>
                    <th>CVV</th>
                    <th>Billing Address</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($visa_cards as $visa_card): ?>
                    <tr>
                        <!-- Check if 'card_id' exists before displaying -->
                        <td><?php echo isset($visa_card['card_id']) ? htmlspecialchars($visa_card['card_id']) : 'N/A'; ?></td>
                        <td><?php echo isset($visa_card['cardholder_name']) ? htmlspecialchars($visa_card['cardholder_name']) : 'N/A'; ?></td>
                        <td><?php echo isset($visa_card['card_number']) ? htmlspecialchars($visa_card['card_number']) : 'N/A'; ?></td>
                        <td><?php echo isset($visa_card['expiration_date']) ? htmlspecialchars($visa_card['expiration_date']) : 'N/A'; ?></td>
                        <td><?php echo isset($visa_card['cvv']) ? htmlspecialchars($visa_card['cvv']) : 'N/A'; ?></td>
                        <td><?php echo isset($visa_card['billing_address']) ? htmlspecialchars($visa_card['billing_address']) : 'N/A'; ?></td>
                        <td><?php echo isset($visa_card['phone_number']) ? htmlspecialchars($visa_card['phone_number']) : 'N/A'; ?></td>
                        <td><?php echo isset($visa_card['email']) ? htmlspecialchars($visa_card['email']) : 'N/A'; ?></td>
                        <td>
                            <!-- Check if 'card_id' exists for actions -->
                            <?php if (isset($visa_card['card_id'])): ?>
                                <a href="edit_visa_card.php?card_id=<?php echo $visa_card['card_id']; ?>">Edit</a> | 
                                <a href="delete_visa_card.php?card_id=<?php echo $visa_card['card_id']; ?>" onclick="return confirm('Are you sure you want to delete this Visa card?');">Delete</a>
                            <?php else: ?>
                                <span>No actions</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No Visa cards found.</p>
    <?php endif; ?>

    <br>
    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>
