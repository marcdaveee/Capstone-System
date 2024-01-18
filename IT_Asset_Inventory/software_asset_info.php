<?php 
   // Authenticate if user is logged in
   include ("../auth_session.php");

    
   if($_SESSION["role"] != "admin"){
       header("Location: /Capstone_System/404.php");
       exit();
   }
  
  require("../config/config.php");
  require("../config/db_config.php");

  $username = $_SESSION["username"];
    
    if(isset($_GET["id"])){
      $currentId = mysqli_real_escape_string($conn, $_GET["id"]);
  
      // sql statement to select a particular record based on id selected
      $sql = "SELECT * FROM software_asset WHERE id = $currentId";

      //execute the query
      $result = mysqli_query($conn, $sql);

      // Fetch result query and convert it into a associative array
      $softwareAsset = mysqli_fetch_assoc($result);

      if($softwareAsset){      
        $productId = $softwareAsset["product_id"];
        $softwareName = $softwareAsset["software"];
        $softwareType = $softwareAsset["software_type"];
        $manufacturer = $softwareAsset["manufacturer"];
        $dateOfPurchase = $softwareAsset["date_of_purchase"];
        $noOfInstallation = $softwareAsset["no_of_installation"];
        $validity = $softwareAsset["validity"];
        $description = $softwareAsset["soft_description"];                
        $currStatus = $softwareAsset["curr_status"];        
      }
      else{
        die("No records Available");
      }

      mysqli_free_result($result);
      mysqli_close($conn);
    }
    else{
      header("Location: software_assets.php");
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
    <section class="main-dashboard bg-light-primary">
      
        <!-- Form for adding new asset -->
      <section class="add-new-asset">

        <div class="top-header">
          <a href="<?php echo SOFTWARE_ASSET_URL?>" class="back btn btn-outline"><i class="fas fa-chevron-left"></i> Back</a>
          <h1 class="txt-lg">Software Asset Information</h1>    
        </div>

          <!-- <div class="view-asset-container card"> -->
          <div class="grid grid-2 grid-gap-3 px-4 mt-2">

            <div>
              <h3 class="txt-sm txt-fw-3">Product ID:</h3>
              <div class="flex flex-just-sb form-card">
                <h3 class="txt-sm txt-fw-2 ml-1"><?php echo htmlspecialchars($productId); ?></h3>                
              </div>
            </div>  

            <div>
              <h3 class="txt-sm txt-fw-3">Software Name:</h3>
              <div class="flex flex-just-sb form-card">
                <h3 class="txt-sm txt-fw-2 ml-1"><?php  echo htmlspecialchars($softwareName); ?></h3>                
              </div>
            </div>  
                    
            <div>
              <h3 class="txt-sm txt-fw-3">Software Type:</h3>
              <div class="flex flex-just-sb form-card">
                <h3 class="txt-sm txt-fw-2 ml-1"><?php  echo htmlspecialchars($softwareType); ?></h3>                
              </div>
            </div>              

            <div>
              <h3 class="txt-sm txt-fw-3">Vendor:</h3>
              <div class="flex flex-just-sb form-card">
                <h3 class="txt-sm txt-fw-2 ml-1"><?php  echo htmlspecialchars($manufacturer); ?></h3>                
              </div>
            </div>  

            <div>
              <h3 class="txt-sm txt-fw-3">Date of Purchase (yyyy/mm/dd):</h3>
              <div class="flex flex-just-sb form-card">
                <h3 class="txt-sm txt-fw-2 ml-1"><?php  echo htmlspecialchars($dateOfPurchase); ?></h3>                
              </div>
            </div>

            <div>
              <h3 class="txt-sm txt-fw-3">Max no. of Installation: </h3>
              <div class="flex flex-just-sb form-card">
                <h3 class="txt-sm txt-fw-2 ml-1"><?php  echo htmlspecialchars($noOfInstallation); ?></h3>                
              </div>
            </div>
            
            <div>
              <h3 class="txt-sm txt-fw-3">Validity: </h3>
              <div class="flex flex-just-sb form-card">
                <h3 class="txt-sm txt-fw-2 ml-1"><?php echo htmlspecialchars($validity); ?></h3>                
              </div>
            </div>
              
            <div>
              <h3 class="txt-sm txt-fw-3">Status: </h3>
              <div class="flex flex-just-sb form-card">
                <h3 class="txt-sm txt-fw-2 ml-1"><?php echo htmlspecialchars($currStatus); ?></h3>                
              </div>
            </div>

            <div>
              <h3 class="txt-sm txt-fw-3">Description/Notes: </h3>
              <div class="flex flex-just-sb form-card">
                <h3 class="txt-sm txt-fw-2 ml-1"><?php echo htmlspecialchars($description); ?></h3>                
              </div>
            </div>              
              
            </div>
              
              
              <form action="edit_software_asset.php" method="GET">
                <div class="edit flex flex-just-right mt-4">                  
                  <input type="hidden" name="current_id" value="<?php echo $currentId;?>">
                  <input type="submit" name="submit" value="Edit" class="btn btn-outline btn-wide">
                  </div>
              </form>                      
      </section>
      
      

    </section>
    
    <script src="js/deleteItem.js"></script>
    <script src="js/validateForm.js"></script>    
    <script src="js/side_nav_handler.js"></script>
  </body>
</html>
