<?php 
  // Authenticate if user is logged in
  include ("../auth_session.php");

    
  if($_SESSION["role"] != "admin"){
      header("Location: /Capstone_System/404.php");
      exit();
  }

  require("../config/config.php");
  require("../config/db_config.php");
  
  
  $error = array(    
    "currPasswordFieldError" => "", 
    "passwordFieldError" => "",
    "confirmPasswordFieldError" => ""
  );

  $username = $_SESSION["username"];

  $currentPassword = $password = $confirmPassword = "";

  $successMsg = "";


  // Get user account information
  $sql = "SELECT * FROM admin_account_tb WHERE username='$username'";
  $result = mysqli_query($conn, $sql);
  if(mysqli_num_rows($result) == 0){
      header("Location: user_access_mng.php");     
  }

  $userInfo = mysqli_fetch_assoc($result);
  $userId = $userInfo["id"];
  $userName = $userInfo["username"];  
  $userRole = $userInfo["user_role"];


  // Check if submit button is clicked
  if(isset($_POST["update"])){

    if(empty($_POST["current-password"])){
      $error["currPasswordFieldError"] = "Enter current password";
    }
    else{
      $currentPassword = htmlspecialchars($_POST["current-password"]);
      if(!isPasswordCorrect($currentPassword, $userName)){
        $error["currPasswordFieldError"] = "Password is incorrect";
      }
    }

    // if password field is empty
   if(empty($_POST["password"])){
       $error["passwordFieldError"] = "Enter new password";
   }
   else{
      $password = $_POST["password"];
      if(strlen($_POST["password"]) < 6){
          $error["passwordFieldError"] = "New Password must be atleast 8 characters";
      }
      else{
          if(!preg_match("/[a-z]/", $_POST["password"])){
              $error["passwordFieldError"] = "New Password must contain at least one letter";
          }
          else{
              if(!preg_match("/[0-9]/", $_POST["password"])){
                  $error["passwordFieldError"] = "New Password must contain at least one number";
              }
              else{
                  // $password = $_POST["password"];
                  // clear
              }                                  
          }                                                
      }      
  }

  if(empty($_POST["confirm-password"])){
      $error["confirmPasswordFieldError"] = "Confirm your password";
  }
  else{
      if($password !== $_POST["confirm-password"]){
          $error["confirmPasswordFieldError"] = "Passwords must match";
      }
      else{
          $confirmPassword = $_POST["confirm-password"];                    
      }
  }

   if($error["currPasswordFieldError"] == "" && $error["passwordFieldError"] == "" && $error["confirmPasswordFieldError"] == ""){
      $password = md5($password);
       $sql = "UPDATE admin_account_tb
           SET password='$password'
           WHERE id='$userId'";

       $result = mysqli_query($conn, $sql);
       $numOfRows = mysqli_affected_rows($conn);

       if($numOfRows == 1){
          $successMsg = "Password Updated!";
          $currentPassword = $password = $confirmPassword = "";
       }
   }

}

function isPasswordCorrect($passwordInput, $username){
  include("../config/db_config.php");

  $sql = "SELECT * FROM admin_account_tb WHERE username='$username' AND password='".md5($passwordInput)."'";
  $result = mysqli_query($conn, $sql);
  $numOfRows = mysqli_num_rows($result);

  if($numOfRows == 1){
    return true;
  }

  return false;

}

                        
?> 



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>IT Assets Inventory</title>
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

  <!-- Side Nav -->
  <?php include("storeshare-admin-sidebar.php") ?>
      
    <!-- Main Dashboard -->
    <section class="main-dashboard bg-light-primary">
        <div class="container">
          <div class="sticky">
              <div id="top-header" class="flex flex-just-sb p-2">
                  <h2 id="current-path" class="txt-md"><i class="fa-regular fa-folder-open mr-1"></i>Profile</h2>
                  
              </div>
          </div>

          <div class="flex-column mt-2">
            <div class="picture-container">
              <i class="fa-solid fa-user fa-6x p-4"></i>
            </div>
            <h1 class="txt-md my-1"><?php echo $username?></h1>        
          </div>

          <div class="grid grid-2 grid-gap-3 px-4 mt-2">
            <div>
              <h3 class="txt-sm txt-fw-3">User Name</h3>
              <div class="flex flex-just-sb form-card">
                <h3 class="txt-sm txt-fw-3 ml-1"><?php echo $username ?></h3>                
              </div>
            </div>
                                    
            <div>
              <h3 class="txt-sm txt-fw-3">Role</h3>
              <div class="flex flex-just-sb form-card">
                <h3 class="txt-sm txt-fw-3 ml-1"><?php echo $userRole ?></h3>                
              </div>
            </div>

            <div class="flex">              
                <button id="change-pass-btn" class="btn btn-primary">Change Password</button>              
            </div>

          </div>
        </div>                                                      
    </section>

    <div class="modal-box flex hide">
          <section id="change-password-form" class="card bg-light-primary p-3">
            <div class="close"><i class="fas fa-times"></i></div>
            <div class="top-header flex-column">          
              <h1 class="txt-lg my-2">Change Password</h1>          
            </div>
          
              <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="my-3">

                <div class="flex-column">

                  <div class="form-group">
                    <label for="current-password" >Current Password</label>
                    <input type="password" id="current-password" name="current-password" value="<?php echo $currentPassword; ?>" placeholder="">
                    <div id="curr-password-field-error" class="error-msg"><?php echo $error["currPasswordFieldError"]; ?></div>
                  </div>
                  
                  <div class="form-group">
                    <label for="password" >New Password</label>
                    <input type="password" id="password" name="password" value="<?php echo $password; ?>" placeholder="">
                    <div id="password-field-error" class="error-msg"><?php echo $error["passwordFieldError"]; ?></div>
                  </div>

                  <div class="form-group">
                    <label for="confirm-password" >Confirm Password</label>
                    <input type="password" id="confirm-password" name="confirm-password" value="<?php echo $confirmPassword; ?>" placeholder="">
                    <div id="conf-password-field-error" class="error-msg"><?php echo $error["confirmPasswordFieldError"]; ?></div>
                  </div>
        
                  <?php if($successMsg == ""): ?>
                    <input type="submit" name="update" value="Update" id="btn-login" class="btn btn-outline btn-wide-full">                                                        
                <?php endif;?>

                <?php if($successMsg != ""): ?>
                    <div class="flex">
                        <div class="success-msg txt-xs txt-fw-3"><?php echo $successMsg; ?></div>
                    </div>
                <?php endif;?>
                  
                </div>
                                              
              </form>
          </section>

        </div>

    
    <script src="../storeshare/storeshare.js"></script>
  </body>
</html>
