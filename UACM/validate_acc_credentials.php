<?php 

    // Authenticate if user is logged in
    include ("../auth_session.php");

    
    if($_SESSION["role"] != "admin"){
        header("Location: /Capstone_System/404.php");
        exit();
    }

    // Validate creation of user account

    $type  = $_POST["type"];
    $value = $_POST["input"];
    
    if($value == "" || $value == "None" || $value == "none"){
        echo "*required";
    }
    else{
        if($type == "email"){
            if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
               echo "email is not valid";
            }
            else{
                if(ifExist($type, $value)){                
                    echo "email already exists";                
                }else{
                    echo "Good!";
                }
            }                          
        }
        else if($type == "username"){
            if(!preg_match('/^[a-zA-Z0-9-.\s]+$/', $value)){ 
                echo "must be combination of letters, numbers and spaces only";
            }             
           else{
                echo "Good!";
            }
        }
        else if($type == "password"){
            $password = htmlspecialchars($value);
            // validate if password has more than 6 characters length
            if(strlen($password) < 6 ){
                echo "password length must be more than 6 characters";
            }
        }                                        
        else if($type == "userRole"){
            if(!preg_match('/^[a-zA-Z0-9-.\s]+$/', $value)){ 
                echo "must be combination of letters, numbers and spaces only";
            }                
            else if(ifExist($type, $value)){                
                echo "Role type already exists";                
            }else{
                echo "Good!";
            }
        }
        else if($type == "department"){
            if(!preg_match('/^[a-zA-Z0-9-.\s]+$/', $value)){ 
                echo "must be combination of letters, numbers and spaces only";
            }                
            else if(ifExist($type, $value)){                
                echo "Department name already exists";                
            }else{
                echo "Good!";
            }
        }
    }
        
    function ifExist($type, $value){
        include("../config/db_config.php");
        if($type == "email"){
            $sql = "SELECT * FROM user_account_table";
        
            $result = mysqli_query($conn, $sql);
        
            $assets = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
            foreach($assets as $asset){
                if(strtoupper($asset["email"]) == strtoupper($value)){
                    mysqli_free_result($result);
                    mysqli_close($conn);        
                    return true;
                }
            }
            return false;
        }
        else if($type == "userRole"){
            $sql = "SELECT * FROM user_role_options_table";
        
            $result = mysqli_query($conn, $sql);
        
            $assets = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
            foreach($assets as $asset){
                if(strtoupper($asset["role_type"]) == strtoupper($value)){
                    mysqli_free_result($result);
                    mysqli_close($conn);        
                    return true;
                }
            }
            return false;
        }
        else if($type == "department"){
            $sql = "SELECT * FROM lgu_saq_dept";

            $result = mysqli_query($conn, $sql);
        
            $assets = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
            foreach($assets as $asset){
                if(strtoupper($asset["dept_name"]) == strtoupper($value)){
                    mysqli_free_result($result);
                    mysqli_close($conn);        
                    return true;
                }
            }
            return false;

        }
        else{
            return false;
        }
    }
?>