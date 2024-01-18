<?php 
    // Authenticate if user is logged in
  include ("../auth_session.php");

    
    // Check if admin is logged in
  if($_SESSION["role"] != "admin"){
      header("Location: /Capstone_System/404.php");
      exit();
  }

  include ("../config/db_config.php");

    $ticketId = $submittedBy = $userEmail = $dateIssued = $ticketDescription = $department = $ticketStatus = $incidentType = $priorityLevel = $assignedTo = $actionsTaken = "";

    $departmentsArr = array("Human Resource", "Treasurer", "Assessor", "Civil Registrar", "Accounting", "MPDC", "Engineering", "Budget Office",
    "Mayor's Office", "Agriculture", "MSDWDO", "MDRRMO", "Judiciary", "IT Dept.");

    sort($departmentsArr);

    $successMsg = "";
    $error = array(            
      "submittedByError" => "",
      "userEmailError" => "",
      "dateIssuedError" => "",
      "ticketDescriptionError" => "",
      "departmentError" => "",             
      "incidentTypeError" => "",
      "priorityLevelError" => "",
      "ticketStatusError" => "",
      "assignedToError" => "",
      "actionsTakenError" => ""     
    );

    if(isset($_POST["create"])){

      if(empty($_POST["respondent-name"])){
        $submittedBy = htmlspecialchars($_POST["respondent-name"]);
        $error["submittedByError"] = "This is a required field.";
      }
      else {
        $submittedBy = htmlspecialchars($_POST["respondent-name"]);
        if(!preg_match('/^[a-zA-Z\s]+$/', $submittedBy)){
          $error["submittedByError"] = "must be combination of letters and spaces only";
        }
      }

      if(empty($_POST["department-name"])){
        $department = htmlspecialchars($_POST["department-name"]);        
        $error["departmentError"] = "This is a required field";        
      }
      else{
        $department = htmlspecialchars($_POST["department-name"]);
      }

      if(empty($_POST["date-issued"])){
        $dateIssued = htmlspecialchars($_POST["date-issued"]);        
        $error["dateIssuedError"] = "This is a required field";        
      }
      else{
        $dateIssued = htmlspecialchars($_POST["date-issued"]);
      }
      
      if(empty($_POST["incident-type"]) || $_POST["incident-type"] == "Define New"){
          $incidentType = htmlspecialchars($_POST["incident-type"]);
          $error["incidentTypeError"] = "This is a required field.";
      }
      else{
        $incidentType = htmlspecialchars($_POST["incident-type"]);
      }

      if(empty($_POST["priority-level"])){
        $priorityLevel = htmlspecialchars($_POST["priority-level"]);
        $error["priorityLevelError"] = "This is a required field.";
      }
      else{
        $priorityLevel = htmlspecialchars($_POST["priority-level"]);
      }

      if(empty($_POST["assigned-to"])){
        $assignedTo = htmlspecialchars($_POST["assigned-to"]);
        $error["assignedToError"] = "This is a required field.";
      }
      else{        
        $assignedTo = htmlspecialchars($_POST["assigned-to"]);
      }

      if(empty($_POST["subject"])){
        $error["ticketDescriptionError"] = "This is a required field.";
      }
      else{
        $ticketDescription = htmlspecialchars($_POST["subject"]);        
      }

      if(empty($_POST["ticket-status"])){
        $error["ticketStatusError"] = "This is a required field.";
      }
      else{
        $ticketStatus = htmlspecialchars($_POST["ticket-status"]);        
      }

      if(empty($_POST["actions-taken-input"]) && $_POST["ticket-status"] == "Resolved"){
        $error["actionsTakenError"] = "This is a required field.";
      }
      else{
        $actionsTaken = htmlspecialchars($_POST["actions-taken-input"]);
      }
      
      if($error["submittedByError"] == "" && $error["departmentError"] == "" && $error["dateIssuedError"] == "" && $error["incidentTypeError"] == "" && $error["priorityLevelError"] == "" &&  $error["assignedToError"] == "" 
      && $error["ticketDescriptionError"] == "" && $error["ticketStatusError"] == "" && $error["actionsTakenError"] == ""){                         
          $incidentType = htmlspecialchars($_POST["incident-type"]);
          $priorityLevel = htmlspecialchars($_POST["priority-level"]);
          $assignedTo = htmlspecialchars($_POST["assigned-to"]);
          $ticketDescription = htmlspecialchars($_POST["subject"]);

          $sql = "INSERT INTO tickets (submitted_by, department, incident_date, incident_type, priority_level, assigned_to, incident_description, ticket_status, actions_taken) 
          VALUES ('$submittedBy', '$department', '$dateIssued', '$incidentType', '$priorityLevel', '$assignedTo', '$ticketDescription', '$ticketStatus', '$actionsTaken')";
          
          if(mysqli_query($conn, $sql)){     
            $ticketId = $submittedBy = $userEmail = $dateIssued = $ticketDescription = $department = $ticketStatus = $incidentType = $priorityLevel = $assignedTo = $actionsTaken = "";         
              header("Location:tickets.php");
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
        <div class="mt-3">
              <a href="tickets.php" class="btn btn-outline-secondary"><i class="fas fa-chevron-left mr-1"></i>Back</a>
        </div>      
        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="create-ticket-form">              
              <!-- Ticket Form -->
          <section id="create-ticket-form" class="overflow-y-true">

            <div class="flex mt-2"><h2>Create Ticket</h2></div>

            <div class="grid grid-2 grid-col-gap-3 mt-2 px-3">

              <div class="flex flex-column flex-align-left form-group px-4">                  
                <label for="respondent-name" class="txt-fw-2 txt-light txt-sm mr-1">Respondent Name: </label>                                
                <input type="text" name="respondent-name" id="respondent-name" value="<?php echo $submittedBy ?>" placeholder="Name of the person who reported the incident">                                                                                                                    
                <div id="respondent-name-error" class="error-msg"><?php echo $error["submittedByError"]; ?></div>  
              </div>

              <div class="flex flex-column flex-align-left form-group px-4">                  
                <label for="department-name" class="txt-fw-2 txt-light txt-sm mr-1">Department: </label>                                
                <input type="hidden" name="department-name" value="<?php echo $department ?>">                                                                                                                    
                  <select name="department-name" id="deparment-name" class="form-card-solid ">
                    <option <?php if(isset($department) && $department == "") echo "selected"; ?> value=""></option>
                    <?php foreach($departmentsArr as $dep): ?>                        
                      <option <?php if(isset($department) && $department == $dep) echo "selected"; ?> value="<?php echo $dep?>"><?php echo $dep?></option>
                    <?php endforeach; ?>                                              
                  </select>                                                   
                  <div id="department-name-error" class="error-msg"><?php echo $error["departmentError"]; ?></div>                      
              </div>
                
              <div class="flex flex-column flex-align-left form-group px-4">                  
                <label for="date-issued" class="txt-fw-2 txt-light txt-sm mr-1">Date Issued: </label>                                
                <input type="datetime-local" name="date-issued" id="date-issued" value="<?php echo $dateIssued ?>">   
                <div id="date-issued-error" class="error-msg"><?php echo $error["dateIssuedError"]; ?></div>                                                                                                                   
              </div>
            </div>

            <!-- Incident Details -->
            <div class="grid grid-2 grid-gap-3 mt-3 px-3 incident-details-form">                
              <div class="flex flex-column flex-align-left form-group px-4">                                     
                <label for="incident-type" class="txt-fw-2 txt-light txt-sm mr-1">Incident Type: </label>                                                  
                <input type="hidden" name="selected-incident-type" value="<?php echo $incidentType?>" id="selected-incident-type">

                <select name="incident-type" id="incident-type" class="form-card-solid ">
                  <option <?php if(isset($incidentType) && $incidentType == "") echo "selected"; ?> value=""></option>                                    
                  <option class="last" value="Define New">Define New</option>
                </select>                                 
                <div id="incident-type-error" class="error-msg"><?php echo $error["incidentTypeError"]; ?></div>        
              </div>

              <div class="flex flex-column flex-align-left form-group">                  
                <div class="flex flex-column flex-align-left form-group px-4">
                  <label for="subject" class="txt-fw-2 txt-light txt-sm">Description</label>                                
                  <textarea name="subject" id="subject" cols="70" rows="13" placeholder="Describe the incident..." class="p-2"></textarea>
                  <div id="subject-error" class="error-msg"><?php echo $error["ticketDescriptionError"]; ?></div>                                                                
                </div>                
              </div>    
                                  
              <div class="flex flex-column flex-align-left form-group px-4">                                     
                <label for="priority-level" class="txt-fw-2 txt-light txt-sm mr-1">Priority Level: </label>                                                
                  <input type="hidden" name="selected-priority-level" value="<?php echo $priorityLevel?>" id="selected-priority-level">

                  <select name="priority-level" id="priority-level" class="form-card-solid ">
                      <option <?php if(isset($priorityLevel) && $priorityLevel == "") echo "selected"; ?> value=""></option>
                      <option <?php if(isset($priorityLevel) && $priorityLevel == "Low") echo "selected"; ?> value="Low">Low</option>
                      <option <?php if(isset($priorityLevel) && $priorityLevel == "Medium") echo "selected"; ?> value="Medium">Medium</option>
                      <option <?php if(isset($priorityLevel) && $priorityLevel == "High") echo "selected"; ?> value="High">High</option>                                                      
                  </select>                                 
                  <div id="priority-level-error" class="error-msg"><?php echo $error["priorityLevelError"]; ?></div>  
              </div>

              <div class="flex flex-column flex-align-left form-group px-4">                  
                <label for="assigned-to" class="txt-fw-2 txt-light txt-sm mr-1">Assigned To: </label>                                
                <input type="text" name="assigned-to" id="assigned-to" value="<?php echo $assignedTo ?>" placeholder="Name of the person assigned">
                <div id="assigned-to-error" class="error-msg"><?php echo $error["assignedToError"]; ?></div>  
              </div>

              <div class="flex flex-column flex-align-left form-group px-4">                                     
                <label for="ticket-status" class="txt-fw-2 txt-light txt-sm mr-1">Status: </label>                                                
                  <input type="hidden" name="selected-status" value="<?php echo $ticketStatus?>" id="selected-status">

                  <select name="ticket-status" id="ticket-status" class="form-card-solid ">
                      <option <?php if(isset($ticketStatus) && $ticketStatus == "") echo "selected"; ?> value=""></option>
                      <option <?php if(isset($ticketStatus) && $ticketStatus == "Pending") echo "selected"; ?> value="Pending">Pending</option>
                      <option <?php if(isset($ticketStatus) && $ticketStatus == "Resolved") echo "selected"; ?> value="Resolved">Resolved</option>                                                      
                  </select>                                 
                  <div id="ticket-status-error" class="error-msg"><?php echo $error["ticketStatusError"]; ?></div>  
              </div>
              
              <div class="flex flex-just-center mt-4">
                <input type="hidden" id="ticket_id" name="ticket_id" value = <?php echo $ticketId;?>>
                <input type="submit" name="create" value="Create Ticket" class="btn btn-primary">
              </div>  
            </div>                           
                                                                  
                                      
          </section>                                          
                  
          
          <section class="actions-taken-form hide">                        
            <div class="form-group form-card bg-light-primary">              
              <div class="flex flex-just-left mb-3"><i class="fas fa-times clickable close-actions-taken"></i></div>
              <label for="actions-taken-input" class="txt-fw-2 txt-light txt-sm">Actions Taken</label>                
              <textarea name="actions-taken-input" id="actions-taken" cols="70" rows="13" placeholder="Describe actions taken in resolving the incident..." class="p-2"></textarea>
              <div id="actions-taken-error" class="error-msg"><?php echo $error["actionsTakenError"]; ?></div> 
              
              <div class="flex flex-just-right mt-2"><div class="btn btn-secondary done-actions-taken">Done</div></div>
            </div>
          </section>
          
          

        </form>                          
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
