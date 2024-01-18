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
  $sql = "SELECT * FROM tickets WHERE ticket_status = 'Resolved' ORDER BY incident_date AND incident_type DESC";
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

  function cutDescription($incidentDescription, $length){
    if(strlen($incidentDescription) > $length){
      $shortenedStr = "";

      for($i = 0; $i <= strlen($incidentDescription); $i++){
        if($i + 1 == $length){
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
                  
        <div class="table-container h-full overflow-y-true px-3">
            <div class="flex flex-just-sb px-2 my-1">
              <h2 class="my-2">Knowledge Base</h2>   
              <div class="form-control">
                <label for="incident-type" class="mr-1">Incident Type: </label>
                  <select name="incident-type" id="incident-type">
                    <option value="all">All</option>                
                  </select>
              </div>           
            </div>
        

            <div class="grid grid-2 grid-gap-3">
                <?php foreach($tickets as $ticket): ?>
                    <div class="card p-2">
                        <div class="flex flex-just-sb">
                            <div><h3 class="txt-fw-3 txt-light txt-18 txt-secondary">Case: <span class="txt-fw-4 txt-dark txt-18"><?php echo htmlspecialchars(cutDescription($ticket["incident_description"], 40))?></span></h3></div>
                            <div class="txt-light txt-sm txt-14"><?php echo htmlspecialchars(getDateFormat($ticket["incident_date"]))?></div>
                        </div>
                        
                        <div class="">
                            <h4 class="txt-fw-2 txt-light txt-14">Category: <span class="txt-fw-3 txt-dark txt-14"><?php echo htmlspecialchars($ticket["incident_type"]) ?></span> | Reported by: <span><?php echo htmlspecialchars($ticket["submitted_by"]) ?></span></h4>
                        </div>

                        <div class="mt-3">                        
                            <p class="txt-fw-2 txt-light txt-16 w-90">Actions Taken: <span class="txt-dark txt-fw-2 txt-16"><?php echo htmlspecialchars(cutDescription($ticket["actions_taken"], 120) ) ?></span></p>
                        </div>

                        <div class="flex flex-just-sb mt-2">
                            <p class="txt-fw-2 txt-light txt-sm txt-14">Resolved by: <span class="txt-fw-3 txt-dark txt-14"><?php echo htmlspecialchars($ticket["assigned_to"]) ?></span></p>
                            <a href="view-res-ticket.php?id=<?php echo htmlspecialchars($ticket["id"]) ?>" class="btn btn-secondary">View</a>
                        </div>

                    </div>
                <?php endforeach; ?>
                
            </div>
                  
    </section>

    <script src="js/ticket.js"></script>    
    <script src="js/knowledge-base.js"></script>
  </body>
</html>
