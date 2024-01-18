<?php 
    // Authenticate if user is logged in
    include ("../auth_session.php");

    
    if($_SESSION["role"] != "admin"){
        header("Location: /Capstone_System/404.php");
        exit();
    }

    $username = $_SESSION["username"];

    // Connect to database
    include ("../config/db_config.php");

    $currentPath = htmlspecialchars($_GET["path"]);
    $previousPath = getPreviousPath($currentPath);

    $paths = array(

    );

    // Get folders available from current folder
    $sql = "SELECT folder_id FROM folder_table WHERE folder_path_name='$currentPath'";   
    $result = mysqli_query($conn, $sql);   
    $currentDirectory = mysqli_fetch_assoc($result);
    $currentDirectory = $currentDirectory["folder_id"];    
    mysqli_free_result($result);
    
    
    $sql = "SELECT * FROM folder_relation_table WHERE parent_folder_id='$currentDirectory'";
    $result = mysqli_query($conn, $sql);    
    if(mysqli_num_rows($result) > 0){
        $folders = mysqli_fetch_all($result, MYSQLI_ASSOC);        
        mysqli_free_result($result);                
    }
        
    

    $error = array(
        "folderNameError" => "",
        "requestedByError" => ""
      );

    $folderName = $folderPathName = $folderClassification = $requestedBy = "";

    // creating new folder in root
    if(isset($_POST["create"])){

        // Check if folder name field input is empty
        if(empty($_POST["new-folder-name"])){
            $error["folderNameError"] = "*required";
        }
        else {
            $folderName = htmlspecialchars($_POST["new-folder-name"]);
            $folderPathName = $currentPath ."/". $folderName;
            
            if(strlen($folderName) > 50){
                $error["folderNameError"] = "Folder name is too long";
            }
            else{
                // validate folder name input
                if(!preg_match('/^[a-zA-Z0-9-_().=:\s]+$/', $folderName)){ 
                    $error["folderNameError"] = "invalid folder name";
                }

                if(checkIfExist($folderPathName)){
                    $error["folderNameError"] = "*folder name already exist";
                }            
            }
            
        }

        // Check if requested by field input is empty
        if(empty($_POST["requested-by"])){
            $error["requestedByError"] = "*required";
        }
        else{
            $requestedBy = htmlspecialchars($_POST["requested-by"]);

            if(strlen($requestedBy) > 20){
                $error["requestedByError"] = "Name is too long.";
            }
            else{
                // validate requested by field input name
                if(!preg_match('/^[a-zA-Z\s]+$/', $requestedBy)){ 
                    $error["requestedByError"] = "Invalid person name";
                }
            }
        }


        if($error["folderNameError"] == "" && $error["requestedByError"] == "") {

            $sql = "INSERT INTO folder_table(folder_path_name, folder_name, folder_classification, requested_by)
            VALUES('$folderPathName', '$folderName', 'Internal', '$requestedBy')";            
            mysqli_query($conn, $sql);

            $sql = "INSERT INTO folder_relation_table(folder_path_name, folder_name, parent_folder_id)
            VALUES('$folderPathName', '$folderName', '$currentDirectory')";            
            mysqli_query($conn, $sql);

            $successMsg = "New Item Added successfully!"; 
            

            $dirName = "../storeshare/folders/{$folderPathName}";
            
            
            mkdir($dirName, 0777, true);

            $folderName = $folderPathName = $folderClassification = "";

            $currentUrl = htmlspecialchars($_SERVER["PHP_SELF"]);

            header("Location: ".$currentUrl."?path=$currentPath");            
        }

    }

    function checkIfExist($folderName){
        include("../config/db_config.php");

        $sql = "SELECT * FROM folder_table";

        $result = mysqli_query($conn, $sql);
        
        $folders = mysqli_fetch_all($result, MYSQLI_ASSOC);

        foreach($folders as $folder){
            if(strtoupper($folder["folder_path_name"]) == strtoupper($folderName)){
                
                mysqli_free_result($result);                      
                return true;
                mysqli_close($conn); 
            }
        }        

        return false;
    }

    function getPreviousPath($currentPath){
        include("../config/db_config.php");

        $sql = "SELECT parent_folder_id FROM folder_relation_table WHERE folder_path_name='$currentPath'";
        $result = mysqli_query($conn, $sql);    

        if(mysqli_num_rows($result) > 0){
            $parentFolderId = mysqli_fetch_assoc($result);        
            $parentFolderId = $parentFolderId["parent_folder_id"];
            
            mysqli_free_result($result);

            $sql = "SELECT folder_path_name FROM folder_table WHERE folder_id='$parentFolderId'";
            $result = mysqli_query($conn, $sql);        
            $previousPath = mysqli_fetch_assoc($result);
            $previousPath = $previousPath["folder_path_name"];            

            if($parentFolderId==1){
                return "root.php";
            }
            else{
                return "folder.php?path=".$previousPath;
            }
                    
        }
          
    }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LGU SAQ Storeshare</title>
    <link rel="stylesheet" href="../CSS/style.css" />
    <link rel="stylesheet" href="../CSS/utilities.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
      integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
  </head>
  <body>

    <?php include("storeshare-admin-sidebar.php") ?>

      <!-- Main Dashboard -->
    <section class="main-dashboard bg-light-primary">
        <div class="container">
            <div id="top-header" class="flex flex-just-sb ">
                <h2 class="txt-md"><i class="fa-regular fa-folder-open mr-1"></i><?php echo $currentPath?></h2>
                <h1 class="txt-md my-3">Hello, <?php echo $username?></h1>        
            </div>

            <div class="py-1 flex flex-just-sb">                
                <div class="m-1 txt-lg"> <a href="<?php echo $previousPath?>"><i class="fa-solid fa-arrow-left-long clickable"></i></a></div>            
                <button id="add-folder" class="btn btn-primary">Add Folder</button>
            </div>
            
            

            <?php if(!empty($folders)): ?>
                <div id="folder-container">
                <?php foreach ($folders as $folder): ?>
                    <a href="folder.php?path=<?php echo $folder["folder_path_name"]?>" class="folder-item flex flex-column">
                        <i class="fa-solid fa-folder txt-lg folder-icon"></i>
                        <p id="" class="txt-sm"><?php echo $folder["folder_name"]; ?></p>
                    </a>                    
                    <!-- <div class="folder-item flex" id="">
                        <i class="fa-regular fa-folder txt-lg mr-2"></i>
                        <p id="" class="txt-sm"><?php echo $folder["folder_name"]; ?></p>
                    </div>                                            -->
                <?php endforeach;?>
                </div>
                <?php else: ?>
                    <div class="flex-column my-4">
                        <h4>No contents available.</h4>
                    </div>
            <?php endif;?>                               
        </div>
                    
    </section>

    <div class="modal-box hide">
                <div class="add-folder card">
                    <div class="close"><i class="fas fa-times"></i></div>
                    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" id="add-item-type" class="my-2">
                        <div class="form-group">
                            <label for="new-folder-name">Folder Name</label>
                            <input type="text" name="new-folder-name" id="new-folder-name" value="<?php echo $folderName?>">
                            <div id="folder-create-error" class="error-msg"><?php echo $error["folderNameError"]?></div>                                                                 
                        </div>
                        
                        <div class="form-group">
                            <label for="requested-by">Requested by</label>
                            <input type="text" name="requested-by" id="requested-by" value="<?php echo $requestedBy?>">
                            <div id="requested-by-field-error" class="error-msg"><?php echo $error["requestedByError"]?></div>                                                                 
                        </div>                                    

                        <div class="flex">
                            <input type="submit" name="create" value="Create" class="btn btn-outline ">
                        </div>
                    
                    </form>                                      
                </div>
            </div>

    <script src="../storeshare/storeshare.js"></script>
  </body>
</html>