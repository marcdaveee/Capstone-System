<?php 
    // Authenticate if user is logged in
    include ("../auth_session.php");

    
    if($_SESSION["role"] != "user"){
        header("Location: /Capstone_System/404.php");
        exit();
    }

    if(!isset($_GET["file_id"])){
        header("Location: root.php");
        exit();
    }

    $username = $_SESSION["username"];

    // Connect to database
    include ("../config/db_config.php");
    
    $targetFileId = htmlspecialchars($_GET["file_id"]);
    

    // Delete file from directory
    // Get file path info from database
    $sql = "SELECT file_path_name FROM file_table WHERE file_id='$targetFileId'";

    $result = mysqli_query($conn, $sql);
    
    $targetFilePath = mysqli_fetch_assoc($result);    

    $targetFilePath = $targetFilePath["file_path_name"];

    $targetFilePath = "../storeshare/folders/{$targetFilePath}";

    mysqli_free_result($result);

    $currentDirectory = htmlspecialchars($_GET["currentPath"]);

    
    // Delete file from its directory
    unlink($targetFilePath);

    // Delete file record from the database
    $sql = "DELETE FROM file_table WHERE  file_ID='$targetFileId'";
    mysqli_query($conn, $sql);
    
    mysqli_close($conn);

    // Redirected back to page
    header("Location: folder.php?path=$currentDirectory");
    


?>
