<?php 
    // Authenticate if user is logged in
    include ("auth_session.php");

    
    if($_SESSION["role"] != "admin"){
        header("Location: /Capstone_System/404.php");
        exit();
    }

    $username = $_SESSION["username"];    
    
    

    $paths = array(

    );

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LGU SAQ Storeshare</title>
    <link rel="stylesheet" href="CSS/style.css" />
    <link rel="stylesheet" href="CSS/utilities.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
      integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
  </head>
  <body>

      <!-- Main Dashboard -->
    <section class="main-dashboard ">
        <section class="flex p-2" id="login-header" >
        <h1>LGU of San Antonio, Quezon</h1>        
        </section>

        <div class="container flex">

            <div id="main-menu" class="grid grid-2 grid-gap-3 mt-4 px-4 flex-just-center w-70">
                <a href="/Capstone_System/storeshare-admin/root.php" class="card bd-round-sm bg-light-primary flex flex-column p-4 clickable menu-link">
                    <i class="fa-solid fa-folder-tree fa-4x mb-2"></i>
                    <h2 class="txt-fw-3 txt-md txt-light">File System</h2>
                </a>

                <a href="/Capstone_System/IT_Asset_Inventory/hardware_assets.php" class="card bd-round-sm bg-light-primary flex flex-column p-4  menu-link">
                <i class="fas fa-house fa-4x mb-2"></i>
                    <h2 class="txt-fw-3 txt-md txt-light">IT Asset Inventory</h2>
                </a>

                <a href="/Capstone_System/ticketing/dashboard.php" class="card bd-round-sm bg-light-primary flex flex-column p-4  menu-link">
                    <i class="fa-solid fa-clipboard-list fa-4x mb-2"></i>
                    <h2 class="txt-fw-3 txt-md txt-light">Incident Management</h2>
                </a>
            </div>

        </div>
        
    </section>
    
  </body>
</html>