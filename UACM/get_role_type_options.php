<?php
    include("../config/db_config.php");

    $request = $_POST["request"];

    if($request == "roleTypeCategories"){
        $sql = "SELECT role_type FROM user_role_options_table";        
        if(mysqli_query($conn, $sql)){
            $result = mysqli_query($conn, $sql);
            $roleTypeCategories = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_free_result($result);            
            echo json_encode($roleTypeCategories);
        }
    }    
    else{
        // none
    }
    
    
?> 