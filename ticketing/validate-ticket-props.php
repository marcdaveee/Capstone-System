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
            if($type == "incidentProperty"){
                echo "This Incident Type Property already exists";    
            }                                    
        }
        else{
            echo "Good";
        }
    }
        
    function ifExist($type, $value){
        include("../config/db_config.php");
        if($type == "incidentProperty"){
            $sql = "SELECT * FROM ticket_properties";
        
            $result = mysqli_query($conn, $sql);
        
            $incidentTypes = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
            foreach($incidentTypes as $incidentType){
                if(strtoupper($incidentType["property_value"]) == strtoupper($value)){
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