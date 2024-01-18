<?php 
   // Authenticate if user is logged in
   include ("../auth_session.php");

    
   if($_SESSION["role"] != "admin"){
       header("Location: /Capstone_System/404.php");
       exit();
   }


    require("../config/db_config.php");

    $username = $_SESSION["username"];

    $sql = "SELECT * from software_asset WHERE validity != '0000-00-00'";

    $result = mysqli_query($conn, $sql);

    $assets = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach ($assets as $asset){
      $currentId = $asset["id"];
      $updatedStatus = getStatus($asset["validity"]);
      
      $sql = "UPDATE software_asset 
          SET curr_status='$updatedStatus' 
          WHERE id='$currentId'";
      mysqli_query($conn, $sql);      
    }

    $sql = "SELECT id, product_id, software, software_type, manufacturer, date_of_purchase, no_of_installation, validity, soft_description, curr_status FROM software_asset ORDER BY software_type";

    $result = mysqli_query($conn, $sql);

    $assets = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_free_result($result);

    mysqli_close($conn);

    function getStatus($validityDate){
      $currentDate = date("Y/m/d");      
      $currentDate = date_create($currentDate);
      $expirationDate = date_create($validityDate);
      $interval = date_diff($currentDate, $expirationDate);      
      $day = $interval->format('%r%a');
       
      if(intval($day) == "1"){
        $currStatus = $interval->format("expires in %a day");  
        return $currStatus;
      }
      else if(intval($day) > 1){
        $currStatus = $interval->format("expires in %a days");  
        return $currStatus;
      }
      else if (intval($day) < 1){
        $currStatus = $interval->format("expired");  
        return $currStatus;
      }
      else{
        // 
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
    <section class="main-dashboard bg-light-primary">

      <!-- Asset Inventory Function (Search, filter, add) -->

      <div  class="container">
          
          <div id="top-header" class="flex flex-just-sb ">
                <h2 class="txt-md"><i class="fa-regular fa-folder-open mr-1"></i>Software Asset</h2> 
                <h1 class="txt-md my-3">Hello, <?php echo $username?></h1>        
          </div>


          <form action="generate-sa-pdf.php" method="POST" class="functions-form-group flex flex-just-sb my-2">
            <div class="form-left">

            <div class="form-control">
                <input type="submit" name="create-pdf" id="create-pdf" value="PDF" class="btn btn-red-outline btn-sm">
            </div>       

              <div class="form-control">
                <input
                  type="text"
                  id="asset-filter-input"
                  placeholder="Search Product ID"
                />
              </div>

              <!-- Asset Inventory Records -->
              <div class="form-control">
                <select name="software-type" id="software-type-select">
                  <option value="all">All Software Type</option>                
                </select>
              </div>
            </div>

            <div class="form-right">
              <div class="form-control">
                <a href="add_software_asset.php" class="btn btn-outline btn-add">
                  Add New <i class="fas fa-add"></i>
                </a>
              </div>
            </div>
          </form>  
        </div>

        <div class="asset-records mx-3">
            <table>   
                <thead class="asset-property">
                  <th>Product Id</th>  
                  <th>Software</th>
                  <th>Type</th>
                  <th>Vendor</th>
                  <!-- <th>Date of Purchase</th> -->
                  <th>No. of Installation</th>
                  <th>Validity</th>
                  <th>Status</th>
                  <th>Actions</th>
                </thead>
                <tbody>

                    <?php foreach($assets as $asset): ?>
                    <tr class="record">
                      <td><?php echo $asset["product_id"]?></td>  
                      <td><?php echo $asset["software"]?></td>
                      <td><?php echo $asset["software_type"]?></td>
                      <td><?php echo $asset["manufacturer"]?></td>                        
                      <td><?php echo $asset["no_of_installation"]?></td>
                      <td><?php echo $asset["validity"]?></td>
                      <td><?php echo $asset["curr_status"]?></td>
                      <td class="data actions"><i class="fas fa-ellipsis ellipse action"></i>
                        <ul class="actions-list hide">                                                     
                          <li><a href="software_asset_info.php?id=<?php echo $asset["id"]; ?>">View</a></li>
                        </ul>      
                      </td>
                    </tr>

                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
      
          
    </section>

    <script src="js/software_assets_handler.js"></script>

</body>
</html>