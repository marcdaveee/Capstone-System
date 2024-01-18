<?php 
   // Authenticate if user is logged in
   include ("../auth_session.php");

    
   if($_SESSION["role"] != "admin"){
       header("Location: /Capstone_System/404.php");
       exit();
   }
   
  require("../config/config.php");
  require("../config/db_config.php");
  
  $serialNo = $itemName = $itemBrand = $itemType = $manufacturer = $description = "";
  

  $error = array(
    "itemNameError" => "", 
    "itemSerialNoError" => "",      
    "itemBrandError" => "", 
    "itemTypeError" => "", 
    "itemManufacturerError" => ""
  );

  $successMsg = "";

  if($_SERVER["REQUEST_METHOD"] == "GET"){
    $currentId = $_GET["current_id"];


    $sql = "SELECT * FROM hardware_asset WHERE id={$currentId}";
    $result = mysqli_query($conn, $sql);

    $selectedRecord = mysqli_fetch_assoc($result);

    mysqli_free_result($result);
    
    if($selectedRecord){
      $serialNo = $selectedRecord["serial_no"];
      $itemName = $selectedRecord["item_name"];
      $itemBrand = $selectedRecord["item_brand"];
      $itemType = $selectedRecord["item_type"];
      $manufacturer =  $selectedRecord["manufacturer"];
      $description =  $selectedRecord["hardware_description"];
    }
    else{
      die("No records Available");
    }

  }


  // Check if submit button is clicked
  if(isset($_POST["submit"])){    
    
    // if serial no. field is empty
    if(empty($_POST["serial-no"])){
      $error["itemSerialNoError"] = "*required";
    }
    else{
      $serialNo = htmlspecialchars($_POST["serial-no"]);

      // validate serial number input
      if(!preg_match('/^[a-zA-Z0-9-.\s]+$/', $serialNo)){ 
        $error["itemSerialNoError"] = "must be combination of letters, numbers and spaces only";
      }

      if(ifExist($serialNo)){
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
      $error["itemTypeError"] = "*required";
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

    // update item to database!
    if($error["itemSerialNoError"] == "" && $error["itemNameError"] == "" && $error["itemBrandError"] == "" && $error["itemTypeError"] == "" && $error["itemManufacturerError"] == ""){
        $currentId = $_POST["current_id"];
        $sql = "UPDATE hardware_asset
              SET serial_no='$serialNo',
                  item_name='$itemName', 
                  item_type='$itemType',
                  item_brand='$itemBrand',
                  manufacturer='$manufacturer',
                  hardware_description='$description'
            WHERE id=$currentId"; 
            
      if(mysqli_query($conn, $sql)){
        $successMsg = "Updated successfully!";     
                
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
      if(strtoupper($asset["serial_no"]) == strtoupper($value) && $asset["id"] != $_GET["current_id"]){            
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
          <a href="<?php echo "hardware_asset.php?id=".$_GET["current_id"]?>" class="back btn btn-outline"><i class="fas fa-chevron-left"></i> Back</a>
          <h1 class="txt-lg">Update Hardware Asset</h1>
          <div class="<?php if($successMsg != "") echo "success-msg" ?>"><i class="<?php if($successMsg != "") echo "fas fa-check"; ?>"></i><h4><?php echo $successMsg ?></h4></div>
        </div>
      
          <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="add-hardware-asset-form">

            <div class="add-asset-form-container">

              <div class="form-group">
                <label for="serial-no" >Serial No.</label>
                <input type="text" id="serial-no" name="serial-no" value="<?php echo $serialNo; ?>" placeholder="">
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
                <input type="text" name="item-brand" id="item-brand" value="<?php echo $itemBrand; ?>" placeholder="Enter the name of the item">
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
              
              <div class="bottom-functions">
                <input type="hidden" id="current_id" name="current_id" value = <?php echo $_GET["current_id"];?>>
                <input type="submit" name="submit" value="Update" class="btn btn-outline btn-wide">
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
