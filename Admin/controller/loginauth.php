
<?php
include "../model/DatabaseConnection.php";

session_start();

$email = $_REQUEST["email"];
$password = $_REQUEST["password"];

$errors = [];
$values = [];

if(!$email){
$errors["email"] = "Email field is required";
}

if(!$password){
    $errors["password"] = "Password field is required";
}

if(count($errors) > 0){
    if($errors["email"]){
        $_SESSION["emailErr"] = $errors["email"];
    }
    else{
       if($_SESSION["emailErr"]){
         unset($_SESSION["emailErr"]);
       }
    }

    if($errors["password"]){
        $_SESSION["passwordErr"] = $errors["password"];
    }

$values["email"] = $email;
$_SESSION["previousValues"] = $values;

Header("Location: ..\view\login.php");

}else{
    $db = new DatabaseConnection();
    $connection = $db->openConnection();
    $result = $db->signin($connection, "users", $email, $password);


    if($result->num_rows  == 1){
        $_SESSION["email"] = $data["email"];
        $_SESSION["isLoggedIn"] =true;
        Header("Location: ..\view\dashboard.php");

    }else{
      $_SESSION["LoginErr"] = "Email or password is incorrect";  
      Header("Location: ..\view\login.php");
      unset($_SESSION["emailErr"]);
      unset($_SESSION["passwordErr"]);
    }


    
}

?>
