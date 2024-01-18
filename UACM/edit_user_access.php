<?php 
  // Authenticate if user is logged in
  include ("../auth_session.php");

    
  if($_SESSION["role"] != "admin"){
      header("Location: /Capstone_System/404.php");
      exit();
  }

  //   Get user id
  if(isset($_GET["id"])){
    $userId = htmlspecialchars($_GET["id"]);
    
    require("../config/config.php");
    require("../config/db_config.php");
  
    $sql = "SELECT * FROM user_account_table WHERE user_id='$userId'";

    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){
        $user = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
    }

    $folderSystem = array();
    $folderPathName = array();

    // Get Entire folder system

    // Get all folders
    $sql = "SELECT folder_id, folder_path_name, folder_name FROM folder_table";
    $result = mysqli_query($conn, $sql); 
    if(mysqli_num_rows($result) > 0){
        $folders = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);                
    }

    
    // For each folder, get all its subfolders
    foreach($folders as $folder){        
      
        // Insert to folder system
        if($folder["folder_path_name"] == "root"){
          array_push($folderSystem, $folder["folder_path_name"]);
          array_push($folderPathName, $folder["folder_path_name"]);
        }
        else{
          $paths = explode("/", $folder["folder_path_name"]);
          $index = 0;
          foreach($paths as $path){        
            if($path != "root"){
              if(array_search($path, $folderSystem)){
                $index = array_search($path, $folderSystem);                
              }else{                
                if($index == 0){
                  array_push($folderSystem, $path);
                  array_push($folderPathName, $folder["folder_path_name"]);
                }
                else{
                  array_splice($folderSystem,$index+1,0,$path);
                  array_splice($folderPathName, $index+1,0, $folder["folder_path_name"]);
                  break;
                }
              }
            }                                      
          }
          
        }
        

        // // echo $folder["folder_name"];
        // array_push($folderSystem, $folder["folder_name"]);        
        // $currentFolderId = $folder["folder_id"];
        // $sql = "SELECT folder_path_name, folder_name FROM folder_relation_table WHERE parent_folder_id='$currentFolderId'";
        // $result = mysqli_query($conn, $sql); 
        // if(mysqli_num_rows($result) > 0){            
        //     $subFolders = mysqli_fetch_all($result, MYSQLI_ASSOC);

        //     foreach($subFolders as $subFolder){
        //         array_push($tempArray, $subFolder["folder_name"]);                
        //     }

        //     // print_r($tempArray);
        //     $folderSystem = array_merge($folderSystem, $tempArray);
        //     mysqli_free_result($result);                
        // }
    }

    // print_r($folderSystem);
    // echo "<br/>";
    // print_r($folderPathName);
  }
  
  function getPathLength($pathInfo){
    $paths = explode("/", $pathInfo);
    $count = count($paths);
    return $count;
  }

  function hasSubfolders($pathInfo){
    include("../config/db_config.php");
    // Get folders available (accessible) from current folder
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
        return true;                
    }
    else{
      false;
    }
  }

  function checkIfPermitted($folderPathName, $userId){
    include("../config/db_config.php");

    // Get username
    $sql = "SELECT * FROM user_account_table WHERE user_id='$userId'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
      $userInfo = mysqli_fetch_assoc($result);
      $email = $userInfo["email"];
      mysqli_free_result($result);
    }

    // 
    $sql = "SELECT * FROM access_rights_table WHERE email='$email' AND folder_path_name='$folderPathName'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
      $permission = mysqli_fetch_assoc($result);
      $permission = $permission["access_level"];
      if($permission == 2){      
        return 2;
      }
      else if($permission == 1){
        return 1;
      }      
    }
    else{
        return "";
    }
  }

  function getCurrentUser($userId){
    // Get username
    include("../config/db_config.php");
    $sql = "SELECT * FROM user_account_table WHERE user_id='$userId'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
      $username = mysqli_fetch_assoc($result);
      $username = $username["username"];
      mysqli_free_result($result);
      return $username;
    }
  }

  function getUserEmail($userId){
    // Get username
    include("../config/db_config.php");
    $sql = "SELECT * FROM user_account_table WHERE user_id='$userId'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
      $userInfo = mysqli_fetch_assoc($result);
      $email = $userInfo["email"];
      mysqli_free_result($result);
      return $email;
    }
  }
  
                        
