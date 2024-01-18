<?php 

   // Authenticate if user is logged in
   include ("../auth_session.php");

    
   if($_SESSION["role"] != "admin"){
       header("Location: /Capstone_System/404.php");
       exit();
   }
  
  require("../config/config.php");
  require("../config/db_config.php");
  
  $itemName = $itemSerialNo = $manufacturer = $description = $itemBrand = $itemType = "";

  $user = "None";
  $location = "IT Department";
  $status = "Inactive";

  $error = array(
    "itemNameError" => "", 
    "itemSerialNoError" => "",      
    "itemBrandError" => "", 
    "itemTypeError" => "",
    "itemManufacturerError" => ""
  );

 
  $successMsg = "";


  // Check if submit button is clicked
  if(isset($_POST["submit"])){    

    // if serial no. field is empty
    if(empty($_POST["item-serial-no"])){
      $error["itemSerialNoError"] = "*required";
    }
    else{
      $itemSerialNo = htmlspecialchars($_POST["item-serial-no"]);

      // validate serial number input
      if(!preg_match('/^[a-zA-Z0-9-.\s]+$/', $itemSerialNo)){ 
        $error["itemSerialNoError"] = "must be combination of letters, numbers and spaces only";
      }

      if(ifExist($itemSerialNo)){
        $error["itemSerialNoError"] = "serial number already exists";
      }

    }

     //if item name field is empty
    if(empty($_POST["item-name"])){ 
      $error["itemNameError"] = "*required";
    }
    else{

      $itemName = htmlspecialchars($_POST["item-name"]);

      // validate item name input
      if(!preg_match('/^[a-zA-Z0-9-.\s]+$/', $itemName)){ 
        $error["itemNameError"] = "must be combination of letters, numbers and spaces only";
      }
    }

    //if item type field is empty
    if(empty($_POST["item-type"])){ 
      $error["itemTypeError"] = "required";
    }
    else{
      $itemType = htmlspecialchars($_POST["item-type"]);      
    }

    //if item brand field is empty
    if(empty($_POST["item-brand"])){ 
      $error["itemBrandError"] = "*required";
    }
    else{

      $itemBrand = htmlspecialchars($_POST["item-brand"]);
       // validate item brand input
       if(!preg_match('/^[a-zA-Z0-9-.\s]+$/', $itemBrand)){ 
        $error["itemBrandError"] = "must be combination of letters, numbers and spaces only";
      }
    }

        
    // if manufacturer field is empty
    if(empty($_POST["item-manufacturer"])){
      $error["itemManufacturerError"] = "*required";
    }
    else{
      $manufacturer = htmlspecialchars($_POST["item-manufacturer"]);

      // validate manufacturer field input
      if(!preg_match('/^[a-zA-Z0-9-.\s]+$/', $manufacturer)){ 
        $error["itemManufacturerError"] = "must be combination of letters, numbers and spaces only";
      }
    }

    $description = htmlspecialchars($_POST["hardware_description"]);
    
    // Add item to database
    if($error["itemSerialNoError"] == "" && $error["itemNameError"] == "" && $error["itemBrandError"] == "" && $error["itemTypeError"] == "" && $error["itemManufacturerError"] == ""){      
      $sql = "INSERT INTO hardware_asset(serial_no, item_name, item_type, item_brand, manufacturer, hardware_description, user, curr_location, curr_status) 
      VALUES ('$itemSerialNo', '$itemName', '$itemType', '$itemBrand', '$manufacturer', '$description', '$user', '$location', '$status')";
      
      if(mysqli_query($conn, $sql)){
        $successMsg = "New Item Added successfully!";
        $itemSerialNo = $itemName = $itemBrand = $itemType = $manufacturer = $description = $user = $location = $status = "";
      } 
      else{
        echo "Error: " . $sql . " " . mysqli_error($conn);
      }
    }    

  }

  function ifExist($value){
    include("../config/db_config.php");
    $sql = "SELECT * FROM hardware_asset";

    $result = mysqli_query($conn, $sql);

    $assets = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach($assets as $asset){
      if(strtoupper($asset["serial_no"]) == strtoupper($value)){                
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
  <?php include("../inc/sidebar.php") ?>


    <!-- Main Dashboard -->
    <section class="main-dashboard">
      
        <!-- Form for adding new asset -->
      <section class="add-new-asset">

        <div class="top-header">
          <a href="<?php echo HARDWARE_ASSET_URL?>" class="back btn btn-outline"><i class="fas fa-chevron-left"></i> Back</a>
          <h1 class="txt-lg">Add New Hardware Asset</h1>
          <div class="<?php if($successMsg != "") echo "success-msg" ?>"><i class="<?php if($successMsg != "") echo "fas fa-check"; ?>"></i><h4><?php echo $successMsg ?></h4></div>
        </div>
      
          <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="add-hardware-asset-form">

            <div class="add-asset-form-container">
              
              <div class="form-group">
                <label for="item-serial-no" >Serial No.</label>
                <input type="text" id="item-serial-no" name="item-serial-no" value="<?php echo $itemSerialNo; ?>" placeholder="">
                <div id="item-serialno-error" class="error-msg"><?php echo $error["itemSerialNoError"]; ?></div>
              </div>

              <div class="form-group">
                <label for="item-name" >Item Name</label>
                <input type="text" id="item-name" name="item-name" value="<?php echo $itemName; ?>" placeholder="e.g Desktop-HR-01">
                <div id="item-name-error" class="error-msg"><?php echo $error["itemNameError"]; ?></div>
              </div>
              
              <div class="form-group">
                <label for="item-type">Item Type</label>
                <input type="hidden" name="selected-item-type" value="<?php echo $itemType?>" id="selected-item-type">
                <select name="item-type" id="item-type">
                  <option <?php if(isset($itemType) && $itemType == "") echo "selected"; ?> value=""></option>
                
                  <option class="last" value="Define New">Define New</option>
                </select>
                <div id="item-type-error" class="error-msg"><?php echo $error["itemTypeError"]; ?></div>
              </div>

              <div class="form-group">
                <label for="item-brand">Item brand</label>
                <input type="text" name="item-brand" id="item-brand" value="<?php echo $itemBrand; ?>" placeholder="Enter the brand name of the item">
                <div id="item-brand-error" class="error-msg"><?php echo $error["itemBrandError"]; ?></div>
              </div>
              
              <div class="form-group">
                <label for="item-manufacturer" >Manufacturer/Vendor</label>
                <input type="text" id="item-manufacturer" name="item-manufacturer" value="<?php echo $manufacturer; ?>" placeholder="">
                <div id="item-manufacturer-error" class="error-msg"><?php echo $error["itemManufacturerError"]; ?></div>
              </div>

              <div class="form-group">
                <label for="hardware_description">Description</label>              
                <textarea name="hardware_description" id="hardware_description" cols="50" rows="5"><?php echo $description; ?></textarea>                
              </div>

            </div>
              
              <div class="bottom-functions mt-2">
                <input type="submit" name="submit" value="Add" class="btn btn-outline btn-wide">
              </div>
              
          </form>
      </section>
                
      
      <section class="modal-box hide">
          <div class="add-category card">
            <div class="close"><i class="fas fa-times"></i></div>
            <form action="" method="POST" id="add-item-type" >
              <div class="form-group">
                <label for="new-item-type">New Category Name</label>
                <input type="text" name="new-item-type" id="new-item-type" value="">
                <div id="item-type-create-error" class="error-msg"></div>                                                                 
              </div>
              
              <input type="submit" name="create" value="Create" class="btn btn-outline ">
            </form>                                      
          </div>

          <div class="successMsg-box card hide">
                <div class="successMsg"> <i class="fa-regular fa-circle-check"></i> New Item Type Category has been created.</div>
                <button type="button" id="done-btn" class="btn btn-outline">Done</button>
            </div>
            
          
      </section>

    </section>

    <script src="js/validateForm.js"></script>
    <script src="js/side_nav_handler.js"></script>
  </body>
</html>
