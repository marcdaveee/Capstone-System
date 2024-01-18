<?php
    // Authenticate if user is logged in
    include ("../auth_session.php");

    
    if($_SESSION["role"] != "admin"){
        header("Location: /Capstone_System/404.php");
        exit();
    }
    
    include("../config/db_config.php");

    $request = $_POST["request"];

    if($request == "addNewDept"){
        $newCategory = htmlspecialchars($_POST["data"]);        

        if($newCategory == "" || $newCategory == "None" || $newCategory == "none"){
            echo "*required";
        }
        else{
            if(!preg_match('/^[a-zA-Z0-9-.\s]+$/', $newCategory)){ 
                echo "must be combination of letters, numbers and spaces only";
            }
            else if(ifExist($request, $newCategory)){                                
                echo "Department name already exists";                                    
            }
            else{
                $sql = "INSERT INTO lgu_saq_dept (dept_name)
                VALUES ('$newCategory')";
                mysqli_query($conn, $sql);                
                mysqli_close($conn);
                echo "Good!";
            }
        }        
    }   

    function ifExist($type, $value){
        include("../config/db_config.php");
        if($type == "addNewDept"){
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