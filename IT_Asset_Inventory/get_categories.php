<?php
     // Authenticate if user is logged in
     include ("../auth_session.php");

    
     if($_SESSION["role"] != "admin"){
         header("Location: /Capstone_System/404.php");
         exit();
     }
    include("../config/db_config.php");

    $request = $_POST["request"];

    if($request == "itemTypeCategories"){
        $sql = "SELECT item_type_category FROM ha_item_type_property";        
        if(mysqli_query($conn, $sql)){
            $result = mysqli_query($conn, $sql);
            $itemTypeCategories = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_free_result($result);            
            echo json_encode($itemTypeCategories);
        }
    }
    else if($request == "locationOptions"){
        $sql = "SELECT location_option FROM ha_location_option";        
        if(mysqli_query($conn, $sql)){
            $result = mysqli_query($conn, $sql);
            $locationOptions = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_free_result($result);            
            echo json_encode($locationOptions);
        }
    }
    else if($request == "softwareTypeOptions"){
        $sql = "SELECT software_type_option FROM sa_type_property";        
        if(mysqli_query($conn, $sql)){
            $result = mysqli_query($conn, $sql);
            $softwareTypeOptions = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_free_result($result);            
            echo json_encode($softwareTypeOptions);
        }
    }
    else{
        
    }
    
    
?> 