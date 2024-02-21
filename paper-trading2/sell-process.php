<?php
session_start();

// Connect to database
$db = require __DIR__ . "/database.php";

// Check the connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $stock_symbol = $_POST['stock_symbol'] ?? '';
    $sell_quantity = $_POST['sell_quantity'] ?? 0;
    $sell_price = $_POST['sell_price'] ?? 0;

    // Validate inputs (adjust as needed)
    if (empty($stock_symbol) || $sell_quantity <= 0 || $sell_price <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit;
    }

    // Calculate the total amount from the sell transaction
    $total_amount = $sell_quantity * $sell_price;

    // Execute the sell transaction
    $sql_sell = "INSERT INTO transactions (user_id, stock_symbol, type, shares, price,created_at) VALUES ('$user_id', '$stock_symbol', 'sell', '$sell_quantity', '$sell_price',CURRENT_TIMESTAMP)";
    $result_sell = $db->query($sql_sell);

    // Update the wallet balance only is transaction successfull
    if ($result_sell) {
        $sql_update_wallet = "UPDATE users SET funds = funds + '$total_amount' WHERE id = '$user_id'";
        $result_update_wallet = $db->query($sql_update_wallet);
        if ($result_update_wallet) {
            echo json_encode(['success' => true, 'message' => 'Sell transaction successful']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating wallet balance']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error executing sell transaction']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
}
// Close the connection
$db->close();
?>
