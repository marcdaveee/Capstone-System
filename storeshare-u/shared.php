<?php 
    // Authenticate if user is logged in
    include ("../auth_session.php");

    
    if($_SESSION["role"] != "user"){
        header("Location: /Capstone_System/404.php");
        exit();
    }



    $username = $_SESSION["username"];
    $userEmail = $_SESSION["email"];


    // Connect to database
    include ("../config/db_config.php");
        

    $paths = array(

    );

// Get folders accessible by current user
    $sql = "SELECT * FROM access_rights_table WHERE email='$userEmail'";
    $result = mysqli_query($conn, $sql);
    // if(mysqli_num_rows($result) == 0){
    //     header("Location: /Capstone_System/404.php");     
    // }

    $userAccessInfo = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
        
        
    // Get main folders accessible by user
    $categories = array();

    foreach($userAccessInfo as $accessibleFolder){
        if(isMainFolder($accessibleFolder["folder_path_name"])){
            $pathInfo = explode("/", $accessibleFolder["folder_path_name"]);
            array_push($categories, $pathInfo[1]);
        }
    }

    function isMainFolder($folderPathName){
        $pathInfo = explode("/", $folderPathName);

        if(count($pathInfo) == 2){
            return true;
        }

        return false;
    }   

    function isOnSameMainFolder($category, $folderPathName){
        if(!isMainFolder($folderPathName)){
            $pathInfo = explode("/", $folderPathName);
            if($pathInfo[1] == $category){
                return true;
            }
        }
        return false;
    }

    function getFolderName($folderPathName){
        $pathInfo = explode("/", $folderPathName);
        $pathInfo = end($pathInfo);
        return $pathInfo;
    }

    function hasAccess($folderPathName, $user){
        include("../config/db_config.php");

        $sql = "SELECT * FROM access_rights_table WHERE email='$user' AND folder_path_name='$folderPathName'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0){
            return true;
        }
        else{
            return false;
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

    <?php include("storeshare-user-sidebar.php") ?>

      <!-- Main Dashboard -->
    <section class="main-dashboard bg-light-primary">
        <div class="container">
            <div class="sticky">
                <div id="top-header" class="flex flex-just-sb ">
                    <h2 id="current-path" class="txt-md"><i class="fa-regular fa-folder-open mr-1"></i>Shared With Me</h2>
                    <h1 class="txt-md my-3">Hello, <?php echo $username?></h1>        
                </div>                
            </div>
            
            
            <section class="directory-content mt-2">
                <?php foreach($categories as $category):?>
                    <div class="flex flex-just-left">
                        <h1 class="txt-fw-3 txt-md mt-2 mb-2"><?php echo $category?></h1>
                    </div>

                    <div class="folder-container mt-2 mb-4">
                        <?php foreach($userAccessInfo as $accessibleFolder):?>
                            <?php if(isOnSameMainFolder($category, $accessibleFolder["folder_path_name"])): ?>
                                <a href="folder.php?path=<?php echo $accessibleFolder["folder_path_name"]?>" class="folder-item flex flex-column">
                                    <i class="fa-solid fa-folder txt-lg folder-icon"></i>
                                    <p class="txt-sm"><?php echo getFolderName($accessibleFolder["folder_path_name"]); ?></p>
                                </a>
                            <?php endif;?>
                        <?php endforeach; ?>
                    </div>
                    

                <?php endforeach?>
                
            </section>
            
        
    </section>

    <script src="../storeshare/storeshare.js"></script>
  </body>
</html>