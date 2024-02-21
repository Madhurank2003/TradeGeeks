<?php
// Update the Session Array
session_start();

header('Content-Type: application/json');
// Check if User is Logged in
if (isset($_SESSION["user_id"])) {
    $user = $_SESSION["user_id"];

    // Check if User has initiated a transaction
    if (isset($_POST["currCost"], $_POST["currStock"], $_POST["quantity"])) {
        // Store all the necessary info
        $cost = $_POST["currCost"];
        $stock_sym = $_POST["currStock"];
        $quantity = $_POST["quantity"];
        $perShare = $_POST["perShare"];

        // Connect to the database
        $db = require __DIR__ . "/database.php";

        // Get user balance
        $user_funds = require __DIR__ . "/get-balance.php";

        // $user_funds_str = json_decode($user_funds_info,true);
        // var_dump($user_funds_str);
        // $user_funds = $user_funds_str['funds'];

        // Check for sufficient balance
        if ($user_funds >= $cost) {
            // Update the user's wallet balance
            $newBalance = $user_funds - $cost;
            $updateSql = "UPDATE users SET funds = $newBalance WHERE id = $user";
            $result = $db->query($updateSql);

            if (!$result) {
                echo json_encode(["status" => "fail", "msg" => "Error updating user's balance"]);
                $db->close();
                exit;
            }

            // Update the transactions table
            $updateTransaction = "INSERT INTO transactions (user_id, stock_symbol, type, shares, price, created_at) VALUES ($user, '$stock_sym', 'buy', $quantity, $perShare, CURRENT_TIMESTAMP)";
            $result = $db->query($updateTransaction);

            if (!$result) {
                echo json_encode(["status" => "fail", "msg" => "Error updating transactions"]);
                $db->close();
                exit;
            }

            // Success!!
            header('Content-Type: application/json');
            echo json_encode(["status" => "success", "msg" => "Transaction successful", "cost" => $cost , "bal" => $user_funds]);
        } else {
            echo json_encode(["status" => "fail", "msg" => "Insufficient balance"]);
            $db->close();
            exit;
        }
    } else {
        echo json_encode(["status" => "fail", "msg" => "Invalid input"]);
        exit;
    }
} else {
    echo json_encode(["status" => "fail", "msg" => "User not logged in"]);
    exit;
}
