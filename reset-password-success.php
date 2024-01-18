<?php 
    session_start();
    if(!isset($_SESSION["reset-success"])){
        header("Location: reset_password.php");
        exit();
    }
    session_destroy();
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Storeshare</title>
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
    <section class="main-dashboard">
      
    <section class="flex p-2" id="login-header" >
      <h1>LGU of San Antonio, Quezon</h1>      
      <a href="login.php" class="btn btn-outline">Log In</a>      
      
    </section>
      
        <!-- login -->
      <section id="login" class="card p-3">

        <div class="top-header flex-column">          
          <h1 class="txt-lg my-2 success-msg">Password Reset Successful!</h1>          
        </div>
      
        <div class="flex">
            <i class="fa-solid fa-circle-check fa-2x success-msg"></i>
        </div>  

      </section>
                

    </section>
    
  </body>
</html>
