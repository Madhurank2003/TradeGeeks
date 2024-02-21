<?php
    $db = require __DIR__."/database.php";
    //session_start();
    $user_id = $_SESSION["user_id"];
    $sql = sprintf("SELECT funds from users where id =  '%s'",$user_id);
    $result = $db->query($sql);
    $row = $result->fetch_assoc();
    // header("Content-Type: application/json");
    // echo json_encode(["funds" => $row["funds"]]);
    return $row["funds"];
?>