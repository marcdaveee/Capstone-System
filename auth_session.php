<?php 
    session_start();

    if(!isset($_SESSION["username"])){
        header("Location: /Capstone_System/login.php");
        exit();
    }

    
?>