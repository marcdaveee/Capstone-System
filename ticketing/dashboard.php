<?php 
    // Authenticate if user is logged in
  include ("../auth_session.php");

    
    // Check if admin is logged in
  if($_SESSION["role"] != "admin"){
      header("Location: /Capstone_System/404.php");
      exit();
  }

  include ("../config/db_config.php");

  // Get tickets from tickets table
  $sql = "SELECT * FROM tickets ORDER BY id DESC";
  $result = mysqli_query($conn, $sql);
  
  $tickets = mysqli_fetch_all($result, MYSQLI_ASSOC);  
  mysqli_free_result($result);


  function getDateFormat($input){      
    $date = strtotime($input); 
    $date = date('M-d-Y', $date);    
    return $date;
  }

  function getTimeFormat($input){
    $date = strtotime($input); 
    return date('h:i a', $date);  
  }

  function getPendingCount(){
    include ("../config/db_config.php");
    $sql = "SELECT COUNT(id) as total FROM tickets WHERE ticket_status = 'Pending'";
    $result = mysqli_query($conn, $sql);  
    $pendingCount = mysqli_fetch_assoc($result);
    return $pendingCount["total"];    
  }

  function getResolvedCount(){
    include ("../config/db_config.php");
    $sql = "SELECT COUNT(id) as total FROM tickets WHERE ticket_status = 'Resolved'";
    $result = mysqli_query($conn, $sql);  
    $resolveCount = mysqli_fetch_assoc($result);
    return $resolveCount["total"];    
  }

  function cutDescription($incidentDescription){
    if(strlen($incidentDescription) > 45){
      $shortenedStr = "";

      for($i = 0; $i <= strlen($incidentDescription); $i++){
        if($i + 1 == 45){
          $shortenedStr = $shortenedStr . "...";
          return $shortenedStr;  
        }

        $shortenedStr = $shortenedStr . $incidentDescription[$i];        
      }     
    }
    else{
      return $incidentDescription;
    }

  }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LGU SAQ Tickets</title>
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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js" integrity="sha512-Z8CqofpIcnJN80feS2uccz+pXWgZzeKxDsDNMD/dJ6997/LSRY+W4NmEt9acwR+Gt9OHN0kkI1CTianCwoqcjQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.debug.js" integrity="sha512-234m/ySxaBP6BRdJ4g7jYG7uI9y2E74dvMua1JzkqM3LyWP43tosIqET873f3m6OQ/0N6TKyqXG4fLeHN9vKkg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <!-- Side Nav Menu -->
  
  <?php include("./sidebar.php") ?>


    <!-- Ticketing System Dashboard -->
    <section class="main-dashboard bg-light-primary">    

      <section class="container">             
              <!-- Header -->
        <div id="top-header" class="flex flex-just-sb">
                <h2 class="txt-md"><i class="fa-regular fa-folder-open mr-1"></i>Dashboard</h2> 
                <h1 class="txt-md my-2">Admin</h1>        
        </div>
          
        <!-- Dashboard-information -->
        <div class="dashboard-layout place-items p-3">
          <div class="dashboard-cards grid grid-2 grid-gap-3">
            <div class="card info-box border-radius-1 flex flex-column">
              <h3 class="txt-lg txt-fw-4 txt-warn"><?php echo getPendingCount() ?></h3>
              <p class="txt-light">Pending</p>
            </div>

            <div class="card info-box border-radius-1 flex flex-column">
              <h3 class="txt-lg txt-fw-4 txt-success"><?php echo getResolvedCount() ?></h3>
              <p class="txt-light">Resolved</p>
            </div>              

          </div>

          <div class="flex flex-align-left flex-just-sb w-90 px-3">
            <canvas id="myChart" class="">
            
            </canvas>

            <button onclick="generateReport()" class="btn btn-sm btn-secondary">Generate PDF</button>
          </div>
          

        </div>


       
        <!-- Tickets table -->

        
         
        

        <!-- <table class="tickets-table">
            <thead >
                <th class="p-1 ">Ticket ID</th>
                <th class="p-1 ">Contact</th>
                <th class="p-1 ">Subject</th>
                <th class="p-1 ">Issued</th>
                <th class="p-1 ">Priority</th>
                <th class="p-1 ">Type</th>
                <th class="p-1 ">Email</th>
                <th class="p-1 ">Status</th>
                <th></th>
            </thead>

            <tbody>
                
                    <tr class="record">
                      <td>1</td>   
                      <td>John Doe</td>   
                      <td>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Est, dolores!</td>   
                      <td>15 Dec 2023</td>
                      <td>High</td>
                      <td>Hardware</td>
                      <td>Johndoe@gmail.com</td>
                      <td>Pending</td>
                    </tr>
                                                        
                
            </tbody>
        </table> -->
     
      </section>
      
      <div class="container">
        <h2 class="my-2">Tickets</h2>
      </div>
      
      <div class="table-container">
      
          <ul class="tickets-table h-220 overflow-y-true">
            <li class="table-li table-header sticky">
              <div class="col col-3">Ticket Id</div>
              <div class="col col-3">User</div>              
              
              <div class="col col-4">Type</div>
              <div class="col col-4">Issued</div>
              <div class="col col-3">Priority</div>              
              <div class="col col-6">Subject</div>
              
              <div class="col col-3">Status</div>
              <div class="col col-2"></div>
            </li>

            <?php foreach($tickets as $ticket): ?>
              <li class="table-li table-row flex">
                <!-- <div class="col col-3"><?php echo $ticket["id"]?></div> -->
                <div class="col col-3">#<?php echo $ticket["id"]?></div>
                <div class="col col-3">
                  <?php echo $ticket["submitted_by"]?>
                </div>              
                <div class="col col-4"><?php echo $ticket["incident_type"]?></div>       
                <div class="col col-4">
                  <div>
                    <?php echo getDateFormat($ticket["incident_date"]);?>
                  </div>                   
              </div>
                <div class="col col-3"><?php echo $ticket["priority_level"]?></div>
                         
                <div class="col col-6"><?php echo cutDescription($ticket["incident_description"])?></div>
                <div class="col col-3"><?php echo $ticket["ticket_status"]?></div>
                <div class="col col-2 data actions">                        
                  <a href="view-tickets.php?id=<?php echo $ticket["id"]?>" class="btn btn-secondary">View</a>                                                             
                </div>                         
              </li>           

            <?php endforeach; ?>


            <!-- <li class="table-li table-row">
              <div class="col col-3">42235</div>
              <div class="col col-3">John Doe</div>
              <div class="col col-6">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Est, dolores!</div>
              <div class="col col-3">15 Dec 2023</div>
              <div class="col col-3">High</div>
              <div class="col col-3">Hardware</div>
              
              <div class="col col-4">Human Resource</div>
              <div class="col col-2">Pending</div>
              <div class="col col-1"></div>              
            </li>           

            <li class="table-li table-row">
              <div class="col col-3">42235</div>
              <div class="col col-3">John Doe</div>
              <div class="col col-6">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Est, dolores!</div>
              <div class="col col-3">15 Dec 2023</div>
              <div class="col col-3">High</div>
              <div class="col col-3">Hardware</div>
              
              <div class="col col-4">Accounting</div>
              <div class="col col-2">Pending</div>
              <div class="col col-1"></div>              
            </li>   
            
            <li class="table-li table-row">
              <div class="col col-3">42235</div>
              <div class="col col-3">John Doe</div>
              <div class="col col-6">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Est, dolores!</div>
              <div class="col col-3">15 Dec 2023</div>
              <div class="col col-3">High</div>
              <div class="col col-3">Hardware</div>
              
              <div class="col col-4">Engineering</div>
              <div class="col col-2">Pending</div>
              <div class="col col-1"></div>              
            </li>   
          </ul>
      </div> -->
       

    </section>

    <!-- <script src="js/uam.js"></script> -->
    <!-- <script src="../storeshare/storeshare.js"></script> -->
    <script src="js/incident-info.js"></script>
    <!-- <script src="../IT_Asset_Inventory/js/side_nav_handler.js"></script> -->
  </body>
</html>
