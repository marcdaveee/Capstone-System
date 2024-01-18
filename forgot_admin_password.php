<?php 
  session_start();

  if(isset($_SESSION["username"]) && $_SESSION["role"] == "user"){
    header("Location: /Capstone_System/storeshare-u/root.php");
    exit();
}

if(isset($_SESSION["username"]) && $_SESSION["role"] == "admin"){
    header("Location: /Capstone_System/storeshare-admin/root.php");
    exit();
}

  require("config/config.php");
  require("config/db_config.php");
  
  

  $email = "";


  $error = array(
    "emailFieldError" => "",    
  );

 
  $successMsg = "";


  // Check if submit button is clicked
  if(isset($_POST["submit"])){    

    // if user name field is empty
    if(empty($_POST["email"])){
      $error["emailFieldError"] = "Enter email";
    }
    else{

      if(!filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL)){
        $error["emailFieldError"] = "*email is not valid";
      }
      else{
        $email = htmlspecialchars($_POST["email"]);
        $email = stripslashes($email);
        $email = mysqli_real_escape_string($conn, $email);
      }      
    }


    // Authenticate credentials
    if($error["emailFieldError"] == ""){
        $token = bin2hex(random_bytes(16));

        $tokenHash = hash("sha256", $token);

        $expiry = date("Y-m-d H:i:s", time() + 60 * 30);

        $sql = "UPDATE admin_account_tb 
        SET reset_token_hash='$tokenHash', reset_token_expires_at='$expiry'
        WHERE email='$email'";
        
        
        $result = mysqli_query($conn, $sql);
        $affectedRows = mysqli_affected_rows($conn);
      
      if($affectedRows){        
      
        $mail = require ("mailer.php"); 
        
        $mail->addAddress($email);

        $mail->Subject = "Password Reset";
        $mail->Body = <<<END
            Click <a href="http://localhost/Capstone_System/reset_admin_password.php?token=$token">here</a> to reset your password. Please ignore if you didn't request this. Thank you!
        END;
        
        try{
            $mail->send();

        }catch(Exception $e){
            echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
        }
      } 
      $successMsg = "Message is sent, please check your inbox.";
    }    
    
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
      
    <section class="flex p-3" id="login-header" >
      <h1>LGU of San Antonio, Quezon</h1>
      <a href="admin-login.php" class="btn btn-outline">Log In as Admin</a>
    </section>
      
        <!-- login -->
      <section id="login" class="card p-3">

        <div class="top-header flex-column">          
          <h1 class="txt-lg my-2">Forgot Password (Admin)</h1>          
        </div>
      
          <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="my-3">

            <div class="flex-column">
              
              <div class="form-group">
                <label for="email" >Email</label>
                <input type="text" id="email" name="email" value="<?php echo $email; ?>" placeholder="">
                <div id="email-error" class="error-msg"><?php echo $error["emailFieldError"]; ?></div>
                <div class="success-msg txt-xs txt-fw-3"><?php echo $successMsg; ?></div>
              </div>

              <?php if($successMsg == ""): ?>
                <input type="submit" name="submit" value="Send" id="btn-send" class="btn btn-outline ">                                                        
              <?php endif; ?>
            </div>
                                          
          </form>
      </section>
                
      
    

    </section>
    
  </body>
</html>
