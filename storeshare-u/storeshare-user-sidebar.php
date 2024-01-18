   <!-- Side Bar Navigation -->
<?php 
    

    if($_SESSION["role"] != "user"){
        header("Location: /Capstone_System/404.php");
        exit();
    }

    $username = $_SESSION["username"];
    $userEmail = $_SESSION["email"];

    $sql = "SELECT * FROM user_account_table WHERE email='$userEmail'";

    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1){
        $userInfo = mysqli_fetch_assoc($result);
        $userId = $userInfo["user_id"]; 
    }
   
?>
   
   <section class="side-bar" id="user-side-nav">
      <h2 class="header txt-md my-3">Storeshare</h2>
      <ul class="flex-column flex-align-left flex-just-sb">

        <div class="flex-column flex-align-left flex-just-left">
            <li class="my-2 flex flex-just-left nav-link" id="file-system-nav">                
                <!-- <div class="flex " > -->
                    <a href="/Capstone_System/storeshare-u/root.php" id="folders-tab" class="block txt-fw-2 flex py-1"> <i class="fa-solid fa-folder-tree mx-1 ml-2"></i>Folder System</a>
                <!-- </div>                 -->
            </li>
            
            <li class="my-2 flex flex-just-left nav-link" id="shared-with-nav">
                <!-- <div class="flex nav-link" > -->
                    <a href="/Capstone_System/storeshare-u/shared.php" id="folders-tab" class="block txt-fw-2 flex py-1"> <i class="fa-solid fa-user-group mx-1 ml-2"></i>Shared with me</a>
                <!-- </div>                 -->
            </li>     

            <li class="my-2 flex flex-just-left nav-link profile-link" id="profile-nav-link">
                <!-- <div class="flex nav-link" > -->
                    <a href="profile.php" id="folders-tab" class="block flex txt-fw-2 flex py-1"> <i class="fa-solid fa-user mx-1 ml-2"></i>Profile</a>
                <!-- </div>                 -->
            </li>     
        </div>

      

        <li class=" flex">            
            <a href="/Capstone_System/logout.php" id="" class="btn btn-primary  txt-fw-2"> <i class="fa-solid fa-right-from-bracket mx-1 invert-left"></i>Logout</a>            
        </li>
        
      </ul>
    </section>