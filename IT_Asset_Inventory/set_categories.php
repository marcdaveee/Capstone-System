<?php
 // Authenticate if user is logged in
 include ("../auth_session.php");

    
 if($_SESSION["role"] != "admin"){
     header("Location: /Capstone_System/404.php");
     exit();
 }
 
    include("../config/db_config.php");

    $request = $_POST["request"];

    if($request == "addNewCategory"){
        $newCategory = htmlspecialchars($_POST["data"]);        

        if($newCategory == "" || $newCategory == "None" || $newCategory == "none"){
            echo "*required";
        }
        else{
            if(!preg_match('/^[a-zA-Z0-9-.\s]+$/', $newCategory)){ 
                echo "must be combination of letters, numbers and spaces only";
            }
            else if(ifExist($request, $newCategory)){                                
                echo "Item Type already exists";                    
                // check duplication in software asset as well
            }
            else{
                $sql = "INSERT INTO ha_item_type_property (item_type_category)
                VALUES ('$newCategory')";
                mysqli_query($conn, $sql);                
                mysqli_close($conn);
                echo "Good!";
            }
        }        
    }
    else if ($request == "addSoftwareCategory"){
        $newSoftwareOption = htmlspecialchars($_POST["data"]);

        if($newSoftwareOption == "" || $newSoftwareOption == "None" || $newSoftwareOption == "none"){
            echo "*required";
        }
        else{
            if(!preg_match('/^[a-zA-Z0-9-.\s]+$/', $newSoftwareOption)){ 
                echo "must be combination of letters, numbers and spaces only";
            }
            else if(ifExist($request, $newSoftwareOption)){                                
                echo "Software type already exists";                    
                // check duplication in software asset as well
            }
            else{
                $sql = "INSERT INTO sa_type_property (software_type_option)
                VALUES ('$newSoftwareOption')";
                mysqli_query($conn, $sql);                
                mysqli_close($conn);
                echo "Good!";
            }
        }
    }
    else if($request == "addNewLocation"){
        $newLocation = htmlspecialchars($_POST["data"]);        

        if($newLocation == "" || $newLocation == "None" || $newLocation == "none"){
            echo "*required";
        }
        else{
            if(!preg_match('/^[a-zA-Z0-9-.\s]+$/', $newLocation)){ 
                echo "must be combination of letters, numbers and spaces only";
            }
            else if(ifExist($request, $newLocation)){                                
                echo "Location already exists";                    
                // check duplication in software asset as well
            }
            else{
                $sql = "INSERT INTO ha_location_option (location_option)
                VALUES ('$newLocation')";
                mysqli_query($conn, $sql);                
                mysqli_close($conn);
                echo "Good!";
            }
        }
    }
    else{
        // 
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
        else if($type == "addNewLocation"){
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
        else if($type == "addNewCategory"){
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
        else if($type == "addSoftwareCategory"){
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