<?php
    // if Name field is Empty , show error message
    if(empty($_POST['name'])){
        die("Name is Required!!");
    }
    // Validate the Email if not  validated at the client browser
    if(! filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        die("Valid Email Required!!");
    }
    //Checking if the Password is of atleast length 8
    if(strlen($_POST["password"])<8){
        die("Password must be of atleast 8 Characters!!");
    }
    //Password must have atleast one lower-case character
    if(!preg_match("/[a-z]/i", $_POST["password"])){
        die("Password must have atleast one lower-case alphabet");
    }
    // Password must have atleast one digit
    if(!preg_match("/[0-9]/",$_POST["password"])){
        die("Password must have atleast one digit");
    }
    //Passwords must match
    if($_POST["password"]!=$_POST["confirm_password"]){
        die("Passwords don't match!!");
    }
    // Hash the Password for Security Issues
    $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $db = require __DIR__ . "/database.php";
    $sql = "INSERT INTO users (name,email,password_hash,funds) values (?,?,?,?)";
    $dummyFund = 500;
    $stmt = $db->stmt_init();
    if(!$stmt->prepare($sql)){
        die("SQL Error : ".$db->connect_error);
    }
    $stmt->bind_param("sssi",$_POST["name"],$_POST["email"], $password_hash,$dummyFund);
    try{
        if($stmt->execute()){
            header("Location: signup-success.html");
            exit;
        }
    }
    catch(Exception $e){
        if($db->errno==1062){
            die("Email already Exists!!");
        }
        else{
            die($db->error." ".$db->errno);
        }
    }
?>