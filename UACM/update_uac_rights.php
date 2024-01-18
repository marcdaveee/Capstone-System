<?php
    include("../config/db_config.php");

    $request = $_POST["request"];


    if($request == "resetUac"){        
        $email = htmlspecialchars($_POST["email"]);

        // Remove all existing access rights for a user
        $sql = "DELETE FROM access_rights_table WHERE email='$email'";
        
        if(mysqli_query($conn, $sql)){
            echo "updated";
        }
    }    
    else if($request == "updateUac"){
        $email = htmlspecialchars($_POST["email"]);
        $accessLevel = htmlspecialchars($_POST["accessLevel"]);
        $pathInfo = htmlspecialchars($_POST["pathInfo"]);

        $sql = "INSERT INTO access_rights_table (email, access_level, folder_path_name)
        VALUES ('$email','$accessLevel', '$pathInfo')";
        
        if(mysqli_query($conn, $sql)){
            echo "updated";
        }
        

    }
    else{

    }
    
    
?> 