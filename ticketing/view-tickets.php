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
        $actionsTaken = $ticket["actions_taken"];
        $ticketStatus = $ticket["ticket_status"];
      }
      else{
        header("Location: tickets.php");
      }

      mysqli_free_result($result);
      mysqli_close($conn);
    }
    else{
      header("Location: dashboard.php");
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
            <div class="mt-3">
                <a href="tickets.php" class="btn btn-outline-secondary"><i class="fas fa-chevron-left mr-1"></i>Back</a>
            </div>

            <!-- Ticket Information -->
            <div class="flex mt-2"><h2>Ticket Details</h2></div>
            <div class="grid grid-2 mt-1 px-3">
                <div>
                    <div class="flex flex-column flex-align-left">
                        <h3 class="txt-fw-2 txt-light txt-sm">Ticked ID: #<?php echo $ticketId ?></h3>                        

                        <div>
                            <h3 class="txt-fw-2 txt-light txt-sm">Respondent Name: <span class="txt-fw-3 txt-dark"> <?php echo $submittedBy ?> - <?php echo $department ?></span></h3>
                                
                                <!-- <h3 class="txt-fw-2 txt-light txt-sm">Respondent Name</h3>
                                <div class="form-card-solid">
                                    <h3 class="txt-sm txt-fw-2"><?php echo $submittedBy ?></h3>                
                                </div> -->
                        </div> 
                                                
                        <h3 class="txt-fw-2 txt-light txt-sm">Date Created: <span class="txt-fw-3 txt-dark"> <?php echo getDateFormat($dateIssued) . getTimeFormat($dateIssued)?> </span></h3>

                        <div class="mt-3"></div>
                      
                        <h3 class="txt-fw-2 txt-light txt-sm">Incident Type: <span class="txt-fw-3 txt-dark"> <?php echo $incidentType ?> </span></h3>
                        
                        <h3 class="txt-fw-2 txt-light txt-sm">Priority Level: <span class="txt-fw-3 txt-dark" id="priority-level"> <?php echo $priorityLevel ?> </span></h3>
                                                                   
                        <h3 class="txt-fw-2 txt-light txt-sm">Assigned To: <span class="txt-fw-3 txt-dark"> <?php echo $assignedTo ?> </span></h3>        
                                                  
                        <h3 class="txt-fw-2 txt-light txt-sm">Subject</h3>
                          <div class="form-card-solid ticket-desc-box h-200 w-full">
                            <h3 class="txt-sm txt-fw-2"><?php echo $ticketDescription ?></h3>                
                          </div>                                                
                    </div>
                </div>

                <div class="align-self-left ml-4">
                    <h3 class="txt-fw-2 txt-light txt-sm">Status: <span class="txt-fw-3 txt-dark" id="status"> <?php echo $ticketStatus ?> </span></h3>                                
                    <h3 class="txt-fw-2 txt-light txt-sm mt-2">Actions Taken</h3>
                    <div class="form-card-solid ticket-desc-box h-180">
                        <?php if($actionsTaken !=""): ?>
                          <h3 class="txt-sm txt-fw-2"><?php echo $actionsTaken ?></h3>                
                        <?php else: ?>
                          <h3 class="txt-sm txt-fw-2 txt-light">Not yet resolved...</h3>                
                          <?php endif; ?>
                    </div>

                    
                    <div class="flex flex-just-right mt-4">
                        <a href="edit-ticket.php?id=<?php echo $ticketId ?>" class="btn btn-outline-secondary mr-2">Edit</a>
                        <?php if($ticketStatus == "Pending"): ?>
                            <a href="resolve-ticket.php?id=<?php echo $ticketId?>" class="btn btn-primary">Mark As Resolved</a>
                        <?php endif;?>
                    </div>
                </div>   
                        
            </div> 
            <!-- End of ticket details -->            

        </div>
    </section>

    <script>
      const status = document.querySelector("#status");

      if(status.innerHTML.includes("Pending")){        
        status.classList.add("inactive-state");
      }
      else{
        status.classList.add("resolve-state");
      }
      

      const priorityLevel = document.querySelector("#priority-level");

      if(priorityLevel.innerHTML.includes("Low")){        
        priorityLevel.classList.add("low-state");
      }
      else if(priorityLevel.innerHTML.includes("Medium")){
        priorityLevel.classList.add("inactive-state");
      }
      else{
        priorityLevel.classList.add("critical-state");
      }

    </script>

    <!-- <script src="js/ticket.js"></script>     -->
  </body>
</html>
