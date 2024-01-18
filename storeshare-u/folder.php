<?php 
    // Authenticate if user is logged in
    include ("../auth_session.php");

    
    if($_SESSION["role"] != "user"){
        header("Location: /Capstone_System/404.php");
        exit();
    }

    if(!isset($_GET["path"])){
        header("Location: root.php");
        exit();
    }

    $username = $_SESSION["username"];
    $userEmail = $_SESSION["email"];


    // Connect to database
    include ("../config/db_config.php");

    $currentPath = htmlspecialchars($_GET["path"]);
    $previousPath = getPreviousPath($currentPath);

    $paths = array(

    );

    // Get folders available (accessible) from current folder
    $sql = "SELECT * FROM access_rights_table WHERE email='$userEmail' AND folder_path_name='$currentPath'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) == 0){
        header("Location: /Capstone_System/404.php");     
    }

    $userAccessInfo = mysqli_fetch_assoc($result);
    $userAccessLevel = $userAccessInfo ["access_level"];


    // Get folders available (accessible) from current folder
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
        
    // Get files that are present (accessible) from current folder
    $sql = "SELECT * FROM file_table WHERE folder_path_name='$currentPath'";
    $result = mysqli_query($conn, $sql);    
    if(mysqli_num_rows($result) > 0){
        $files = mysqli_fetch_all($result, MYSQLI_ASSOC);      
        mysqli_free_result($result);                
    }
    
    $error = array(
        "fileUploadError" => "",
        "fileSensiError" => ""
      );

    $fileName = $filePathName = $fileSensitivity = "";

    $successMsg = "";
    $currentUrl = "";

    // uploading file in folders
    if(isset($_POST["upload"])){

        $allowedExt = array("png", "jpg", "jpeg", "docx", "pdf", "xlsx", "xls");

        // Check if user selected a file to be uploaded
        if(empty($_FILES["file-upload"]["name"])){
            $error["fileUploadError"] = "*Please choose a file";
        }
        else {
            // die(print_r($_FILES));
            $fileName = $_FILES["file-upload"]["name"];
            $fileSize = $_FILES["file-upload"]["size"];
            $fileTmp = $_FILES["file-upload"]["tmp_name"];
            
            // Get file extension
            $fileExt = explode('.', $fileName);
            $fileExt = strtolower(end($fileExt));

            // Validate file extension
            if(!in_array($fileExt, $allowedExt)){
                $error["fileUploadError"] = "*Invalid file extension";
            }
            else{
                // Check file size
                if($fileSize > 1000000){
                    $error["fileUploadError"] = "*File is too large";
                }
                else{
                    $filePathName = $currentPath ."/". $fileName;            
                    $targetDir = "../storeshare/folders/{$filePathName}";
                    
                    if(file_exists($targetDir)){
                        $error["fileUploadError"] = "*File already exist";
                    }
                }
            }
            
        }

        if(empty($_POST["file-sensitivity"])){
            $error["fileSensiError"] = "*required";
        }
        else{
            $fileSensitivity = htmlspecialchars($_POST["file-sensitivity"]);
        }

        
        if($error["fileUploadError"] == "" && $error["fileSensiError"] == "") {

            // Get user id
            $sql = "SELECT * FROM user_account_table WHERE email = '$userEmail'";
            $result = mysqli_query($conn, $sql);    
            if(mysqli_num_rows($result) > 0){
                $userInfo = mysqli_fetch_assoc($result);        
                mysqli_free_result($result);
                $userId = $userInfo["user_id"];       
            }


            $sql = "INSERT INTO file_table(file_path_name, file_name, file_size, file_type, folder_path_name, file_owner, file_classification)
            VALUES('$filePathName', '$fileName','$fileSize', '$fileExt', '$currentPath', '$userId', '$fileSensitivity')";                        
            mysqli_query($conn, $sql);
            
            move_uploaded_file($fileTmp, $targetDir);
            $successMsg = "File uploaded successfully!";

            $folderName = $folderPathName = $folderClassification = "";

            $currentUrl = htmlspecialchars($_SERVER["PHP_SELF"]);

            // echo "<script type='text/javascript'> showSuccessMsg($currentUrl, $currentPath)</script>";
                    

            // $dirName = "../storeshare/folders/{$folderPathName}";
            
                      
          

            // header("Location: ".$currentUrl."?path=$currentPath");            
        }

    }

    function getFileExtension($fileName){
        $fileExt = explode('.', $fileName);
        $fileExt = strtolower(end($fileExt));
        return $fileExt;
    }

    function getFileTypeIcon($ext){
        if($ext == "jpg" || $ext == "jpeg" || $ext == "png"){
            return "fa-solid fa-image";
        }
        else if($ext == "docx" || $ext == "doc"){
            return "fa-solid fa-file-word";
        }
        else if($ext == "pdf"){
            return "fa-regular fa-file-pdf";
        }
        else if($ext == "xls" || $ext == "xlsx"){
            return "fa-regular fa-file-excel";
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

    function getUsername($userId){
        include("../config/db_config.php");
        // Get username
        $sql = "SELECT * FROM user_account_table WHERE user_id = '$userId'";
        $result = mysqli_query($conn, $sql);    
        if(mysqli_num_rows($result) > 0){
            $userInfo = mysqli_fetch_assoc($result);        
            mysqli_free_result($result);
            $username = $userInfo["username"];       
            return $username;
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
                    <h2 id="current-path" class="txt-md"><i class="fa-regular fa-folder-open mr-1"></i><?php echo $currentPath?></h2>
                    <h1 class="txt-md my-3">Hello, <?php echo $username?></h1>        
                </div>

                <div class="py-1 flex flex-just-sb ">                                
                <div class="m-1 txt-lg"> <a href="<?php echo $previousPath?>"><i class="fa-solid fa-arrow-left-long clickable"></i></a></div>
                    <?php if($userAccessLevel == 2): ?>
                        <button id="upload-file" class="btn btn-primary">Upload File</button>
                    <?php endif; ?>
                </div>
            </div>
            
            
            <section class="directory-content">
                <div class="ml-4 txt-md txt-fw-2">Folder</div>

                <?php if(!empty($folders)): ?>
                    <div class="folder-container mt-2 mb-4">
                        <?php foreach ($folders as $folder): ?>
                            <?php if(hasAccess($folder["folder_path_name"], $userEmail)):?>
                                <a href="folder.php?path=<?php echo $folder["folder_path_name"]?>" class="folder-item flex flex-column">                                    
                                    <i class="fa-solid fa-folder txt-lg folder-icon"></i>                                
                                    <p class="txt-sm"><?php echo $folder["folder_name"]; ?></p>
                                </a>
                            <?php endif;?>                                                 
                        <?php endforeach;?>
                    </div>
                    <?php else: ?>
                        <div class="flex-column my-4">
                            <h4>No folders available.</h4>
                        </div>
                <?php endif;?>


                <div class="ml-4 my-2 txt-md txt-fw-2">Files</div>
                
                <?php if(!empty($files)): ?>
                    <div id="file-container">
                        <table class="file-table">
                            <thead >
                                <th class="py-2 ">File Name</th>
                                <th class="py-2 ">Owner</th>
                                <th class="py-2 ">Size</th>
                                <th class="py-2 ">Date Uploaded</th>
                                <th></th>
                            </thead>

                            <tbody>
                                <?php foreach ($files as $file): ?>
                                    <tr class="record">
                                        <td class="py-1">
                                            <?php if($file["file_type"] == "pdf" || $file["file_type"] == "jpg" || $file["file_type"] == "jpeg" || $file["file_type"] == "png"): ?>
                                            <!-- <p class="txt-xs"><i class="<?php echo getFileTypeIcon($file["file_type"])?> mr-1 file-icon"></i> <a data-id="" data-type="file" data-filename="" href="<?php echo "../storeshare/folders/".$file["file_path_name"];?>" target="_blank"><?php echo $file["file_name"]; ?></a></p>-->
                                                <p class="txt-xs"><i class="<?php echo getFileTypeIcon($file["file_type"])?> mr-1 file-icon"></i> <a href="./preview.php?filepath=<?php echo "../storeshare/folders/".$file["file_path_name"]; ?>" target="_blank"><?php echo $file["file_name"]; ?></a></p>                                                                                
                                                <?php else: ?>
                                                    <p class="txt-xs"><i class="<?php echo getFileTypeIcon($file["file_type"])?> mr-1 file-icon"></i> <a data-id="" data-type="file" data-filename="" href="<?php echo "../storeshare/folders/".$file["file_path_name"];?>" target="_blank"><?php echo $file["file_name"]; ?></a></p>
                                            <?php endif; ?>
                                        </td>

                                        <td class="py-1 ">                                        
                                            <p class="txt-xs "><?php echo (getUsername($file["file_owner"]) == $username)? "You" : getUsername($file["file_owner"]); ?></p>
                                        </td>

                                        <td class="py-1">                                        
                                            <p class="txt-xs"><?php echo round($file["file_size"]/1000)."KB"; ?></p>
                                        </td>

                                        <td class="py-1">                                        
                                            <p class="txt-xs"><?php echo $file["date_uploaded"]; ?></p>
                                        </td>
                                    
                                        <td class="py-1">                                        
                                            <a href="download.php?file=<?php echo $file["file_path_name"] ?>" class="btn btn-outline btn-sm">Download</a>
                                            <?php if(getUsername($file["file_owner"]) == $username): ?>
                                                <button id="<?php echo $file["file_id"]?>" class="btn btn-outline btn-delete btn-sm">Remove</button>
                                                <!-- <a href="remove-file.php?filePath=<?php echo $file["file_path_name"] ?>" class="btn btn-delete btn-sm">Remove</a> -->
                                            <?php endif;?>
                                        </td>
                                        
                                    </tr>
                                                                        
                                <?php endforeach; ?>        
                            </tbody>
                        </table>
                        
                    </div>

                    <?php else: ?>
                        <div class="flex-column my-4">
                            <h4>No files available.</h4>
                        </div>

                 <?php endif; ?>
            
                </div>
            </section>
            
                        
            <div class="modal-box hide">

                <div class="confirmation-box card <?php if($error["fileUploadError"] != "") echo "hide"; ?>">
                    <div class="flex"><span class="txt-warn txt-fw-4 txt-lg">!</span></div>
                    <h3>People who have access to this folder can view the file you will upload. Do you wish to continue?</h3>
                    <div class="group-button">
                    <button type="button" id="cancel-btn" class="btn btn-outline btn-wide">Cancel</button>
                    <button type="button" id="continue-btn" class="btn btn-outline btn-wide btn-warn">Continue</button>
                    </div>                                   
                </div>

                <div id="upload-file-box" class="add-folder card hide">
                    <div class="close"><i class="fas fa-times"></i></div>
                    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype="multipart/form-data" id="add-item-type" class="my-2">
                        <div class="form-group">                            
                            Select file to upload: 
                            <input type="file" name="file-upload" id="file-upload" multiple>
                            <div id="file-upload-error" class="error-msg"><?php echo $error["fileUploadError"]?></div>                                                                 
                        </div>    
                                                
                        <div class="form-group">
                            <label for="file-sensitivity">Sensitivity Level</label>
                            <select name="file-sensitivity" id="file-sensitivity">
                              <option <?php if(isset($fileSensitivity) && $fileSensitivity == "Internal Use Only") echo "selected"; ?> value="Internal Use Only">Internal Use Only</option>    
                              <option <?php if(isset($fileSensitivity) && $fileSensitivity == "Confidential") echo "selected"; ?> value="Confidential">Confidential</option>
                            </select>
                            <div id="file-sensitivity-error" class="error-msg"><?php echo $error["fileSensiError"]?></div>                                                                 
                        </div>    

                        <input type="submit" id="upload" name="upload" value="Upload" class="btn btn-outline ">
                    </form>                                      
                </div>                
            </div>
            
        <div class="modal-box flex <?php if($successMsg == "") echo "hide"?>">    
                <div class="successMsg-box card <?php if($successMsg == "") echo "hide"?>">
                    <div class="successMsg"> <i class="fa-regular fa-circle-check"></i>File was uploaded successfully</div>
                    <a href="<?php echo $currentUrl."?path=$currentPath"?>" id="done-btn" class="btn btn-outline">Done</a>
                    <!-- <button type="button" id="done-btn" class="btn btn-outline">Done</button> -->
                </div>          
        </div>

        <div id="remove-modal-box" class="modal-box flex hide">    
            <div class="file-removal-box card">
                <!-- <div class="flex"><span class="txt-warn txt-fw-4 txt-lg">!</span></div> -->
                    <h3 class="my-4">Are you sure you want to remove this file?</h3>
                    <div class="group-button flex flex-just-sb">
                    <button type="button" id="cancel-del-btn" class="btn btn-outline">Cancel</button>
                    <button type="button" id="confirm-del-btn" class="btn btn-outline btn-delete">Remove</button>
                    </div>
                </div>          
        </div>
        
    </section>

    <script src="../storeshare/storeshare.js"></script>
  </body>
</html>