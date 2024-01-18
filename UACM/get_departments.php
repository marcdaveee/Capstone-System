<?php
    include("../config/db_config.php");

    $request = $_POST["request"];

    if($request == "getDepartments"){
        $sql = "SELECT dept_name FROM lgu_saq_dept";        
        if(mysqli_query($conn, $sql)){
            $result = mysqli_query($conn, $sql);
            $departments = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_free_result($result);            
            echo json_encode($departments);
        }
    }    
    else{
        // none
    }
    
    
?> 