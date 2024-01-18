<?php 
     // Authenticate if user is logged in
     include ("../auth_session.php");

    
     if($_SESSION["role"] != "admin"){
         header("Location: /Capstone_System/404.php");
         exit();
     }
     
    require("../config/config.php");
    require("../config/db_config.php");

    if(!empty($_POST["targetId"])){
        $targetId = $_POST["targetId"];
        // echo "Ready to delete $targetId";
        // $sql = "DELETE FROM hardware_asset WHERE id={$targetId}";
        $sql = "UPDATE hardware_asset
            SET user='None',                
                curr_status='Inactive'      
            WHERE id=$targetId"; 

        if(mysqli_query($conn, $sql)){
            echo "Asset was updated successfully";
        }
        else{
            echo "Error updating the record ". mysqli_error($conn);
        }
    }

?>