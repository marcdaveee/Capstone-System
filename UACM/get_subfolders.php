<?php
    include("../config/db_config.php");

    $request = $_POST["request"];


    if($request == "getSubFolders"){        
        $pathInfo = htmlspecialchars($_POST["pathInfo"]);

        // Get folders available from current folder
        $sql = "SELECT folder_id FROM folder_table WHERE folder_path_name='$pathInfo'";   
        $result = mysqli_query($conn, $sql);   
        $currentDirectory = mysqli_fetch_assoc($result);
        $currentDirectory = $currentDirectory["folder_id"];    
        mysqli_free_result($result);
        
        
        $sql = "SELECT * FROM folder_relation_table WHERE parent_folder_id='$currentDirectory'";
        $result = mysqli_query($conn, $sql);    
        if(mysqli_num_rows($result) > 0){
            $folders = mysqli_fetch_all($result, MYSQLI_ASSOC);        
            mysqli_free_result($result);
            echo json_encode($folders);                
        }
        else{
          echo "";
        }
    }    
    else if($request == "getParentFolders"){
        $pathInfo = htmlspecialchars($_POST["pathInfo"]);
        $parentFolderArray = array();
        $parentDirectoryId = "";

        while($parentDirectoryId != 1){
            // Get parent folder of current path folder info
            $sql = "SELECT parent_folder_id FROM folder_relation_table WHERE folder_path_name='$pathInfo'";   
            $result = mysqli_query($conn, $sql);   
            $parentDirectoryId = mysqli_fetch_assoc($result);
            $parentDirectoryId = $parentDirectoryId["parent_folder_id"];    
            mysqli_free_result($result);

            $sql = "SELECT folder_id, folder_path_name FROM folder_table WHERE folder_id='$parentDirectoryId'";   
            $result = mysqli_query($conn, $sql);
            $pathInfo = mysqli_fetch_assoc($result);
            $pathInfo = $pathInfo["folder_path_name"];
            array_push($parentFolderArray, $pathInfo);
        }
        
        echo json_encode($parentFolderArray);

    }
    else{

    }
    
    
?> 