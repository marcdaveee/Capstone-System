<?php
    // Authenticate if user is logged in
    include ("../auth_session.php");

    
    if($_SESSION["role"] != "admin"){
        header("Location: /Capstone_System/404.php");
        exit();
    }
    
    include("../config/db_config.php");

    $request = $_POST["request"];

    if($request == "addRoleTypeCategory"){
        $newCategory = htmlspecialchars($_POST["data"]);        

        if($newCategory == "" || $newCategory == "None" || $newCategory == "none"){
            echo "*required";
        }
        else{
            if(!preg_match('/^[a-zA-Z0-9-.\s]+$/', $newCategory)){ 
                echo "must be combination of letters, numbers and spaces only";
            }
            else if(ifExist($request, $newCategory)){                                
                echo "Role Type already exists";                                    
            }
            else{
                $sql = "INSERT INTO user_role_options_table (role_type)
                VALUES ('$newCategory')";
                mysqli_query($conn, $sql);                
                mysqli_close($conn);
                echo "Good!";
            }
        }        
    }   

    function ifExist($type, $value){
        include("../config/db_config.php");
        if($type == "addRoleTypeCategory"){
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
        else{
            return false;
        }
    }

?> 