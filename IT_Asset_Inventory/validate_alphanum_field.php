    <?php 
     // Authenticate if user is logged in
     include ("../auth_session.php");

    
     if($_SESSION["role"] != "admin"){
         header("Location: /Capstone_System/404.php");
         exit();
     }
        $value = $_POST["input"];
        
        if($value == "" || $value == "None" || $value == "none"){
            echo "*required";
        }
        else{
            if(!preg_match('/^[a-zA-Z0-9-.\s]+$/', $value)){ 
                echo "must be combination of letters, numbers and spaces only";
            }else{
                echo "Good";
            }
        }
        
    ?>