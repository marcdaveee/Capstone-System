<?php 

   // Authenticate if user is logged in
   include ("../auth_session.php");

    
   if($_SESSION["role"] != "admin"){
       header("Location: /Capstone_System/404.php");
       exit();
   }
  
  require("../config/config.php");
  require("../config/db_config.php");
  
  $productId = $softwareName = $softwareType = $manufacturer = $dateOfPurchase = $numOfInstallation = $validity = $softwDescription = $currStatus = "";
  
  $error = array(
    "productIdError" => "",
    "softwareNameError" => "", 
    "softwareTypeError" => "", 
    "manufacturerFieldError" => "", 
    "dateOfPurchaseError" => "",
    "noOfInstallationError" => "", 
    "validityDateError" => "",    
    "softwDescriptionError" => "",
    "currStatus" => ""
  );

  $successMsg = "";

  // Check if submit button is clicked
  if(isset($_POST["submit"])){    

    //if Product Id field is empty
    if(empty($_POST["product-id"])){
      $error["productIdError"] = "*required";
    }
    else{
      $productId = htmlspecialchars($_POST["product-id"]);

      // validate product ID input
      if(!preg_match('/^[a-zA-Z0-9-.\s]+$/', $productId)){ 
        $error["productIdError"] = "must be combination of letters, numbers and spaces only";
      }

      if(ifExist($productId)){
        $error["productIdError"] = "Product ID already exists";
      }

    }


     //if software name field is empty
    if(empty($_POST["software-name"])){ 
      $error["softwareNameError"] = "*required";
    }
    else{

      $softwareName = htmlspecialchars($_POST["software-name"]);

      // validate software name input
      if(!preg_match('/^[a-zA-Z0-9-.\s]+$/', $softwareName)){ 
        $error["softwareNameError"] = "must be combination of letters, numbers and spaces only";
      }
    }

    //if software type field is empty
    if(empty($_POST["software-type"])){ 
      $error["softwareTypeError"] = "*required";
    }
    else{

      $softwareType = htmlspecialchars($_POST["software-type"]);
    }

    //if manufacturer field is empty
    if(empty($_POST["manufacturer"])){ 
      $error["manufacturerFieldError"] = "*required";
    }
    else{

      $manufacturer = htmlspecialchars($_POST["manufacturer"]);
       // validate manufacturer name input
       if(!preg_match('/^[a-zA-Z0-9-.\s]+$/', $manufacturer)){ 
        $error["manufacturerFieldError"] = "must be combination of letters, numbers and spaces only";
      }
    }

    //if date of purchase field is empty
    if(empty($_POST["date-of-purchase"])){ 
      $error["dateOfPurchaseError"] = "*required";
    }
    else{        
      $dateOfPurchase = htmlspecialchars($_POST["date-of-purchase"]);      
    }

    //if no of installation field is empty
    if(empty($_POST["no-of-installation"])){ 
      $error["noOfInstallationError"] = "*required";
    }
    else{
        
      $numOfInstallation = htmlspecialchars($_POST["no-of-installation"]);

        // validate if an integer
      if(!preg_match('/^[0-9]+$/', $numOfInstallation)){
        $error["noOfInstallationError"] = "must be a number";
      }
      else{
        $numOfInstallation = intval($numOfInstallation);
      }

    }

    //handle validity date field
    
    $validity = htmlspecialchars($_POST["validity"]);
    if(!empty($_POST["validity"])){
      $currentDate = date("Y/m/d");      
      $currentDate = date_create($currentDate);
      $expirationDate = date_create($validity);
      $interval = date_diff($currentDate, $expirationDate);      
      $day = $interval->format('%r%a');
      
      if(intval($day) == "1"){
        $currStatus = $interval->format("expires in %a day");  
      }
      else if(intval($day) > 1){
        $currStatus = $interval->format("expires in %a days");  
      }
      else if (intval($day) < 1){
        $currStatus = $interval->format("expired");  
      }
      else{
        // 
      }            
    }
    else{
      // $currStatus = "No expiration";
    }    
    
    //handle software description field
    $softwDescription = htmlspecialchars($_POST["softw_description"]);

    // Add item to database!
    if($error["productIdError"] == "" && $error["softwareNameError"] == "" && $error["softwareTypeError"] == "" && $error["manufacturerFieldError"] == "" && $error["dateOfPurchaseError"] == "" && $error["noOfInstallationError"] == "" && $error["softwDescriptionError"] == ""){
      $sql = "INSERT INTO software_asset(product_id, software, software_type, manufacturer, date_of_purchase, no_of_installation, validity, soft_description, curr_status) 
      VALUES ('$productId','$softwareName', '$softwareType', '$manufacturer', '$dateOfPurchase', '$numOfInstallation', '$validity', '$softwDescription', '$currStatus')";

      if(mysqli_query($conn, $sql)){
        $successMsg = "New Software Asset Added to Inventory!";     
        $productId = $softwareName = $softwareType = $manufacturer = $dateOfPurchase = $numOfInstallation = $validity = $softwDescription = $currStatus = "";
      }
      else{
        echo "Error: " . $sql . " " . mysqli_error($conn);
      }
    }

  }

  function ifExist($value){
    include("../config/db_config.php");
    $sql = "SELECT * FROM software_asset";

    $result = mysqli_query($conn, $sql);

    $assets = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach($assets as $asset){
      if(strtoupper($asset["product_id"]) == strtoupper($value)){                
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
          <a href="<?php echo SOFTWARE_ASSET_URL?>" class="back btn btn-outline"><i class="fas fa-chevron-left"></i> Back</a>
          <h1 class="txt-lg">Add Software Asset</h1>
          <div class="<?php if($successMsg != "") echo "success-msg" ?>"><i class="<?php if($successMsg != "") echo "fas fa-check"; ?>"></i><h4><?php echo $successMsg ?></h4></div>
        </div>
      
          <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="add-software-asset-form">

            <div class="add-asset-form-container">

               <div class="form-group">
                <label for="product-id" >Product ID</label>
                <input type="text" id="product-id" name="product-id" value="<?php echo $productId ?>" placeholder="Enter the Product ID of the software">
                <div id="product-id-error" class="error-msg"><?php echo $error["productIdError"]; ?></div>
              </div>
              
              <div class="form-group">
                <label for="software-name" >Software Name</label>
                <input type="text" id="software-name" name="software-name" value="<?php echo $softwareName ?>" placeholder="e.g Chrome">
                <div id="software-name-error" class="error-msg"><?php echo $error["softwareNameError"]; ?></div>
              </div>

              <div class="form-group">
                <label for="software-type">Software Type</label>
                <input type="hidden" name="selected-software-type" value="<?php echo $softwareType?>" id="selected-software-type">
                <select name="software-type" id="software-type">
                  <option <?php if(isset($softwareType) && $softwareType == "") echo "selected"; ?> value=""></option>
                  
                  <option class="last" value="Define New">Define New</option>
                </select>
                <div id="software-type-error" class="error-msg"><?php echo $error["softwareTypeError"]; ?></div>
              </div>

              <div class="form-group">
                <label for="manufacturer">Vendor</label>
                <input type="text" name="manufacturer" id="manufacturer" value="<?php echo $manufacturer; ?>" placeholder="e.g Google Inc.">
                <div id="manufacturer-field-error" class="error-msg"><?php echo $error["manufacturerFieldError"]; ?></div>
              </div>

              <div class="form-group">
                <label for="date-of-purchase" >Date of Purchase</label>
                <input type="date" id="date-of-purchase" name="date-of-purchase" value="<?php echo $dateOfPurchase ?>" placeholder="">
                <div id="date-purchase-error" class="error-msg"><?php echo $error["dateOfPurchaseError"]; ?></div>
              </div>

              <div class="form-group">
                <label for="no_of_installation">Max no. of Installation(s)</label>
                <input type="number" name="no-of-installation" id="no-of-installation" min="1" max="10000" value="<?php echo $numOfInstallation; ?>">
                <div id="no-of-installation-error" class="error-msg"><?php echo $error["noOfInstallationError"]; ?></div>
              </div>

              <div class="form-group">
                <label for="validity" >Validity</label>
                <input type="date" id="validity" name="validity" value="<?php echo $validity ?>" placeholder="Date of expiration">
                <div id="date-validity-error" class="error-msg"><?php echo $error["validityDateError"]; ?></div>
              </div>

              <div class="form-group">
                <label for="soft_description">Notes/Description</label>              
                <textarea name="softw_description" id="softw_description" cols="50" rows="5" placeholder=""><?php echo $softwDescription; ?></textarea>
                <div id="softwDescriptionError" class="error-msg"><?php echo $error["softwDescriptionError"]; ?></div>
              </div>

            </div>
              
              <div class="bottom-functions">
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
              <div class="successMsg"> <i class="fa-regular fa-circle-check"></i> New Software Type Category has been created.</div>
              <button type="button" id="done-btn" class="btn btn-outline">Done</button>
          </div>                    
      </section>

    </section>

    <script src="js/side_nav_handler.js"></script>
    <script src="js/validateSoftwareForm.js"></script>
  </body>
</html>
