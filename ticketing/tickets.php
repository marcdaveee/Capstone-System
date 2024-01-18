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
    $date = $date . " | ";
    return $date;
  }

  function getTimeFormat($input){
    $date = strtotime($input); 
    return date('h:i a', $date);  
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

  <!-- Side Nav Menu -->
  
  <?php include("./sidebar.php") ?>
        <!-- Tickets table -->

    <section class="main-dashboard bg-light-primary">
                  
        <div class="table-container">
            <div class="flex flex-just-sb px-2">
              <h2 class="my-2">Tickets</h2>
              <a href="create-ticket.php" class="btn btn-outline-secondary">Create Ticket</a>
            </div>

            <div class="flex flex-just-left mb-2">

              <div class="form-control">
                <label for="incident-type" class="mr-1">Incident Type: </label>
                  <select name="incident-type" id="incident-type">
                    <option value="all">All</option>                
                  </select>
              </div>

              <div class="form-control">
                <label for="priority-level" class="mr-1">Priority Level: </label>
                  <select name="priority-level" id="priority-level">
                    <option value="all">All</option>                
                    <option value="Low">Low</option>    
                    <option value="Medium">Medium</option>    
                    <option value="High">High</option>    
                  </select>
              </div>

              <div class="form-control">
                <label for="status-type" class="mr-1">Status: </label>
                  <select name="status-type" id="status-type">
                    <option value="all">All</option>                
                    <option value="Pending">Pending</option> 
                    <option value="Resolved">Resolved</option> 
                    
                  </select>
              </div>
              
            </div>
              
            <ul class="tickets-table">
            <li class="table-li ticket-table-header sticky">
                <div class="col col-3">Ticket Id</div>
                <div class="col col-5">User</div>                              
                <div class="col col-3">Type</div>
                <div class="col col-4">Issued</div>
                <div class="col col-3">Priority</div>              
                <div class="col col-6">Subject</div>
                <div class="col col-4">Assigned To</div>
                <div class="col col-3">Status</div>
                <div class="col col-2"></div>
            </li>

            <?php foreach($tickets as $ticket): ?>
                <li class="table-li ticket-row flex my-2">                
                    <div class="col col-3">
                      #<?php echo $ticket["id"]?>
                    </div>

                    <div class="col col-5">
                        <div>
                            <?php echo $ticket["submitted_by"]?>
                        </div>

                        <!-- <div class="my-1">
                            <?php echo $ticket["email"]?>
                        </div> -->

                        <div>
                            <?php echo $ticket["department"]?>
                        </div>
                        
                    </div>
                    
                    <div class="col col-3 txt-fw-2">
                      <?php echo $ticket["incident_type"]?>
                    </div>       

                    <div class="col col-4">
                        <div>
                            <?php echo getDateFormat($ticket["incident_date"]);?>
                        </div> 
                        <div>
                            <?php echo getTimeFormat($ticket["incident_date"])?>
                        </div>
                    </div>

                    <div class="col col-3"><?php echo $ticket["priority_level"]?></div>
                                
                    <div class="col col-6 txt-fw-2"><?php echo cutDescription($ticket["incident_description"])?></div>

                    <div class="col col-4"><?php echo $ticket["assigned_to"]?> </div>


                    <div class="col col-3"><?php echo $ticket["ticket_status"]?></div>


                    <div class="col col-2 data actions">                        
                          <a href="view-tickets.php?id=<?php echo $ticket["id"]?>" class="btn btn-secondary">View</a>                        
                      </div>
                                                
                </li>           

            <?php endforeach; ?>
        </div>
    </section>
    
    <script src="js/ticket-filter.js"></script>        
  </body>
</html>
