<?php
    $is_invalid = false;
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        $db = require __DIR__."/database.php";

        $sql = sprintf("SELECT * FROM users WHERE email = '%s'",$db->real_escape_string($_POST["email"]));

        $result = $db->query($sql);

        $user = $result->fetch_assoc();

        if($user){
            // Verifying if the user's password  matches with the one provided by the user
            if(password_verify($_POST["password"],$user["password_hash"])){
                session_start();
                session_regenerate_id();
                $_SESSION["user_id"] = $user["id"];
                header("Location: index.php");
                exit;
            }
        }
        $is_invalid = true;
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogIn</title>
    <link rel="stylesheet" href="./CSS/login.css">
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <?php if($is_invalid): ?>
            <em>Invalid Login</em>
        <?php endif;?>
        <form method = "post">
            <label for = "email">Email</label>
            <input type = "email" name = "email" id = "email"
            value = "<?= htmlspecialchars($_POST["email"] ?? "") ?>">
            
            <label for = "password">Password</label>
            <input type = "password" name = "password" id = "password">
            
            <button type = "submit">Login</button>
        </form>
    </div>
</body>
</html>