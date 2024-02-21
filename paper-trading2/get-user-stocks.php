<?php
session_start();

// Include the database connection
$conn = require __DIR__ . "/database.php";

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch net shares of each stock for the user
    $sql = "SELECT stock_symbol, SUM(CASE WHEN type = 'buy' THEN shares ELSE -shares END) as net_shares
            FROM transactions
            WHERE user_id = '$user_id'
            GROUP BY stock_symbol
            HAVING net_shares > 0";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $stocks = [];
        while ($row = $result->fetch_assoc()) {
            $stocks[] = $row;
        }
        // Return the stocks as JSON
        echo json_encode(['success' => true, 'stocks' => $stocks]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User has no stocks']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
}
// Close the connection
$conn->close();
?>
