<?php
 // Authenticate if user is logged in
 include ("../auth_session.php");

    
 if($_SESSION["role"] != "admin"){
     header("Location: /Capstone_System/404.php");
     exit();
 }
 
    include("../config/db_config.php");

    $request = $_POST["request"];

    if($request == "addIncidentType"){
        $newCategory = htmlspecialchars($_POST["data"]);        

        if($newCategory == "" || $newCategory == "None" || $newCategory == "none"){
            echo "*required";
        }
        else{
            if(!preg_match('/^[a-zA-Z0-9-.\s]+$/', $newCategory)){ 
                echo "must be combination of letters, numbers and spaces only";
            }
            else if(ifExist($request, $newCategory)){                                
                echo "This Incident Type Property already exists";                    
                // check duplication in software asset as well
            }
            else{
                $sql = "INSERT INTO ticket_properties (property_value, property_type)
                VALUES ('$newCategory', 'Incident type')";
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
        if($type == "addIncidentType"){
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