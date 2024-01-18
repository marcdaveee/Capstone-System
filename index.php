<?php 
    session_start();

    if(isset($_SESSION["username"]) && $_SESSION["role"] == "user"){
        header("Location: /Capstone_System/storeshare-u/root.php");
        exit();
    }

    if(isset($_SESSION["username"]) && $_SESSION["role"] == "admin"){
        header("Location: /Capstone_System/storeshare-admin/root.php");
        exit();
    }

    header("Location: /Capstone_System/login.php");

?>