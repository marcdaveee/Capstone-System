   <!-- Side Bar Navigation -->
   <?php 
    

    if($_SESSION["role"] != "admin"){
        header("Location: /Capstone_System/404.php");
        exit();
    }

    $username = $_SESSION["username"];        
   
?>
   
   <section class="side-bar" id="user-side-nav">
      <h2 class="header txt-md my-3">Storeshare</h2>
      <ul class="flex-column flex-align-left flex-just-sb">

        <div class="flex-column flex-align-left flex-just-left">
            <li class="my-1 flex flex-just-left nav-link" id="file-system-nav">                
                <!-- <div class="block"> -->
                    <a href="/Capstone_System/storeshare-admin/root.php" id="folders-tab" class="block txt-fw-2 flex py-1"> <i class="fa-solid fa-folder-tree mx-1 ml-2"></i>Folder System</a>
                <!-- </div>                 -->
            </li>

            <li class="my-1 flex flex-just-left nav-link" id="folder-info-nav">
                <!-- <div class="flex nav-link" id="shared-with-nav"> -->
                    <a href="/Capstone_System/storeshare-admin/logs.php" id="" class="block txt-fw-2 flex py-1"> <i class="fa-regular fa-folder-open mx-1 ml-2"></i>Folder Logs</a>
                <!-- </div>                 -->
            </li>
            
            <li class="my-1 flex flex-just-left nav-link"  id="user-tab">
                <!-- <div class="block"> -->
                    <a href="/Capstone_System/UACM/user_access_mng.php" id="" class="block txt-fw-2 flex py-1"> <i class="fa-solid fa-user-group mx-1 ml-2"></i>User Access</a>
                <!-- </div>                 -->
            </li>     
                                                                       
            <li class="my-1 flex flex-just-left profile-link" id="profile-admin-link" >
                <div id="" class="flex nav-link">
                    <a href="/Capstone_System/storeshare-admin/profile.php" id="folders-tab" class="block flex txt-fw-2 flex py-1"> <i class="fa-solid fa-user mx-1 ml-2"></i>Profile</a>
                </div>                
            </li>     

            <li class="my-1 flex flex-just-left nav-link mt-4">
                <!-- <div class="flex nav-link" id="shared-with-nav"> -->
                    <a href="/Capstone_System/main-menu.php" id="" class="block txt-fw-2 flex py-1"> <i class="fas fa-left-long mx-1 ml-2"></i>Back to Menu</a>
                <!-- </div>                 -->
            </li>    


        </div>

      

        <li class=" flex">            
            <a href="/Capstone_System/logout.php" id="" class="btn btn-primary  txt-fw-2"> <i class="fa-solid fa-right-from-bracket mx-1 invert-left"></i>Logout</a>            
        </li>
        
      </ul>
    </section>