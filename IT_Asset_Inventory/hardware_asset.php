<?php 

   // Authenticate if user is logged in
   include ("../auth_session.php");

    
   if($_SESSION["role"] != "admin"){
       header("Location: /Capstone_System/404.php");
       exit();
   }
  
  require("../config/config.php");
  require("../config/db_config.php");
    
    if(isset($_GET["id"])){
      $currentId = mysqli_real_escape_string($conn, $_GET["id"]);
  
      // sql statement to select a particular record based on id selected
      $sql = "SELECT * FROM hardware_asset WHERE id = $currentId";

      //execute the query
      $result = mysqli_query($conn, $sql);

      // Fetch result query and convert it into a associative array
      $hardwareAsset = mysqli_fetch_assoc($result);

      if($hardwareAsset){      
        $serialNo = $hardwareAsset["serial_no"];
        $itemName = $hardwareAsset["item_name"];
        $itemBrand = $hardwareAsset["item_brand"];
        $itemType = $hardwareAsset["item_type"];
        $manufacturer = $hardwareAsset["manufacturer"];
        $description = $hardwareAsset["hardware_description"];
        $user = $hardwareAsset["user"];
        $location = $hardwareAsset["curr_location"];
        $dateAllocated = $hardwareAsset["date_allocated"];           
      }
      else{
        die("No records Available");
      }

      mysqli_free_result($result);
      mysqli_close($conn);
    }
    else{
      header("Location: hardware_assets.php");
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
          <h1 class="txt-lg">Hardware Asset Information</h1>    
        </div>

          <!-- <div class="view-asset-container card"> -->
            <div class="grid grid-2 grid-gap-3 px-4 mt-2">
              <div>
                <h3 class="txt-sm txt-fw-3">Serial No:</h3>
                <div class="flex flex-just-sb form-card">
                  <h3 class="txt-sm txt-fw-2 ml-1"><?php echo $hardwareAsset["serial_no"] ?></h3>                
                </div>
              </div>  

              <div>
                <h3 class="txt-sm txt-fw-3">Item Name:</h3>
                <div class="flex flex-just-sb form-card">
                  <h3 class="txt-sm txt-fw-2 ml-1"><?php echo $hardwareAsset["item_name"] ?></h3>                
                </div>
              </div>    
              
              <div>
                <h3 class="txt-sm txt-fw-3">Item Type:</h3>
                <div class="flex flex-just-sb form-card">
                  <h3 class="txt-sm txt-fw-2 ml-1"><?php echo $hardwareAsset["item_type"] ?></h3>                
                </div>
              </div>  

              <div>
                <h3 class="txt-sm txt-fw-3">User:</h3>
                <div class="flex flex-just-sb form-card">
                  <h3 class="txt-sm txt-fw-2 ml-1"><?php echo $hardwareAsset["user"] ?></h3>                
                </div>
              </div>  

              <div>
                <h3 class="txt-sm txt-fw-3">Item Brand:</h3>
                <div class="flex flex-just-sb form-card">
                  <h3 class="txt-sm txt-fw-2 ml-1"><?php echo $hardwareAsset["item_brand"] ?></h3>                
                </div>
              </div>                                                          

              <div>
                <h3 class="txt-sm txt-fw-3">Location:</h3>
                <div class="flex flex-just-sb form-card">
                  <h3 class="txt-sm txt-fw-2 ml-1"><?php echo $hardwareAsset["curr_location"] ?></h3>                
                </div>
              </div>                

              <div>
                <h3 class="txt-sm txt-fw-3">Manufacturer:</h3>
                <div class="flex flex-just-sb form-card">
                  <h3 class="txt-sm txt-fw-2 ml-1"><?php echo $hardwareAsset["manufacturer"] ?></h3>                
                </div>
              </div>  

              <div>
                <h3 class="txt-sm txt-fw-3">Status:</h3>
                <div class="flex flex-just-sb form-card">
                  <h3 class="txt-sm txt-fw-2 ml-1"><?php echo $hardwareAsset["curr_status"] ?></h3>                
                </div>
              </div>                

              <div>
                <h3 class="txt-sm txt-fw-3">Description:</h3>
                <div class="flex flex-just-sb form-card">
                  <h3 class="txt-sm txt-fw-2 ml-1"><?php echo $hardwareAsset["hardware_description"] ?></h3>                
                </div>
              </div>
              
              <?php if($hardwareAsset["curr_status"] == "Active"): ?>
                <div>
                  <h3 class="txt-sm txt-fw-3">Date Allocated:</h3>
                  <div class="flex flex-just-sb form-card">
                    <h3 class="txt-sm txt-fw-2 ml-1"><?php echo $hardwareAsset["date_allocated"] ?></h3>                
                  </div>
                </div>              
              <?php endif; ?>

            </div>
              
              
              <form action="edit_hardware_asset.php" method="GET">
                <div class="bottom-functions edit">
                  <?php if($hardwareAsset["curr_status"] == "Active"): ?>
                    <button type="button" id="delete-btn" class="btn btn-outline btn-wide btn-warn">Set to Inactive</button>
                  <?php else: ?>
                    <a href="<?php ROOT_URL?>allocate_hardware_asset.php?id=<?php echo $currentId?>" id="allocate-btn" class="btn btn-outline ">Allocate Asset</a>
                  <?php endif; ?>

                  <input type="hidden" name="current_id" value="<?php echo $currentId;?>">
                  <input type="submit" name="submit" value="Edit" class="btn btn-outline btn-wide">
                  </div>
              </form>                      
      </section>
      
      <section class="modal-box hide">
          <div class="confirmation-box card">
            <h3>Do you want to update the 'Status' of this asset?</h3>
            <div class="group-button">
              <button type="button" id="cancel-btn" class="btn btn-outline btn-wide">Cancel</button>
              <button type="button" id="delete-data-<?php echo $currentId?>" class="btn btn-outline btn-wide btn-warn">Set to Inactive</button>
            </div>                                   
          </div>                    
          
          <div class="successMsg-box card hide">
                <div class="successMsg"> <i class="fa-regular fa-circle-check"></i></div>
                <button type="button" id="done-btn" class="btn btn-outline">Done</button>
            </div>
      </section>

    </section>
    
    <script src="js/deleteItem.js"></script>
    <script src="js/validateForm.js"></script>    
    <script src="js/side_nav_handler.js"></script>
  </body>
</html>
