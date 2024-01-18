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
  
  // Get folders available from current folder
    $sql = "SELECT * FROM folder_table ORDER BY folder_path_name";  
    $result = mysqli_query($conn, $sql);
       
    $folders = mysqli_fetch_all($result, MYSQLI_ASSOC);        
    mysqli_free_result($result);    
    mysqli_close($conn);                              

    function getDateFormat($input){      
      $date = strtotime($input); 
      $date = date('M-d-Y', $date);
      $date = $date . " | ";
      return $date;
    }

    function getTimeFormat($input){
      $date = strtotime($input); 
      return date('h:i a', $date);  
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
  <?php include("storeshare-admin-sidebar.php") ?>

  <section class="main-dashboard bg-light-primary">
        <div class="container">
            <div id="top-header" class="flex flex-just-sb ">
                <h2 class="txt-md"><i class="fa-regular fa-folder-open mr-1"></i>Folder Logs</h2> 
                <h1 class="txt-md my-3">Hello, <?php echo $username?></h1>        
            </div>

            <div class="my-2">
              <form action="" class="functions-form-group">
                <div class="form-left">
                  <div class="form-control">
                    <input
                      type="text" class="search-box txt-xs"
                      id="folder-search-input"
                      placeholder="Search folder name"
                    />
                  </div>  
                                    
                </div>              
              </form>
            </div>

            <!-- Asset Table -->
        <div class="asset-records" id="folder-records">
          <table>
            <thead class="sticky">          
                  <th>Folder Name</th>
                  <th class="td-align-left">Path</th>
                  <th class="td-align-left">Requested by</th>
                  <th>Date Created</th>                                                      
            </thead>            

            <tbody >
                <?php foreach($folders as $folder): ?>
                
                <tr class="record">
                    <td class="data"><?php echo $folder["folder_name"]?></td>
                    <td class="data td-align-left"><?php echo $folder["folder_path_name"]?></td>
                    <td class="data td-align-left"><?php echo $folder["requested_by"]?></td>
                    <td class="data"><?php echo getDateFormat($folder["created_at"]);?> <?php echo getTimeFormat($folder["created_at"])?></td> 
                </tr>

                
                <?php endforeach; ?>
                
              </tbody>
          </table>
        </div>
                  
        </div>
    </section>
    
    <script src="../storeshare/storeshare.js"></script>
  </body>
</html>
