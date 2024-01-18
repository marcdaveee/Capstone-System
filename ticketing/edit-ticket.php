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
        header("Location: tickets.php");
      }

      mysqli_free_result($result);      
    }
    else{
      header("Location: dashboard.php");
      exit();
    }   

    $successMsg = "";
    $error = array(
      "incidentTypeError" => "",
      "priorityLevelError" => "",
      "assignedToError" => ""      
    );

    if(isset($_POST["update"])){
      if(empty($_POST["incident-type"]) || $_POST["incident-type"] == "Define New"){
          $incidentType = htmlspecialchars($_POST["incident-type"]);
          $error["incidentTypeError"] = "This is a required field.";
      }

      if(empty($_POST["priority-level"])){
        $priorityLevel = htmlspecialchars($_POST["priority-level"]);
        $error["priorityLevelError"] = "This is a required field.";
      }

      if(empty($_POST["assigned-to"])){
        $assignedTo = htmlspecialchars($_POST["assigned-to"]);
        $error["assignedToError"] = "This is a required field.";
      }
      
      if($error["incidentTypeError"] == "" && $error["priorityLevelError"] == "" &&  $error["assignedToError"] == ""){            
          $id = htmlspecialchars($_POST["ticket_id"]);          
          $incidentType = htmlspecialchars($_POST["incident-type"]);
          $priorityLevel = htmlspecialchars($_POST["priority-level"]);
          $assignedTo = htmlspecialchars($_POST["assigned-to"]);

          $sql = "UPDATE tickets
          SET incident_type='$incidentType', priority_level='$priorityLevel',
              assigned_to='$assignedTo'                
          WHERE id=$id"; 
          
          if(mysqli_query($conn, $sql)){              
            $successMsg = "Ticket Updated";
            // header("Location: view-tickets.php?id=".$id);
            //   exit();
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
                <div class="<?php if($successMsg != "") echo "success-msg" ?>">
                  <div class="flex">
                    <i class="<?php if($successMsg != "") echo "fas fa-check"; ?>"></i><h4><?php echo $successMsg ?></h4>
                  </div>
                </div>
          </div>

          <div class="flex mt-2"><h2>Edit Ticket Details</h2></div>

          <div class="grid grid-2 mt-1 px-3">
              <!-- Ticket Information -->
              <div>
                
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

                        <div class="mt-3"></div>
                        
                        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="edit-ticket-form">
                          <div class="flex flex-just-left">
                            
                            <label for="incident-type" class="txt-fw-2 txt-light txt-sm mr-1">Incident Type: </label>                          
                            
                            <input type="hidden" name="selected-incident-type" value="<?php echo $incidentType?>" id="selected-incident-type">

                            <select name="incident-type" id="incident-type" class="form-card-solid ">
                                <option <?php if(isset($incidentType) && $incidentType == "") echo "selected"; ?> value=""></option>                                    
                                <option class="last" value="Define New">Define New</option>
                            </select>                                 
                            <div id="incident-type-error" class="error-msg"><?php echo $error["incidentTypeError"]; ?></div>  
                                                        
                          </div>
                          
                                              
                        <div class="flex flex-just-left mt-2">
                            
                            <label for="priority-level" class="txt-fw-2 txt-light txt-sm mr-1">Priority Level: </label>                          
                            
                            <input type="hidden" name="selected-priority-level" value="<?php echo $priorityLevel?>" id="selected-priority-level">

                            <select name="priority-level" id="priority-level" class="form-card-solid ">
                                <option <?php if(isset($priorityLevel) && $priorityLevel == "Low") echo "selected"; ?> value="Low">Low</option>
                                <option <?php if(isset($priorityLevel) && $priorityLevel == "Medium") echo "selected"; ?> value="Medium">Medium</option>
                                <option <?php if(isset($priorityLevel) && $priorityLevel == "High") echo "selected"; ?> value="High">High</option>                                                      
                            </select>                                 
                            <div id="priority-level-error" class="error-msg"><?php echo $error["priorityLevelError"]; ?></div>  
                                                        
                          </div>
                          
                          <div class="flex flex-just-left mt-2">                                                                                    
                              <label for="assigned-to" class="txt-fw-2 txt-light txt-sm mr-1">Assigned To: </label>
                              <input type="text" name="assigned-to" class="form-card-solid w-60" value="<?php echo $assignedTo ?>">                                                                                  
                          </div>
                          <div class="flex">
                            <div id="assigned-to-error" class="error-msg"><?php echo $error["assignedToError"]; ?></div>  
                          </div>
                          
                        
                        <!-- <div class="flex flex-column flex-align-left"> -->
                          <h3 class="txt-fw-2 txt-light txt-sm">Subject</h3>
                          <div class="form-card-solid ticket-desc-box h-220 w-full flex flex-just-left flex-align-left">
                              <h3 class="txt-sm txt-fw-2"><?php echo htmlspecialchars($ticketDescription) ?></h3>                
                          </div>
                        <!-- </div>  -->

                      </div>
                    </div>
              </div>
              
              <!-- Resolving of Ticket -->
              

              <div class="align-self-left ml-4">
                <h3 class="txt-fw-2 txt-light txt-sm">Status: <span class="txt-fw-3 txt-dark"> <?php echo htmlspecialchars($ticketStatus) ?> </span></h3>                                                                  
                  <h3 class="txt-fw-2 txt-light txt-sm mt-2">Actions Taken</h3>
                  <div class="form-card-solid ticket-desc-box h-180">
                    <?php if($actionsTaken !=""): ?>
                      <h3 class="txt-sm txt-fw-2"><?php echo $actionsTaken ?></h3>                
                    <?php else: ?>
                      <h3 class="txt-sm txt-fw-2 txt-light">Not yet resolved...</h3>                
                      <?php endif; ?>
                  </div>

                  <div class="flex flex-just-right mt-4">
                    <input type="hidden" id="ticket_id" name="ticket_id" value = <?php echo $ticketId;?>>
                    <input type="submit" name="update" value="Save" class="btn btn-primary">
                  </div>
                    
                </form>

                </div>
              
            </div>
            
            <!-- Ticket Information -->
            
            
            

                
                        
          </div> 
            <!-- End of ticket details -->          
      </div>      

    </section>

    <section class="modal-box hide">
          <div class="add-category card">
            <div class="close"><i class="fas fa-times"></i></div>
            <form action="" method="POST" id="add-incident-type" >
              <div class="form-group">
                <label for="new-incident-type">New Incident Type</label>
                <input type="text" name="new-incident-type" id="new-incident-type" value="">
                <div id="incident-type-create-error" class="error-msg"></div>                                                                 
              </div>
              
              <input type="submit" name="create" value="Create" class="btn btn-outline ">
            </form>                                      
          </div>
          
          <div class="successMsg-box card hide">
                <div class="successMsg"> <i class="fa-regular fa-circle-check"></i> New Incident Type Category has been created.</div>
                <button type="button" id="done-btn" class="btn btn-outline">Done</button>
            </div>                      
      </section>

    <script src="js/ticket.js"></script>    
  </body>
</html>
