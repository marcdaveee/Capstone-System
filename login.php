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
  
  

  $email = $password = "";


  $error = array(
    "emailFieldError" => "", 
    "passwordFieldError" => "",
    "logInError" => ""
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

     //if password field is empty
    if(empty($_POST["password"])){ 
      $error["passwordFieldError"] = "Enter password";
    }
    else{

      $password = htmlspecialchars($_POST["password"]);    
      $password = stripslashes($password);
      $password = mysqli_real_escape_string($conn, $password);          
    }

    // Authenticate credentials
    if($error["emailFieldError"] == "" && $error["passwordFieldError"] == "" ){      
        $sql = "SELECT * FROM user_account_table WHERE email='$email' AND password='".md5($password)."'";
        $result = mysqli_query($conn, $sql);
        $numOfRows = mysqli_num_rows($result);
      
      if($numOfRows == 1){
        $userInfo = mysqli_fetch_assoc($result);
        $username = $userInfo["username"];        
        $userEmail = $userInfo["email"];
        $_SESSION["username"] = $username;
        $_SESSION["email"] = $userEmail;
        $_SESSION["role"] = "user";
        
        header("Location: storeshare-u/root.php");
      } 
      else{
        $error["emailFieldError"] = "Incorrect email/password";
        $error["passwordFieldError"] = "Incorrect email/password";
      }
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
      
    <section class="flex p-2" id="login-header" >
      <h1>LGU of San Antonio, Quezon</h1>
      <a href="admin-login.php" class="btn btn-outline">Log In as Admin</a>
    </section>
      
        <!-- login -->
      <section id="login" class="card p-3">

        <div class="top-header flex-column">          
          <h1 class="txt-lg my-2">Log In</h1>          
        </div>
      
          <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="my-3">

            <div class="flex-column">
              
              <div class="form-group">
                <label for="email" >Email</label>
                <input type="text" id="email" name="email" value="<?php echo $email; ?>" placeholder="">
                <div id="email-error" class="error-msg"><?php echo $error["emailFieldError"]; ?></div>
              </div>

              <div class="form-group">
                <label for="password" >Password</label>
                <input type="password" id="password" name="password" value="<?php echo $password; ?>" placeholder="">
                <div id="password-field-error" class="error-msg"><?php echo $error["passwordFieldError"]; ?></div>
              </div>

              <div class="flex my-1">
                  <a href="forgot_password.php" class="txt-link">Forgot password?</a>
              </div>
              
              <input type="submit" name="submit" value="Log In" id="btn-login" class="btn btn-outline btn-wide-full">                                                        
            </div>
                                          
          </form>
      </section>
                
      

    </section>
    
  </body>
</html>
