<?php 
   // Authenticate if user is logged in
   include ("../auth_session.php");

    
   if($_SESSION["role"] != "admin"){
       header("Location: /Capstone_System/404.php");
       exit();
   }

  require("../config/config.php");
  require("../config/db_config.php");

  $serialNo = $itemName = $itemBrand = $itemType = $manufacturer = $description = $user = $location = $status = "";

  $successMsg = "";
  

  $error = array(    
    "userFieldError" => "", 
    "locationFieldError" => "", 
  );
  
    if(isset($_GET["id"])){
      
      $currentId = $_GET["id"];

      // sql statement to select a particular record based on id selected
      $sql = "SELECT * FROM hardware_asset WHERE id = $currentId";

      //execute the query
      $result = mysqli_query($conn, $sql);

      // Fetch result query and convert it into a associative array
      $hardwareAsset = mysqli_fetch_assoc($result);

      if($hardwareAsset){
        if($hardwareAsset["curr_status"] == "Inactive"){
          $serialNo = $hardwareAsset["serial_no"];
          $itemName = $hardwareAsset["item_name"];
          $itemBrand = $hardwareAsset["item_brand"];
          $itemType = $hardwareAsset["item_type"];
          $manufacturer =  $hardwareAsset["manufacturer"];
          $description =  $hardwareAsset["hardware_description"];
          // $user = $hardwareAsset["user"];
          $user = "";
          $location = $hardwareAsset["curr_location"];
          $status = $hardwareAsset["curr_status"];
        }
        else{
          die("This asset is already allocated!");  
        }
        
      }
      else{
        die("No records Available");
      }
      
     
    }
    
    if(isset($_POST["submit"])){
      //if user field is empty
  
      if(empty($_POST["user"]) || $_POST["user"] == "None" || $_POST["user"] == "none"){ 
        $error["userFieldError"] = "*required";
      }
      else{
  
        $user = htmlspecialchars($_POST["user"]);
        
        if(!preg_match('/^[a-zA-Z\s]+$/', $user)){
          
          $error["userFieldError"] = "must be combination of letters and spaces only";
        }
  
      }
  
      //if Location field is empty
      if(empty($_POST["curr-location"])){ 
        $error["locationFieldError"] = "*required";
      }
      else{
        $location = htmlspecialchars($_POST["curr-location"]);        
      }
      
        // Add item to database
      if($error["userFieldError"] == "" && $error["locationFieldError"] == ""){ 
        $currentId = $_POST["current_id"];
        $status = "Active";
               
        $sql = "UPDATE hardware_asset
        SET user='$user', 
            curr_location='$location',
            curr_status='$status'
        WHERE id=$currentId"; 
  
        if(mysqli_query($conn, $sql)){
          $successMsg = "Asset Allocated!";     
          // $user = $location = $status = "";
        }
        else{
          echo "Error: " . $sql . " " . mysqli_error($conn);
        }
      }

      mysqli_free_result($result);
      mysqli_close($conn);
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
  <?php include("../inc/sidebar.php") ?>


    <!-- Main Dashboard -->
    <section class="main-dashboard">
      
        <!-- Form for allocating hardware asset -->
      <section class="add-new-asset">

        <div class="top-header">
          <a href="<?php echo HARDWARE_ASSET_URL?>" class="back btn btn-outline"><i class="fas fa-chevron-left"></i> Back</a>
          <div class="<?php if($successMsg != "") echo "success-msg" ?>"><i class="<?php if($successMsg != "") echo "fas fa-check"; ?>"></i><h4><?php echo $successMsg ?></h4></div>
        </div>
        
        <div class="allocate-asset-view">

          <div class="asset-info card">
            <h1 class="txt-md">Hardware Asset Information</h1>    
            <div class="asset-info-container">
              
              <div class="form-group">
                <p class="txt-sm txt-weight-medium">Serial No: <span class="txt-sm txt-weight-light"><?php echo $serialNo; ?></span></p>
              </div>

              <div class="form-group">
                <p class="txt-sm txt-weight-medium">Item Name: <span class="txt-sm txt-weight-light"><?php echo $itemName; ?></span></p>
              </div>

              <div class="form-group">
                <p class="txt-sm txt-weight-medium">Item Type: <span class="txt-sm txt-weight-light"><?php echo $itemType;?></span></p>
              </div>

              <div class="form-group">
                  <p class="txt-sm txt-weight-medium">Item Brand: <span class="txt-sm txt-weight-light"><?php echo $itemBrand; ?></span></p> 
              </div>

              <div class="form-group">
                  <p class="txt-sm txt-weight-medium">Manufacturer/Vendor: <span class="txt-sm txt-weight-light"><?php echo $manufacturer; ?></span></p> 
              </div>

              <div class="form-group">
                  <p class="txt-sm txt-weight-medium">Status: <span class="txt-sm txt-weight-light"><?php echo $status; ?></span></p> 
              </div>

              <div class="form-group">
                  <p class="txt-sm txt-weight-medium">Description: <span class="txt-sm txt-weight-light"><?php echo $description; ?></span></p> 
              </div>

            </div>
          </div>
                                    
          <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="asset-alloc-form card">
            <h1 class="txt-md">Asset Allocation</h1>    
            <div class="form-group">
              <label for="user">User</label>
              <input type="text" name="user" id="user" value="<?php echo $user; ?>" placeholder="e.g John Doe">
              <div id="user-field-error" class="error-msg"><?php echo $error["userFieldError"]; ?></div>
            </div>

            <div class="form-group">
              <label for="curr-location">Location</label>     
              <input type="hidden" name="selected-location-option" value="<?php echo $location?>" id="selected-location-option">         
              <select name="curr-location" id="curr-location">
                <option <?php if(isset($location) && $location == "") echo "selected"; ?> value=""></option>
                <option class="last" value="Define New">Define New</option>          
              </select>      
              <div id="curr-location-error" class="error-msg"><?php echo $error["locationFieldError"]; ?></div>
            </div>
            
            <div class="bottom-functions allocate">                          
              <?php if($successMsg == ""): ?>                
                <input type="hidden" name="current_id" value="<?php echo $currentId;?>">
                <input type="submit" name="submit" value="Allocate" class="btn btn-outline ">
                <?php else: ?>
                  <a href="<?php echo HARDWARE_ASSET_URL?>" class="btn btn-outline ">Done</a>
              <?php endif; ?>
              </div>
          </form>      
    
          </div>
      </section>
      
      <section class="modal-box hide">
          <div class="add-category card">
            <div class="close"><i class="fas fa-times"></i></div>
            <form action="" method="POST" id="add-location-option" >
              <div class="form-group">
                <label for="new-location-option">Location Name</label>
                <input type="text" name="new-location-option" id="new-location-option" value="">
                <div id="location-create-error" class="error-msg"></div>                                                                 
              </div>
              
              <input type="submit" name="create" value="Create" class="btn btn-outline ">
            </form>                                      
          </div>

          <div class="successMsg-box card hide">
                <div class="successMsg"> <i class="fa-regular fa-circle-check"></i> New Location has been created.</div>
                <button type="button" id="done-btn" class="btn btn-outline">Done</button>
          </div>
                                  
      </section>

    </section>

    <script src="js/validateAlloc.js"></script>
    <script src="js/side_nav_handler.js"></script>
  </body>
</html>
