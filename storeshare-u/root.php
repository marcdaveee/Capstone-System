<?php 
    // Authenticate if user is logged in
    include ("../auth_session.php");

    if($_SESSION["role"] != "user"){
        header("Location: /Capstone_System/404.php");
        exit();
    }

    $username = $_SESSION["username"];

    // Connect to database
    include ("../config/db_config.php");

    $currentDirectory = "";

    // Get folders available from root
    $sql = "SELECT folder_id FROM folder_table WHERE folder_path_name='root'";   
    $result = mysqli_query($conn, $sql);   
    $currentDirectory = mysqli_fetch_assoc($result);
    $currentDirectory = $currentDirectory["folder_id"];
    // echo "Current Directory: ".$currentDirectory;
    mysqli_free_result($result);
    
    $sql = "SELECT * FROM folder_relation_table WHERE parent_folder_id='$currentDirectory'";
    $result = mysqli_query($conn, $sql);    
    if(mysqli_num_rows($result) > 0){
        $folders = mysqli_fetch_all($result, MYSQLI_ASSOC);        
        mysqli_free_result($result);                
    }
        
    

    $error = array(
        "folderNameError" => ""
      );

    $folderName = $folderPathName = $folderClassification = "";

    // creating new folder in root
    if(isset($_POST["create"])){

        // Check if folder name field input is empty
        if(empty($_POST["new-folder-name"])){
            $error["folderNameError"] = "*required";
        }
        else {
            $folderName = htmlspecialchars($_POST["new-folder-name"]);
            $folderPathName = "root/".$folderName;

            // validate folder name input
            if(!preg_match('/^[a-zA-Z0-9-_()\s]+$/', $folderName)){ 
                $error["folderNameError"] = "invalid folder name";
            }

            if(checkIfExist($folderPathName)){
                $error["folderNameError"] = "*folder name already exist";
            }            
        }

        if($error["folderNameError"] == "") {

            $sql = "INSERT INTO folder_table(folder_path_name, folder_name, folder_classification)
            VALUES('$folderPathName', '$folderName', 'Internal')";            
            mysqli_query($conn, $sql);

            $sql = "INSERT INTO folder_relation_table(folder_path_name, folder_name, parent_folder_id)
            VALUES('$folderPathName', '$folderName', '$currentDirectory')";            
            mysqli_query($conn, $sql);

            $successMsg = "New Item Added successfully!"; 
            

            $dirName = "../storeshare/folders/{$folderPathName}";
            
            
            mkdir($dirName, 0777, true);
            

            $currentUrl = htmlspecialchars($_SERVER["PHP_SELF"]);

            header("Location: ".$currentUrl);            
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

    <?php include("storeshare-user-sidebar.php") ?>

      <!-- Main Dashboard -->
    <section class="main-dashboard bg-light-primary">
        <div class="container">
            <div id="top-header" class="flex flex-just-sb ">
                <h2 class="txt-md"><i class="fa-regular fa-folder-open mr-1"></i>Root</h2> 
                <h1 class="txt-md my-3">Hello, <?php echo $username?></h1>        
            </div>

            <div class="my-2 ml-4 txt-md txt-fw-3 ">Folder</div>


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
        
        
            <div class="modal-box hide">
                <div class="add-folder card">
                    <div class="close"><i class="fas fa-times"></i></div>
                    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" id="add-item-type" class="my-2">
                        <div class="form-group">
                            <label for="new-folder-name">Folder Name</label>
                            <input type="text" name="new-folder-name" id="new-folder-name" value="<?php echo $folderName?>">
                            <div id="folder-create-error" class="error-msg"><?php echo $error["folderNameError"]?></div>                                                                 
                        </div>                                    
                    <input type="submit" name="create" value="Create" class="btn btn-outline ">
                    </form>                                      
                </div>
            </div>
        
    </section>

    <script src="../storeshare/storeshare.js"></script>
  </body>
</html>