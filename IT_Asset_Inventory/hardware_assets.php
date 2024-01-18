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
  
    $sql = "SELECT * FROM hardware_asset ORDER BY curr_location";

    $result = mysqli_query($conn, $sql);

    $assets = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_free_result($result);

    mysqli_close($conn);      
                        
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

        <div class="container">
          <div id="top-header" class="flex flex-just-sb ">
                  <h2 class="txt-md"><i class="fa-regular fa-folder-open mr-1"></i>Hardware Asset</h2> 
                  <h1 class="txt-md my-3">Hello, <?php echo $username?></h1>        
            </div>
        
          

        <!-- Asset Inventory Function (Search, filter, add) -->        
        <form action="generate-ha-pdf.php" method="POST" class="functions-form-group flex flex-just-sb my-2">
          <div class="form-left flex flex-just-left flex-align-right">

            <div class="form-control">
              <input type="submit" name="create-pdf" id="create-pdf" value="PDF" class="btn btn-red-outline btn-sm">
            </div>       
            
            <div class="form-control">
              <input
                type="text"
                id="asset-filter-input"
                placeholder="Search Serial No."
              />
            </div>

            <div class="form-control">
              <label for="item-type">Item Type: </label>
              <select name="item-type" id="item-type-select">
                <option value="all">All</option>                
              </select>
            </div>

            <!-- Asset Inventory Records -->
            <div class="form-control">
              <label for="department">Department: </label>
              <select name="department" id="department-select">
                <option value="all">All Dept</option>

                <!-- <option value="Human Resource Dept.">HR Dept</option>
                <option value="Agriculture Dept.">Agri Dept</option>
                <option value="Treasurer Office">Treasurer office</option>
                <option value="Assessor Office">Assessor office</option>
                <option value="Accounting">Accounting office</option> -->
              </select>
            </div>            

            <div class="form-control">
              <label for="status">Status: </label>
              <select name="status" id="status-select">
                <option value="all"></option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>                
              </select>
            </div>    
            
                     
            
          </div> 

          <div class="form-control">
              <a href="add_hardware_asset.php" class="btn btn-outline btn-add td-align-center">
                 <i class="fas fa-add"></i>
              </a>
            </div>

       
        </form>

        </div>

        <!-- Asset Table -->
        <div class="asset-records mx-3">
          <table>
            <thead class="asset-property">          
                  <th>Serial No.</th>
                  <th>Item Name</th>
                  <th>Item Type</th>
                  <th>Item Brand</th>
                  <th>User</th>
                  <th>Location</th>
                  <th>Status</th>
                  <th>Actions</th>
            </thead>            

            <tbody >
                <?php foreach($assets as $asset): ?>
                
                <tr class="record">
                    <td class="data"><?php echo $asset["serial_no"]?></td>
                    <td class="data"><?php echo $asset["item_name"]?></td>
                    <td class="data item-type"><?php echo $asset["item_type"]?></td>
                    <td class="data"><?php echo $asset["item_brand"]?></td>
                    <td class="data"><?php echo $asset["user"]?></td>
                    <td class="data location"><?php echo $asset["curr_location"]?></td>
                    <td class="data <?php if($asset["curr_status"] == "Active") echo "active-state"?> <?php if($asset["curr_status"] == "Inactive") echo "inactive-state"?>"><p><?php echo $asset["curr_status"]?></p></td>
                    <td class="data actions"><i class="fas fa-ellipsis ellipse action"></i>
                      <ul class="actions-list hide">
                          <?php if($asset["curr_status"] == "Inactive"): ?>
                            <li><a href="<?php echo ROOT_URL;?>allocate_hardware_asset.php?id=<?php echo $asset["id"];?>">Allocate</a></li>
                            <?php endif; ?>
                            <!-- <li><a href="">Allocate </a></li> -->
                            <li><a href="<?php echo ROOT_URL;?>hardware_asset.php?id=<?php echo $asset["id"];?>">View</a></li>
                      </ul>      
                    </td>
                                  
                </tr>

                
                <?php endforeach; ?>
                
              </tbody>
          </table>
        </div>
      </section>
                

    </section>

    <script src="js/main.js"></script>
    <script src="js/side_nav_handler.js"></script>
  </body>
</html>
