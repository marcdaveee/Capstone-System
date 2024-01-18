<?php 

     // Authenticate if user is logged in
     include ("../auth_session.php");

    
     if($_SESSION["role"] != "admin"){
         header("Location: /Capstone_System/404.php");
         exit();
     }
     
    $type  = $_POST["type"];
    $value = $_POST["input"];
    
    if($value == "" || $value == "None" || $value == "none"){
        echo "*required";
    }
    else{
        if(!preg_match('/^[a-zA-Z0-9-.\s]+$/', $value)){ 
            echo "must be combination of letters, numbers and spaces only";
        }                
        else if(ifExist($type, $value)){
            if($type == "serialNo"){
                echo "serial number already exists";    
            }
            else if($type == "location"){
                echo "Location already exists";    
            }
            else if($type == "productId"){
                echo "Product ID already exists";    
            }
            else if($type == "softwareType"){
                echo "Software Type already exists";
            }
            else{
                echo "Item Type already exists";    
            }            
            
        }
        else{
            echo "Good";
        }
    }
        
    function ifExist($type, $value){
        include("../config/db_config.php");
        if($type == "serialNo"){
            $sql = "SELECT * FROM hardware_asset";
        
            $result = mysqli_query($conn, $sql);
        
            $assets = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
            foreach($assets as $asset){
                if(strtoupper($asset["serial_no"]) == strtoupper($value)){
                    mysqli_free_result($result);
                    mysqli_close($conn);        
                    return true;
                }
            }
        }
        else if($type == "location"){
            $sql = "SELECT * FROM ha_location_option";
        
            $result = mysqli_query($conn, $sql);
        
            $assets = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
            foreach($assets as $asset){
                if(strtoupper($asset["location_option"]) == strtoupper($value)){
                    mysqli_free_result($result);
                    mysqli_close($conn);        
                    return true;
                }
            }
        }
        else if($type == "itemType"){
            $sql = "SELECT * FROM ha_item_type_property";
        
            $result = mysqli_query($conn, $sql);
        
            $assets = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
            foreach($assets as $asset){
                if(strtoupper($asset["item_type_category"]) == strtoupper($value)){
                    mysqli_free_result($result);
                    mysqli_close($conn);        
                    return true;
                }
            }
        }
        else if($type == "productId"){
            $sql = "SELECT * FROM software_asset";
        
            $result = mysqli_query($conn, $sql);
        
            $assets = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
            foreach($assets as $asset){
                if(strtoupper($asset["product_id"]) == strtoupper($value)){
                    mysqli_free_result($result);
                    mysqli_close($conn);        
                    return true;
                }
            }
        }
        else if($type == "softwareType"){
            $sql = "SELECT * FROM sa_type_property";
        
            $result = mysqli_query($conn, $sql);
        
            $assets = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
            foreach($assets as $asset){
                if(strtoupper($asset["software_type_option"]) == strtoupper($value)){
                    mysqli_free_result($result);
                    mysqli_close($conn);        
                    return true;
                }
            }
        }
        else{
            return false;
        }
    }
?>