?> 

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>IT Assets Inventory</title>
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

  <!-- Side Nav -->
  <?php include("../storeshare-admin/storeshare-admin-sidebar.php") ?>


    <!-- Main Dashboard -->
    <section class="main-dashboard">    

      <section class="dashboard-content">
        
        <div class="container">
          <div class="flex flex-just-sb py-2">
                <a href="user_access_mng.php" class="btn btn-outline">Back</a>
                <h3 class="txt-lg">User Access Rights Matrix</h3>
                <button id="update-uac-rights" class="btn btn-primary">Update</button>
            </div>
        </div>
        
        

        <div class="container">
            <div class="flex flex-just-left">
                <div class="mr-3">
                    <p class="txt-sm">User Name: <span class="txt-md txt-fw-3"> <?php echo $user["username"]?></span>  </p>                    
                </div>
                               
                <div >
                <p class="txt-sm">Role: <span class="txt-md txt-fw-3"> <?php echo $user["user_role"]?></span>  </p>                    
                </div>

            </div>

            <!-- Access Matrix Table -->
            <div id="access-matrix">
                <div class="flex py-1 txt-fw-4">Folder Permissions</div>
                <table class="access-matrix-table sticky">
                  <thead>
                    <th>Folders</th>
                    <th>View</th>
                    <th>Upload</th>
                  </thead>

                  <tbody>
                    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" id="access-rights">                  

                      <?php for($i = 0; $i < count($folderSystem); $i++): ?>
                        <?php if($folderSystem[$i] != "root"):?>
                          <tr id="<?php echo "id-$i"?>" class="<?php if(getPathLength($folderPathName[$i])>2) echo "hide"?>">
                            <input type="hidden" class="folder-path-info" name="<?php echo "id-$i"?>" value="<?php echo $folderPathName[$i]?>">
                            <td class="pl-<?php echo getPathLength($folderPathName[$i]);?> <?php if(hasSubFolders($folderPathName[$i])) echo "has-subfolders clickable" ?>"><?php if(hasSubFolders($folderPathName[$i])) echo "<i class='fa-solid fa-chevron-right mr-1'></i>" ?> <?php echo $folderSystem[$i] ?></td>
                            
                            <td class="td-align-center"><input type="checkbox" class="view-check-box" name="<?php echo "view-id-$i"?>" value="<?php echo "view-id-$i"?>" <?php if(checkIfPermitted($folderPathName[$i], $userId) == 2 || checkIfPermitted($folderPathName[$i], $userId) == 1) echo "checked";?>></td>
                            <td class="td-align-center"><input type="checkbox" class="upload-check-box" name="<?php echo "upload-id-$i"?>" value="<?php echo "upload-id-$i"?>" <?php if(checkIfPermitted($folderPathName[$i], $userId) == 2) echo "checked"?> ></td>

                          </tr>
                          <?php endif?>
                      <?php endfor;?>

                    </form>
                  </tbody>
                </table>
            </div>

        </div>

      </section>

      <div class="modal-box hide">
        <div class="confirmation-box card">
            <div class="flex"><span class="txt-warn txt-fw-4 txt-lg">!</span></div>
            <h3>Are you sure you want to update the access rights of '<?php echo getCurrentUser($userId)?>'</h3>
            <div class="group-button">
              <input type="hidden" name="email" id="email" value="<?php echo getUserEmail($userId)?>">
            <button type="button" id="cancel-btn" class="btn btn-outline btn-wide">Cancel</button>
            <button type="button" id="confirm-uac-update" class="btn btn-outline btn-wide btn-warn">Continue</button>
            </div>                                   
        </div>
        
        <div class="successMsg-box card hide">
            <div class="successMsg"> <i class="fa-regular fa-circle-check mr-1"></i>User Access Rights updated.</div>
            <button type="button" id="done-btn" class="btn btn-outline">Done</button>              
        </div>   

      </div>


    </section>

    <!-- <script src="js/uac.js"></script> -->
    <script src="js/access_rights.js"></script>
    <script src="../IT_Asset_Inventory/js/side_nav_handler.js"></script>
  </body>
</html>
