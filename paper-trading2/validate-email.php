<?php
    $db = require __DIR__."/database.php";

    $sql = sprintf("SELECT * FROM users WHERE email = '%s'",$db->real_escape_string($_GET["email"]));

    $result = $db->query($sql);

    $isAvailable = $result->num_rows===0;

    header("Content-Type: application/json");
    echo json_encode(["available" => $isAvailable]);
?>