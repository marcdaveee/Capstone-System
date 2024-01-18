<?php
     // Authenticate if user is logged in
     include ("../auth_session.php");

    
     if($_SESSION["role"] != "admin"){
         header("Location: /Capstone_System/404.php");
         exit();
     }
    include("../config/db_config.php");

    $request = $_POST["request"];

    if($request == "incidentType"){
        $sql = "SELECT * FROM ticket_properties WHERE property_type = 'incident type'";        
        if(mysqli_query($conn, $sql)){
            $result = mysqli_query($conn, $sql);
            $incidentTypes = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_free_result($result);            
            echo json_encode($incidentTypes);
        }
    }    
    else{
        // none
    }
    
    
?> 