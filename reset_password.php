<?php
    if(isset($_GET["token"])){
        $token = $_GET["token"];
        $token_hash = hash("sha256", $token);

        require("config/config.php");
        require("config/db_config.php");
        
        $sql = "SELECT * FROM user_account_table WHERE reset_token_hash='$token_hash'";

        $result = mysqli_query($conn, $sql);
        $user = mysqli_fetch_assoc($result);                
        // $numOfRows = mysqli_num_rows($result);
        
        if($user === null){
            $error = "token-not-found";            
            // redirect to error page
            header("Location: reset-password-error.php?error=$error");
        }

        if(strtotime($user["reset_token_expires_at"]) <= time()){            
            $error = "token-has-expire";  
            // redirect to error page
            header("Location: reset-password-error.php?error=$error");
        }

        $userId = $user["user_id"];
    }
    

    $password = $confirmPassword = "";
    
    $error = array(
        "passwordFieldError" => "", 
        "confirmPasswordFieldError" => "",        
    );

    $successMsg = "";

    if(isset($_POST["update"])){
         // if user name field is empty
        if(empty($_POST["password"])){
            $error["passwordFieldError"] = "Enter password";
        }
        else{
            

            if(strlen($_POST["password"]) < 6){
                $error["passwordFieldError"] = "Password must be atleast 8 characters";
            }
            else{
                if(!preg_match("/[a-z]/", $_POST["password"])){
                    $error["passwordFieldError"] = "Password must contain at least one letter";
                }
                else{
                    if(!preg_match("/[0-9]/", $_POST["password"])){
                        $error["passwordFieldError"] = "Password must contain at least one number";
                    }
                    else{
                        $password = $_POST["password"];
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
                $password = md5($password);
                
            }
        }
    
        if($error["passwordFieldError"] == "" && $error["confirmPasswordFieldError"] == ""){
            $sql = "UPDATE user_account_table
                SET reset_token_hash = NULL,
                reset_token_expires_at = NULL,
                password='$password'
                WHERE user_id='$userId'";

            $result = mysqli_query($conn, $sql);
            $numOfRows = mysqli_affected_rows($conn);

            if($numOfRows == 1){
                mysqli_close($conn);
                session_start();
                $_SESSION["reset-success"] = "reset-successful";
                header("Location: reset-password-success.php");
                
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
      <?php if($successMsg != ""): ?>
        <a href="login.php" class="btn btn-outline">Log In as User</a>      
        <?php endif;?>
    </section>
      
        <!-- login -->
      <section id="login" class="card p-3">

        <div class="top-header flex-column">          
          <h1 class="txt-lg my-2">Reset Password</h1>          
        </div>
      
          <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="my-3">

            <div class="flex-column">
              
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
                
      

    </section>
    
  </body>
</html>
