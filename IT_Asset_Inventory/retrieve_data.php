<?php 
     // Authenticate if user is logged in
     include ("../auth_session.php");

    
     if($_SESSION["role"] != "admin"){
         header("Location: /Capstone_System/404.php");
         exit();
     }

    require("config/db_config.php");
    $assetType = $_POST["assetType"];

    if($assetType == "hardware-category"){
        
        $sql = "SELECT * FROM hardware_asset";
        if(mysqli_query($conn, $sql)){
            $_SESSION["currentCategory"] = "hardwareAsset";
        }        
    }
    else if($assetType == "software-category"){
        $sql = "SELECT * FROM software_asset";
        if(mysqli_query($conn, $sql)){
            $_SESSION["currentCategory"] = "softwareAsset";
        }
    }
    else{
        $sql = "SELECT * FROM data_asset";
        if(mysqli_query($conn, $sql)){
            $_SESSION["currentCategory"] = "dataAsset";
        }
        
    }

?>