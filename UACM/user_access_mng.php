<?php 
  // Authenticate if user is logged in
  include ("../auth_session.php");

    
  if($_SESSION["role"] != "admin"){
      header("Location: /Capstone_System/404.php");
      exit();
  }

  require("../config/config.php");
  require("../config/db_config.php");
  
    $sql = "SELECT * FROM user_account_table";

    $result = mysqli_query($conn, $sql);

    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);

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
  
  <?php include("../storeshare-admin/storeshare-admin-sidebar.php") ?>


    <!-- Main Dashboard -->
    <section class="main-dashboard bg-light-primary">    

      <section class="container">

        <!-- Asset Inventory Function (Search, filter, add) -->
        <!-- <div id="asset-heading" class="flex py-1">
          <h2 class="txt-md">User Access Control Management</h2>
        </div> -->

        <div id="top-header" class="flex flex-just-sb ">
                <h2 class="txt-md"><i class="fa-regular fa-folder-open mr-1"></i>User Access Control Management</h2> 
                <h1 class="txt-md my-3">Hello, <?php echo $username?></h1>        
            </div>
        
        <form action="" class="functions-form-group my-2">
          <div class="form-left">
            <div class="form-control">
              <input
                type="text" class="search-box"
                id="user-search-input"
                placeholder="Search Username"
              />
            </div>

            <!-- Asset Inventory Records -->
            <div class="form-control">
              <label for="department">Department: </label>
              <select name="department" id="department">
                <option value="all">All</option>              
              </select>
            </div>            

          </div>

          <div class="form-right">
            <div class="form-control">
              <a href="<?php echo USER_ACCESS_MNG_URL;?>add_user_acc.php" class="btn btn-outline btn-add">
                Add User <i class="fas fa-add"></i>
              </a>
            </div>
          </div>
        </form>

        <!-- Asset Table -->
        <div class="asset-records" id="user-records">
          <table>
            <thead class="sticky">          
                  <th>User Name</th>
                  <th>Email</th>
                  <th>Role</th>                                    
                  <th>Department</th>
                  <th>Actions</th>
            </thead>            

            <tbody >
                <?php foreach($users as $user): ?>
                
                <tr class="record">
                    <td class="data"><?php echo $user["username"]?></td>
                    <td class="data"><?php echo $user["email"]?></td>
                    <td class="data"><?php echo $user["user_role"]?></td>                                        
                    <td class="data"><?php echo $user["department"]?></td>                                        
                    <td class="data actions">
                      <ul class="flex">                          
                          <li class="mr-1"><a class="link" href="<?php echo USER_ACCESS_MNG_URL;?>edit_user_access.php?id=<?php echo $user["user_id"];?>">Edit Access</a></li>
                          <li><a class="link" href="edit_user_acc.php?id=<?php echo $user["user_id"];?>">Edit Account</a></li>
                      </ul>      
                    </td>
                                  
                </tr>

                
                <?php endforeach; ?>
                
              </tbody>
          </table>
        </div>
      </section>
                

    </section>

    <script src="js/uam.js"></script>
    <script src="../storeshare/storeshare.js"></script>
    <!-- <script src="../IT_Asset_Inventory/js/side_nav_handler.js"></script> -->
  </body>
</html>
