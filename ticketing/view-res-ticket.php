<?php 
    // Authenticate if user is logged in
  include ("../auth_session.php");

    
    // Check if admin is logged in
  if($_SESSION["role"] != "admin"){
      header("Location: /Capstone_System/404.php");
      exit();
  }

  include ("../config/db_config.php");

  $incidentType = $priorityLevel = $assignedTo = "";  

  // Ticket Get Request
  if(isset($_GET["id"])){
      $ticketId = mysqli_real_escape_string($conn, $_GET["id"]);
  
      // sql statement to select a particular record based on id selected
      $sql = "SELECT * FROM tickets WHERE id = $ticketId";

      //execute the query
      $result = mysqli_query($conn, $sql);

      // Fetch result query and convert it into a associative array
      $ticket = mysqli_fetch_assoc($result);

      if($ticket){      
        $ticketId = $ticket["id"];
        $submittedBy = $ticket["submitted_by"];
        // $userEmail = $ticket["email"];        
        $incidentType = $ticket["incident_type"];
        $dateIssued = $ticket["incident_date"];
        $ticketDescription = $ticket["incident_description"];
        $priorityLevel = $ticket["priority_level"];
        $locatedAt = $ticket["located_at"];
        $assignedTo = $ticket["assigned_to"];                
        $department = $ticket["department"];
        $ticketStatus = $ticket["ticket_status"];
        $actionsTaken = $ticket["actions_taken"];
      }
      else{
        header("Location: knowledge-base.php");
      }

      mysqli_free_result($result);      
    }
    else{
      header("Location: knowledge-base.php");
      exit();
    }   

    $successMsg = "";
    $error = array(
      "incidentTypeError" => "",
      "priorityLevelError" => "",
      "assignedToError" => ""      
    );    


  function getDateFormat($input){      
    $date = strtotime($input); 
    $date = date('M-d-Y', $date);    
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

            <div class="container">
                <div class="flex flex-just-sb mt-3">
                <a href="knowledge-base.php" class="btn btn-outline-secondary"><i class="fas fa-chevron-left mr-1"></i>Back</a>
                </div>
                <div class="table-container">
                    <div class="flex flex-just-sb px-2 my-1">
                        <h2 class="my-2">Case Information</h2>              
                    </div>
   
                    <div class="card p-2">
                        <div class="flex flex-just-sb">
                            <div><h3 class="txt-fw-3 txt-light txt-18 txt-secondary">Case: <span class="txt-fw-4 txt-dark txt-18"><?php echo htmlspecialchars($ticket["incident_description"])?></span></h3></div>
                            <div class="txt-light txt-sm txt-14"><?php echo htmlspecialchars(getDateFormat($ticket["incident_date"]))?></div>
                        </div>
                        
                        <div class="">
                            <h4 class="txt-fw-2 txt-light txt-14">Category: <span class="txt-fw-3 txt-dark txt-14"><?php echo htmlspecialchars($ticket["incident_type"]) ?></span> | Reported by: <span><?php echo htmlspecialchars($ticket["submitted_by"]) ?></span></h4>
                        </div>

                        <div class="mt-3">                        
                            <p class="txt-fw-2 txt-light txt-16 w-90">Actions Taken: <span class="txt-dark txt-fw-2 txt-16"><?php echo htmlspecialchars($ticket["actions_taken"], 120)?></span></p>
                        </div>

                        <div class="flex flex-just-sb mt-2">
                            <p class="txt-fw-2 txt-light txt-sm txt-14">Resolved by: <span class="txt-fw-3 txt-dark txt-14"><?php echo htmlspecialchars($ticket["assigned_to"]) ?></span></p>                          
                        </div>

                    </div>                                            
            </div>            
                            
              </section>

    <script src="js/ticket.js"></script>    
  </body>
</html>
