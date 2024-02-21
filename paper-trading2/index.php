<?php
    session_start();
    //print_r($_SESSION);
    if(isset($_SESSION["user_id"])){
        $db = require __DIR__."/database.php";
        $sql = "SELECT * FROM users where id = {$_SESSION["user_id"]}";

        $result = $db->query($sql);

        $user = $result->fetch_assoc();

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <link rel="stylesheet" href="./CSS/index.css"> <!-- External CSS file -->
</head>
<body>
    <h1></h1>
        <div class="container">
        <?php if($user) :?>
            <h2 style="color:white;"> Hello <?= htmlspecialchars($user["name"]) ?></h2>
            <h3 style="color:white;"> Your Wallet Balance is : $<?= $user['funds'] ?></h3>
            <div class="logout">
                    <button class="logout-btn" onclick="location.href='./main.html'">Log out</button>
                    <p class="logout-link"><a href="./main.html">Log out</a></p>
                </div>
            <?php endif; ?>   

        <button class="option" onclick="window.location='/paper-trading2/buy/buy.html'">
            <h2>Buy Stocks</h2>
        </button>

        <button class="option" onclick="window.location='/paper-trading2/sell/sell.html'">
            <h2>Sell Stocks</h2>
        </button>
    </div>
</body>
</html>