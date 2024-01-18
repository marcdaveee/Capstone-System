<?php 
  // Authenticate if user is logged in
  include ("../auth_session.php");

    
  if($_SESSION["role"] != "admin"){
      header("Location: /Capstone_System/404.php");
      exit();
  }

  require("../config/config.php");
  require("../config/db_config.php");
  
  $userName = $email = $password = $userRole = $department = "";


  $error = array(
    "userNameFieldError" => "", 
    "emailFieldError" => "",
    "passwordFieldError" => "",      
    "userRoleFieldError" => "",
    "departmentFieldError" => ""        
  );

 
  $successMsg = "";


  // Check if submit button is clicked
  if(isset($_POST["submit"])){

    // if email field is empty
    if(empty($_POST["email"])){
      $error["emailFieldError"] = "*required";
    }
    else{

      if(!filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL)){
        $error["emailFieldError"] = "*email is not valid";
      }
      else{
          
        $email = htmlspecialchars($_POST["email"]);

        if(ifExist($userName)){
          $error["userNameFieldError"] = "email already exists";
        }
      }      

    }

    // if user name field is empty
    if(empty($_POST["user-name"])){
      $error["userNameFieldError"] = "*required";
    }
    else{
      $userName = htmlspecialchars($_POST["user-name"]);

      // validate user name input
      if(!preg_match('/^[a-zA-Z0-9-._\s]+$/', $userName)){ 
        $error["userNameFieldError"] = "must be combination of letters, numbers and spaces only";
      }
    }

     //if password field is empty
    if(empty($_POST["password"])){ 
      $error["passwordFieldError"] = "*required";
    }
    else{

      $password = htmlspecialchars($_POST["password"]);
      
      // validate if password has more than 6 characters length
      if(strlen($password) < 6 ){
        $error["passwordFieldError"] = "password length must be more than 6 characters";
      }
    
    //   if(!preg_match('/^[a-z]+$/', $password)){
    //     $error["passwordFieldError"] = "must have at least one lowercase character.";
    //   }

    // //   if(!preg_match('/^[A-Z]+$/', $password)){
    // //     $error["passwordFieldError"] = "must have at least one uppercase character.";
    // //   }

    // //   if(!preg_match('/^[0-9]+$/', $password)){
    // //     $error["passwordFieldError"] = "must have at least one number";
    // //   }
    }


    //if user role field is empty
    if(empty($_POST["role-type"])){ 
      $error["userRoleFieldError"] = "*required";
    }
    else{

      $userRole = htmlspecialchars($_POST["role-type"]);
       // validate user role input
       if(!preg_match('/^[a-zA-Z0-9-.\s]+$/', $userRole)){ 
        $error["userRoleFieldError"] = "must be combination of letters, numbers and spaces only";
      }
    }

      //if department field is empty
      if(empty($_POST["department"])){ 
        $error["departmentFieldError"] = "*required";
      }
      else{
  
        $department = htmlspecialchars($_POST["department"]);
         // validate department input
         if(!preg_match('/^[a-zA-Z0-9-.\s]+$/', $department)){ 
          $error["departmentFieldError"] = "must be combination of letters, numbers and spaces only";
        }
      }


    // Add item to database
    if($error["userNameFieldError"] == "" && $error["emailFieldError"] == "" && $error["passwordFieldError"] == "" && $error["userRoleFieldError"] == "" && $error["departmentFieldError"] == ""){      
      $sql = "INSERT INTO user_account_table(email, username, password, user_role, department) 
      VALUES ('$email', '$userName','" . md5($password) . "', '$userRole', '$department')";
      
      if(mysqli_query($conn, $sql)){
        $successMsg = "New User Added!";     
        $email = $userName = $password = $userRole = $userStatus = $department = "";
      } 
      else{
        echo "Error: " . $sql . " " . mysqli_error($conn);
      }
    }    

  }

  function ifExist($value){
    include("../config/db_config.php");
    $sql = "SELECT * FROM user_account_table";

    $result = mysqli_query($conn, $sql);

    $assets = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach($assets as $asset){
      if(strtoupper($asset["email"]) == strtoupper($value)){                
        return true;
      }
    }
        
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
  <?php include("../storeshare-admin/storeshare-admin-sidebar.php") ?>


    <!-- Main Dashboard -->
    <section class="main-dashboard">
      
        <!-- Form for adding new asset -->
      <section class="add-new-asset">

        <div class="top-header">
          <a href="<?php echo USER_ACCESS_MNG_URL?>user_access_mng.php" class="back btn btn-outline"><i class="fas fa-chevron-left"></i> Back</a>
          <h1 class="txt-lg">Add New User Account</h1>
          <div class="<?php if($successMsg != "") echo "success-msg" ?>"><i class="<?php if($successMsg != "") echo "fas fa-check"; ?>"></i><h4><?php echo $successMsg ?></h4></div>
        </div>
      
          <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="add-hardware-asset-form">

            <div class="add-asset-form-container grid-2">
              
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" placeholder="">
                <div id="email-error" class="error-msg"><?php echo $error["emailFieldError"]; ?></div>
              </div>  
            
            <div class="form-group">
                <label for="user-name" >Username</label>
                <input type="text" id="user-name" name="user-name" value="<?php echo $userName; ?>" placeholder="">
                <div id="username-error" class="error-msg"><?php echo $error["userNameFieldError"]; ?></div>
              </div>
              
              <div class="form-group">
                <label for="password" >Password</label>
                <input type="text" id="password" name="password" value="<?php echo $password; ?>" placeholder="">
                <div id="password-field-error" class="error-msg"><?php echo $error["passwordFieldError"]; ?></div>
              </div>
              
              <div class="form-group">
                <label for="role-type">Role</label>
                <input type="hidden" name="selected-role-type" value="<?php echo $userRole?>" id="selected-role-type">
                <select name="role-type" id="role-type">
                  <option <?php if(isset($userRole) && $userRole == "") echo "selected"; ?> value=""></option>
                
                  <option class="last" value="Define New">Define New</option>
                </select>
                <div id="role-type-error" class="error-msg"><?php echo $error["userRoleFieldError"]; ?></div>
              </div>

              <div class="form-group">
                <label for="selected-department">Department</label>
                <input type="hidden" name="selected-department" value="<?php echo $department?>" id="selected-department">
                <select name="department" id="department">
                  <option <?php if(isset($department) && $department == "") echo "selected"; ?> value=""></option>
                
                  <option class="last" value="Define New">Define New</option>
                </select>
                <div id="department-error" class="error-msg"><?php echo $error["departmentFieldError"]; ?></div>
              </div>
              
            </div>
              
              <div class="bottom-functions">
                <input type="submit" name="submit" value="Add" class="btn btn-outline btn-wide">
              </div>
              
          </form>
      </section>
                
      
      <section class="modal-box hide">
          <div class="add-category card hide" id="new-role-box">
            <div class="close"><i class="fas fa-times"></i></div>
            <form action="" method="POST" id="add-role-type">
              <div class="form-group">
                <label for="new-role-type">Create Role Type</label>
                <input type="text" name="new-role-type" id="new-role-type" value="">
                <div id="role-type-create-error" class="error-msg"></div>                                                                 
              </div>
              
              <input type="submit" name="create" value="Create" class="btn btn-outline ">
            </form>                                                  
          </div>

          <div class="add-category card hide" id="new-department-box">
            <div class="close"><i class="fas fa-times"></i></div> 
            <form action="" method="POST" id="add-department">
                <div class="form-group">
                  <label for="new-department">New Department name</label>
                  <input type="text" name="new-department" id="new-department" value="">
                  <div id="department-create-error" class="error-msg"></div>                                                                 
                </div>
                
                <input type="submit" name="create" value="Create" class="btn btn-outline ">
            </form>                        
          </div>

          <div class="successMsg-box card hide">
                <div class="successMsg"> <i class="fa-regular fa-circle-check"></i> New category has been created.</div>
                <button type="button" id="done-btn" class="btn btn-outline">Done</button>
            </div>
            
          
      </section>

    </section>

    <script src="js/uacm_validate_form.js"></script>
    <script src="../IT_Asset_Inventory/js/side_nav_handler.js"></script>
  </body>
</html>
