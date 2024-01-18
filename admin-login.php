<?php 
  session_start();

  if(isset($_SESSION["username"])){
    header("Location: storeshare-admin/root.php");
    exit();
}

  require("config/config.php");
  require("config/db_config.php");
  
  $userName = $password = "";


  $error = array(
    "userNameFieldError" => "", 
    "passwordFieldError" => "",
    "logInError" => ""
  );

 
  $successMsg = "";


  // Check if submit button is clicked
  if(isset($_POST["submit"])){    

    // if user name field is empty
    if(empty($_POST["user-name"])){
      $error["userNameFieldError"] = "Enter username";
    }
    else{

      $userName = htmlspecialchars($_POST["user-name"]);
      $userName = stripslashes($userName);
      $userName = mysqli_real_escape_string($conn, $userName);
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
    if($error["userNameFieldError"] == "" && $error["passwordFieldError"] == "" ){              
        $sql = "SELECT * FROM admin_account_tb WHERE username='$userName' AND password='".md5($password)."'";
        $result = mysqli_query($conn, $sql);
        $numOfRows = mysqli_num_rows($result);
      
      if($numOfRows == 1){
        $_SESSION["username"] = $userName;
        $_SESSION["role"] = "admin";

        header("Location: main-menu.php");
      } 
      else{
        $error["userNameFieldError"] = "Incorrect username/password";
        $error["passwordFieldError"] = "Incorrect username/password";
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
      <a href="login.php" class="btn btn-outline">Log In as User</a>
    </section>
      
        <!-- login -->
      <section id="login" class="card p-3">

        <div class="top-header flex-column">          
          <h1 class="txt-lg my-2">Admin Log In</h1>          
        </div>
      
          <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="my-3">

            <div class="flex-column">
              
              <div class="form-group">
                <label for="user-name" >Username</label>
                <input type="text" id="user-name" name="user-name" value="<?php echo $userName; ?>" placeholder="">
                <div id="username-error" class="error-msg"><?php echo $error["userNameFieldError"]; ?></div>
              </div>

              <div class="form-group">
                <label for="password" >Password</label>
                <input type="password" id="password" name="password" value="<?php echo $password; ?>" placeholder="">
                <div id="password-field-error" class="error-msg"><?php echo $error["passwordFieldError"]; ?></div>
              </div>
              
              <div class="flex my-1">
                  <a href="forgot_admin_password.php" class="txt-link">Forgot password?</a>
              </div>

              <input type="submit" name="submit" value="Log In" id="btn-login" class="btn btn-outline btn-wide-full">                                                        
            </div>
                                          
          </form>
      </section>
                
      
      <section class="modal-box hide">
          <div class="add-category card">
            <div class="close"><i class="fas fa-times"></i></div>
            <form action="" method="POST" id="add-role-type" >
              <div class="form-group">
                <label for="new-role-type">Create Role Type</label>
                <input type="text" name="new-role-type" id="new-role-type" value="">
                <div id="role-type-create-error" class="error-msg"></div>                                                                 
              </div>
              
              <input type="submit" name="create" value="Create" class="btn btn-outline ">
            </form>                                      
          </div>

          <div class="successMsg-box card hide">
              <div class="successMsg"> <i class="fa-regular fa-circle-check"></i> New Role Type Category has been created.</div>
              <button type="button" id="done-btn" class="btn btn-outline">Done</button>
          </div>                      
      </section>

    </section>
    
  </body>
</html>
