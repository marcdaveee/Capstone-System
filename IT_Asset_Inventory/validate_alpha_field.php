<?php 
     // Authenticate if user is logged in
     include ("../auth_session.php");

    
     if($_SESSION["role"] != "admin"){
         header("Location: /Capstone_System/404.php");
         exit();
     }
     
    $value = $_POST["input"];        
    $type = htmlspecialchars($_POST["type"]);
    
    
    if($value == "" || $value == "None" || $value == "none"){
        echo "*required";
    }
    else{
        if($type == "date"){
            echo "Good";
        }
        else if(!preg_match('/^[a-zA-Z\s]+$/', $value)){ 
            echo "must be combination of letters and spaces only";
        }else{
            echo "Good";
        }
    }
    
?>