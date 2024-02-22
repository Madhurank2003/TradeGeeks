<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "paper_trading";

    $db = new mysqli($host,$username,$password,$dbname);

    if($db->connect_errno){
        die("Connection Error!!".$db->connect_error);
    }

    return $db;
?>
