<?php
// Ensure session is started
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html"); // Redirect to login page if not logged in
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

        /* Table styles */
        table {
            border-collapse: collapse;
            width: 90%;
            margin: 20px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background: #ffffff;
        }

        th, td {
            text-align: left;
            padding: 10px;
        }

        th {
            background: #0077b6;
            color: #ffffff;
        }

        tr:nth-child(even) {
            background: #e6f7ff;
        }

        tr:hover {
            background: #cceeff;
        }

        td a {
            text-decoration: none;
            color: #0077b6;
            font-weight: bold;
        }

        td a:hover {
            text-decoration: underline;
        }

        /* Button styles */
        a.button {
            display: inline-block;
            background: #0077b6;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }

        a.button:hover {
            background: #005f8b;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            table {
                font-size: 14px;
            }

            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="circle circle1"></div>
    <div class="circle circle2"></div>

    <h1>View Visa Cards</h1>
<!-- Display Visa Cards Table -->
    <?php if (!empty($visa_cards)): ?>
        <table>
            <thead>
                <tr>
                    <th>Card ID</th>
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
                        <td><?php echo isset($visa_card['card_id']) ? htmlspecialchars($visa_card['card_id']) : 'N/A'; ?></td>
                        <td><?php echo isset($visa_card['cardholder_name']) ? htmlspecialchars($visa_card['cardholder_name']) : 'N/A'; ?></td>
                        <td><?php echo isset($visa_card['card_number']) ? htmlspecialchars($visa_card['card_number']) : 'N/A'; ?></td>
                        <td><?php echo isset($visa_card['expiration_date']) ? htmlspecialchars($visa_card['expiration_date']) : 'N/A'; ?></td>
                        <td><?php echo isset($visa_card['cvv']) ? htmlspecialchars($visa_card['cvv']) : 'N/A'; ?></td>
                        <td><?php echo isset($visa_card['billing_address']) ? htmlspecialchars($visa_card['billing_address']) : 'N/A'; ?></td>
                        <td><?php echo isset($visa_card['phone_number']) ? htmlspecialchars($visa_card['phone_number']) : 'N/A'; ?></td>
                        <td><?php echo isset($visa_card['email']) ? htmlspecialchars($visa_card['email']) : 'N/A'; ?></td>
                        <td>
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
    <a href="admin_dashboard.php" class="button">Back to Dashboard</a>
</body>
</html>