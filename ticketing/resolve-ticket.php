<?php 
    // Authenticate if user is logged in
  include ("../auth_session.php");

    
    // Check if admin is logged in
  if($_SESSION["role"] != "admin"){
      header("Location: /Capstone_System/404.php");
      exit();
  }

  include ("../config/db_config.php");

  
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

        if($ticketStatus != "Pending"){            
            header("Location: tickets.php");   
            exit();
        }

      }
      else{
        header("Location: tickets.php");
      }

      mysqli_free_result($result);      
    }
    else{
      header("Location: dashboard.php");
      exit();
    }
    
    $successMsg = "";
    $actionsTakenFormErr = "";

    if(isset($_POST["update"])){
        if(empty($_POST["actions-taken-input"])){
            $actionsTakenFormErr = "This is a required field.";
        }

        if($actionsTakenFormErr == ""){            
            $id = htmlspecialchars($_POST["ticket_id"]);
            $actionsTaken = htmlspecialchars($_POST["actions-taken-input"]);

            $sql = "UPDATE tickets
            SET ticket_status='Resolved',
                actions_taken='$actionsTaken'                
            WHERE id=$id"; 
            
            if(mysqli_query($conn, $sql)){
                header("Location: view-tickets.php?id=".$id);
                exit();
            }
        }
    }


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
        <div class="container">
            <div class="mt-3 flex flex-just-sb">
                <a href="view-tickets.php?id=<?php echo htmlspecialchars($ticketId);?>" class="btn btn-outline-secondary"><i class="fas fa-chevron-left mr-1"></i>Back</a>                                
            </div>

            <div class="grid grid-2 grid-gap-3 px-1">
              <!-- Ticket Information -->
              <div>
                <div class="flex mt-2"><h2>Ticket Details</h2></div>
                  <div>
                    <div class="flex flex-column flex-align-left">
                        <h3 class="txt-fw-2 txt-light txt-sm">Ticked ID: #<?php echo htmlspecialchars($ticketId)  ?></h3>                        

                        <div>
                            <h3 class="txt-fw-2 txt-light txt-sm">Respondent Name: <span class="txt-fw-3 txt-dark"> <?php echo htmlspecialchars($submittedBy) ?> - <?php echo $department ?></span></h3>
                                  
                              <!-- <h3 class="txt-fw-2 txt-light txt-sm">Respondent Name</h3>
                              <div class="form-card-solid">
                                  <h3 class="txt-sm txt-fw-2"><?php echo $submittedBy ?></h3>                
                              </div> -->
                        </div> 
                                                  
                        <h3 class="txt-fw-2 txt-light txt-sm">Date Issued: <span class="txt-fw-3 txt-dark"> <?php echo getDateFormat($dateIssued) . getTimeFormat($dateIssued)?> </span></h3>

                        <div class="mt-2"></div>
                      
                        <h3 class="txt-fw-2 txt-light txt-sm">Incident Type: <span class="txt-fw-3 txt-dark"> <?php echo htmlspecialchars($incidentType) ?> </span></h3>
                        
                        <h3 class="txt-fw-2 txt-light txt-sm">Priority Level: <span class="txt-fw-3 txt-dark"> <?php echo htmlspecialchars($priorityLevel) ?> </span></h3>
                                            
                        <div class="grid grid-2 grid-gap-2">                        
                          <h3 class="txt-fw-2 txt-light txt-sm">Assigned To: <span class="txt-fw-3 txt-dark"> <?php echo htmlspecialchars ($assignedTo) ?> </span></h3>        
                          <h3 class="txt-fw-2 txt-light txt-sm">Status: <span class="txt-fw-3 txt-dark"> <?php echo htmlspecialchars($ticketStatus) ?> </span></h3>                                
                        </div>                        

                        <div class="flex flex-column flex-align-left">
                          <h3 class="txt-fw-2 txt-light txt-sm">Subject</h3>
                          <div class="form-card-solid ticket-desc-box w-full">
                              <h3 class="txt-sm txt-fw-2"><?php echo htmlspecialchars($ticketDescription) ?></h3>                
                          </div>
                        </div> 

                      </div>
                    </div>
              </div>
              
              <!-- Resolving of Ticket -->
              

              <div class="align-self-left mt-4 ml-2">
                <div class="mt-3"></div>
                  <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="resolve-ticket-form">
                    <div class="form-group">
                        <label for="actions-taken-input" class="txt-fw-2 txt-light txt-sm">Actions Taken</label>                
                        <textarea name="actions-taken-input" id="actions-taken" cols="70" rows="13" placeholder="Describe actions taken in resolving the incident..." class="p-2"></textarea>
                        <div id="actions-taken-error" class="error-msg"><?php echo $actionsTakenFormErr; ?></div>                                                                
                    </div>

                    <div class="flex">
                        <input type="hidden" id="ticket_id" name="ticket_id" value = <?php echo $ticketId;?>>
                      <input type="submit" name="update" value="Done" class="btn btn-primary">
                    </div>
                    
                  </form>

              </div>
              
            </div>
            

            <!-- Ticket Information -->
            
            
            <div class="grid grid-2 mt-1 px-3">
                
                </div>

                
                        
            </div> 
            <!-- End of ticket details -->

            <!-- <div class="flex flex-just-right mt-4">
                <a href="edit-ticket.php?id=<?php echo $ticketId ?>" class="btn btn-outline-secondary mr-2">Edit</a>
                <?php if($ticketStatus == "Pending"): ?>
                    <a href="resolve-ticket.php?id=<?php echo $ticketId ?>" class="btn btn-primary">Mark As Resolved</a>
                <?php endif;?>
            </div> -->

        </div>
    </section>

    <script src="js/ticket.js"></script>    
  </body>
</html>
