<?php 
     // Authenticate if user is logged in
     include ("../auth_session.php");

    
     if($_SESSION["role"] != "admin"){
         header("Location: /Capstone_System/404.php");
         exit();
     }

    include("config/db_config.php");

    $itemName = mysqli_real_escape_string($conn, $_POST["item-name"]);
    $itemType = mysqli_real_escape_string($conn, $_POST["item-type"]);
    $itemBrand = mysqli_real_escape_string($conn, $_POST["item-brand"]);
    $user = mysqli_real_escape_string($conn, $_POST["user"]);
    $loc = mysqli_real_escape_string($conn, $_POST["curr-location"]);
    $currStatus = mysqli_real_escape_string($conn, $_POST["curr-status"]);    

    $sql = "INSERT INTO hardware_asset(item_name, item_type, item_brand, user, curr_location, curr_status) VALUES('$itemName', '$itemType', '$itemBrand', '$user', '$loc', '$currStatus')";

    if(mysqli_query($conn, $sql)){
        echo "New Record Added!";
        // $sql = "SELECT item_name, item_type, item_brand, user, curr_location, curr_status FROM hardware_asset";

        // $result = mysqli_query($conn, $sql);

        // $assets = mysqli_fetch_all($result, MYSQLI_ASSOC);
        // echo json_encode($assets);
    }
    else{
        echo "Error adding data";
    }

    mysqli_close($conn);
?>