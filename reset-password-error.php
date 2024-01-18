<?php 
    $error = "";
    if(isset($_GET["error"])){
        $error = htmlspecialchars($_GET["error"]);
    }
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
      
    </section>
      
        <!-- login -->
      <section id="login" class="card p-3">

        <div class="top-header flex-column">  
            <?php if($error == "token-has-expire"):?>        
                <h4 class="txt-md my-2 txt-warn txt-fw-2">Password Reset Token has expired</h1>      
                <?php else: ?>
                 <h4 class="txt-md my-2 txt-warn txt-fw-2">Password Reset Token was not found</h1>      
            <?php endif;?>    
        </div>
      
        <div class="flex">
        <i class="fa-solid fa-magnifying-glass-minus fa-2x txt-warn"></i>
        </div>  

      </section>
                

    </section>
    
  </body>
</html>
